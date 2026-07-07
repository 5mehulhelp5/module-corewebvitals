<?php
declare(strict_types=1);

namespace Panth\CoreWebVitals\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Panth\CoreWebVitals\Helper\Data as CoreWebVitalsHelper;

class ResourceHints extends Template
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

    public function getDnsPrefetchDomains(): array
    {
        return $this->helper->getDnsPrefetchDomains();
    }

    public function getPreconnectDomains(): array
    {
        return $this->helper->getPreconnectDomains();
    }

    public function getPrefetchUrls(): array
    {
        return $this->helper->getPrefetchUrls();
    }
}
