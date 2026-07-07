<?php
declare(strict_types=1);

namespace Panth\CoreWebVitals\Test\Unit\Observer;

use Panth\CoreWebVitals\Observer\LayoutRenderBefore;
use Magento\Framework\Event\Observer;
use PHPUnit\Framework\TestCase;

class LayoutRenderBeforeTest extends TestCase
{
    public function testExecuteDoesNothing(): void
    {
        $observer = new LayoutRenderBefore();
        $observerMock = $this->createMock(Observer::class);

        $observer->execute($observerMock);
        $this->assertTrue(true);
    }
}
