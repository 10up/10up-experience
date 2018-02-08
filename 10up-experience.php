<?php
/**
 * Plugin Name: 10up Experience
 * Description: Bringing a little more of the joy of 10up into your WordPress dashboard experience.
 * Version:     1.0
 * Author:      10up
 * Author URI:  https://10up.com
 * License:     GPLv2 or later
 * Text Domain: tenup
 * Domain Path: /languages/
 */

require_once __DIR__ . '/includes/admin.php';
require_once __DIR__ . '/includes/admin-bar.php';
require_once __DIR__ . '/includes/admin-pages.php';
require_once __DIR__ . '/includes/plugins.php';
require_once __DIR__ . '/includes/rest-api.php';

require_once __DIR__ . '/vendor/plugin-update-checker/plugin-update-checker.php';

$tenup_plugin_updater = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/10up/10up-experience/',
	__FILE__,
	'10up-experience'
);

if ( defined( 'TENUP_EXPERIENCE_GITHUB_KEY' ) ) {
	$tenup_plugin_updater->setAuthentication( TENUP_EXPERIENCE_GITHUB_KEY );
}

$tenup_plugin_updater->addResultFilter( function( $pluginInfo, $httpResponse = null ) {
	$pluginInfo->icons = array(
		'svg' => plugins_url( '/assets/img/tenup.svg', __FILE__ ),
	);

	return $pluginInfo;
});
