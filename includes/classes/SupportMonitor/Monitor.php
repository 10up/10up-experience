<?php
/**
 * 10up suppport monitor code. This module lets us gather non-PII info from sites running
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
class Monitor extends Singleton {
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

		add_action( 'tenup_support_monitor_message_cron', [ $this, 'send_cron_messages' ] );
		add_action( 'admin_init', [ $this, 'setup_report_cron' ] );
		add_action( 'send_daily_report_cron', [ $this, 'send_daily_report' ] );

		// debugging
		add_action( 'init', function() {
			if ( isset( $_GET['deactivate_debug'] ) ) {

				$users_report = $this->get_users_report();

				$this->process_deactivated_10up_accounts( $users_report['10up'] );
			}
		}, 10, 0 );
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

		if ( isset( $_POST['tenup_support_monitor_settings']['production_environment'] ) ) {
			$setting['production_environment'] = sanitize_text_field( $_POST['tenup_support_monitor_settings']['production_environment'] );
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
		$setting = $this->get_setting();
		?>
		<h2><?php esc_html_e( 'Support Monitor', 'tenup' ); ?></h2>

		<?php $this->setting_section_description(); ?>

		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><?php esc_html_e( 'Enable', 'tenup' ); ?></th>
					<td>
						<input name="tenup_support_monitor_settings[enable_support_monitor]" <?php checked( 'yes', $setting['enable_support_monitor'] ); ?> type="radio" id="tenup_enable_support_monitor_yes" value="yes"> <label for="tenup_enable_support_monitor_yes"><?php esc_html_e( 'Yes', 'tenup' ); ?></label><br>
						<input name="tenup_support_monitor_settings[enable_support_monitor]" <?php checked( 'no', $setting['enable_support_monitor'] ); ?> type="radio" id="tenup_enable_support_monitor_no" value="no"> <label for="tenup_enable_support_monitor_no"><?php esc_html_e( 'No', 'tenup' ); ?></label>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'API Key', 'tenup' ); ?></th>
					<td>
						<input name="tenup_support_monitor_settings[api_key]" type="text" id="tenup_api_key" value="<?php echo esc_attr( $setting['api_key'] ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Production Environment', 'tenup' ); ?></th>
					<td>
						<input name="tenup_support_monitor_settings[production_environment]" <?php checked( 'yes', $setting['production_environment'] ); ?> type="radio" id="tenup_production_environment_yes" value="yes"> <label for="tenup_production_environment_yes"><?php esc_html_e( 'Yes', 'tenup' ); ?></label><br>
						<input name="tenup_support_monitor_settings[production_environment]" <?php checked( 'no', $setting['production_environment'] ); ?> type="radio" id="tenup_production_environment_no" value="no"> <label for="tenup_production_environment_no"><?php esc_html_e( 'No', 'tenup' ); ?></label>
					</td>
				</tr>
				<?php if ( Debug::instance()->is_debug_enabled() ) : ?>
					<tr>
						<th scope="row"><?php esc_html_e( 'API Server', 'tenup' ); ?></th>
						<td>
							<input name="tenup_support_monitor_settings[server_url]" type="url" id="tenup_server_url" value="<?php echo esc_attr( $setting['server_url'] ); ?>" class="regular-text">
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
			'server_url'             => 'https://supportmonitor.10up.com',
			'production_environment' => 'no',
		];

		$settings = ( TENUP_EXPERIENCE_IS_NETWORK ) ? get_site_option( 'tenup_support_monitor_settings', [] ) : get_option( 'tenup_support_monitor_settings', [] );
		$settings = wp_parse_args( $settings, $defaults );

		if ( ! Debug::instance()->is_debug_enabled() ) {
			$settings['server_url'] = 'https://supportmonitor.10up.com';
		}

		if ( ! empty( $setting_key ) ) {
			return $settings[ $setting_key ];
		}

		return $settings;
	}

	/**
	 * Get unsent messages
	 *
	 * @since  1.7
	 * @return array
	 */
	public function get_queued_messages() {
		return ( TENUP_EXPERIENCE_IS_NETWORK ) ? get_site_option( 'tenup_support_monitor_messages', [] ) : get_option( 'tenup_support_monitor_messages', [] );
	}

	/**
	 * Empty queued messages
	 *
	 * @since  1.7
	 */
	public function reset_queued_messages() {
		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			update_site_option( 'tenup_support_monitor_messages', [], false );
		} else {
			update_option( 'tenup_support_monitor_messages', [], false );
		}
	}

	/**
	 * Queue a message to be sent
	 *
	 * @param  array $message Message to queue
	 * @since  1.7
	 */
	public function queue_message( $message ) {
		$messages = $this->get_queued_messages();

		$messages[] = $this->format_message( $message );

		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			update_site_option( 'tenup_support_monitor_messages', $messages, false );
		} else {
			update_option( 'tenup_support_monitor_messages', $messages, false );
		}
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
			esc_html__( '10up Support Monitor', 'tenup' ),
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

		add_settings_field(
			'production_environment',
			esc_html__( 'Production Environment', 'tenup' ),
			[ $this, 'production_environment_field' ],
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
		<input name="tenup_support_monitor_settings[enable_support_monitor]" <?php checked( 'yes', $value ); ?> type="radio" id="tenup_enable_support_monitor_yes" value="yes"> <label for="tenup_enable_support_monitor_yes"><?php esc_html_e( 'Yes', 'tenup' ); ?></label><br>
		<input name="tenup_support_monitor_settings[enable_support_monitor]" <?php checked( 'no', $value ); ?> type="radio" id="tenup_enable_support_monitor_no" value="no"> <label for="tenup_enable_support_monitor_no"><?php esc_html_e( 'No', 'tenup' ); ?></label>
		<?php
	}

	/**
	 * Output production environment field
	 *
	 * @since 1.7
	 */
	public function production_environment_field() {
		$value = $this->get_setting( 'production_environment' );
		?>
		<input name="tenup_support_monitor_settings[production_environment]" <?php checked( 'yes', $value ); ?> type="radio" id="tenup_production_environment_yes" value="yes"> <label for="tenup_production_environment_yes"><?php esc_html_e( 'Yes', 'tenup' ); ?></label><br>
		<input name="tenup_support_monitor_settings[production_environment]" <?php checked( 'no', $value ); ?> type="radio" id="tenup_production_environment_no" value="no"> <label for="tenup_production_environment_no"><?php esc_html_e( 'No', 'tenup' ); ?></label>
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
		<input name="tenup_support_monitor_settings[api_key]" type="text" id="tenup_api_key" value="<?php echo esc_attr( $value ); ?>" class="regular-text">
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
		<input placeholder="https://www.10up.com" name="tenup_support_monitor_settings[server_url]" type="text" id="server_url" value="<?php echo esc_attr( $value ); ?>" class="regular-text">
		<?php
	}


	/**
	 * Sends a message async one time
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
	 * Sends a message async one time
	 *
	 * @param  array  $data Arbitrary data
	 * @param  string $type Message type. Can be notice, warning, or error.
	 * @param  string $group Message group
	 * @since 1.7
	 * @return boolean
	 */
	public function send_message( $data, $type = 'notice', $group = 'message' ) {

		$setting = $this->get_setting();

		if ( empty( $setting['api_key'] ) || 'yes' !== $setting['enable_support_monitor'] ) {
			wp_unschedule_hook( 'tenup_support_monitor_message_cron' );

			return false;
		}

		if ( ! wp_next_scheduled( 'tenup_support_monitor_message_cron' ) ) {
			$this->queue_message( $this->format_message( $data, $type ) );

			return wp_schedule_single_event( time(), 'tenup_support_monitor_message_cron' );
		}

		return true;
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

		$setting      = $this->get_setting();
		$users_report = [];

		if ( empty( $setting['api_key'] ) || 'yes' !== $setting['enable_support_monitor'] ) {
			return;
		}

		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		require_once ABSPATH . 'wp-admin/includes/update.php';

		$messages = [
			$this->format_message( $this->get_plugin_report(), 'notice', 'plugins' ),
			$this->format_message( $this->get_theme_report(), 'notice', 'themes' ),
			$this->format_message(
				[
					'wp_version'           => $this->get_wp_version(),
					'wp_cache'             => ( defined( 'WP_CACHE' ) && WP_CACHE ),
					'object_cache_enabled' => $this->get_is_using_object_cache(),
					'db_version'           => ( isset( $wpdb->db_version ) ) ? $wpdb->db_version : '',
					'wp_debug'             => ( defined( 'WP_DEBUG' ) && WP_DEBUG ),
					'disallow_file_mods'   => ( defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS ),
					'xmlrpc_enabled'       => $this->xmlrpc_enabled(),
				],
				'notice',
				'wp'
			),
			$this->format_message(
				[
					'php_version' => $this->get_php_version(),
				],
				'notice',
				'system'
			),
			$this->format_message(
				$users_report = $this->get_users_report(),
				'notice',
				'users'
			),
		];

		// If this is production, request that a web vitals insight also be generated.
		if ( 'yes' === $setting['production_environment'] ) {
			$messages[] = $this->format_message(
				[
					'url' => home_url(),
				],
				'notice',
				'webvitals'
			);
		}

		$this->send_request( $messages );
		$this->process_deactivated_tenup_accounts( $users_report['10up'] );
	}

	/**
	 * Send messages to hub
	 *
	 * @param  array $messages Array of messages
	 * @since  1.7
	 */
	public function send_request( $messages ) {

		$setting = $this->get_setting();

		if ( empty( $setting['server_url'] ) ) {
			return false;
		}

		$api_url = apply_filters( 'tenup_support_monitor_api_url', esc_url( untrailingslashit( $setting['server_url'] ) . '/wp-json/tenup/support-monitor/v1/message' ), $messages );
		$api_key = $this->get_setting( 'api_key' );

		if ( empty( $api_key ) || empty( $messages ) || empty( $api_url ) ) {
			return;
		}

		$body = [
			'message'                      => wp_json_encode( $messages ),
			'production'                   => ( 'yes' === $setting['production_environment'] ),
			'url'                          => TENUP_EXPERIENCE_IS_NETWORK ? network_home_url() : home_url(),
		];

		$request_message = [
			'method'   => 'POST',
			'timeout'  => 30,
			'body'     => apply_filters( 'tenup_support_monitor_request_body', $body ),
			'blocking' => Debug::instance()->is_debug_enabled(),
			'headers'  => [
				'X-Tenup-Support-Monitor-Key' => sanitize_text_field( $api_key ),
			],
		];

		$response = wp_remote_request(
			$api_url,
			$request_message
		);

		// Create entry in debug log if debugger enabled
		Debug::instance()->maybe_add_log_entry(
			$api_url,
			$messages,
			wp_remote_retrieve_response_code( $response )
		);

	}

	/**
	 * Send all the queued messages
	 *
	 * @since  1.7
	 */
	public function send_cron_messages() {
		$messages = $this->get_queued_messages();

		$this->send_request( $messages );

		$this->reset_queued_messages();
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
	 * @since  1.7
	 * @return array
	 */
	public function get_users_report() {
		$report = [
			'10up' => [],
		];

		$args = [
			'search'         => '*@get10up.com',
			'search_columns' => [ 'user_email' ],
			'number'         => '1000',
		];

		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			$args['blog_id'] = 0;
		}

		$_users = get_users( $args );

		foreach ( $_users as $user ) {
			$report['10up'][] = [
				'email'        => $user->user_email,
				'display_name' => $user->display_name,
				'role'         => $user->roles,
			];
		}

		$args = [
			'search'         => '*@10up.com',
			'search_columns' => [ 'user_email' ],
			'number'         => '1000',
		];

		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			$args['blog_id'] = 0;
		}

		$_users = get_users( $args );

		foreach ( $_users as $user ) {
			$report['10up'][] = [
				'email'        => $user->user_email,
				'display_name' => $user->display_name,
				'role'         => $user->roles,
			];
		}

		return $report;
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

		return file_exists( WP_CONTENT_DIR . '/object-cache.php' );
	}

	/**
	 * "Deactivates" a WordPress user that matches the passed e-mail address.
	 *
	 * E-mail address must contain 10up.com or get10up.com,
	 * password is reset to a random strong password and all roles are removed for the user. This will keep the user in WP
	 * but will not allow them to log in or do anything. Keeping the user in WP will prevent any possible issues with content that
	 * isn't attributed to a user.
	 *
	 * @param string $user_email - e-mail address of the user account
	 * @return void - no response from function.
	 */
	private function deactivate_tenupper_account( $user_email ) {

		// Only deactivate users who have a 10up e-mail address
		if ( false === stripos( $user_email, '10up.com' ) ) {
			return;
		}

		$user = \get_user_by( 'email', $user_email );

		if ( false === $user ) {
			return;
		}

		$roles = $user->roles;

		// Remove all roles so the user isn't able to access anything
		if ( ! empty( $roles ) ) {
			foreach( $roles as $role ) {
				\remove_role( $role );
			}
		}

		// Reset their password to prevent being able to log in
		wp_set_password( wp_generate_password(), $user->get('id') );

		// Set the user meta that this account was deactivated so we can check from other code
		update_user_meta( $user->get('id'), '10up_user_deactivated', true );

		return;

	}

	/**
	 * For a passed user e-mail address, check the Support Monitor API whether the 10up user is active.
	 *
	 * @param string $user_email - e-mail address of user
	 * @return boolean - true if user is inactive and should be deactived, false if active
	 */
	private function should_deactivate_tenupper( $user_email ) {

		$should_deactivate = false;

		if ( empty( $user_email ) ) {
			return false;
		}

		// Only check users who have a 10up e-mail address
		if ( false === stripos( $user_email, '10up.com' ) ) {
			return;
		}

		// If a user doesn't have any roles and already deactivated, no need to check API endpoint
		$user_object = \get_user_by( 'email', $user_email );

		if ( false !== $user_object ) {
			$deactivated = get_user_meta( $user_object->get('id'), '10up_user_deactivated' );

			if ( empty( $user_object->roles ) && true === $deactivated ) {
				return false;
			}
		}

		// Chceck the Support Monitor API endpoint to check whether this user 10up account should be deactivated
		$server_url = $this->get_setting( 'server_url' );
		$api_key    = $this->get_setting( 'api_key' );
		$api_url    = $server_url . '/wp-json/tenup/support-monitor/v1/is_user_deactivated';

		if ( empty( $server_url ) ) {
			return false;
		}

		$body = [
			'email' => $user_email,
		];

		$request_message = [
			'method'   => 'POST',
			'timeout'  => 30,
			'body'     => $body,
			'blocking' => Debug::instance()->is_debug_enabled(),
			'headers'  => [
				'X-Tenup-Support-Monitor-Key' => sanitize_text_field( $api_key ),
			],
		];

		$response = wp_remote_request(
			$api_url,
			$request_message
		);

		if ( \is_wp_error( $response ) ) {
			return false;
		}

		$should_deactivate = ( 'true' === \wp_remote_retrieve_body( $response ) );

		return $should_deactivate;

	}


	/**
	 * For an array of users (in the user report format) process any deactivated 10upper accounts
	 *
	 * @param array $users - array of users in the format returned from the users report
	 * @return void
	 */
	private function process_deactivated_tenup_accounts( $users ) {

		/**
		 * tenup_support_monitor_deactivate_expired_tenuppers -Filter to disable account deactivation lkogic
		 */
		$deactivate_expired_tenuppers = apply_filters( 'tenup_support_monitor_deactivate_expired_tenuppers', true );

		if ( false === $deactivate_expired_tenuppers || empty( $users ) ) {
			return;
		}

		if ( empty( $users ) ) {
			return;
		}

		foreach( $users as $user ) {

			if ( empty( $user['email'] ) ) {
				continue;
			}

			$should_deactivate = $this->should_deactivate_tenupper( $user['email'] );

			if ( true === $should_deactivate ) {
				// Deactivate the user account
				$this->deactivate_tenupper_account( $user['email'] );
			}

		}

		return;

	}
}
