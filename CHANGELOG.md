# Changelog

All notable changes to this project will be documented in this file, per [the Keep a Changelog standard](http://keepachangelog.com/).

## [1.11.1] - 2023-10-27

- Fixed disallow direct login warning

## [1.11.0] - 2023-10-27

- Support Fueled SSO
- Linting clean up
- Ensure unique username

## [1.10.3] - 2023-08-15

- Make sure redirect_to is a string

## [1.10.2] - 2023-07-11

- Remove WP Acceptance
- Upgrade build process to 10up Toolkit
- Upgrade Plugin Update Checker
- Allow SSO to be turned off in the admin

## [1.10.1] - 2022-09-13

### Fixed

- Fix bug allowing admin username user to authenticate

## [1.10.0] - 2022-09-13

### Added

- Added Activity Log
- Support for PHP 8.1

## [1.9.0] - 2022-03-21

### Added

- Bundled 10up SSO plugin into 10up Experience

## [1.8.2] - 2022-02-28

### Fixed

- Ensure mbstring exists before using password strength checker.
- If Gutenberg is disabled, also make sure widget editor doesn't use Gutenberg.

## [1.8.1] - 2021-06-28

### Fixed

- Fix Filtering WP List Table Views by 10up Author
- Unhide Stream menu
- Add filter around disabling X Frame header. Props [jamesmorrison](https://github.com/jamesmorrison).

## [1.8.0] - 2020-12-08

### Added

- Improves detection of object cache drop-ins. Props [christianc1](https://github.com/christianc1).
- Adds themes to support monitor reporting. Props [tylercherpak](https://github.com/tylercherpak).
- Adds web vitals to support monitor reporting. Props [christianc1](https://github.com/christianc1).

## [1.7.3] - 2020-07-20

### Fixed

- Fix how we retrieve WP version.
- Improve how we generate message ID for Support Monitor

### Added

- Show welcome admin notification
- Change API restriction to default to only restricting the users endpoint.
- 10up Experience header added during author redirect to improve debugging. Props [petenelson](https://github.com/petenelson).

### Fixed

- Fix how we retrieve WP version.
- Improve how we generate message ID for Support Monitor

## [1.7.2] - 2020-06-01

### Added

- Send object cache info to Support Monitor

### Fixed

- Fix `esc_html__` call.
- Query for users across network if network activated

## [1.7.1] - 2020-05-28

### Fixed

- Fix number of users being queried by Support Monitor.

## [1.7] - 2020-05-21

### Added

- Support monitor functionality. Sends non-PII data e.g. plugin versions back to 10up.
- Require strong passwords by default. This can be disabled in general settings.
- Disallow reserved usernames from being used e.g. admin.
- Set X-Frame-Options to same origin.
- Add constant `TENUP_DISABLE_BRANDING` to disable 10up admin branding.

### Fixed

- Refactored to use classes and modern build scripts.

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

- If plugin updates via dashboard are disabled, still show notification that an update exists

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
[1.7.3]: https://github.com/10up/10up-experience/compare/1.7.2...1.7.3
[1.7.2]: https://github.com/10up/10up-experience/compare/1.7.1...1.7.2
[1.7.1]: https://github.com/10up/10up-experience/compare/1.7...1.7.1
[1.7]: https://github.com/10up/10up-experience/compare/1.6.2...1.7
[1.6.2]: https://github.com/10up/10up-experience/compare/1.6.1...1.6.2
[1.6.1]: https://github.com/10up/10up-experience/compare/1.6...1.6.1
[1.6]: https://github.com/10up/10up-experience/compare/1.5...1.6
[1.5]: https://github.com/10up/10up-experience/compare/1.4...1.5
[1.4]: https://github.com/10up/10up-experience/compare/1.3...1.4
[1.3]: https://github.com/10up/10up-experience/compare/1.2...1.3
[1.2]: https://github.com/10up/10up-experience/compare/1.1...1.2
[1.1]: https://github.com/10up/10up-experience/compare/1.0...1.1
[1.0]: https://github.com/10up/10up-experience/releases/tag/1.0
