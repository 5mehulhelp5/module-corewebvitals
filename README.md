# Magento 2 Core Web Vitals

Panth_CoreWebVitals collects Core Web Vitals (LCP, FID, INP and CLS) from real visitors of a Magento 2 storefront. When the module is enabled it renders an inline JavaScript snippet on every frontend page that registers PerformanceObserver listeners in the browser, rates each collected value, and optionally forwards the values to Google Analytics 4 and to a custom beacon endpoint. It also lets the administrator output resource hint link tags (dns-prefetch, preconnect, prefetch) and adds performance related HTTP headers (Server-Timing, X-DNS-Prefetch-Control, Link) to the response.

The module is aimed at merchants and developers who want field measurements of their store's Core Web Vitals without adding a third party library. It uses the standard `head.additional` block and `before.body.end` container and contains no theme specific code, so it works on both Hyva and Luma themes.

Product page: [kishansavaliya.com/magento-2-corewebvitals.html](https://kishansavaliya.com/magento-2-corewebvitals.html)

## Features

- Collects LCP using the `largest-contentful-paint` PerformanceObserver entry type (the last reported entry is used).
- Collects FID using the `first-input` entry type.
- Collects INP using the `event` entry type with a `durationThreshold` of 16 ms; only entries with an interaction id count, and the worst interaction duration seen so far is kept.
- Collects CLS using the `layout-shift` entry type, ignoring shifts that follow recent user input and aggregating shifts into session windows (gap under 1 s, window under 5 s).
- Rates each value as `good`, `needs-improvement` or `poor` using fixed thresholds (LCP 2500/4000 ms, FID 100/300 ms, INP 200/500 ms, CLS 0.1/0.25).
- Sends each metric to GA4 through `gtag()` when `gtag` is present on the page, with an optional `send_to` measurement ID.
- Sends the final value of each metric as a JSON document to a custom endpoint through `navigator.sendBeacon` when the page is hidden or left (again only if the value changed afterwards).
- Dispatches a DOM `CustomEvent` (`coreWebVitals:lcp`, `coreWebVitals:fid`, `coreWebVitals:inp`, `coreWebVitals:cls`) on `window` for every collected metric.
- Debug mode logs every metric to the browser console, exposes `window.coreWebVitalsMetrics`, and prints a final summary when the page becomes hidden.
- Outputs `<link rel="dns-prefetch">`, `<link rel="preconnect" crossorigin>` and `<link rel="prefetch">` tags from admin configured lists.
- Adds a `Server-Timing` header with the PHP execution time, an `X-DNS-Prefetch-Control: on` header when DNS prefetch domains are configured, and a `Link: <origin>; rel=preconnect; crossorigin` header for each preconnect domain.
- Per metric enable switches; all settings are scoped to default, website and store view.
- Unit tests for the helper, plugin and observer under `Test/Unit`.

## Compatibility

| Platform | Versions |
|---|---|
| Magento Open Source | 2.4.4, 2.4.5, 2.4.6, 2.4.7, 2.4.8 (as published on the product page) |
| Adobe Commerce | 2.4.4, 2.4.5, 2.4.6, 2.4.7, 2.4.8 (as published on the product page) |
| PHP | 8.1, 8.2, 8.3, 8.4 (`~8.1.0||~8.2.0||~8.3.0||~8.4.0` in `composer.json`) |
| Themes | Hyva and Luma |

Composer constraints on Magento packages: `magento/framework` `^103.0`, `magento/module-store` `^101.0`, `magento/module-config` `^101.0`, `magento/module-backend` `^102.0`.

## Requirements

- Magento Open Source or Adobe Commerce 2.4.4 to 2.4.8.
- PHP 8.1, 8.2, 8.3 or 8.4.
- `mage2kishan/module-core` `^1.0` (module `Panth_Core`), which provides the "Panth Extensions" admin menu that this module attaches to. Composer installs it automatically.
- No other packages are required or suggested by `composer.json`.

## Installation

```bash
composer require mage2kishan/module-corewebvitals
bin/magento module:enable Panth_Core Panth_CoreWebVitals
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush
```

`setup:di:compile` is only needed when Magento runs in production mode. The module ships no files under `view/*/web`, so `setup:static-content:deploy` is not required for it.

Check that the module is active:

```bash
bin/magento module:status Panth_CoreWebVitals
```

## Configuration

Admin path: Stores > Configuration > Panth Extensions > Core Web Vitals (section id `panth_corewebvitals`). All fields can be set at default, website and store view scope. The module is disabled by default; nothing is rendered and no headers are added until "Enable Module" is set to Yes.

### General Settings

| Setting | Default | What it does |
|---|---|---|
| Enable Module | No | Master switch. Renders the monitoring snippet on frontend pages and activates the resource hints and response headers. All other fields depend on it. |
| Debug Mode | No | Logs collected metrics to the browser console and exposes `window.coreWebVitalsMetrics`. |
| Send Metrics to Analytics | Yes | Sends collected metrics to GA4 (when `gtag` exists on the page) and to the beacon endpoint below. |
| Custom Beacon Endpoint URL | (blank) | Absolute URL that receives a JSON POST through `navigator.sendBeacon` with the final value of each metric when the page is hidden or left. Blank skips the beacon. Shown only when "Send Metrics to Analytics" is Yes. |
| GA4 Measurement ID | (blank) | Optional `G-XXXXXXX` value used as the `send_to` parameter of the `gtag()` call. Shown only when "Send Metrics to Analytics" is Yes. |

Config paths: `panth_corewebvitals/general/enabled`, `panth_corewebvitals/general/debug_mode`, `panth_corewebvitals/general/real_user_monitoring`, `panth_corewebvitals/general/endpoint_url`, `panth_corewebvitals/general/ga4_measurement_id`.

### LCP (Largest Contentful Paint) Monitoring

| Setting | Default | What it does |
|---|---|---|
| Monitor LCP | Yes | Registers the `largest-contentful-paint` observer. |
| Target LCP (milliseconds) | 2500 | Upper bound for a "good" LCP rating. Values above it and up to 4000 ms (or up to the target, if higher) are rated "needs-improvement"; anything slower is "poor". |

Config paths: `panth_corewebvitals/lcp/enabled`, `panth_corewebvitals/lcp/target_lcp`.

### FID / INP (Interaction Responsiveness) Monitoring

| Setting | Default | What it does |
|---|---|---|
| Monitor FID and INP | Yes | Registers the `first-input` and `event` observers. |
| Target FID (milliseconds) | 100 | Upper bound for a "good" FID rating. The "poor" boundary stays at 300 ms unless the target is higher. |
| Target INP (milliseconds) | 200 | Upper bound for a "good" INP rating. The "poor" boundary stays at 500 ms unless the target is higher. |

Config paths: `panth_corewebvitals/fid/enabled`, `panth_corewebvitals/fid/target_fid`, `panth_corewebvitals/fid/target_inp`.

### CLS (Cumulative Layout Shift) Monitoring

| Setting | Default | What it does |
|---|---|---|
| Monitor CLS | Yes | Registers the `layout-shift` observer. |
| Target CLS Score | 0.1 | Upper bound for a "good" CLS rating. The "poor" boundary stays at 0.25 unless the target is higher. |

Config paths: `panth_corewebvitals/cls/enabled`, `panth_corewebvitals/cls/target_cls`.

### Resource Hints

| Setting | Default | What it does |
|---|---|---|
| DNS Prefetch Domains | (blank) | One domain per line without protocol. Each line becomes `<link rel="dns-prefetch" href="//domain">`; a line that already starts with `http://`, `https://` or `//` is used as is. Also turns on the `X-DNS-Prefetch-Control: on` response header. |
| Preconnect Domains | (blank) | One domain per line without protocol. Each line is reduced to its origin (https:// is added when no scheme is given, paths are dropped, duplicates and invalid lines are skipped) and becomes `<link rel="preconnect" href="https://domain" crossorigin>` plus a `Link: <https://domain>; rel=preconnect; crossorigin` response header. |
| Prefetch URLs | (blank) | One full URL per line. Each line becomes `<link rel="prefetch" href="url">`. |

Config paths: `panth_corewebvitals/resource_hints/dns_prefetch`, `panth_corewebvitals/resource_hints/preconnect`, `panth_corewebvitals/resource_hints/prefetch`.

Blank lines and surrounding whitespace in the textarea fields are ignored.

## Usage

Page output. With the module enabled, the frontend `default.xml` layout adds two blocks:

- `panth.corewebvitals.resource.hints` (template `resource-hints.phtml`) in the `head.additional` block. It prints the dns-prefetch, preconnect and prefetch link tags, if any are configured, inside `<head>`.
- `panth.corewebvitals.monitor` (template `core-web-vitals.phtml`) in the `before.body.end` container. It prints an inline `<script>` that reads a JSON configuration object built by `Helper\Data::getConfigJson()` and registers the PerformanceObserver listeners. The script exits immediately if `PerformanceObserver` is not available in the browser.

Metric collection. Each metric is reported with a name (`LCP`, `FID`, `INP`, `CLS`), a numeric value (milliseconds, or a unitless score for CLS), a rating, an id, a delta and the observed entries. Values are rated against the thresholds published for Core Web Vitals (see [web.dev](https://web.dev/articles/vitals)), with the "good" boundary of each metric replaced by the configured target.

Reporting. When "Send Metrics to Analytics" is Yes:

- If `window.gtag` exists, the script calls `gtag('event', <metric name>, {...})` with `event_category: 'Web Vitals'`, `value` (CLS multiplied by 1000 and rounded; other metrics rounded to whole milliseconds), `event_label` (the metric id), `metric_rating` and `non_interaction: true`. When a GA4 Measurement ID is configured it is added as `send_to`.
- If a beacon URL is available, the script posts a JSON body with the fields `name`, `value`, `rating`, `id`, `delta`, `page` (path and query string) and `timestamp` using `navigator.sendBeacon`. The URL comes from "Custom Beacon Endpoint URL"; if that is blank the script falls back to `window.panthCoreWebVitalsEndpoint` when a site defines it.

In addition, every metric dispatches `window.dispatchEvent(new CustomEvent('coreWebVitals:<metric>', { detail }))`, so other scripts can listen with, for example, `window.addEventListener('coreWebVitals:lcp', function (e) { console.log(e.detail); })`.

Response headers. The plugin `Plugin\AddPerformanceHeaders` runs before `Magento\Framework\App\Response\Http::sendResponse()` and, when the module is enabled, sets:

- `Server-Timing: app;desc="PHP Execution";dur=<milliseconds since REQUEST_TIME_FLOAT>` (appended to an existing Server-Timing value)
- `X-DNS-Prefetch-Control: on` when at least one DNS prefetch domain is configured
- `Link: <https://domain>; rel=preconnect; crossorigin, ...` for the configured preconnect domains, appended to an existing Link header

The plugin is declared in `etc/frontend/di.xml`, so it only applies to storefront responses, not to the admin, REST, SOAP or GraphQL areas.

The module has no admin report pages, no scheduled cron jobs and no console commands. Collected metrics are not stored in Magento; they only exist in the browser and in whatever analytics destination is configured.

## Developer Notes

- Module name: `Panth_CoreWebVitals`
- Composer package: `mage2kishan/module-corewebvitals` (version 1.0.11)
- PHP namespace: `Panth\CoreWebVitals`
- Load sequence: after `Magento_Store`, `Magento_Config` and `Panth_Core`
- Key classes:
  - `Helper\Data` reads all configuration values at store scope (the current store unless a store id is passed) and builds the JSON configuration for the script (`getConfigJson()`).
  - `Block\CoreWebVitals` exposes `isEnabled()` and `getConfigJson()` to `core-web-vitals.phtml`.
  - `Block\ResourceHints` exposes `isEnabled()`, `getDnsPrefetchDomains()`, `getPreconnectDomains()` and `getPrefetchUrls()` to `resource-hints.phtml`.
  - `Plugin\AddPerformanceHeaders::beforeSendResponse()` adds the response headers (plugin name `panth_corewebvitals_performance_headers`, sort order 10).
- Extension points: the `coreWebVitals:*` DOM events, the `window.panthCoreWebVitalsEndpoint` global as a fallback beacon URL, and the two named layout blocks, which can be moved or removed in a theme's `default.xml`.
- ACL resources: `Panth_CoreWebVitals::corewebvitals` ("Core Web Vitals") and `Panth_CoreWebVitals::config` ("Configuration"), both under `Magento_Config::config`.
- Admin menu: "Core Web Vitals" > "Configuration" under the "Panth Extensions" menu provided by `Panth_Core`, linking to the configuration section.
- Database: the module ships no `db_schema.xml` and creates no tables.
- Translations: `i18n/en_US.csv`.

## Uninstallation

```bash
bin/magento module:disable Panth_CoreWebVitals
composer remove mage2kishan/module-corewebvitals
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush
```

No database tables are created by the module, so nothing needs to be dropped. Saved configuration values under `panth_corewebvitals/*` remain in `core_config_data` and can be deleted manually if wanted. Remove `Panth_Core` only if no other Panth module depends on it.

## Support

- Product page: [kishansavaliya.com/magento-2-corewebvitals.html](https://kishansavaliya.com/magento-2-corewebvitals.html)
- Contact form: [kishansavaliya.com/contact](https://kishansavaliya.com/contact)
- Email: kishansavaliyakb@gmail.com
- Bug reports: [GitHub issues](https://github.com/mage2sk/module-corewebvitals/issues)

## Documentation

[USER_GUIDE.md](USER_GUIDE.md) is written for store administrators and covers installation, verifying that the module is active, every configuration field, what each metric means, resource hints, the performance HTTP headers, integrating with GA4 or a custom endpoint, and troubleshooting.

## License

Commercial software license. See [LICENSE.txt](LICENSE.txt) in this repository.

## Changelog

See [CHANGELOG.md](CHANGELOG.md).

## Links

- Website: [kishansavaliya.com](https://kishansavaliya.com)
- All extensions: [kishansavaliya.com/magento-extensions.html](https://kishansavaliya.com/magento-extensions.html)
- GitHub: [github.com/mage2sk/module-corewebvitals](https://github.com/mage2sk/module-corewebvitals)
- Packagist: [packagist.org/packages/mage2kishan/module-corewebvitals](https://packagist.org/packages/mage2kishan/module-corewebvitals)
