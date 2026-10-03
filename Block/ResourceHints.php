<?php
declare(strict_types=1);

namespace Panth\CoreWebVitals\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Panth\CoreWebVitals\Helper\Data as CoreWebVitalsHelper;
use Panth\CoreWebVitals\Model\OriginNormalizer;

class ResourceHints extends Template
{
    private CoreWebVitalsHelper $helper;

    private ?OriginNormalizer $originNormalizer = null;

    public function __construct(
        Context $context,
        CoreWebVitalsHelper $helper,
        array $data = [],
        ?OriginNormalizer $originNormalizer = null
    ) {
        $this->helper = $helper;
        $this->originNormalizer = $originNormalizer;
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

    public function getDnsPrefetchHrefs(): array
    {
        $hrefs = [];
        foreach ($this->getDnsPrefetchDomains() as $domain) {
            $href = $this->toHintHref((string) $domain);
            if ($href !== '' && !in_array($href, $hrefs, true)) {
                $hrefs[] = $href;
            }
        }
        return $hrefs;
    }

    public function getPreconnectOrigins(): array
    {
        return $this->getOriginNormalizer()->normalizeList($this->getPreconnectDomains());
    }

    public function getUniquePrefetchUrls(): array
    {
        return array_values(array_unique(array_filter(array_map('trim', $this->getPrefetchUrls()))));
    }

    public function toHintHref(string $domain): string
    {
        $domain = trim($domain);
        if ($domain === '' || strpos($domain, '//') === 0 || preg_match('#^https?://#i', $domain)) {
            return $domain;
        }
        return '//' . $domain;
    }

    private function getOriginNormalizer(): OriginNormalizer
    {
        if ($this->originNormalizer === null) {
            $this->originNormalizer = new OriginNormalizer();
        }
        return $this->originNormalizer;
    }
}
