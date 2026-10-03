<?php
declare(strict_types=1);

namespace Panth\CoreWebVitals\Test\Unit\Model;

use Panth\CoreWebVitals\Model\OriginNormalizer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class OriginNormalizerTest extends TestCase
{
    public static function originProvider(): array
    {
        return [
            'bare host'                 => ['fonts.gstatic.com', 'https://fonts.gstatic.com'],
            'bare host trimmed'         => ["  cdn.example.com \t", 'https://cdn.example.com'],
            'host starting with http'   => ['httpbin.org', 'https://httpbin.org'],
            'protocol relative'         => ['//cdn.example.com', 'https://cdn.example.com'],
            'http kept'                 => ['http://legacy.example.com', 'http://legacy.example.com'],
            'uppercase lowered'         => ['HTTPS://CDN.Example.COM', 'https://cdn.example.com'],
            'path query fragment gone'  => ['https://cdn.example.com/a/b.js?v=1#x', 'https://cdn.example.com'],
            'bare host with path'       => ['cdn.example.com/fonts', 'https://cdn.example.com'],
            'port kept'                 => ['https://cdn.example.com:8443/x', 'https://cdn.example.com:8443'],
            'credentials dropped'       => ['https://user:pass@cdn.example.com', 'https://cdn.example.com'],
            'ipv6 host'                 => ['https://[2001:db8::1]:8080', 'https://[2001:db8::1]:8080'],
            'empty'                     => ['', ''],
            'whitespace'                => ['   ', ''],
            'ftp rejected'              => ['ftp://files.example.com', ''],
            'javascript rejected'       => ['javascript://alert(1)', ''],
            'markup rejected'           => ['cdn.example.com"><script>', ''],
            'scheme only rejected'      => ['https://', ''],
        ];
    }

    #[DataProvider('originProvider')]
    public function testNormalize(string $input, string $expected): void
    {
        $this->assertSame($expected, (new OriginNormalizer())->normalize($input));
    }

    public function testNormalizeListDeduplicatesKeepsOrderAndSkipsInvalid(): void
    {
        $this->assertSame(
            ['https://cdn.example.com', 'http://cdn.example.com', 'https://fonts.example.com'],
            (new OriginNormalizer())->normalizeList([
                'cdn.example.com',
                'http://cdn.example.com',
                '//cdn.example.com',
                'ftp://x.example.com',
                'https://CDN.example.com/app.js',
                'fonts.example.com',
                '',
            ])
        );
    }

    public function testNormalizeListOfNothingIsEmpty(): void
    {
        $this->assertSame([], (new OriginNormalizer())->normalizeList([]));
    }
}
