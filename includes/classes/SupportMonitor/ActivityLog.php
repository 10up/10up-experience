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
		add_action( 'delete_plugin', [ $this, 'delete_plugin' ] );

		add_action( 'switch_theme', [ $this, 'switch_theme' ], 10, 3 );
		add_action( 'deleted_theme', [ $this, 'deleted_theme' ], 10, 2 );

		add_action( 'updated_option', [ $this, 'updated_option' ] );
		add_action( 'added_option', [ $this, 'added_option' ] );
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
	 * Plugin is deleted
	 *
	 * @param string $plugin Plugin path
	 */
	public function delete_plugin( $plugin ) {
		$msg = 'Plugin `' . $plugin . '` is deleted';

		Monitor::instance()->log(
			$msg,
			'plugins',
			'delete_plugin'
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

	/**
	 * Theme is deleted
	 *
	 * @param string  $stylesheet Stylesheet name.
	 * @param boolean $deleted    Whether the theme is deleted.
	 */
	public function deleted_theme( $stylesheet, $deleted ) {
		if ( $deleted ) {
			Monitor::instance()->log(
				'Theme `' . $stylesheet . '` is deleted',
				'themes',
				'deleted_theme'
			);
		}
	}

	/**
	 * Provides options to log.
	 *
	 * @see https://codex.wordpress.org/Option_Reference
	 * @return array
	 */
	private function get_option_changes_to_log() {
		$options_to_log = [
			'admin_email',
			'blogname',
			'default_role',
			'home',
			'siteurl',
			'users_can_Register',
			'upload_path',
			'upload_url_path',
			'permalink_structure',
			'category_base',
			'tag_base',
			'blog_public',
			'page_on_front',
			'page_for_posts',
			'default_comment_status',
			'show_on_front',
			'posts_per_page',
		];

		/**
		 * Filters the options to log.
		 *
		 * @param array $options_to_log Options to log.
		 */
		return apply_filters( 'tenup_support_monitor_logged_option_changes', $options_to_log );
	}

	/**
	 * Option is updated
	 *
	 * @param string $option Option name.
	 */
	public function updated_option( $option ) {
		if ( in_array( $option, $this->get_option_changes_to_log(), true ) ) {
			Monitor::instance()->log(
				'Option `' . $option . '` is updated',
				'options',
				'updated_option'
			);
		}
	}

	/**
	 * Option is added
	 *
	 * @param string $option Option name.
	 */
	public function added_option( $option ) {
		if ( in_array( $option, $this->get_option_changes_to_log(), true ) ) {
			Monitor::instance()->log(
				'Option `' . $option . '` is added',
				'options',
				'added_option'
			);
		}
	}
}
