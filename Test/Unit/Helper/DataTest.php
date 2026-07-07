<?php
declare(strict_types=1);

namespace Panth\CoreWebVitals\Test\Unit\Helper;

use Panth\CoreWebVitals\Helper\Data;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class DataTest extends TestCase
{
    private Data $helper;

    private $scopeConfigMock;

    protected function setUp(): void
    {
        $this->scopeConfigMock = $this->createMock(ScopeConfigInterface::class);
        $contextMock = $this->createMock(Context::class);
        $contextMock->method('getScopeConfig')->willReturn($this->scopeConfigMock);

        $this->helper = new Data($contextMock);
    }

    public function testIsEnabledReturnsTrue(): void
    {
        $this->scopeConfigMock->method('getValue')
            ->with('panth_corewebvitals/general/enabled', ScopeInterface::SCOPE_STORE, null)
            ->willReturn('1');

        $this->assertTrue($this->helper->isEnabled());
    }

    public function testIsEnabledReturnsFalse(): void
    {
        $this->scopeConfigMock->method('getValue')
            ->with('panth_corewebvitals/general/enabled', ScopeInterface::SCOPE_STORE, null)
            ->willReturn('0');

        $this->assertFalse($this->helper->isEnabled());
    }

    public function testIsDebugModeRequiresModuleEnabled(): void
    {
        $this->scopeConfigMock->method('getValue')
            ->willReturnMap([
                ['panth_corewebvitals/general/enabled', ScopeInterface::SCOPE_STORE, null, '0'],
            ]);

        $this->assertFalse($this->helper->isDebugMode());
    }

    public function testIsDebugModeReturnsTrueWhenBothEnabled(): void
    {
        $this->scopeConfigMock->method('getValue')
            ->willReturnMap([
                ['panth_corewebvitals/general/enabled', ScopeInterface::SCOPE_STORE, null, '1'],
                ['panth_corewebvitals/general/debug_mode', ScopeInterface::SCOPE_STORE, null, '1'],
            ]);

        $this->assertTrue($this->helper->isDebugMode());
    }

    public function testIsRealUserMonitoringReturnsTrueWhenBothEnabled(): void
    {
        $this->scopeConfigMock->method('getValue')
            ->willReturnMap([
                ['panth_corewebvitals/general/enabled', ScopeInterface::SCOPE_STORE, null, '1'],
                ['panth_corewebvitals/general/real_user_monitoring', ScopeInterface::SCOPE_STORE, null, '1'],
            ]);

        $this->assertTrue($this->helper->isRealUserMonitoring());
    }

    public function testIsLcpEnabledRequiresModuleEnabled(): void
    {
        $this->scopeConfigMock->method('getValue')
            ->willReturnMap([
                ['panth_corewebvitals/general/enabled', ScopeInterface::SCOPE_STORE, null, '1'],
                ['panth_corewebvitals/lcp/enabled', ScopeInterface::SCOPE_STORE, null, '1'],
            ]);

        $this->assertTrue($this->helper->isLcpEnabled());
    }

    public function testGetTargetLcpReturnsConfiguredValue(): void
    {
        $this->scopeConfigMock->method('getValue')
            ->with('panth_corewebvitals/lcp/target_lcp', ScopeInterface::SCOPE_STORE, null)
            ->willReturn('3000');

        $this->assertEquals(3000, $this->helper->getTargetLcp());
    }

    public function testGetTargetLcpFallsBackTo2500(): void
    {
        $this->scopeConfigMock->method('getValue')
            ->with('panth_corewebvitals/lcp/target_lcp', ScopeInterface::SCOPE_STORE, null)
            ->willReturn('0');

        $this->assertEquals(2500, $this->helper->getTargetLcp());
    }

    public function testIsFidEnabledReturnsTrueWhenBothEnabled(): void
    {
        $this->scopeConfigMock->method('getValue')
            ->willReturnMap([
                ['panth_corewebvitals/general/enabled', ScopeInterface::SCOPE_STORE, null, '1'],
                ['panth_corewebvitals/fid/enabled', ScopeInterface::SCOPE_STORE, null, '1'],
            ]);

        $this->assertTrue($this->helper->isFidEnabled());
    }

    public function testGetTargetFidFallsBackTo100(): void
    {
        $this->scopeConfigMock->method('getValue')
            ->with('panth_corewebvitals/fid/target_fid', ScopeInterface::SCOPE_STORE, null)
            ->willReturn('0');

        $this->assertEquals(100, $this->helper->getTargetFid());
    }

    public function testGetTargetInpFallsBackTo200(): void
    {
        $this->scopeConfigMock->method('getValue')
            ->with('panth_corewebvitals/fid/target_inp', ScopeInterface::SCOPE_STORE, null)
            ->willReturn('0');

        $this->assertEquals(200, $this->helper->getTargetInp());
    }

    public function testIsClsEnabledReturnsTrueWhenBothEnabled(): void
    {
        $this->scopeConfigMock->method('getValue')
            ->willReturnMap([
                ['panth_corewebvitals/general/enabled', ScopeInterface::SCOPE_STORE, null, '1'],
                ['panth_corewebvitals/cls/enabled', ScopeInterface::SCOPE_STORE, null, '1'],
            ]);

        $this->assertTrue($this->helper->isClsEnabled());
    }

    public function testGetTargetClsFallsBackToPointOne(): void
    {
        $this->scopeConfigMock->method('getValue')
            ->with('panth_corewebvitals/cls/target_cls', ScopeInterface::SCOPE_STORE, null)
            ->willReturn('0');

        $this->assertEquals(0.1, $this->helper->getTargetCls());
    }

    public function testGetDnsPrefetchDomainsParsesNewlines(): void
    {
        $this->scopeConfigMock->method('getValue')
            ->with('panth_corewebvitals/resource_hints/dns_prefetch', ScopeInterface::SCOPE_STORE, null)
            ->willReturn("cdn.example.com\napi.example.com\nanalytics.example.com");

        $result = $this->helper->getDnsPrefetchDomains();
        $this->assertCount(3, $result);
        $this->assertContains('cdn.example.com', $result);
    }

    public function testGetDnsPrefetchDomainsReturnsEmptyArrayWhenNull(): void
    {
        $this->scopeConfigMock->method('getValue')
            ->with('panth_corewebvitals/resource_hints/dns_prefetch', ScopeInterface::SCOPE_STORE, null)
            ->willReturn(null);

        $this->assertEmpty($this->helper->getDnsPrefetchDomains());
    }

    public function testIsDnsPrefetchEnabledWhenDomainsExist(): void
    {
        $this->scopeConfigMock->method('getValue')
            ->with('panth_corewebvitals/resource_hints/dns_prefetch', ScopeInterface::SCOPE_STORE, null)
            ->willReturn("cdn.example.com");

        $this->assertTrue($this->helper->isDnsPrefetchEnabled());
    }

    public function testIsDnsPrefetchDisabledWhenEmpty(): void
    {
        $this->scopeConfigMock->method('getValue')
            ->with('panth_corewebvitals/resource_hints/dns_prefetch', ScopeInterface::SCOPE_STORE, null)
            ->willReturn('');

        $this->assertFalse($this->helper->isDnsPrefetchEnabled());
    }

    public function testGetPreconnectOriginsReturnsArray(): void
    {
        $this->scopeConfigMock->method('getValue')
            ->with('panth_corewebvitals/resource_hints/preconnect', ScopeInterface::SCOPE_STORE, null)
            ->willReturn("https://cdn.example.com\nhttps://api.example.com");

        $result = $this->helper->getPreconnectOrigins();
        $this->assertCount(2, $result);
    }

    public function testGetPrefetchUrlsReturnsEmptyArrayWhenNull(): void
    {
        $this->scopeConfigMock->method('getValue')
            ->with('panth_corewebvitals/resource_hints/prefetch', ScopeInterface::SCOPE_STORE, null)
            ->willReturn(null);

        $this->assertEmpty($this->helper->getPrefetchUrls());
    }

    public function testGetConfigJsonReturnsValidJson(): void
    {
        $this->scopeConfigMock->method('getValue')
            ->willReturnMap([
                ['panth_corewebvitals/general/enabled', ScopeInterface::SCOPE_STORE, null, '1'],
                ['panth_corewebvitals/general/debug_mode', ScopeInterface::SCOPE_STORE, null, '1'],
                ['panth_corewebvitals/general/real_user_monitoring', ScopeInterface::SCOPE_STORE, null, '1'],
                ['panth_corewebvitals/lcp/enabled', ScopeInterface::SCOPE_STORE, null, '1'],
                ['panth_corewebvitals/lcp/target_lcp', ScopeInterface::SCOPE_STORE, null, '2500'],
                ['panth_corewebvitals/fid/enabled', ScopeInterface::SCOPE_STORE, null, '1'],
                ['panth_corewebvitals/fid/target_fid', ScopeInterface::SCOPE_STORE, null, '100'],
                ['panth_corewebvitals/fid/target_inp', ScopeInterface::SCOPE_STORE, null, '200'],
                ['panth_corewebvitals/cls/enabled', ScopeInterface::SCOPE_STORE, null, '1'],
                ['panth_corewebvitals/cls/target_cls', ScopeInterface::SCOPE_STORE, null, '0.1'],
            ]);

        $json = $this->helper->getConfigJson();
        $config = json_decode($json, true);

        $this->assertIsArray($config);
        $this->assertTrue($config['enabled']);
        $this->assertTrue($config['debug']);
        $this->assertTrue($config['rum']);
        $this->assertArrayHasKey('lcp', $config);
        $this->assertArrayHasKey('fid', $config);
        $this->assertArrayHasKey('cls', $config);
        $this->assertEquals(2500, $config['lcp']['target']);
        $this->assertEquals(100, $config['fid']['targetFid']);
        $this->assertEquals(200, $config['fid']['targetInp']);
    }

    public function testGetConfigJsonWhenDisabled(): void
    {
        $this->scopeConfigMock->method('getValue')
            ->willReturnMap([
                ['panth_corewebvitals/general/enabled', ScopeInterface::SCOPE_STORE, null, '0'],
                ['panth_corewebvitals/general/debug_mode', ScopeInterface::SCOPE_STORE, null, '0'],
                ['panth_corewebvitals/general/real_user_monitoring', ScopeInterface::SCOPE_STORE, null, '0'],
                ['panth_corewebvitals/lcp/enabled', ScopeInterface::SCOPE_STORE, null, '0'],
                ['panth_corewebvitals/lcp/target_lcp', ScopeInterface::SCOPE_STORE, null, '2500'],
                ['panth_corewebvitals/fid/enabled', ScopeInterface::SCOPE_STORE, null, '0'],
                ['panth_corewebvitals/fid/target_fid', ScopeInterface::SCOPE_STORE, null, '100'],
                ['panth_corewebvitals/fid/target_inp', ScopeInterface::SCOPE_STORE, null, '200'],
                ['panth_corewebvitals/cls/enabled', ScopeInterface::SCOPE_STORE, null, '0'],
                ['panth_corewebvitals/cls/target_cls', ScopeInterface::SCOPE_STORE, null, '0.1'],
            ]);

        $json = $this->helper->getConfigJson();
        $config = json_decode($json, true);
        $this->assertFalse($config['enabled']);
    }
}
