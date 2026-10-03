<?php
declare(strict_types=1);

namespace Panth\CoreWebVitals\Test\Unit\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Panth\CoreWebVitals\Helper\Data;
use PHPUnit\Framework\TestCase;

class DataEdgeCasesTest extends TestCase
{
    /**
     * @var array<int, array{0: string, 1: mixed, 2: mixed}>
     */
    private array $calls = [];

    private function helper(array $values, bool $storeMissing = false, int $storeId = 3): Data
    {
        $this->calls = [];
        $scopeConfig = $this->createStub(ScopeConfigInterface::class);
        $scopeConfig->method('getValue')->willReturnCallback(
            function ($path, $scope = null, $scopeCode = null) use ($values) {
                $this->calls[] = [$path, $scope, $scopeCode];
                return $values[$path] ?? null;
            }
        );
        $context = $this->createStub(Context::class);
        $context->method('getScopeConfig')->willReturn($scopeConfig);

        $storeManager = $this->createStub(StoreManagerInterface::class);
        if ($storeMissing) {
            $storeManager->method('getStore')->willThrowException(new NoSuchEntityException());
        } else {
            $store = $this->createStub(StoreInterface::class);
            $store->method('getId')->willReturn((string) $storeId);
            $storeManager->method('getStore')->willReturn($store);
        }

        return new Data($context, $storeManager);
    }

    public function testCurrentStoreIdIsCastToIntAndUsedAsScopeCode(): void
    {
        $helper = $this->helper(['panth_corewebvitals/general/enabled' => '1'], false, 7);

        $this->assertTrue($helper->isEnabled());
        $this->assertSame(
            ['panth_corewebvitals/general/enabled', ScopeInterface::SCOPE_STORE, 7],
            $this->calls[0]
        );
    }

    public function testMissingStoreFallsBackToNullScopeCode(): void
    {
        $helper = $this->helper(['panth_corewebvitals/general/enabled' => '1'], true);

        $this->assertTrue($helper->isEnabled());
        $this->assertNull($this->calls[0][2]);
    }

    public function testExplicitStoreIdWinsOverCurrentStore(): void
    {
        $helper = $this->helper([], false, 7);
        $helper->getTargetLcp(12);

        $this->assertSame(12, $this->calls[0][2]);
    }

    public function testFeatureFlagsShortCircuitWhenModuleDisabled(): void
    {
        $helper = $this->helper([
            'panth_corewebvitals/general/enabled' => '0',
            'panth_corewebvitals/general/debug_mode' => '1',
            'panth_corewebvitals/general/real_user_monitoring' => '1',
            'panth_corewebvitals/lcp/enabled' => '1',
            'panth_corewebvitals/fid/enabled' => '1',
            'panth_corewebvitals/cls/enabled' => '1',
        ]);

        $this->assertFalse($helper->isDebugMode());
        $this->assertFalse($helper->isRealUserMonitoring());
        $this->assertFalse($helper->isLcpEnabled());
        $this->assertFalse($helper->isFidEnabled());
        $this->assertFalse($helper->isClsEnabled());
        $paths = array_values(array_unique(array_column($this->calls, 0)));
        $this->assertSame(['panth_corewebvitals/general/enabled'], $paths);
    }

    public function testFeatureFlagsAreFalseWhenOnlyModuleEnabled(): void
    {
        $helper = $this->helper(['panth_corewebvitals/general/enabled' => '1']);

        $this->assertFalse($helper->isDebugMode());
        $this->assertFalse($helper->isRealUserMonitoring());
        $this->assertFalse($helper->isLcpEnabled());
        $this->assertFalse($helper->isFidEnabled());
        $this->assertFalse($helper->isClsEnabled());
    }

    public function testEndpointAndGa4IdAreTrimmedAndDefaultToEmpty(): void
    {
        $helper = $this->helper([
            'panth_corewebvitals/general/endpoint_url' => "  https://rum.example.com/collect \n",
            'panth_corewebvitals/general/ga4_measurement_id' => ' G-ABC123 ',
        ]);
        $this->assertSame('https://rum.example.com/collect', $helper->getEndpointUrl());
        $this->assertSame('G-ABC123', $helper->getGa4MeasurementId());

        $empty = $this->helper([]);
        $this->assertSame('', $empty->getEndpointUrl());
        $this->assertSame('', $empty->getGa4MeasurementId());
    }

