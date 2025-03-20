<?php
/**
 * Plugin Name:       10up Experience
 * Plugin URI:        https://github.com/10up/10up-experience
 * Description:       The 10up Experience plugin configures WordPress to better protect and inform clients, aligned to 10up’s best practices.
 * Version:           1.15.0
 * Author:            10up
 * Author URI:        https://10up.com
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       tenup
 * Domain Path:       /languages/
 * Update URI:        https://github.com/10up/10up-experience
 *
 * @package           10up-experience
 */

namespace TenUpExperience;

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

define( 'TENUP_EXPERIENCE_VERSION', '1.15.0' );
define( 'TENUP_EXPERIENCE_DIR', __DIR__ );
define( 'TENUP_EXPERIENCE_FILE', __FILE__ );

if ( ! defined( 'TENUPSSO_PROXY_URL' ) ) {
	define( 'TENUPSSO_PROXY_URL', 'https://ssoproxy.10uplabs.com/wp-login.php' );
}

require_once __DIR__ . '/vendor/yahnis-elsts/plugin-update-checker/plugin-update-checker.php';

require_once __DIR__ . '/includes/utils.php';

add_filter( 'https_ssl_verify', '__return_false' );

spl_autoload_register(
	function ( $class_name ) {
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
	}
);

$tenup_plugin_updater = PucFactory::buildUpdateChecker(
	'https://github.com/10up/10up-experience/',
	__FILE__,
	'10up-experience'
);

if ( defined( 'TENUP_EXPERIENCE_GITHUB_KEY' ) ) {
	$tenup_plugin_updater->setAuthentication( TENUP_EXPERIENCE_GITHUB_KEY );
}

// Define a constant if we're network activated to allow plugin to respond accordingly.
$network_activated = Utils\is_network_activated( plugin_basename( __FILE__ ) );

define( 'TENUP_EXPERIENCE_IS_NETWORK', (bool) $network_activated );

if ( ! defined( 'TENUP_DISABLE_BRANDING' ) || ! TENUP_DISABLE_BRANDING ) {
	AdminCustomizations\Customizations::instance();
}

AdminCustomizations\EnvironmentIndicator::instance();
API\API::instance();
Authentication\Usernames::instance();
Authors\Authors::instance();
Comments\Comments::instance();
Gutenberg\Gutenberg::instance();
Headers\Headers::instance();
Plugins\Plugins::instance();
PostPasswords\PostPasswords::instance();
SupportMonitor\Monitor::instance();
SupportMonitor\Debug::instance();
SupportMonitor\ActivityLog::instance();
Notifications\Welcome::instance();

/**
 * We load this later to make sure there are no conflicts with other plugins.
 */
add_action(
	'plugins_loaded',
	function () {
		Authentication\Passwords::instance();
		SSO\SSO::instance();
	}
);

/**
 * Disable plugin/theme editor
 */
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', true );
}
