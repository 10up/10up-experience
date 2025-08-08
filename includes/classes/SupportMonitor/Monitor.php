<?php
/**
 * 10up monitor code. This module lets us gather non-PII info from sites running
 * the plugin e.g. plugin versions, WP version, etc.
 *
 * @since  1.7
 * @package 10up-experience
 */

namespace TenUpExperience\SupportMonitor;

use TenUpExperience\Singleton;

/**
 * Monitor class
 */
class Monitor {

	use Singleton;

	/**
	 * Setup module
	 *
	 * @since 1.7
	 */
	public function setup() {

		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			add_action( 'wpmu_options', [ $this, 'ms_settings' ] );
			add_action( 'admin_init', [ $this, 'ms_save_settings' ] );
		} else {
			add_action( 'admin_init', [ $this, 'register_settings' ] );
		}

		add_action( 'admin_init', [ $this, 'setup_report_cron' ] );
		add_action( 'send_daily_report_cron', [ $this, 'send_daily_report' ] );
	}

	/**
	 * Set options in multisite
	 *
	 * @since 1.7
	 */
	public function ms_save_settings() {
		global $pagenow;
		if ( ! is_network_admin() ) {
			return;
		}

		if ( 'settings.php' !== $pagenow ) {
			return;
		}

		if ( ! is_super_admin() ) {
			return;
		}

		// We're only checking if the nonce exists here, so no need to sanitize.
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'siteoptions' ) ) {
			return;
		}

		if ( ! isset( $_POST['tenup_support_monitor_settings'] ) ) {
			return;
		}

		$setting = $this->get_setting();

		if ( isset( $_POST['tenup_support_monitor_settings']['api_key'] ) ) {
			$setting['api_key'] = sanitize_text_field( $_POST['tenup_support_monitor_settings']['api_key'] );
		}

		if ( isset( $_POST['tenup_support_monitor_settings']['enable_support_monitor'] ) ) {
			$setting['enable_support_monitor'] = sanitize_text_field( $_POST['tenup_support_monitor_settings']['enable_support_monitor'] );
		}

		if ( isset( $_POST['tenup_support_monitor_settings']['server_url'] ) ) {
			$setting['server_url'] = sanitize_text_field( $_POST['tenup_support_monitor_settings']['server_url'] );
		}

		update_site_option( 'tenup_support_monitor_settings', $setting );
	}

	/**
	 * Output multisite settings
	 *
	 * @since 1.7
	 */
	public function ms_settings() {
		?>
		<h2><?php esc_html_e( 'Monitor', 'tenup' ); ?></h2>

		<?php $this->setting_section_description(); ?>

		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><?php esc_html_e( 'Enable', 'tenup' ); ?></th>
					<td>
						<?php $this->enable_field(); ?>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'API Key', 'tenup' ); ?></th>
					<td>
						<?php $this->api_key_field(); ?>
					</td>
				</tr>
				<?php if ( Debug::instance()->is_debug_enabled() ) : ?>
					<tr>
						<th scope="row"><?php esc_html_e( 'API Server', 'tenup' ); ?></th>
						<td>
							<?php $this->api_server_field(); ?>
						</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Get module
	 *
	 * @param  string $setting_key Setting key
	 * @since  1.7
	 * @return array
	 */
	public function get_setting( $setting_key = null ) {
		$defaults = [
			'enable_support_monitor' => 'no',
			'api_key'                => '',
			'server_url'             => 'https://monitor.10up.com',
		];

		$settings = ( TENUP_EXPERIENCE_IS_NETWORK ) ? get_site_option( 'tenup_support_monitor_settings', [] ) : get_option( 'tenup_support_monitor_settings', [] );
		$settings = wp_parse_args( $settings, $defaults );

		if ( ! Debug::instance()->is_debug_enabled() ) {
			$settings['server_url'] = 'https://monitor.10up.com';
		}

		if ( defined( 'SUPPORT_MONITOR_SERVER_URL' ) ) {
			$settings['server_url'] = SUPPORT_MONITOR_SERVER_URL;
		}

		if ( defined( 'SUPPORT_MONITOR_API_KEY' ) ) {
			$settings['api_key'] = SUPPORT_MONITOR_API_KEY;
		}

		if ( defined( 'SUPPORT_MONITOR_ENABLE' ) ) {
			$settings['enable_support_monitor'] = SUPPORT_MONITOR_ENABLE;
		}

		if ( ! empty( $setting_key ) ) {
			return $settings[ $setting_key ];
		}

		return $settings;
	}

	/**
	 * Output setting section description
	 *
	 * @since 1.7
	 */
	public function setting_section_description() {
		?>
		<p>
			<?php esc_html_e( '10up collects data on site health including plugin, WordPress, and system versions as well as general site issues to provide proactive support to your website. No proprietary data or user information is sent back to us. Although recommended, this functionality is optional and can be disabled.', 'tenup' ); ?>
		</p>
		<?php
	}

	/**
	 * Register settings
	 *
	 * @since 1.7
	 */
	public function register_settings() {
		add_settings_section(
			'tenup_support_monitor',
			esc_html__( 'Monitor', 'tenup' ),
			[ $this, 'setting_section_description' ],
			'general'
		);

		register_setting(
			'general',
			'tenup_support_monitor_settings',
			[
				'sanitize_callback' => [ $this, 'sanitize_settings' ],
			]
		);

		add_settings_field(
			'enable_support_monitor',
			esc_html__( 'Enable', 'tenup' ),
			[ $this, 'enable_field' ],
			'general',
			'tenup_support_monitor'
		);

		add_settings_field(
			'api_key',
			esc_html__( 'API Key', 'tenup' ),
			[ $this, 'api_key_field' ],
			'general',
			'tenup_support_monitor'
		);

		if ( Debug::instance()->is_debug_enabled() ) {
			add_settings_field(
				'server_url',
				esc_html__( 'API Server URL', 'tenup' ),
				[ $this, 'api_server_field' ],
				'general',
				'tenup_support_monitor'
			);
		}
	}

	/**
	 * Sanitize all settings
	 *
	 * @param  array $settings New settings
	 * @since  1.7
	 * @return array
	 */
	public function sanitize_settings( $settings ) {
		foreach ( $settings as $key => $setting ) {
			$settings[ $key ] = sanitize_text_field( $setting );
		}

		return $settings;
	}

	/**
	 * Output enable field
	 *
	 * @since 1.7
	 */
	public function enable_field() {
		$value = $this->get_setting( 'enable_support_monitor' );
		?>
		<input name="tenup_support_monitor_settings[enable_support_monitor]" <?php checked( 'yes', $value ); ?><?php disabled( defined( 'SUPPORT_MONITOR_ENABLE' ) ); ?> type="radio" id="tenup_enable_support_monitor_yes" value="yes"> <label for="tenup_enable_support_monitor_yes"><?php esc_html_e( 'Yes', 'tenup' ); ?></label><br>
		<input name="tenup_support_monitor_settings[enable_support_monitor]" <?php checked( 'no', $value ); ?><?php disabled( defined( 'SUPPORT_MONITOR_ENABLE' ) ); ?> type="radio" id="tenup_enable_support_monitor_no" value="no"> <label for="tenup_enable_support_monitor_no"><?php esc_html_e( 'No', 'tenup' ); ?></label>
		<?php
	}

	/**
	 * Output api key field
	 *
	 * @since 1.7
	 */
	public function api_key_field() {
		$value = $this->get_setting( 'api_key' );
		?>
		<input name="tenup_support_monitor_settings[api_key]" type="text" id="tenup_api_key" value="<?php echo esc_attr( $value ); ?>"<?php disabled( defined( 'SUPPORT_MONITOR_API_KEY' ) ); ?> class="regular-text">
		<?php
	}

	/**
	 * Output api server URL
	 *
	 * @since 1.7
	 */
	public function api_server_field() {
		$value = $this->get_setting( 'server_url' );

		?>
		<input placeholder="https://monitor.10up.com" name="tenup_support_monitor_settings[server_url]" type="text" id="server_url" value="<?php echo esc_attr( $value ); ?>"<?php disabled( defined( 'SUPPORT_MONITOR_SERVER_URL' ) ); ?> class="regular-text">
		<?php
	}


	/**
	 * Format a message to send
	 *
	 * @param  array  $data Arbitrary data
	 * @param  string $type Message type. Can be notice, warning, or error.
	 * @param  string $group Message group
	 * @since  1.7
	 * @return boolean
	 */
	public function format_message( $data, $type = 'notice', $group = 'message' ) {
		$setting = $this->get_setting();

		$message = [
			'time'       => time(),
			'data'       => $data,
			'type'       => sanitize_text_field( $type ),
			'group'      => sanitize_text_field( $group ),
			'message_id' => md5( $setting['api_key'] . microtime( true ) . wp_rand( 0, 10000 ) ),

		];

		return apply_filters( 'tenup_support_monitor_message', $message );
	}

	/**
	 * Check if logging is enabled
	 *
	 * @return boolean
	 */
	public function logging_enabled() {
		return ( ! defined( 'TENUP_DISABLE_ACTIVITYLOG' ) || ! TENUP_DISABLE_ACTIVITYLOG );
	}

	/**
	 * Create a log entry
	 *
	 * @param array  $data   Data related to the action..
	 * @param string $group Group
	 */
	public function log( $data = [], $group = null ) {
		if ( ! $this->logging_enabled() ) {
			return;
		}

		/**
		 * Filters whether to log the message.
		 *
		 * @param string $data   Data related to the action.
		 * @param string $subgroup Sub group.
		 */
		if ( ! apply_filters( 'tenup_support_monitor_log_item', $data, $group ) ) {
			return;
		}

		$current_logs = get_option( 'tenup_support_monitor_activity_logs', [] );

		if ( apply_filters( 'tenup_support_monitor_max_activity_log_count', 500 ) <= count( $current_logs ) ) {
			return;
		}

		$log_item = [
			'group'     => $group,
			'createdAt' => time(),
			'log'       => $data['summary'],
			'action'    => $data['action'],
			'userId'    => get_current_user_id(),
		];

		$current_logs[] = $log_item;

		update_option( 'tenup_support_monitor_activity_logs', $current_logs, false );
	}

	/**
	 * Setup daily report cron
	 *
	 * @since  1.7
	 */
	public function setup_report_cron() {
		$setting = $this->get_setting();

		if ( empty( $setting['api_key'] ) || 'yes' !== $setting['enable_support_monitor'] ) {
			if ( wp_next_scheduled( 'send_daily_report_cron' ) ) {
				wp_unschedule_hook( 'send_daily_report_cron' );
			}

			return;
		}

		if ( ! wp_next_scheduled( 'send_daily_report_cron' ) ) {
			wp_schedule_event( time(), 'daily', 'send_daily_report_cron' );
		}
	}

	/**
	 * Check if xmlrpc is enabled
	 *
	 * @since 1.7
	 * @return boolean
	 */
	public function xmlrpc_enabled() {
		$enabled = apply_filters( 'pre_option_enable_xmlrpc', false );

		if ( false === $enabled ) {
			$enabled = apply_filters( 'option_enable_xmlrpc', true );
		}

		return apply_filters( 'xmlrpc_enabled', $enabled );
	}

	/**
	 * Send the daily report async
	 *
	 * @since 1.7
	 */
	public function send_daily_report() {
		global $wpdb;

		$setting = $this->get_setting();

		if ( empty( $setting['api_key'] ) || 'yes' !== $setting['enable_support_monitor'] ) {
			return;
		}

		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		require_once ABSPATH . 'wp-admin/includes/update.php';

		if ( $this->logging_enabled() ) {
			$logs = get_option( 'tenup_support_monitor_activity_logs', [] );

			if ( ! empty( $logs ) ) {
				update_option( 'tenup_support_monitor_activity_logs', [], false );
			}
		}

		$custom_data = [
			[
				'key'   => 'wp_cache',
				'value' => ( defined( 'WP_CACHE' ) && WP_CACHE ),
				'group' => 'system',
			],
			[
				'key'   => 'object_cache_enabled',
				'value' => $this->get_is_using_object_cache(),
				'group' => 'system',
			],
			[
				'key'   => 'db_version',
				'value' => ( isset( $wpdb->db_version ) ) ? $wpdb->db_version : '',
				'group' => 'system',
			],
			[
				'key'   => 'wp_debug',
				'value' => ( defined( 'WP_DEBUG' ) && WP_DEBUG ),
				'group' => 'system',
			],
			[
				'key'   => 'disallow_file_mods',
				'value' => ( defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS ),
				'group' => 'system',
			],
			[
				'key'   => 'xmlrpc_enabled',
				'value' => $this->xmlrpc_enabled(),
				'group' => 'system',
			],
		];

		$body = [
			'url'          => TENUP_EXPERIENCE_IS_NETWORK ? network_home_url() : home_url(),
			'platform'     => 'wordpress',
			'packages'     => $this->get_packages(),
			'activityLogs' => $logs,
			'customData'   => $custom_data,
			'users'        => $this->get_users(),
		];

		$this->send_request( $body );
	}

	/**
	 * Send request to hub
	 *
	 * @param array $request_body Body of request
	 * @since 1.7
	 */
	public function send_request( $request_body ) {

		$setting = $this->get_setting();

		if ( empty( $setting['server_url'] ) ) {
			return false;
		}

		$api_url = apply_filters( 'tenup_support_monitor_api_url', esc_url( untrailingslashit( $setting['server_url'] ) . '/api/reports/create' ), $request_body );
		$api_key = $this->get_setting( 'api_key' );

		if ( empty( $api_key ) || empty( $request_body ) || empty( $api_url ) ) {
			return;
		}

		$request = [
			'method'   => 'POST',
			'timeout'  => 30,
			'body'     => apply_filters( 'tenup_support_monitor_request_body', wp_json_encode( $request_body ) ),
			'blocking' => Debug::instance()->is_debug_enabled(),
			'headers'  => [
				'Content-Type' => 'application/json',
				'x-api-key'    => sanitize_text_field( $api_key ),
			],
		];

		$response = wp_remote_request(
			$api_url,
			$request
		);

		// Create entry in debug log if debugger enabled
		Debug::instance()->maybe_add_log_entry(
			$api_url,
			$request_body,
			wp_remote_retrieve_response_code( $response )
		);
	}

	/**
	 * Get WP plugins
	 *
	 * @since  1.7
	 * @return array
	 */
	public function get_plugin_report() {

		$plugins = [];

		$_plugins = get_mu_plugins();

		foreach ( $_plugins as $file => $plugin ) {
			$plugins[] = [
				'slug'    => $this->get_plugin_name( $file ),
				'name'    => $plugin['Name'],
				'status'  => 'must-use',
				'version' => $plugin['Version'],
			];
		}

		$_plugins = get_plugins();

		foreach ( $_plugins as $file => $plugin ) {
			$plugins[] = [
				'slug'    => $this->get_plugin_name( $file ),
				'name'    => $plugin['Name'],
				'status'  => $this->get_status( $file ),
				'version' => $plugin['Version'],
			];
		}
		return $plugins;
	}

	/**
	 * Get all packages
	 *
	 * @return array
	 */
	public function get_packages() {
		$packages = [
			[
				'name'    => 'WordPress',
				'version' => $this->get_wp_version(),
				'type'    => 'system',
				'slug'    => 'wordpress',
			],
			[
				'name'    => 'PHP',
				'type'    => 'system',
				'slug'    => 'php',
				'version' => $this->get_php_version(),
			],
		];

		$_plugins = get_mu_plugins();

		foreach ( $_plugins as $file => $plugin ) {
			$packages[] = [
				'slug'    => $this->get_plugin_name( $file ),
				'name'    => $plugin['Name'],
				'status'  => 'must-use',
				'type'    => 'wp-plugin',
				'version' => $plugin['Version'],
			];
		}

		$_plugins = get_plugins();

		foreach ( $_plugins as $file => $plugin ) {
			$packages[] = [
				'slug'    => $this->get_plugin_name( $file ),
				'name'    => $plugin['Name'],
				'status'  => $this->get_status( $file ),
				'type'    => 'wp-plugin',
				'version' => $plugin['Version'],
			];
		}

		$_themes = wp_get_themes();
		foreach ( $_themes as $key => $theme ) {
			$packages[] = [
				'slug'    => $key,
				'name'    => $theme['Name'],
				'status'  => $theme['Status'],
				'version' => $theme['Version'],
				'type'    => 'wp-theme',
			];
		}

		return $packages;
	}

	/**
	 * Get WP themes
	 *
	 * @since  1.7
	 * @return array
	 */
	public function get_theme_report() {

		$themes = [];

		$_themes = wp_get_themes();
		foreach ( $_themes as $key => $theme ) {
			$themes[] = [
				'slug'    => $key,
				'name'    => $theme['Name'],
				'status'  => $theme['Status'],
				'version' => $theme['Version'],
			];
		}
		return $themes;
	}

	/**
	 * Get WP version
	 *
	 * @since  1.7
	 * @return string
	 */
	public function get_wp_version() {
		global $wp_version;

		if ( ! empty( $wp_version ) ) {
			return $wp_version;
		}

		return null;
	}

	/**
	 * Get name of plugin from file
	 *
	 * @param  string $basename File name
	 * @since  1.7
	 * @return string
	 */
	public function get_plugin_name( $basename ) {
		if ( false === strpos( $basename, '/' ) ) {
			$name = basename( $basename, '.php' );
		} else {
			$name = dirname( $basename );
		}
		return $name;
	}

	/**
	 * Get status for plugin
	 *
	 * @param  string $file File name for plugin
	 * @since  1.7
	 * @return string
	 */
	public function get_status( $file ) {
		if ( is_plugin_active_for_network( $file ) ) {
			return 'active-network';
		} if ( is_plugin_active( $file ) ) {
			return 'active';
		}

		return 'inactive';
	}

	/**
	 * Get PHP version for site
	 *
	 * @since  1.7
	 * @return string
	 */
	public function get_php_version() {
		return phpversion();
	}


	/**
	 * Get users for site
	 *
	 * @return array
	 */
	public function get_users() {
		$users = [];

		$args = [
			'search'         => '*@get10up.com',
			'search_columns' => [ 'user_email' ],
			'number'         => '1000',
			'ep_integrate'   => false,
		];

		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			$args['blog_id'] = 0;
		}

		$_users = get_users( $args );

		foreach ( $_users as $user ) {
			$users[] = [
				'email' => $user->user_email,
				'name'  => $user->display_name,
				'role'  => $user->roles,
			];
		}

		$args = [
			'search'         => '*@10up.com',
			'search_columns' => [ 'user_email' ],
			'number'         => '1000',
			'ep_integrate'   => false,
		];

		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			$args['blog_id'] = 0;
		}

		$_users = get_users( $args );

		foreach ( $_users as $user ) {
			$users[] = [
				'email' => $user->user_email,
				'name'  => $user->display_name,
				'role'  => $user->roles,
			];
		}

		$args = [
			'search'         => '*@fueled.com',
			'search_columns' => [ 'user_email' ],
			'number'         => '1000',
			'ep_integrate'   => false,
		];

		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			$args['blog_id'] = 0;
		}

		$_users = get_users( $args );

		foreach ( $_users as $user ) {
			$users[] = [
				'email' => $user->user_email,
				'name'  => $user->display_name,
				'role'  => $user->roles,
			];
		}

		return $users;
	}

	/**
	 * Check if the site is using an external object cache.
	 *
	 * @return bool
	 */
	public function get_is_using_object_cache() {
		if ( wp_using_ext_object_cache() ) {
			return true;
		}

		// If this is a VIP site, we can assume they are using an object cache.
		if ( defined( 'VIP_GO_APP_ENVIRONMENT' ) && 'local' !== VIP_GO_APP_ENVIRONMENT ) {
			return true;
		}

		return file_exists( WP_CONTENT_DIR . '/object-cache.php' );
	}
}
