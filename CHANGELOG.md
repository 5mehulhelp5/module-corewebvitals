# Changelog

All notable changes to this extension are documented here. The format
is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/).

## [1.0.9]

### Changed
- Replaced typographic characters (em dashes, curly quotes, ellipsis) with plain ASCII punctuation. No functional changes.

---

## [1.0.8] - 2026-07-07

### Changed
- Code cleanup: removed redundant inline comments and docblocks from the PHP source. No functional changes.

---

## [1.0.7] - 2026-06-18

### Changed
- README.md rewritten to match Panth Infotech gold template: gold-standard
  section order, Quick Answer block, correct system.xml Configuration table,
  accurate feature coverage, updated canonical URL and badges.

---

## [1.1.0] - Marketplace preparation

### Changed
- `composer.json` - vendor changed from `panth/` to `mage2kishan/` for
  Adobe Marketplace submission.
- `composer.json` - PHP constraint changed to
  `~8.1.0||~8.2.0||~8.3.0||~8.4.0`; Magento dependencies use `^`
  semver ranges.
- Added `mage2kishan/module-core: ^1.0` as a required dependency.

### Added
- Marketplace assets: README.md, USER_GUIDE.md, CHANGELOG.md,
  LICENSE.txt, COPYING.txt, .gitattributes.

---

## [1.0.0] - Initial release

### Added
- **Core Web Vitals monitoring** - lightweight JavaScript snippet using
  the browser-native PerformanceObserver API to measure LCP, FID, INP,
  and CLS on every frontend page load.
- **Debug mode** - logs all collected metrics to the browser developer
  console and exposes `window.coreWebVitalsMetrics` for inspection.
- **Real User Monitoring (RUM)** - sends metrics to Google Analytics 4
  via `gtag()` or to a custom endpoint via `navigator.sendBeacon`.
- **Custom DOM events** - dispatches `coreWebVitals:lcp`,
  `coreWebVitals:fid`, `coreWebVitals:inp`, `coreWebVitals:cls`
  events for third-party integrations.
- **Resource hints** - admin-configurable dns-prefetch, preconnect, and
  prefetch domains rendered as `<link>` tags in the page head.
- **Performance HTTP headers** - Server-Timing (PHP execution time),
  X-DNS-Prefetch-Control, and Link rel=preconnect headers added to
  every frontend response via plugin.
- **Per-metric configuration** - each metric (LCP, FID/INP, CLS) can
  be independently enabled/disabled with configurable target thresholds.
- **Font loading strategy source model** - dropdown for font-display
  values (auto, block, swap, fallback, optional).
- **ACL resources** - granular admin permissions for module config.
- **Admin menu** - settings accessible via Panth Infotech sidebar.
- **Unit tests** - PHPUnit tests for Helper, Observer, and Plugin.

### Compatibility
- Magento Open Source / Commerce / Cloud 2.4.4 - 2.4.8
- PHP 8.1, 8.2, 8.3, 8.4

---

## Support

For all questions, bug reports, or feature requests:

- **Email:** kishansavaliyakb@gmail.com
- **Website:** https://kishansavaliya.com
- **WhatsApp:** +91 84012 70422
