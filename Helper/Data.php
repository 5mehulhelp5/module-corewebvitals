<?php
declare(strict_types=1);

namespace Panth\CoreWebVitals\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    private const XML_PATH = 'panth_corewebvitals/';

    private function getConfigValue(string $group, string $field, ?int $storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH . $group . '/' . $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function isEnabled(?int $storeId = null): bool
    {
        return (bool) $this->getConfigValue('general', 'enabled', $storeId);
    }

    public function isDebugMode(?int $storeId = null): bool
    {
        return $this->isEnabled($storeId)
            && (bool) $this->getConfigValue('general', 'debug_mode', $storeId);
    }

    public function isRealUserMonitoring(?int $storeId = null): bool
    {
        return $this->isEnabled($storeId)
            && (bool) $this->getConfigValue('general', 'real_user_monitoring', $storeId);
    }

    public function getEndpointUrl(?int $storeId = null): string
    {
        return trim((string) $this->getConfigValue('general', 'endpoint_url', $storeId));
    }

    public function getGa4MeasurementId(?int $storeId = null): string
    {
        return trim((string) $this->getConfigValue('general', 'ga4_measurement_id', $storeId));
    }

    public function isLcpEnabled(?int $storeId = null): bool
    {
        return $this->isEnabled($storeId)
            && (bool) $this->getConfigValue('lcp', 'enabled', $storeId);
    }

    public function getTargetLcp(?int $storeId = null): int
    {
        return (int) $this->getConfigValue('lcp', 'target_lcp', $storeId) ?: 2500;
    }

    public function isFidEnabled(?int $storeId = null): bool
    {
        return $this->isEnabled($storeId)
            && (bool) $this->getConfigValue('fid', 'enabled', $storeId);
    }

    public function getTargetFid(?int $storeId = null): int
    {
        return (int) $this->getConfigValue('fid', 'target_fid', $storeId) ?: 100;
    }

    public function getTargetInp(?int $storeId = null): int
    {
        return (int) $this->getConfigValue('fid', 'target_inp', $storeId) ?: 200;
    }

    public function isClsEnabled(?int $storeId = null): bool
    {
        return $this->isEnabled($storeId)
            && (bool) $this->getConfigValue('cls', 'enabled', $storeId);
    }

    public function getTargetCls(?int $storeId = null): float
    {
        return (float) $this->getConfigValue('cls', 'target_cls', $storeId) ?: 0.1;
    }

    public function isDnsPrefetchEnabled(?int $storeId = null): bool
    {
        return !empty($this->getDnsPrefetchDomains($storeId));
    }

    public function getDnsPrefetchDomains(?int $storeId = null): array
    {
        $domains = $this->getConfigValue('resource_hints', 'dns_prefetch', $storeId);
        return $this->splitTextareaLines((string) ($domains ?? ''));
    }

    public function getPreconnectDomains(?int $storeId = null): array
    {
        $domains = $this->getConfigValue('resource_hints', 'preconnect', $storeId);
        return $this->splitTextareaLines((string) ($domains ?? ''));
    }

    public function getPreconnectOrigins(?int $storeId = null): array
    {
        return $this->getPreconnectDomains($storeId);
    }

    public function getPrefetchUrls(?int $storeId = null): array
    {
        $urls = $this->getConfigValue('resource_hints', 'prefetch', $storeId);
        return $this->splitTextareaLines((string) ($urls ?? ''));
    }

    public function getConfigJson(?int $storeId = null): string
    {
        return (string) json_encode([
            'enabled'     => $this->isEnabled($storeId),
            'debug'       => $this->isDebugMode($storeId),
            'rum'         => $this->isRealUserMonitoring($storeId),
            'endpointUrl' => $this->getEndpointUrl($storeId),
            'ga4Id'       => $this->getGa4MeasurementId($storeId),
            'lcp'     => [
                'enabled' => $this->isLcpEnabled($storeId),
                'target'  => $this->getTargetLcp($storeId),
            ],
            'fid'     => [
                'enabled'   => $this->isFidEnabled($storeId),
                'targetFid' => $this->getTargetFid($storeId),
                'targetInp' => $this->getTargetInp($storeId),
            ],
            'cls'     => [
                'enabled' => $this->isClsEnabled($storeId),
                'target'  => $this->getTargetCls($storeId),
            ],
        ]);
    }

    private function splitTextareaLines(string $text): array
    {
        if ($text === '') {
            return [];
        }
        return array_values(array_filter(array_map('trim', explode("\n", $text))));
    }
}
