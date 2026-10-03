<?php
declare(strict_types=1);

namespace Panth\CoreWebVitals\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Panth\CoreWebVitals\Helper\Data as CoreWebVitalsHelper;

class CoreWebVitals extends Template
{
    private CoreWebVitalsHelper $helper;

    public function __construct(
        Context $context,
        CoreWebVitalsHelper $helper,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    public function isEnabled(): bool
    {
        return $this->helper->isEnabled();
    }

    public function getConfigJson(): string
    {
        return $this->helper->getConfigJson();
    }
}
