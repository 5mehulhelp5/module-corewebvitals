<?php
declare(strict_types=1);

namespace Panth\CoreWebVitals\Test\Unit\Plugin;

use Magento\Framework\App\Response\Http;
use Panth\CoreWebVitals\Helper\Data as ConfigHelper;
use Panth\CoreWebVitals\Plugin\AddPerformanceHeaders;
use PHPUnit\Framework\TestCase;

class AddPerformanceHeadersTest extends TestCase
{
    /**
     * @var array<int, array{0: string, 1: string, 2: bool}>
     */
    private array $headers = [];

    private function helper(bool $enabled, bool $dnsPrefetch = false, array $preconnect = []): ConfigHelper
    {
        $helper = $this->createStub(ConfigHelper::class);
        $helper->method('isEnabled')->willReturn($enabled);
        $helper->method('isDnsPrefetchEnabled')->willReturn($dnsPrefetch);
        $helper->method('getPreconnectDomains')->willReturn($preconnect);
        return $helper;
    }

    private function response(): Http
    {
        $this->headers = [];
        $response = $this->createStub(Http::class);
        $response->method('setHeader')->willReturnCallback(
            function ($name, $value, $replace = false) use ($response) {
                $this->headers[] = [$name, $value, $replace];
                return $response;
            }
        );
        return $response;
    }

    private function headerNames(): array
    {
        return array_column($this->headers, 0);
    }

    private function headerValue(string $name): ?string
    {
        foreach ($this->headers as $header) {
            if ($header[0] === $name) {
                return $header[1];
            }
        }
        return null;
    }

    private function durationOf(?string $serverTiming): float
    {
        $matches = [];
        $this->assertSame(1, preg_match('/dur=([\d.]+)$/', (string) $serverTiming, $matches));
        return (float) $matches[1];
    }

    public function testSkipsEverythingWhenModuleDisabled(): void
    {
        $response = $this->createMock(Http::class);
        $response->expects($this->never())->method('setHeader');

        (new AddPerformanceHeaders($this->helper(false, true, ['cdn.example.com'])))
            ->beforeSendResponse($response);
    }

    public function testAddsOnlyServerTimingWhenNothingElseConfigured(): void
    {
        $response = $this->response();
        (new AddPerformanceHeaders($this->helper(true)))->beforeSendResponse($response);

        $this->assertSame(['Server-Timing'], $this->headerNames());
        $this->assertMatchesRegularExpression(
            '/^app;desc="PHP Execution";dur=\d+(\.\d+)?$/',
            (string) $this->headerValue('Server-Timing')
        );
        $this->assertTrue($this->headers[0][2], 'Server-Timing must replace an existing header');
    }

    public function testServerTimingDurationIsMeasuredFromRequestStart(): void
    {
        $previous = $_SERVER['REQUEST_TIME_FLOAT'] ?? null;
        $_SERVER['REQUEST_TIME_FLOAT'] = microtime(true) - 2.0;
        try {
            $response = $this->response();
            (new AddPerformanceHeaders($this->helper(true)))->beforeSendResponse($response);
        } finally {
            if ($previous === null) {
                unset($_SERVER['REQUEST_TIME_FLOAT']);
            } else {
                $_SERVER['REQUEST_TIME_FLOAT'] = $previous;
            }
        }

        $duration = $this->durationOf($this->headerValue('Server-Timing'));
        $this->assertGreaterThanOrEqual(2000.0, $duration);
        $this->assertLessThan(60000.0, $duration);
    }

    public function testServerTimingIsNearZeroWithoutRequestTime(): void
    {
        $previous = $_SERVER['REQUEST_TIME_FLOAT'] ?? null;
        unset($_SERVER['REQUEST_TIME_FLOAT']);
        try {
            $response = $this->response();
            (new AddPerformanceHeaders($this->helper(true)))->beforeSendResponse($response);
        } finally {
            if ($previous !== null) {
                $_SERVER['REQUEST_TIME_FLOAT'] = $previous;
            }
        }

        $this->assertLessThan(1000.0, $this->durationOf($this->headerValue('Server-Timing')));
    }

    public function testAddsDnsPrefetchControlHeader(): void
    {
        $response = $this->response();
        (new AddPerformanceHeaders($this->helper(true, true)))->beforeSendResponse($response);

        $this->assertSame(['Server-Timing', 'X-DNS-Prefetch-Control'], $this->headerNames());
        $this->assertSame('on', $this->headerValue('X-DNS-Prefetch-Control'));
        $this->assertNull($this->headerValue('Link'));
    }

    public function testPreconnectLinkHeaderListsEveryDomainWithHttpsScheme(): void
    {
        $response = $this->response();
        (new AddPerformanceHeaders($this->helper(true, false, ['fonts.googleapis.com', ' cdn.example.com '])))
            ->beforeSendResponse($response);

        $this->assertSame(['Server-Timing', 'Link'], $this->headerNames());
        $this->assertSame(
            '<https://fonts.googleapis.com>; rel=preconnect; crossorigin, '
            . '<https://cdn.example.com>; rel=preconnect; crossorigin',
            $this->headerValue('Link')
        );
    }

