<?php
/**
 * Plugin Name: 10up Experience
 * Description: The 10up Experience plugin configures WordPress to better protect and inform clients, aligned to 10up’s best practices.
 * Version:     1.4
 * Author:      10up
 * Author URI:  https://10up.com
 * License:     GPLv2 or later
 * Text Domain: tenup
 * Domain Path: /languages/
 *
 * @package 10up-experience
 */

define( 'TENUP_EXPERIENCE_VERSION', '1.5' );

require_once __DIR__ . '/includes/admin.php';
require_once __DIR__ . '/includes/admin-bar.php';
require_once __DIR__ . '/includes/admin-pages.php';
require_once __DIR__ . '/includes/plugins.php';
require_once __DIR__ . '/includes/rest-api.php';
require_once __DIR__ . '/includes/gutenberg.php';
require_once __DIR__ . '/includes/authors.php';
require_once __DIR__ . '/includes/authentication.php';
require_once __DIR__ . '/includes/option-failsafes.php';

require_once __DIR__ . '/vendor/plugin-update-checker/plugin-update-checker.php';

$tenup_plugin_updater = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/10up/10up-experience/',
	__FILE__,
	'10up-experience'
);

if ( defined( 'TENUP_EXPERIENCE_GITHUB_KEY' ) ) {
	$tenup_plugin_updater->setAuthentication( TENUP_EXPERIENCE_GITHUB_KEY );
}

$tenup_plugin_updater->addResultFilter(
	function( $plugin_info, $http_response = null ) {
			$plugin_info->icons = array(
				'svg' => plugins_url( '/assets/img/tenup.svg', __FILE__ ),
			);

			return $plugin_info;
	}
);
