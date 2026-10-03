# Changelog

All notable changes to this extension are documented here. The format
is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/).

## [1.0.13] - 2026-10-03

### Fixed
- INP now only counts real interactions (event entries with an interaction id), so hovering or moving the pointer no longer inflates the value.
- LCP stops updating after the first click or key press and uses the entry start time, as defined for the metric.
- Analytics and the beacon endpoint now receive the final value of each metric once when the page is hidden or left, instead of one event for every intermediate LCP, CLS or INP update.
- Preconnect values are reduced to a clean origin (scheme, host and port) for both the Link header and the head tag: protocol relative values get https://, paths are dropped, duplicates and invalid lines are skipped.
- The Server-Timing header is appended to an existing Server-Timing value instead of replacing it, and an existing Link header that already lists every preconnect origin is left untouched.
- The head no longer prints empty HTML comments when no resource hints are configured, and duplicate dns-prefetch and prefetch lines are printed once.
- Target LCP, FID, INP and CLS fields are hidden when the module is disabled, and a target of 0 is rejected instead of silently falling back to the default.
