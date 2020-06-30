# Changelog

All notable changes to this project will be documented in this file, per [the Keep a Changelog standard](http://keepachangelog.com/).

## [1.7.3]
### Fixed
* Fix how we retrieve WP version.

## [1.7.2] - 2020-06-02
### Fixed
* Fix `esc_html__` call.
* Query for users across network if network activated

### Added
* Send object cache info to Support Monitor

## [1.7.1]
### Fixed
* Fix number of users being queried by Support Monitor.

## [1.7]
### Added
* Support monitor functionality. Sends non-PII data e.g. plugin versions back to 10up.
* Require strong passwords by default. This can be disabled in general settings.
* Disallow reserved usernames from being used e.g. admin.
* Set X-Frame-Options to same origin.
* Add constant `TENUP_DISABLE_BRANDING` to disable 10up admin branding.

### Fixed
* Refactored to use classes and modern build scripts.

## [1.6.2] - 2020-04-15
### Added
- Changelog and License files, updated Readme (props [@jeffpaul](https://github.com/jeffpaul) via [#49](https://github.com/10up/10up-experience/pull/49), [#62](https://github.com/10up/10up-experience/pull/62))

### Fixed
- Resolved version number mismatch between GitHub and Packagist (props [@ivankruchkoff](https://github.com/ivankruchkoff), [@jeffpaul](https://github.com/jeffpaul), [@cameronterry](https://github.com/cameronterry), [@colegeissinger](https://github.com/colegeissinger) via [#56](https://github.com/10up/10up-experience/pull/56))
- WP Acceptance environment instruction for 5.3 version test (props [@felipeelia](https://github.com/felipeelia) via [#62](https://github.com/10up/10up-experience/pull/62))

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

[Unreleased]: https://github.com/10up/10up-experience/compare/master...develop
[1.6.2]: https://github.com/10up/10up-experience/compare/1.6.1...1.6.2
[1.6.1]: https://github.com/10up/10up-experience/compare/1.6...1.6.1
[1.6]: https://github.com/10up/10up-experience/compare/1.5...1.6
[1.5]: https://github.com/10up/10up-experience/compare/1.4...1.5
[1.4]: https://github.com/10up/10up-experience/compare/1.3...1.4
[1.3]: https://github.com/10up/10up-experience/compare/1.2...1.3
[1.2]: https://github.com/10up/10up-experience/compare/1.1...1.2
[1.1]: https://github.com/10up/10up-experience/compare/1.0...1.1
[1.0]: https://github.com/10up/10up-experience/releases/tag/1.0
