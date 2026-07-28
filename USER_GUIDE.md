# Panth Core Web Vitals - User Guide

This guide is for store administrators who want to set up real-time
Core Web Vitals monitoring on their Magento 2 storefront using the
Panth_CoreWebVitals extension.

---

## Table of contents

1. [Installation](#1-installation)
2. [Verifying the module is active](#2-verifying-the-module-is-active)
3. [Configuration](#3-configuration)
4. [Understanding the metrics](#4-understanding-the-metrics)
5. [Resource hints](#5-resource-hints)
6. [Performance HTTP headers](#6-performance-http-headers)
7. [Integrating with analytics](#7-integrating-with-analytics)
8. [Troubleshooting](#8-troubleshooting)

---

## 1. Installation

### Composer (recommended)

```bash
composer require mage2kishan/module-corewebvitals
bin/magento module:enable Panth_CoreWebVitals
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f
bin/magento cache:flush
```

### Manual zip

1. Download the extension package zip
2. Extract to `app/code/Panth/CoreWebVitals`
3. Run the same `module:enable ... cache:flush` commands above

---

## 2. Verifying the module is active

```bash
bin/magento module:status Panth_CoreWebVitals
# Module is enabled
```

Navigate to **Stores > Configuration > Panth Extensions** and confirm
that the **Core Web Vitals** section is visible.

---

## 3. Configuration

Open **Stores > Configuration > Panth Extensions > Core Web Vitals**.

### General Settings

| Setting | Default | Description |
|---|---|---|
| **Enable Module** | No | Master switch. When enabled, injects a lightweight JavaScript snippet on every frontend page that monitors Core Web Vitals using the PerformanceObserver API. |
| **Debug Mode** | No | Logs all collected metrics (LCP, FID, INP, CLS) to the browser developer console. Also exposes `window.coreWebVitalsMetrics` for manual inspection. Disable in production. |
| **Send Metrics to Analytics** | Yes | Sends metrics to Google Analytics 4 (if `gtag` is present) or to a custom endpoint via `navigator.sendBeacon`. |

### LCP (Largest Contentful Paint)

| Setting | Default | Description |
|---|---|---|
| **Monitor LCP** | Yes | Track Largest Contentful Paint. Measures the time it takes for the largest visible element to render. |
| **Target LCP (ms)** | 2500 | Your target LCP time. Google considers < 2500ms "good". |

### FID / INP (Interaction Responsiveness)

| Setting | Default | Description |
|---|---|---|
| **Monitor FID and INP** | Yes | Track First Input Delay and Interaction to Next Paint. INP replaced FID as a Core Web Vital in March 2024. |
| **Target FID (ms)** | 100 | Google recommends < 100ms. |
| **Target INP (ms)** | 200 | Google recommends < 200ms. |

### CLS (Cumulative Layout Shift)

| Setting | Default | Description |
|---|---|---|
| **Monitor CLS** | Yes | Track Cumulative Layout Shift. Measures visual stability during loading. |
| **Target CLS Score** | 0.1 | Google considers < 0.1 "good". |

### Resource Hints

| Setting | Description |
|---|---|
| **DNS Prefetch Domains** | One domain per line, without protocol (e.g., `fonts.googleapis.com`). Outputs `<link rel="dns-prefetch">` tags. Saves 20-120ms per domain on first request. |
| **Preconnect Domains** | One domain per line (e.g., `fonts.gstatic.com`). Outputs `<link rel="preconnect" crossorigin>` tags. Performs DNS + TCP + TLS handshake early. Limit to 2-4 critical origins. |
| **Prefetch URLs** | One full URL per line. Outputs `<link rel="prefetch">` tags. Downloads resources at low priority for the next navigation. |

---

## 4. Understanding the metrics

Each metric is rated as **good**, **needs improvement**, or **poor**
using Google's standard thresholds:

| Metric | Good | Needs improvement | Poor |
|---|---|---|---|
| LCP | <= 2500ms | <= 4000ms | > 4000ms |
| FID | <= 100ms | <= 300ms | > 300ms |
| INP | <= 200ms | <= 500ms | > 500ms |
| CLS | <= 0.1 | <= 0.25 | > 0.25 |

When debug mode is enabled, open the browser developer console
(F12 > Console) to see real-time metric reports.

---

## 5. Resource hints

Resource hints tell the browser to resolve DNS, establish connections,
or fetch resources ahead of time. Configure them under
**Resource Hints** in the admin:

- **dns-prefetch** - resolves a domain name early, saving DNS lookup time
- **preconnect** - performs the full handshake (DNS + TCP + TLS) early
- **prefetch** - downloads a resource at low priority for use on the
  next page navigation

These are rendered as `<link>` tags near the top of the page.

---

## 6. Performance HTTP headers

When the module is enabled, the following HTTP headers are automatically
added to every frontend response:

| Header | Description |
|---|---|
| `Server-Timing` | Reports PHP execution time in milliseconds. Visible in the browser DevTools Network tab under "Timing". |
| `X-DNS-Prefetch-Control` | Set to `on` when dns-prefetch domains are configured. Enables speculative DNS resolution. |
| `Link` | Combined `rel=preconnect` hints for all configured preconnect domains (RFC 8288). |

---

## 7. Integrating with analytics

### Google Analytics 4

If `gtag()` is present on the page, metrics are automatically sent as
GA4 events with `event_category: "Web Vitals"`. No extra configuration
is needed.

### Custom beacon endpoint

Set `window.panthCoreWebVitalsEndpoint` to your collection URL before
the monitoring script runs. Metrics will be sent via
`navigator.sendBeacon` as JSON.

### Custom DOM events

Each metric dispatches a `CustomEvent` on the `window` object:

- `coreWebVitals:lcp`
- `coreWebVitals:fid`
- `coreWebVitals:inp`
- `coreWebVitals:cls`

Listen for these events to build custom integrations.

---

## 8. Troubleshooting

| Symptom | Cause | Fix |
|---|---|---|
| No metrics in console | Module disabled or debug mode off | Enable both in admin config and flush cache |
| "PerformanceObserver is not defined" | Very old browser (IE11) | PerformanceObserver requires a modern browser. The script gracefully exits on unsupported browsers. |
| Metrics not appearing in GA4 | `gtag` not loaded or RUM disabled | Verify `gtag` is on the page and "Send Metrics to Analytics" is enabled |
| Resource hint `<link>` tags missing | Module disabled or no domains configured | Enable the module and add domains under Resource Hints |

---

## Support

For all questions, bug reports, or feature requests:

- **Email:** kishansavaliyakb@gmail.com
- **Website:** https://kishansavaliya.com
- **WhatsApp:** +91 84012 70422

Free email support is provided on a best-effort basis.