    public function testTargetsUseConfiguredValuesAndCastTypes(): void
    {
        $helper = $this->helper([
            'panth_corewebvitals/lcp/target_lcp' => '1800',
            'panth_corewebvitals/fid/target_fid' => '50',
            'panth_corewebvitals/fid/target_inp' => '150',
            'panth_corewebvitals/cls/target_cls' => '0.05',
        ]);

        $this->assertSame(1800, $helper->getTargetLcp());
        $this->assertSame(50, $helper->getTargetFid());
        $this->assertSame(150, $helper->getTargetInp());
        $this->assertSame(0.05, $helper->getTargetCls());
    }

    public function testZeroOrNonNumericTargetsFallBackToDefaults(): void
    {
        $helper = $this->helper([
            'panth_corewebvitals/lcp/target_lcp' => '0',
            'panth_corewebvitals/fid/target_fid' => 'abc',
            'panth_corewebvitals/fid/target_inp' => '',
            'panth_corewebvitals/cls/target_cls' => '0',
        ]);

        $this->assertSame(2500, $helper->getTargetLcp());
        $this->assertSame(100, $helper->getTargetFid());
        $this->assertSame(200, $helper->getTargetInp());
        $this->assertSame(0.1, $helper->getTargetCls());
    }

    public function testTextareaListsAreTrimmedAndSkipBlankAndCrLfLines(): void
    {
        $helper = $this->helper([
            'panth_corewebvitals/resource_hints/dns_prefetch' => "  a.example.com \r\n\r\n\tb.example.com\n   \n",
            'panth_corewebvitals/resource_hints/preconnect' => "https://cdn.example.com\r\n",
            'panth_corewebvitals/resource_hints/prefetch' => "/checkout\n/cart\n",
        ]);

        $this->assertSame(['a.example.com', 'b.example.com'], $helper->getDnsPrefetchDomains());
        $this->assertSame(['https://cdn.example.com'], $helper->getPreconnectDomains());
        $this->assertSame(['https://cdn.example.com'], $helper->getPreconnectOrigins());
        $this->assertSame(['/checkout', '/cart'], $helper->getPrefetchUrls());
    }

    public function testWhitespaceOnlyDnsListMeansPrefetchDisabled(): void
    {
        $this->assertFalse($this->helper([
            'panth_corewebvitals/resource_hints/dns_prefetch' => " \n\r\n\t",
        ])->isDnsPrefetchEnabled());
        $this->assertTrue($this->helper([
            'panth_corewebvitals/resource_hints/dns_prefetch' => 'x.example.com',
        ])->isDnsPrefetchEnabled());
    }

    public function testResourceHintsDoNotDependOnModuleEnabledFlag(): void
    {
        $helper = $this->helper([
            'panth_corewebvitals/general/enabled' => '0',
            'panth_corewebvitals/resource_hints/preconnect' => 'cdn.example.com',
        ]);

        $this->assertSame(['cdn.example.com'], $helper->getPreconnectDomains());
    }

    public function testConfigJsonDefaultsWhenNothingConfigured(): void
    {
        $config = json_decode($this->helper([])->getConfigJson(), true);

        $this->assertSame([
            'enabled' => false,
            'debug' => false,
            'rum' => false,
            'endpointUrl' => '',
            'ga4Id' => '',
            'lcp' => ['enabled' => false, 'target' => 2500],
            'fid' => ['enabled' => false, 'targetFid' => 100, 'targetInp' => 200],
            'cls' => ['enabled' => false, 'target' => 0.1],
        ], $config);
    }

    public function testConfigJsonHexEncodesQuotesAmpersandsAndApostrophes(): void
    {
        $raw = 'G-1' . chr(39) . chr(34) . '&';
        $json = $this->helper([
            'panth_corewebvitals/general/ga4_measurement_id' => $raw,
        ])->getConfigJson();

        $this->assertStringNotContainsString(chr(39), $json);
        $this->assertStringNotContainsString('&', $json);
        $this->assertStringContainsString('\u0027', $json);
        $this->assertStringContainsString('\u0026', $json);
        $this->assertStringContainsString('\u0022', $json);
        $this->assertSame($raw, json_decode($json, true)['ga4Id']);
    }

    public function testConfigJsonUsesExplicitStoreForEveryLookup(): void
    {
        $helper = $this->helper([], false, 7);
        $helper->getConfigJson(4);

        $this->assertNotEmpty($this->calls);
        $this->assertSame([4], array_values(array_unique(array_column($this->calls, 2))));
    }
}
