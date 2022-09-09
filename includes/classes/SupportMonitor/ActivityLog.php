<?php
/**
 * Logs critical user activities inside Support Monitor
 *
 * @since  2.0
 * @package 10up-experience
 */

namespace TenUpExperience\SupportMonitor;

use TenUpExperience\Singleton;

/**
 * Activity log class
 */
class ActivityLog extends Singleton {
	/**
	 * Setup module
	 *
	 * @since 1.7
	 */
	public function setup() {

		add_action( 'profile_update', [ $this, 'profile_update' ], 10, 3 );
		add_action( 'user_register', [ $this, 'user_register' ], 10, 2 );
		add_action( 'deleted_user', [ $this, 'deleted_user' ], 10 );
		add_action( 'wp_login', [ $this, 'wp_login' ] );

		add_action( 'activated_plugin', [ $this, 'activated_plugin' ], 10, 2 );
		add_action( 'deactivated_plugin', [ $this, 'deactivated_plugin' ], 10, 2 );

		add_action( 'switch_theme', [ $this, 'switch_theme' ], 10, 3 );
	}

	/**
	 * Log profile update
	 *
	 * @param int     $user_id       User ID.
	 * @param WP_User $old_user_data Object containing user's data prior to update.
	 * @param array   $userdata      The raw array of data passed to wp_insert_user(
	 */
	public function profile_update( $user_id, $old_user_data, $userdata ) {
		Monitor::instance()->log(
			'User ' . $user_id . ' profile updated.',
			'users',
			'profile_update'
		);
	}

	/**
	 * New user created
	 *
	 * @param int   $user_id  User ID.
	 * @param array $userdata The raw array of data passed to wp_insert_user().
	 */
	public function user_register( $user_id, $userdata ) {
		Monitor::instance()->log(
			'User ' . $user_id . ' created.',
			'users',
			'user_register'
		);
	}

	/**
	 * User deleted
	 *
	 * @param int $user_id  User ID.
	 */
	public function deleted_user( $user_id ) {
		Monitor::instance()->log(
			'User ' . $user_id . ' deleted.',
			'users',
			'deleted_user'
		);
	}

	/**
	 * User logged in
	 */
	public function wp_login() {
		Monitor::instance()->log(
			'User logged in.',
			'users',
			'wp_login'
		);
	}

	/**
	 * Plugin is activated
	 *
	 * @param string  $plugin Plugin path
	 * @param boolean $network_wide Whether the plugin is activated network wide
	 */
	public function activated_plugin( $plugin, $network_wide ) {
		$msg = 'Plugin ' . $plugin . ' is activated';

		if ( $network_wide ) {
			$msg .= ' network-wide';
		}

		Monitor::instance()->log(
			$msg,
			'plugins',
			'activated_plugin'
		);
	}

	/**
	 * Plugin is deactivated
	 *
	 * @param string  $plugin Plugin path
	 * @param boolean $network_wide Whether the plugin is deactivated network wide
	 */
	public function deactivated_plugin( $plugin, $network_wide ) {
		$msg = 'Plugin `' . $plugin . '` is deactivated';

		if ( $network_wide ) {
			$msg .= ' network-wide';
		}

		Monitor::instance()->log(
			$msg,
			'plugins',
			'deactivated_plugin'
		);
	}

	/**
	 * Switch theme
	 *
	 * @param string   $new_name  Name of the new theme.
	 * @param WP_Theme $new_theme WP_Theme instance of the new theme.
	 * @param WP_Theme $old_theme WP_Theme instance of the old theme.
	 */
	public function switch_theme( $new_name, $new_theme, $old_theme ) {
		Monitor::instance()->log(
			'Theme switched to `' . $new_name . '` from `' . $old_theme->get( 'Name' ) . '`',
			'themes',
			'switch_theme'
		);
	}
}
