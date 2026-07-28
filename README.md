<!-- SEO Meta -->
<!--
  Title: Magento 2 Core Web Vitals Extension: LCP, FID, INP and CLS Monitoring | Hyva + Luma | Panth Infotech
  Description: Panth Core Web Vitals tracks LCP, FID, INP, and CLS on every Magento 2 storefront page using the native PerformanceObserver API. Configurable resource hints (dns-prefetch, preconnect, prefetch), performance HTTP headers, GA4 analytics integration, and a custom beacon endpoint. Works on Magento 2.4.4 to 2.4.8, PHP 8.1 to 8.4, Hyva and Luma. Built by Top Rated Plus Magento developer Kishan Savaliya.
  Keywords: magento 2 core web vitals, core web vitals magento 2, magento 2 LCP, magento 2 FID, magento 2 INP, magento 2 CLS, magento 2 page speed extension, magento 2 performance monitoring, real user monitoring magento, PerformanceObserver magento 2, magento 2 resource hints, magento 2 Server-Timing header, magento 2 GA4 web vitals, hyva core web vitals, luma core web vitals
  Author: Kishan Savaliya (Panth Infotech)
  Canonical: https://kishansavaliya.com/magento-2-corewebvitals.html
-->

# Magento 2 Core Web Vitals Extension: LCP, FID, INP and CLS Monitoring (Hyva + Luma)

