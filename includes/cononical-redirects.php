<?php
/**
 * WordPress admin and login shorthand redirects
 *
 * @package  10up-experience
 */

namespace tenup;

/**
 * Remove the default shorthand redirects WordPress core implements like
 * /login, /admin, and / dashboard.
 */
remove_action( 'template_redirect', 'wp_redirect_admin_locations', 1000 );
