# 10up Experience Plugin

The 10up Experience plugin configures WordPress to better protect and inform our clients, aligned to 10up’s best practices. It is not meant as a general-distribution plugin and does not have an open development process, but is available for public perusal.

<p align="center">
<a href="http://10up.com/contact/"><img src="https://10updotcom-wpengine.s3.amazonaws.com/uploads/2016/10/10up-Github-Banner.png" width="850"></a>
</p>

## Requirements

* PHP 5.3+
* [WordPress](http://wordpress.org) 4.7+

## Install

1. Clone or [download](https://github.com/10up/10up-experience/archive/master.zip) and extract the plugin into `wp-content/plugins`. Make sure you use the `master` branch which contains the latest stable release.
1. Activate the plugin via the dashboard or WP-CLI.
1. Updates use the built-in WordPress update system to pull from GitHub releases.

## Plugin Usage

This plugin requires no configuration.

## Features

### 10up Branding
- Add 10up information page
- Add link to 10up page to Admin Bar
- Add 10up thank you message to admin footer
- Add link to 10up Suggested Plugins to Plugins page

### Security
- Disable backend file editing via `DISALLOW_FILE_EDIT` constant
- Add `tenup_restrict_rest_api` option to restrict REST API access for unauthenticated users.
- Remove disabled REST API endpoints for unauthenticated users

## Changelog

* 1.0 - Initial release.

## License

This plugin is free software; you can redistribute it and/or modify it under the terms of the [GNU General Public License](http://www.gnu.org/licenses/gpl-2.0.html) as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