[![Magento 2.4.4 - 2.4.8](https://img.shields.io/badge/Magento-2.4.4%20--%202.4.8-orange?logo=magento&logoColor=white)](https://magento.com)
[![PHP 8.1 - 8.4](https://img.shields.io/badge/PHP-8.1%20--%208.4-blue?logo=php&logoColor=white)](https://php.net)
[![Hyva + Luma](https://img.shields.io/badge/Themes-Hyva%20%2B%20Luma-14b8a6)](https://www.hyva.io)
[![Live Demo & Details](https://img.shields.io/badge/Live%20Demo%20%26%20Details-magento--2--corewebvitals-0D9488?style=flat)](https://kishansavaliya.com/magento-2-corewebvitals.html)
[![Packagist](https://img.shields.io/badge/Packagist-mage2kishan%2Fmodule--corewebvitals-orange?logo=packagist&logoColor=white)](https://packagist.org/packages/mage2kishan/module-corewebvitals)
[![Upwork Top Rated Plus](https://img.shields.io/badge/Upwork-Top%20Rated%20Plus-14a800?logo=upwork&logoColor=white)](https://www.upwork.com/freelancers/~016dd1767321100e21)
[![Website](https://img.shields.io/badge/Website-kishansavaliya.com-0D9488)](https://kishansavaliya.com)

> **Measure real Core Web Vitals from real shoppers on every page of your Magento 2 store.** Panth Core Web Vitals injects a lightweight JavaScript snippet that uses the native browser PerformanceObserver API to collect LCP, FID, INP, and CLS. It also adds resource hints (dns-prefetch, preconnect, prefetch) and performance HTTP headers from the admin, with no third-party library needed. Works on both **Hyva** and **Luma**.

**Product page:** [kishansavaliya.com/magento-2-corewebvitals.html](https://kishansavaliya.com/magento-2-corewebvitals.html)

---

## Quick Answer

**What is Panth Core Web Vitals?** It is a Magento 2 extension that collects LCP, FID, INP, and CLS from every real visitor using the browser's native PerformanceObserver API, then sends those metrics to GA4 or a custom endpoint so you can track your Google ranking signals over time.

**What does it add to my store?**

- A **lightweight monitoring snippet** that records LCP, FID, INP, and CLS on every frontend page load, with no polling and no third-party library.
- **Per-metric targets** you set in the admin, so each metric is rated as good, needs improvement, or poor.
- **Resource hints** (dns-prefetch, preconnect, prefetch) you configure from the admin to reduce latency for fonts, CDNs, and analytics origins.
- **Performance HTTP headers** (Server-Timing, X-DNS-Prefetch-Control, Link rel=preconnect) added to every frontend response.
- **GA4 and custom beacon integration** so metrics go to your own analytics pipeline.

**Which themes are supported?** Both **Hyva** and **Luma**. The snippet loads via standard layout XML and has no theme-specific dependencies.

**What does it need?** Magento 2.4.4 to 2.4.8, PHP 8.1 to 8.4, and the free `mage2kishan/module-core` package.

---

## Need Custom Magento 2 Development?

> **Get a free quote for your project in 24 hours** for custom modules, Hyva themes, performance work, M1 to M2 migrations, and Adobe Commerce Cloud.

<p align="center">
  <a href="https://kishansavaliya.com/get-quote">
    <img src="https://img.shields.io/badge/Get%20a%20Free%20Quote%20%E2%86%92-Reply%20within%2024%20hours-DC2626?style=for-the-badge" alt="Get a Free Quote" />
  </a>
</p>

<table>
<tr>
<td width="50%" align="center">

### Kishan Savaliya
**Top Rated Plus on Upwork**

[![Hire on Upwork](https://img.shields.io/badge/Hire%20on%20Upwork-Top%20Rated%20Plus-14a800?style=for-the-badge&logo=upwork&logoColor=white)](https://www.upwork.com/freelancers/~016dd1767321100e21)

100% Job Success • 10+ Years Magento Experience
Adobe Certified • Hyva Specialist

</td>
<td width="50%" align="center">

### Panth Infotech Agency
**Magento Development Team**

[![Visit Agency](https://img.shields.io/badge/Visit%20Agency-Panth%20Infotech-14a800?style=for-the-badge&logo=upwork&logoColor=white)](https://www.upwork.com/agencies/1881421506131960778/)

Custom Modules • Theme Design • Migrations
Performance • SEO • Adobe Commerce Cloud

</td>
</tr>
</table>

**Visit our website:** [kishansavaliya.com](https://kishansavaliya.com) &nbsp;|&nbsp; **Get a quote:** [kishansavaliya.com/get-quote](https://kishansavaliya.com/get-quote)

---

## Table of Contents

- [Who Is It For](#who-is-it-for)
- [Key Features](#key-features)
- [Metric Thresholds](#metric-thresholds)
- [Compatibility](#compatibility)
- [Installation](#installation)
- [Configuration](#configuration)
- [How It Works](#how-it-works)
- [FAQ](#faq)
- [Support](#support)
- [About Panth Infotech](#about-panth-infotech)
- [Quick Links](#quick-links)

---

## Who Is It For

- **Merchants who care about Google rankings** and want field data (real user measurements) instead of synthetic lab tests from PageSpeed Insights.
- **Stores with slow LCP or high CLS** that want to identify problem pages using real visitor data before making code changes.
- **Hyva and Luma stores** that need a zero-dependency monitoring snippet that loads without blocking the page.
- **Developers and agencies** who want to send Core Web Vitals to GA4 or a custom RUM endpoint without writing the JavaScript themselves.
- **Multi-site merchants** who need per-store-view metric targets and resource hint configuration.

---

## Key Features

### Real-time Metric Collection (PerformanceObserver API)
- **LCP tracking** using the `largest-contentful-paint` entry type, captured on the final paint before user input.
- **FID tracking** using the `first-input` entry type, for legacy browser dashboards.
- **INP tracking** using the `event` entry type, the official Core Web Vital since March 2024.
- **CLS tracking** using the `layout-shift` entry type with session-window aggregation.
- **Zero runtime dependencies**, vanilla JavaScript, loads with `async` + `defer`.
- **Debug mode** that logs all collected metrics to the browser console and exposes `window.coreWebVitalsMetrics` for inspection.

### Per-metric Targets and Ratings
- Each metric (LCP, FID/INP, CLS) can be **independently enabled or disabled** from the admin.
- Set your own **target thresholds** for each metric. The module rates each value as good, needs improvement, or poor against your targets and against Google's official thresholds.

### Resource Hints
- **DNS prefetch** - one domain per line in the admin, outputs `<link rel="dns-prefetch">` in the page head to resolve hostnames early.
- **Preconnect** - performs DNS, TCP, and TLS handshake ahead of time for critical origins like fonts and CDNs.
- **Prefetch** - low-priority resource download for the next page navigation.

### Performance HTTP Headers
- **Server-Timing: php;dur=NN** added to every frontend response so PHP execution time is visible in DevTools.
- **X-DNS-Prefetch-Control: on** to enable speculative DNS on Chromium and WebKit.
- **Link: rel=preconnect** at the HTTP header layer so the browser can act before HTML parsing begins.

### Analytics Integration
- **GA4 integration** via `gtag`, sends metrics as events with `event_category: "Web Vitals"`. Enter an optional GA4 Measurement ID to route to a specific property.
- **Custom beacon endpoint** via `navigator.sendBeacon` for your own RUM pipeline.
- **Browser CustomEvents** dispatched for each metric (`coreWebVitals:lcp`, `coreWebVitals:fid`, `coreWebVitals:inp`, `coreWebVitals:cls`) for third-party integrations.

### Admin and Code Quality
- **Admin section** at Stores -> Configuration -> Panth Extensions -> Core Web Vitals.
- **Scope-aware settings** that respect Magento's default, website, and store view hierarchy.
- **MEQP-style code** with constructor DI only and no ObjectManager.
- **Translation ready**, every label uses Magento's `__()` function.

---

## Metric Thresholds

| Metric | Good | Needs Improvement | Poor |
|---|---|---|---|
| **LCP** (Largest Contentful Paint) | less than 2500 ms | less than 4000 ms | more than 4000 ms |
| **FID** (First Input Delay) | less than 100 ms | less than 300 ms | more than 300 ms |
| **INP** (Interaction to Next Paint) | less than 200 ms | less than 500 ms | more than 500 ms |
| **CLS** (Cumulative Layout Shift) | less than 0.1 | less than 0.25 | more than 0.25 |

Targets align with Google's official thresholds from [web.dev/vitals](https://web.dev/vitals/).

---

## Compatibility

| Requirement | Versions Supported |
|---|---|
| Magento Open Source | 2.4.4, 2.4.5, 2.4.6, 2.4.7, 2.4.8 |
| Adobe Commerce | 2.4.4, 2.4.5, 2.4.6, 2.4.7, 2.4.8 |
| Adobe Commerce Cloud | 2.4.4 to 2.4.8 |
| PHP | 8.1.x, 8.2.x, 8.3.x, 8.4.x |
| Hyva Theme | 1.0+ (fully supported) |
| Luma Theme | Native support |
| Required Dependency | `mage2kishan/module-core` (free) |

---

## Installation

### Composer Installation (Recommended)

```bash
composer require mage2kishan/module-corewebvitals
bin/magento module:enable Panth_Core Panth_CoreWebVitals
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f
bin/magento cache:flush
```

### Manual Installation via ZIP

1. Download the latest release from [Packagist](https://packagist.org/packages/mage2kishan/module-corewebvitals) or from the [product page](https://kishansavaliya.com/magento-2-corewebvitals.html).
2. Extract it to `app/code/Panth/CoreWebVitals/` in your Magento install.
3. Make sure `Panth_Core` is installed too (required dependency).
4. Run the commands above starting from `bin/magento module:enable`.

### Verify Installation

```bash
bin/magento module:status Panth_CoreWebVitals
# Expected: Module is enabled
```

After install, open:
```
Admin -> Stores -> Configuration -> Panth Extensions -> Core Web Vitals
```

Set **Enable Module = Yes** and flush the cache.

---

## Configuration

Go to **Stores -> Configuration -> Panth Extensions -> Core Web Vitals**.

### General Settings

| Setting | Group | Default | Description |
|---|---|---|---|
| Enable Module | General | No | Master toggle. Injects the monitoring snippet on every frontend page. |
| Debug Mode | General | No | Logs metrics to the browser console and exposes `window.coreWebVitalsMetrics`. Disable in production. |
| Send Metrics to Analytics | General | Yes | Forwards metrics to GA4 via `gtag` or to the custom beacon endpoint. |
| Custom Beacon Endpoint URL | General | (blank) | Absolute URL that receives metric data as JSON via `navigator.sendBeacon`. |
| GA4 Measurement ID | General | (blank) | Optional `G-XXXXXXX` ID to route metrics to a specific GA4 property. |

### LCP Monitoring

| Setting | Group | Default | Description |
|---|---|---|---|
| Monitor LCP | LCP | Yes | Enable Largest Contentful Paint tracking. |
| Target LCP (milliseconds) | LCP | 2500 | Your target LCP time. Used to rate the metric as good, needs improvement, or poor. |

### FID / INP Monitoring

| Setting | Group | Default | Description |
|---|---|---|---|
| Monitor FID and INP | FID / INP | Yes | Enable First Input Delay and Interaction to Next Paint tracking. |
| Target FID (milliseconds) | FID / INP | 100 | Your target First Input Delay. |
| Target INP (milliseconds) | FID / INP | 200 | Your target Interaction to Next Paint. INP is the primary interaction metric since March 2024. |

### CLS Monitoring

| Setting | Group | Default | Description |
|---|---|---|---|
| Monitor CLS | CLS | Yes | Enable Cumulative Layout Shift tracking. |
| Target CLS Score | CLS | 0.1 | Your target CLS score (unitless). |

### Resource Hints

| Setting | Group | Default | Description |
|---|---|---|---|
| DNS Prefetch Domains | Resource Hints | (blank) | One domain per line, no protocol. Outputs `<link rel="dns-prefetch">` in the page head. |
| Preconnect Domains | Resource Hints | (blank) | One domain per line. Outputs `<link rel="preconnect" crossorigin>`. Limit to 2 to 4 critical origins. |
| Prefetch URLs | Resource Hints | (blank) | One full URL per line for low-priority next-navigation prefetch. |

---

## How It Works

1. When the module is enabled, a block renders `core-web-vitals.phtml` in the page head via layout XML.
2. The template outputs a small JavaScript snippet that registers PerformanceObserver listeners for `largest-contentful-paint`, `first-input`, `event` (INP), and `layout-shift` entry types.
3. Each time a metric value is captured, it is rated against your configured target and Google's thresholds, then sent to GA4 via `gtag` if present, to your custom beacon endpoint via `navigator.sendBeacon`, and as a browser CustomEvent.
4. If Debug Mode is on, every metric value is also logged to the browser console.
5. A second block renders `resource-hints.phtml`, which outputs `<link>` tags for dns-prefetch, preconnect, and prefetch from your admin settings.
6. A response plugin adds the Server-Timing, X-DNS-Prefetch-Control, and Link headers to every frontend HTTP response.

---

## FAQ

### Does this replace Google PageSpeed Insights?
No. PageSpeed Insights runs synthetic lab tests from Google's servers. This module collects field data from your real visitors, which is what Google actually uses as a ranking signal.

### Will the snippet slow down my pages?
No. The snippet loads with `defer`, uses the native PerformanceObserver API with no polling, and has no third-party library dependency.

### Is INP the same as FID?
INP replaced FID as an official Core Web Vital in March 2024. The module tracks both. FID is kept for legacy dashboards and backwards compatibility. INP is the current ranking signal.

### Does it work on Hyva themes?
Yes. The snippet and resource hints load via standard `default_head_blocks.xml` and have no Hyva-specific or Luma-specific code.

### Does it send data to third parties by default?
No. With the default configuration, no data leaves your store unless you have GA4 (`gtag`) loaded on your pages. You control every destination via the admin settings.

### Is it GDPR / CCPA compliant?
Yes. No personal data is collected. The metrics are anonymous performance numbers. You decide where they are sent.

### Does it work with multi-store setups?
Yes. All settings respect Magento's scope hierarchy: default, website, and store view.

### Can I use it with Adobe Commerce Cloud and Fastly?
Yes. The Server-Timing and Link headers work with Fastly's VCL. Make sure your VCL does not strip these headers if you want them visible in DevTools.

### Does Panth Core Web Vitals need Panth Core?
Yes. `mage2kishan/module-core` is a free, required dependency that Composer installs for you automatically.

---

## Support

| Channel | Contact |
|---|---|
| Product Page | [kishansavaliya.com/magento-2-corewebvitals.html](https://kishansavaliya.com/magento-2-corewebvitals.html) |
| Email | kishansavaliyakb@gmail.com |
| Website | [kishansavaliya.com](https://kishansavaliya.com) |
| WhatsApp | +91 84012 70422 |
| GitHub Issues | [github.com/mage2sk/module-corewebvitals/issues](https://github.com/mage2sk/module-corewebvitals/issues) |
| Upwork (Top Rated Plus) | [Hire Kishan Savaliya](https://www.upwork.com/freelancers/~016dd1767321100e21) |
| Upwork Agency | [Panth Infotech](https://www.upwork.com/agencies/1881421506131960778/) |

Response time: 1-2 business days.

### Need Custom Magento Development?

Looking for **Core Web Vitals optimization**, **Hyva migrations**, **Luma speed tuning**, or **Adobe Commerce Cloud performance work**? Get a free quote in 24 hours:

<p align="center">
  <a href="https://kishansavaliya.com/get-quote">
    <img src="https://img.shields.io/badge/%F0%9F%92%AC%20Get%20a%20Free%20Quote-kishansavaliya.com%2Fget--quote-DC2626?style=for-the-badge" alt="Get a Free Quote" />
  </a>
</p>

<p align="center">
  <a href="https://www.upwork.com/freelancers/~016dd1767321100e21">
    <img src="https://img.shields.io/badge/Hire%20Kishan-Top%20Rated%20Plus-14a800?style=for-the-badge&logo=upwork&logoColor=white" alt="Hire on Upwork" />
  </a>
  &nbsp;&nbsp;
  <a href="https://www.upwork.com/agencies/1881421506131960778/">
    <img src="https://img.shields.io/badge/Visit-Panth%20Infotech%20Agency-14a800?style=for-the-badge&logo=upwork&logoColor=white" alt="Visit Agency" />
  </a>
  &nbsp;&nbsp;
  <a href="https://kishansavaliya.com/magento-2-corewebvitals.html">
    <img src="https://img.shields.io/badge/View%20Product%20Page-magento--2--corewebvitals-0D9488?style=for-the-badge" alt="View Product Page" />
  </a>
</p>

---

## About Panth Infotech

Built and maintained by **Kishan Savaliya** ([kishansavaliya.com](https://kishansavaliya.com)), a **Top Rated Plus** Magento developer on Upwork with 10+ years of eCommerce experience.

**Panth Infotech** is a Magento 2 development agency that builds high quality, security focused extensions and themes for both Hyva and Luma storefronts. The extension suite covers SEO, performance, checkout, product presentation, customer engagement, and store management, with each module built to MEQP standards and tested across Magento 2.4.4 to 2.4.8.

Browse the full extension catalog on our [Magento extensions page](https://kishansavaliya.com/magento-extensions.html) or on [Packagist](https://packagist.org/packages/mage2kishan/).

---

## Quick Links

| Resource | Link |
|---|---|
| **Product Page** | [magento-2-corewebvitals.html](https://kishansavaliya.com/magento-2-corewebvitals.html) |
| **Packagist** | [mage2kishan/module-corewebvitals](https://packagist.org/packages/mage2kishan/module-corewebvitals) |
| **GitHub** | [mage2sk/module-corewebvitals](https://github.com/mage2sk/module-corewebvitals) |
| **Website** | [kishansavaliya.com](https://kishansavaliya.com) |
| **Free Quote** | [kishansavaliya.com/get-quote](https://kishansavaliya.com/get-quote) |
| **Upwork (Top Rated Plus)** | [Hire Kishan Savaliya](https://www.upwork.com/freelancers/~016dd1767321100e21) |
| **Upwork Agency** | [Panth Infotech](https://www.upwork.com/agencies/1881421506131960778/) |
| **Email** | kishansavaliyakb@gmail.com |
| **WhatsApp** | +91 84012 70422 |

---

<p align="center">
  <strong>Ready to track your Core Web Vitals from real shoppers?</strong><br/>
  <a href="https://kishansavaliya.com/magento-2-corewebvitals.html">
    <img src="https://img.shields.io/badge/%F0%9F%9A%80%20See%20Core%20Web%20Vitals%20%E2%86%92-Product%20Page%20%26%20Details-DC2626?style=for-the-badge" alt="See Core Web Vitals" />
  </a>
</p>

---

**SEO Keywords:** magento 2 core web vitals, core web vitals magento 2, magento 2 LCP, largest contentful paint magento, magento 2 FID, first input delay magento, magento 2 INP, interaction to next paint magento, magento 2 CLS, cumulative layout shift magento, magento 2 performance monitoring, magento 2 page speed extension, real user monitoring magento, RUM magento 2, PerformanceObserver magento 2, magento 2 resource hints, dns-prefetch magento 2, preconnect magento 2, prefetch magento 2, magento 2 Server-Timing header, magento 2 GA4 web vitals, google analytics 4 core web vitals magento, magento 2 performance extension, magento 2.4.8 performance, hyva core web vitals, luma core web vitals, magento 2 google ranking performance, magento 2 web vitals extension, magento 2 speed extension, mage2kishan corewebvitals, panth core web vitals, panth infotech, kishan savaliya magento, hire magento performance developer, top rated plus upwork, adobe commerce cloud performance, fastly magento performance