    public function testPreconnectKeepsExplicitSchemesAndUpgradesProtocolRelativeOrigins(): void
    {
        $response = $this->response();
        (new AddPerformanceHeaders($this->helper(
            true,
            false,
            ['http://legacy.example.com', 'https://secure.example.com', '//cdn.example.com']
        )))->beforeSendResponse($response);

        $this->assertSame(
            '<http://legacy.example.com>; rel=preconnect; crossorigin, '
            . '<https://secure.example.com>; rel=preconnect; crossorigin, '
            . '<https://cdn.example.com>; rel=preconnect; crossorigin',
            $this->headerValue('Link')
        );
    }

    public function testAllHeadersTogetherWhenFullyConfigured(): void
    {
        $response = $this->response();
        (new AddPerformanceHeaders($this->helper(true, true, ['cdn.example.com'])))
            ->beforeSendResponse($response);

        $this->assertSame(['Server-Timing', 'X-DNS-Prefetch-Control', 'Link'], $this->headerNames());
        foreach ($this->headers as $header) {
            $this->assertTrue($header[2], $header[0] . ' should be set with replace=true');
        }
    }

    public function testBareHostStartingWithHttpGetsHttpsScheme(): void
    {
        $response = $this->response();
        (new AddPerformanceHeaders($this->helper(true, false, ['httpbin.org', 'HTTPS://upper.example.com'])))
            ->beforeSendResponse($response);

        $this->assertSame(
            '<https://httpbin.org>; rel=preconnect; crossorigin, '
            . '<https://upper.example.com>; rel=preconnect; crossorigin',
            $this->headerValue('Link')
        );
    }

    public function testPreconnectIsAppendedToExistingLinkHeader(): void
    {
        $response = $this->response();
        $existing = new \Laminas\Http\Header\GenericHeader('Link', '</app.css>; rel=preload; as=style');
        $response->method('getHeader')->willReturn($existing);
        (new AddPerformanceHeaders($this->helper(true, false, ['cdn.example.com'])))
            ->beforeSendResponse($response);

        $this->assertSame(
            '</app.css>; rel=preload; as=style, <https://cdn.example.com>; rel=preconnect; crossorigin',
            $this->headerValue('Link')
        );
    }

    public function testPreconnectAlreadyInLinkHeaderIsNotDuplicated(): void
    {
        $response = $this->response();
        $existing = new \Laminas\Http\Header\GenericHeader(
            'Link',
            '<https://cdn.example.com>; rel=preconnect; crossorigin'
        );
        $response->method('getHeader')->willReturn($existing);
        (new AddPerformanceHeaders($this->helper(true, false, ['cdn.example.com'])))
            ->beforeSendResponse($response);

        $this->assertNull($this->headerValue('Link'), 'An existing Link header with the origin must stay as it is');
        $this->assertSame(['Server-Timing'], $this->headerNames());
    }

    public function testPreconnectStripsPathsDeduplicatesAndSkipsInvalidLines(): void
    {
        $response = $this->response();
        (new AddPerformanceHeaders($this->helper(
            true,
            false,
            [
                'https://cdn.example.com/assets/app.js?v=1',
                'cdn.example.com',
                '//CDN.example.com',
                'ftp://files.example.com',
                'bad host<script>',
                'https://fonts.example.com:8443/css',
            ]
        )))->beforeSendResponse($response);

        $this->assertSame(
            '<https://cdn.example.com>; rel=preconnect; crossorigin, '
            . '<https://fonts.example.com:8443>; rel=preconnect; crossorigin',
            $this->headerValue('Link')
        );
    }

    public function testOnlyInvalidPreconnectLinesSendNoLinkHeader(): void
    {
        $response = $this->response();
        (new AddPerformanceHeaders($this->helper(true, false, ['ftp://files.example.com', '   '])))
            ->beforeSendResponse($response);

        $this->assertSame(['Server-Timing'], $this->headerNames());
    }

    public function testExistingLinkHeaderIsLeftUntouchedWhenAllOriginsPresent(): void
    {
        $response = $this->response();
        $existing = new \Laminas\Http\Header\GenericHeader(
            'Link',
            '</app.css>; rel=preload; as=style, <https://cdn.example.com>; rel=preconnect'
        );
        $response->method('getHeader')->willReturn($existing);
        (new AddPerformanceHeaders($this->helper(true, false, ['CDN.example.com'])))
            ->beforeSendResponse($response);

        $this->assertNull($this->headerValue('Link'));
    }

    public function testServerTimingIsAppendedToExistingServerTimingHeader(): void
    {
        $response = $this->response();
        $existing = new \Laminas\Http\Header\GenericHeader('Server-Timing', 'db;dur=12');
        $response->method('getHeader')->willReturnCallback(
            fn ($name) => $name === 'Server-Timing' ? $existing : false
        );
        (new AddPerformanceHeaders($this->helper(true)))->beforeSendResponse($response);

        $this->assertMatchesRegularExpression(
            '/^db;dur=12, app;desc="PHP Execution";dur=\d+(\.\d+)?$/',
            (string) $this->headerValue('Server-Timing')
        );
    }
}
