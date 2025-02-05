<?php
/**
 * Logs critical user activities inside Monitor
 *
 * @since  2.0
 * @package 10up-experience
 */

namespace TenUpExperience\SupportMonitor;

use TenUpExperience\Singleton;

/**
 * Activity log class
 */
class ActivityLog {

	use Singleton;

	/**
	 * Setup module
	 *
	 * @since 1.7
	 */
	public function setup() {

		add_action( 'profile_update', [ $this, 'profile_update' ], 10, 3 );
		add_action( 'set_user_role', [ $this, 'set_user_role' ], 10, 3 );
		add_action( 'updated_user_meta', [ $this, 'updated_user_meta' ], 10, 3 );
		add_action( 'user_register', [ $this, 'user_register' ], 10, 2 );
		add_action( 'deleted_user', [ $this, 'deleted_user' ], 10 );
		add_action( 'wp_login', [ $this, 'wp_login' ], 10, 2 );

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
		$changed_keys = [];

		foreach ( $userdata as $key => $value ) {
			if ( isset( $old_user_data->data->$key ) && (string) $old_user_data->data->$key !== (string) $value ) {
				$changed_keys[] = $key;
			}
		}

		Monitor::instance()->log(
			[
				'action'  => 'profile_update',
				'summary' => 'User ' . $user_id . ' profile updated.' . ( ! empty( $changed_keys ) ? ' Changed: ' . implode( ', ', $changed_keys ) : '' ),
			],
			'users'
		);
	}

	/**
	 * Log user role change
	 *
	 * @param int    $user_id User ID.
	 * @param string $role    Role name.
	 * @param array  $old_roles Old roles.
	 */
	public function set_user_role( $user_id, $role, $old_roles ) {
		if ( ! empty( $old_roles ) ) { // Don't log on user creation.
			Monitor::instance()->log(
				[
					'action'  => 'set_user_role',
					'summary' => 'User ' . $user_id . ' role changed from ' . implode( ', ', $old_roles ) . ' to ' . $role,
				],
				'users'
			);
		}
	}

	/**
	 * Provides keys of user meta changes to log.
	 *
	 * @return array
	 */
	private function get_user_meta_keys_to_log() {
		$user_meta_keys_to_log = [
			'description',
			'first_name',
			'last_name',
			'nickname',
		];

		/**
		 * Filters the user meta keys to log.
		 *
		 * @param array $user_meta_keys_to_log
		 */
		return apply_filters( 'tenup_experience_logged_user_meta_changes', $user_meta_keys_to_log );
	}


	/**
	 * Log user meta update
	 *
	 * @param int    $meta_id    ID of updated metadata entry.
	 * @param int    $user_id    User ID.
	 * @param string $meta_key   Metadata key.
	 */
	public function updated_user_meta( $meta_id, $user_id, $meta_key ) {
		if ( in_array( $meta_key, $this->get_user_meta_keys_to_log(), true ) ) {
			Monitor::instance()->log(
				[
					'action'  => 'updated_user_meta',
					'summary' => 'User ' . $user_id . ' meta updated. Key: ' . $meta_key,
				],
				'users'
			);
		}
	}

	/**
	 * New user created
	 *
	 * @param int   $user_id  User ID.
	 * @param array $userdata The raw array of data passed to wp_insert_user().
	 */
	public function user_register( $user_id, $userdata ) {
		$role = ( ! empty( $userdata['role'] ) ) ? $userdata['role'] : 'n/a';
		Monitor::instance()->log(
			[
				'action'  => 'user_register',
				'summary' => 'User ' . $user_id . ' created with role ' . $role,
			],
			'users'
		);
	}

	/**
	 * User deleted
	 *
	 * @param int $user_id  User ID.
	 */
	public function deleted_user( $user_id ) {
		Monitor::instance()->log(
			[
				'action'  => 'deleted_user',
				'summary' => 'User ' . $user_id . ' deleted.',
			],
			'users'
		);
	}

	/**
	 * User logged in
	 *
	 * @param string $user_login Username.
	 * @param object $user       WP_User object of the logged-in user.
	 */
	public function wp_login( $user_login, $user ) {
		Monitor::instance()->log(
			[
				'action'  => 'wp_login',
				'summary' => 'User ' . $user->ID . ' logged in.',
			],
			'users'
		);
	}

	/**
	 * Plugin is activated
	 *
	 * @param string  $plugin Plugin path
	 * @param boolean $network_wide Whether the plugin is activated network wide
	 */
	public function activated_plugin( $plugin, $network_wide ) {
		$msg = 'Plugin `' . $plugin . '` is activated';

		if ( $network_wide ) {
			$msg .= ' network-wide';
		}

		Monitor::instance()->log(
			[
				'action'  => 'activated_plugin',
				'summary' => $msg,
			],
			'plugins'
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
			[
				'action'  => 'deactivated_plugin',
				'summary' => $msg,
			],
			'plugins'
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
			[
				'action'  => 'delete_plugin',
				'summary' => $msg,
			],
			'plugins'
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
			[
				'action'  => 'switch_theme',
				'summary' => 'Theme switched to `' . $new_name . '` from `' . $old_theme->get( 'Name' ) . '`',
			],
			'themes'
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
				[
					'action'  => 'deleted_theme',
					'summary' => 'Theme `' . $stylesheet . '` is deleted',
				],
				'themes'
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
			'adminhash',
			'blog_public',
			'blogname',
			'category_base',
			'default_comment_status',
			'default_role',
			'home',
			'page_for_posts',
			'page_on_front',
			'permalink_structure',
			'posts_per_page',
			'show_on_front',
			'siteurl',
			'tag_base',
			'upload_path',
			'upload_url_path',
			'users_can_register',
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
				[
					'action'  => 'updated_option',
					'summary' => 'Option `' . $option . '` is updated',
				],
				'options'
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
				[
					'action'  => 'added_option',
					'summary' => 'Option `' . $option . '` is added',
				],
				'options'
			);
		}
	}
}
