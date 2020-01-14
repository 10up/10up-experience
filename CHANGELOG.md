# Changelog

All notable changes to this project will be documented in this file, per [the Keep a Changelog standard](http://keepachangelog.com/).

## [Unreleased] - TBD

## [1.6.1] - 2019-12-09
### Removed
- Option failsafes

## [1.6] - 2019-12-03
### Added
- Password protected post functionality turned off by default. Add a setting to "Writing" to re-enable.

### Fixed
- Rewrite rule flushing bug.

## [1.5] - 2019-03-29
### Added
- WP Acceptance tests
- Failsafes if temporary loss of database connection causes required options to be stored in the `notoptions` cache

## [1.4] - 2019-03-22
### Added
- If plugin updates via dashboard are disabled, still show notifcation that an update exists

### Removed
- 10up users from author archives

## [1.3] - 2018-11-04
### Added
- "Use Classic Editor" toggle to writing settings

### Fixed
- Properly call a hook as a filter, not an action

## [1.2] - 2018-09-24
### Added
- Use a base64-encoded admin bar icon so it can be colorized

### Changed
- Only load admin bar CSS on front-end if the admin bar is showing

### Fixed
- Ensure plugin deactivation message linebreaks are displayed correctly

## [1.1] - 2018-08-03
### Added
- `tenup_experience_remove_stream_menu_item` filter
- `composer.json` file
- `editorconfig` file

### Fixed
- Coding standard issues

## [1.0] - 2018-03-01
- Initial release

[Unreleased]: https://github.com/10up/10up-experience/compare/1.6.1...develop
[1.6.1]: https://github.com/10up/10up-experience/compare/1.6...1.6.1
[1.6]: https://github.com/10up/10up-experience/compare/1.5...1.6
[1.5]: https://github.com/10up/10up-experience/compare/1.4...1.5
[1.4]: https://github.com/10up/10up-experience/compare/1.3...1.4
[1.3]: https://github.com/10up/10up-experience/compare/1.2...1.3
[1.2]: https://github.com/10up/10up-experience/compare/1.1...1.2
[1.1]: https://github.com/10up/10up-experience/compare/1.0...1.1
[1.0]: https://github.com/10up/10up-experience/releases/tag/1.0
