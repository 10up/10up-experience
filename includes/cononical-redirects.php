<?php
/**
 * WordPress admin and login shorthand redirects
 *
 * @package  10up-experience
 */

namespace tenup;

remove_action( 'template_redirect', 'wp_redirect_admin_locations', 1000 );
