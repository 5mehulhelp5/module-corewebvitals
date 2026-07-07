<?php
declare(strict_types=1);

namespace Panth\CoreWebVitals\Plugin;

use Magento\Framework\App\Response\Http;
use Panth\CoreWebVitals\Helper\Data as ConfigHelper;

class AddPerformanceHeaders
{
    private ConfigHelper $configHelper;

    public function __construct(ConfigHelper $configHelper)
    {
        $this->configHelper = $configHelper;
    }

    public function beforeSendResponse(Http $subject): void
    {
        if (!$this->configHelper->isEnabled()) {
            return;
        }

        $subject->setHeader(
            'Server-Timing',
            'app;desc="PHP Execution";dur=' . $this->getExecutionTime(),
            true
        );

        if ($this->configHelper->isDnsPrefetchEnabled()) {
            $subject->setHeader('X-DNS-Prefetch-Control', 'on', true);
        }

        $preconnectDomains = $this->configHelper->getPreconnectDomains();
        if (!empty($preconnectDomains)) {
            $linkValues = [];
            foreach ($preconnectDomains as $domain) {
                $origin = $this->normalizeOrigin($domain);
                $linkValues[] = '<' . $origin . '>; rel=preconnect; crossorigin';
            }

            $subject->setHeader('Link', implode(', ', $linkValues), true);
        }
    }

    private function getExecutionTime(): float
    {
        $requestTime = $_SERVER['REQUEST_TIME_FLOAT'] ?? microtime(true);
        return round((microtime(true) - $requestTime) * 1000, 2);
    }

    private function normalizeOrigin(string $domain): string
    {
        $domain = trim($domain);
        if (strpos($domain, '//') === 0 || strpos($domain, 'http') === 0) {
            return $domain;
        }
        return 'https://' . $domain;
    }
}
