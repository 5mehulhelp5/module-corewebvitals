<?php
declare(strict_types=1);

namespace Panth\CoreWebVitals\Model;

class OriginNormalizer
{
    public function normalize(string $value): string
    {
        $value = trim($value);
        if ($value === '') {
            return '';
        }
        if (strpos($value, '//') === 0) {
            $value = 'https:' . $value;
        } elseif (!preg_match('#^[a-z][a-z0-9+.-]*://#i', $value)) {
            $value = 'https://' . $value;
        }

        $parts = parse_url($value);
        if (!is_array($parts) || empty($parts['host'])) {
            return '';
        }
        $scheme = strtolower((string) ($parts['scheme'] ?? 'https'));
        if ($scheme !== 'http' && $scheme !== 'https') {
            return '';
        }
        $host = strtolower((string) $parts['host']);
        if (!preg_match('/^[a-z0-9.\-]+$|^\[[0-9a-f:.]+\]$/', $host)) {
            return '';
        }
        $origin = $scheme . '://' . $host;
        if (isset($parts['port'])) {
            $origin .= ':' . (int) $parts['port'];
        }
        return $origin;
    }

    public function normalizeList(array $values): array
    {
        $origins = [];
        foreach ($values as $value) {
            $origin = $this->normalize((string) $value);
            if ($origin !== '' && !in_array($origin, $origins, true)) {
                $origins[] = $origin;
            }
        }
        return $origins;
    }
}
