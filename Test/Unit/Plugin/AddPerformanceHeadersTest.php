<?php
declare(strict_types=1);

namespace Panth\CoreWebVitals\Test\Unit\Plugin;

use Panth\CoreWebVitals\Plugin\AddPerformanceHeaders;
use Panth\CoreWebVitals\Helper\Data as ConfigHelper;
use Magento\Framework\App\Response\Http;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class AddPerformanceHeadersTest extends TestCase
{
    private AddPerformanceHeaders $plugin;

    private $configHelperMock;

    private $responseMock;

    protected function setUp(): void
    {
        $this->configHelperMock = $this->createMock(ConfigHelper::class);
        $this->responseMock = $this->createMock(Http::class);

        $this->plugin = new AddPerformanceHeaders($this->configHelperMock);
    }

    public function testSkipsEverythingWhenModuleDisabled(): void
    {
        $this->configHelperMock->expects($this->once())
            ->method('isEnabled')
            ->willReturn(false);

        $this->responseMock->expects($this->never())
            ->method('setHeader');

        $this->plugin->beforeSendResponse($this->responseMock);
    }

    public function testAddsServerTimingHeaderWhenEnabled(): void
    {
        $this->configHelperMock->method('isEnabled')->willReturn(true);
        $this->configHelperMock->method('isDnsPrefetchEnabled')->willReturn(false);
        $this->configHelperMock->method('getPreconnectDomains')->willReturn([]);

        $this->responseMock->expects($this->once())
            ->method('setHeader')
            ->with(
                'Server-Timing',
                $this->stringContains('app;desc="PHP Execution";dur='),
                true
            );

        $this->plugin->beforeSendResponse($this->responseMock);
    }

    public function testAddsDnsPrefetchControlHeader(): void
    {
        $this->configHelperMock->method('isEnabled')->willReturn(true);
        $this->configHelperMock->method('isDnsPrefetchEnabled')->willReturn(true);
        $this->configHelperMock->method('getPreconnectDomains')->willReturn([]);

        $this->responseMock->expects($this->exactly(2))
            ->method('setHeader');

        $this->plugin->beforeSendResponse($this->responseMock);
    }

    public function testAddsPreconnectLinkHeaders(): void
    {
        $domains = ['fonts.googleapis.com', 'cdn.example.com'];

        $this->configHelperMock->method('isEnabled')->willReturn(true);
        $this->configHelperMock->method('isDnsPrefetchEnabled')->willReturn(false);
        $this->configHelperMock->method('getPreconnectDomains')->willReturn($domains);

        $this->responseMock->expects($this->exactly(2))
            ->method('setHeader');

        $this->plugin->beforeSendResponse($this->responseMock);
    }

    public function testAllHeadersTogetherWhenFullyConfigured(): void
    {
        $domains = ['fonts.googleapis.com', 'cdn.example.com'];

        $this->configHelperMock->method('isEnabled')->willReturn(true);
        $this->configHelperMock->method('isDnsPrefetchEnabled')->willReturn(true);
        $this->configHelperMock->method('getPreconnectDomains')->willReturn($domains);

        $this->responseMock->expects($this->exactly(3))
            ->method('setHeader');

        $this->plugin->beforeSendResponse($this->responseMock);
    }
}
