<?php
declare(strict_types=1);

namespace Panth\CoreWebVitals\Test\Unit\Block;

use Panth\CoreWebVitals\Block\ResourceHints;
use Panth\CoreWebVitals\Helper\Data as Helper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ResourceHintsTest extends TestCase
{
    private function block(?Helper $helper = null): ResourceHints
    {
        $reflection = new \ReflectionClass(ResourceHints::class);
        $block = $reflection->newInstanceWithoutConstructor();
        $reflection->getProperty('helper')->setValue($block, $helper ?? $this->createStub(Helper::class));
        return $block;
    }

    public function testDelegatesEnabledFlagAndDomainListsToHelper(): void
    {
        $helper = $this->createStub(Helper::class);
        $helper->method('isEnabled')->willReturn(true);
        $helper->method('getDnsPrefetchDomains')->willReturn(['dns.example.com']);
        $helper->method('getPreconnectDomains')->willReturn(['https://cdn.example.com', 'fonts.example.com']);
        $helper->method('getPrefetchUrls')->willReturn(['/checkout']);

        $block = $this->block($helper);

        $this->assertTrue($block->isEnabled());
        $this->assertSame(['dns.example.com'], $block->getDnsPrefetchDomains());
        $this->assertSame(['https://cdn.example.com', 'fonts.example.com'], $block->getPreconnectDomains());
        $this->assertSame(['/checkout'], $block->getPrefetchUrls());
    }

    public function testDisabledHelperGivesDisabledBlockAndEmptyLists(): void
    {
        $helper = $this->createStub(Helper::class);
        $helper->method('isEnabled')->willReturn(false);
        $helper->method('getDnsPrefetchDomains')->willReturn([]);
        $helper->method('getPreconnectDomains')->willReturn([]);
        $helper->method('getPrefetchUrls')->willReturn([]);

        $block = $this->block($helper);

        $this->assertFalse($block->isEnabled());
        $this->assertSame([], $block->getDnsPrefetchDomains());
        $this->assertSame([], $block->getPreconnectDomains());
        $this->assertSame([], $block->getPrefetchUrls());
    }

    public static function hintHrefProvider(): array
    {
        return [
            'bare domain'           => ['cdn.example.com', '//cdn.example.com'],
            'bare domain trimmed'   => ['  cdn.example.com  ', '//cdn.example.com'],
            'protocol relative'     => ['//cdn.example.com', '//cdn.example.com'],
            'https kept'            => ['https://cdn.example.com', 'https://cdn.example.com'],
            'http kept'             => ['http://cdn.example.com', 'http://cdn.example.com'],
            'uppercase scheme kept' => ['HTTPS://CDN.example.com', 'HTTPS://CDN.example.com'],
            'domain starting http'  => ['httpbin.org', '//httpbin.org'],
            'url with path trimmed' => [" https://cdn.example.com/x\t", 'https://cdn.example.com/x'],
        ];
    }

    #[DataProvider('hintHrefProvider')]
    public function testToHintHref(string $input, string $expected): void
    {
        $this->assertSame($expected, $this->block()->toHintHref($input));
    }

    public function testHeadHintListsAreNormalizedAndDeduplicated(): void
    {
        $helper = $this->createStub(Helper::class);
        $helper->method('getDnsPrefetchDomains')->willReturn(['dns.example.com', 'dns.example.com', '//dns.example.com']);
        $helper->method('getPreconnectDomains')->willReturn(
            ['cdn.example.com', '//cdn.example.com', 'https://cdn.example.com/x.js', 'http://legacy.example.com', 'ftp://x.example.com']
        );
        $helper->method('getPrefetchUrls')->willReturn(['/checkout', ' /checkout ', 'https://cdn.example.com/next.js']);

        $block = $this->block($helper);

        $this->assertSame(['//dns.example.com'], $block->getDnsPrefetchHrefs());
        $this->assertSame(['https://cdn.example.com', 'http://legacy.example.com'], $block->getPreconnectOrigins());
        $this->assertSame(['/checkout', 'https://cdn.example.com/next.js'], $block->getUniquePrefetchUrls());
    }

    public function testEmptyListsGiveNoHeadHints(): void
    {
        $block = $this->block();

        $this->assertSame([], $block->getDnsPrefetchHrefs());
        $this->assertSame([], $block->getPreconnectOrigins());
        $this->assertSame([], $block->getUniquePrefetchUrls());
        $this->assertSame('', $block->toHintHref('   '));
    }
}
