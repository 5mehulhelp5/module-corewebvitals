<?php
declare(strict_types=1);

namespace Panth\CoreWebVitals\Plugin;

use Laminas\Http\Header\HeaderInterface;
use Magento\Framework\App\Response\Http;
use Panth\CoreWebVitals\Helper\Data as ConfigHelper;
use Panth\CoreWebVitals\Model\OriginNormalizer;

class AddPerformanceHeaders
{
    private ConfigHelper $configHelper;

    private OriginNormalizer $originNormalizer;

    public function __construct(ConfigHelper $configHelper, ?OriginNormalizer $originNormalizer = null)
    {
        $this->configHelper = $configHelper;
        $this->originNormalizer = $originNormalizer ?? new OriginNormalizer();
    }

    public function beforeSendResponse(Http $subject): void
    {
        if (!$this->configHelper->isEnabled()) {
            return;
        }

        $timing = 'app;desc="PHP Execution";dur=' . $this->getExecutionTime();
        $existingTiming = $this->getExistingHeaderValue($subject, 'Server-Timing');
        $subject->setHeader(
            'Server-Timing',
            $existingTiming === '' ? $timing : $existingTiming . ', ' . $timing,
            true
        );

        if ($this->configHelper->isDnsPrefetchEnabled()) {
            $subject->setHeader('X-DNS-Prefetch-Control', 'on', true);
        }

        $origins = $this->originNormalizer->normalizeList($this->configHelper->getPreconnectDomains());
        if (empty($origins)) {
            return;
        }

        $existing = $this->getExistingHeaderValue($subject, 'Link');
        $linkValues = $existing === '' ? [] : [$existing];
        $added = false;
        foreach ($origins as $origin) {
            if ($existing !== '' && stripos($existing, '<' . $origin . '>; rel=preconnect') !== false) {
                continue;
            }
            $linkValues[] = '<' . $origin . '>; rel=preconnect; crossorigin';
            $added = true;
        }

        if ($added) {
            $subject->setHeader('Link', implode(', ', $linkValues), true);
        }
    }

    private function getExecutionTime(): float
    {
        $requestTime = $_SERVER['REQUEST_TIME_FLOAT'] ?? microtime(true);
        return round((microtime(true) - $requestTime) * 1000, 2);
    }

    private function getExistingHeaderValue(Http $subject, string $name): string
    {
        $header = $subject->getHeader($name);
        $headers = $header instanceof \Traversable ? iterator_to_array($header, false) : [$header];
        $values = [];
        foreach ($headers as $item) {
            if ($item instanceof HeaderInterface && trim((string) $item->getFieldValue()) !== '') {
                $values[] = trim((string) $item->getFieldValue());
            }
        }
        return implode(', ', $values);
    }
}
