<?php
declare(strict_types=1);

namespace Panth\CoreWebVitals\Test\Unit\Block;

use Magento\Framework\View\Element\Template\Context;
use Panth\CoreWebVitals\Block\CoreWebVitals;
use Panth\CoreWebVitals\Helper\Data as Helper;
use PHPUnit\Framework\TestCase;

class CoreWebVitalsTest extends TestCase
{
    private function block(Helper $helper): CoreWebVitals
    {
        $reflection = new \ReflectionClass(CoreWebVitals::class);
        $block = $reflection->newInstanceWithoutConstructor();
        $property = $reflection->getProperty('helper');
        $property->setValue($block, $helper);
        return $block;
    }

    public function testConstructorStoresHelper(): void
    {
        $this->assertTrue((new \ReflectionClass(CoreWebVitals::class))->hasProperty('helper'));
        $params = (new \ReflectionMethod(CoreWebVitals::class, '__construct'))->getParameters();
        $this->assertSame(Context::class, (string) $params[0]->getType());
        $this->assertSame(Helper::class, (string) $params[1]->getType());
    }

    public function testIsEnabledDelegatesToHelper(): void
    {
        $on = $this->createStub(Helper::class);
        $on->method('isEnabled')->willReturn(true);
        $off = $this->createStub(Helper::class);
        $off->method('isEnabled')->willReturn(false);

        $this->assertTrue($this->block($on)->isEnabled());
        $this->assertFalse($this->block($off)->isEnabled());
    }

    public function testGetConfigJsonReturnsHelperJsonUnchanged(): void
    {
        $json = '{"enabled":true,"lcp":{"target":2500}}';
        $helper = $this->createStub(Helper::class);
        $helper->method('getConfigJson')->willReturn($json);

        $this->assertSame($json, $this->block($helper)->getConfigJson());
    }
}
