# 10up Experience Plugin

> The 10up Experience plugin configures WordPress to better protect and inform our clients, aligned to 10up’s best practices. It is not meant as a general-distribution plugin and does not have an open development process, but is available for public perusal.

[![Build Status](https://travis-ci.org/10up/10up-experience.svg?branch=master)](https://travis-ci.org/10up/10up-experience) [![Support Level](https://img.shields.io/badge/support-active-green.svg)](#support-level) [![Release Version](https://img.shields.io/github/v/tag/10up/10up-experience?label=release)](https://github.com/10up/10up-experience/tags) ![WordPress tested up to version](https://img.shields.io/badge/WordPress-v5.9%20tested-success.svg) [![GPLv2 License](https://img.shields.io/github/license/10up/10up-experience.svg)](https://github.com/10up/10up-experience/blob/develop/LICENSE.md)

## Requirements

* PHP 7.2+
* [WordPress](http://wordpress.org) 4.7+

## Installation

### Composer

The recommended way to use this plugin is with Composer.

```
composer require 10up/10up-experience
```

### Git
For development purposes, you can clone the plugin into `wp-content/plugins` and install the dependencies.

```
git clone git@github.com:10up/10up-experience.git && cd 10up-experience && composer install && npm install
```

### Archive
If you need a built version of the plugin to install via the dashboard, [download](https://github.com/10up/10up-experience/archive/master.zip) and extract the plugin into `wp-content/plugins`. Make sure you use the `master` branch which contains the latest stable release.
## Activation

Activate the plugin via the dashboard or WP-CLI.

```
wp plugin activate 10up-experience
```

## Updates

Updates use the built-in WordPress update system to pull from GitHub releases.

## Functionality

### REST API

Adds an option to general settings to restrict REST API access. The options are: show REST API to everyone, only show REST API to logged in users, and show REST API to everyone except `/users` endpoint. By default, the plugin requires authentication for the `/users` endpoint.

*Configured in `Settings > Reading`.*

### Authors

Removes 10up user author archives so they aren't mistakenly indexed by search engines.

### Gutenberg

Adds an option in writing to switch back to Classic Editor.

*Configured in `Settings > Writing`.*

### Plugins

 Adds a 10up Suggested Plugins section to the plugins screen. Warns users who attempt to deactivate the 10up Experience plugin. Outputs a notice on non-suggested plugins tabs warning users from installing non-approved plugins. If `DISALLOW_FILE_MODS` is on, update notices will be shown in the plugins table.

### Post Passwords

Password protecting post functionality is removed both in Gutenberg and the classic editor. This can be disabled in the writing section of the admin.

*Configured in `Settings > Writing`.*

### Monitor

Sends non-PII information about the website back to 10up including plugins installed, constants defined in `wp-config.php`, 10up user accounts, and more.

*Configured in `Settings > General` or `Settings > Network Settings` if network activated.*

### Authentication

By default, all users must use a medium or greater strength password. This can be turned off in general settings (or network settings if network activated). Reserved usernames such as `admin` are prevented from being used.

*Configured in `Settings > General` or `Settings > Network Settings` if network activated.*

 **Password strength functionality requires the PHP extension [mbstring](https://www.php.net/manual/en/mbstring.installation.php) to be installed on the web server. Functionality will be bypassed if extension not installed.*


### Headers

`X-Frame-Origins` is set to `sameorigin` to prevent click jacking.

*Note:* 10up admin branding can be disabled by defining the constant `TENUP_DISABLE_BRANDING` as `true`.

There are 2 filters available here:
- `tenup_experience_x_frame_options` - (default value) `SAMEORIGIN` can be changed to `DENY`.
- `tenup_experience_disable_x_frame_options` - (default value) `FALSE` can be changed to `TRUE` - doing so will omit the header.

### SSO

10up Experience includes 10up SSO functionality. This feature can be enabled or disabled in `Settings > General`. It is enabled by default. There are some useful constants related to this functionality:

- `TENUPSSO_DISABLE` - Define this as `true` to force disable SSO.
- `TENUPSSO_DISALLOW_ALL_DIRECT_LOGIN` - Define this as `true` to disable username/password log ins completely.

### Activity Log

The Activity Log tracks key actions taken by logged in users and stores them in Monitor. Note that no PII is stored. This feature can be disabled by defining `TENUP_DISABLE_ACTIVITYLOG` as `true`.

#### Logged Actions

- `profile_update` Runs when a user profile is updated. Example log message: "User 1 profile updated."
- `set_user_role` Runs when a user's role has changed. Example log message: "User 1 role changed from editor to administrator."
- `updated_user_meta` Runs when certain user metadata has changed. Example log message: "User 1 meta updated. Key: nickname."
- `user_register` Runs when a new user is registered. Example log message: "User 1 registered."
- `deleted_user` Runs when a user is deleted. Example log message: "User 1 deleted."
- `wp_login` Runs when a user logs in. Example log message: "User 1 logged in."
- `activated_plugin` Runs when a plugin is activated. Example log message: "Plugin wordpress-seo is activated."
- `delete_plugin` Runs when a plugin is deleted. Example log message: "Plugin wordpress-seo" is deleted.
- `switch_theme` Runs the theme changes. Example log message: "Theme switch to twentytwentytwo from twentytwentyone."
- `deleted_theme` Runs when a theme is deleted from the site. Example log message: "Theme twentytwentyone is deleted."
- `updated_option` Runs when one of a specified set of core options changes. Example log message: "Option `users_can_register` is updated."
- `added_option` Runs when one of a specified set of core options is added. Example log message: "Option `users_can_register` is added."

#### Filters

- `tenup_experience_logged_user_meta_changes`

Filters the user meta keys whose changes should be logged.

- `tenup_support_monitor_logged_option_changes`

Filters the option keys whose changes should be logged.

- `tenup_support_monitor_log_item`

Filters whether to log a message.

- `tenup_support_monitor_max_activity_log_count`

Filters how many log items to store. Items are stored in array saved to the options table. Default is 500.

#### Constants

- `TENUP_DISABLE_ACTIVITYLOG`

Define `TENUP_DISABLE_ACTIVITYLOG` as `true` to disable Activity Log.

### Comments

10up Experience includes a feature to disable comments across the site. This feature can be enabled or disabled in `Settings > General`. It is disabled by default.

On top of disabling the comment form, this feature removes the following:

- Comments from the admin menu.
- Comment blocks from the post editor.
- Comments from the admin bar.

#### Constants

- `TENUP_DISABLE_COMMENTS`

Define this as `true` to force disable comments or `false` to enable comments from a config file.
Setting this constant will disable the UI for enabling/disabling comments in the admin.

#### Filters

- `tenup_experience_disable_comments`

Filters whether to disable comments. Default is `false`.
Defining this filter will disable the UI for enabling/disabling comments in the admin.

- `tenup_experience_disable_comments_disallowed_blocks`

Filters the list of blocks that should be disallowed when comments are disabled. This is useful when core adds new blocks that aren't covered by the default list.

The default list of disallowed blocks is:

- `core/comment-author-name`
- `core/comment-content`
- `core/comment-date`
- `core/comment-edit-link`
- `core/comment-reply-link`
- `core/comment-template`
- `core/comments`
- `core/comments-pagination`
- `core/comments-pagination-next`
- `core/comments-pagination-numbers`
- `core/comments-pagination-previous`
- `core/comments-title`
- `core/post-comments`
- `core/post-comments-form`
- `core/latest-comments`

## Support Level

**Active:** 10up is actively working on this, and we expect to continue work for the foreseeable future including keeping tested up to the most recent version of WordPress.  Bug reports, feature requests, questions, and pull requests are welcome.

## Changelog

A complete listing of all notable changes to the 10up Experience Plugin are documented in [CHANGELOG.md](https://github.com/10up/10up-experience/blob/develop/CHANGELOG.md).

## Like what you see?

<p align="center">
<a href="http://10up.com/contact/"><img src="https://10up.com/uploads/2016/10/10up-Github-Banner.png" width="850"></a>
</p>
