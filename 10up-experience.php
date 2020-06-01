<?php
/**
 * Plugin Name: 10up Experience
 * Description: The 10up Experience plugin configures WordPress to better protect and inform clients, aligned to 10up’s best practices.
 * Version:     1.7.2
 * Author:      10up
 * Author URI:  https://10up.com
 * License:     GPLv2 or later
 * Text Domain: tenup
 * Domain Path: /languages/
 *
 * @package 10up-experience
 */

namespace TenUpExperience;

use Puc_v4_Factory;

define( 'TENUP_EXPERIENCE_VERSION', '1.7.2' );
define( 'TENUP_EXPERIENCE_DIR', __DIR__ );
define( 'TENUP_EXPERIENCE_FILE', __FILE__ );

require_once __DIR__ . '/vendor/yahnis-elsts/plugin-update-checker/plugin-update-checker.php';

require_once __DIR__ . '/includes/utils.php';

spl_autoload_register( function( $class_name ) {
	$path_parts = explode( '\\', $class_name );

	if ( ! empty( $path_parts ) ) {
		$package = $path_parts[0];

		unset( $path_parts[0] );

		if ( 'TenUpExperience' === $package ) {
			require_once __DIR__ . '/includes/classes/' . implode( '/', $path_parts ) . '.php';
		} elseif ( 'ZxcvbnPhp' === $package ) {
			require_once __DIR__ . '/vendor/bjeavons/zxcvbn-php/src/' . implode( '/', $path_parts ) . '.php';
		}
	}
} );

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

// Define a constant if we're network activated to allow plugin to respond accordingly.
$network_activated = Utils\is_network_activated( plugin_basename( __FILE__ ) );

define( 'TENUP_EXPERIENCE_IS_NETWORK', (bool) $network_activated );

if ( ! defined( 'TENUP_DISABLE_BRANDING' ) || ! TENUP_DISABLE_BRANDING ) {
	AdminCustomizations\Customizations::instance()->setup();
}

API\API::instance()->setup();
Authentication\Usernames::instance()->setup();
Authors\Authors::instance()->setup();
Gutenberg\Gutenberg::instance()->setup();
Headers\Headers::instance()->setup();
Plugins\Plugins::instance()->setup();
PostPasswords\PostPasswords::instance()->setup();
SupportMonitor\Monitor::instance()->setup();
SupportMonitor\Debug::instance()->setup();

/**
 * We load this later to make sure there are no conflicts with other plugins.
 */
add_action( 'plugins_loaded', function() {
	Authentication\Passwords::instance()->setup();
} );

/**
 * Disable plugin/theme editor
 */
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', true );
}
