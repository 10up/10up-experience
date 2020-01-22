<?php
/**
 * 10up suppport monitor code. This module lets us gather non-PII info from sites running
 * the plugin e.g. plugin versions, WP version, etc.
 *
 * @since  1.7
 * @package 10up-experience
 */

namespace TenUpExperience\SupportMonitor;

/**
 * Setup module
 *
 * @since 1.7
 */
function setup() {
	if ( TENUP_EXPERIENCE_IS_NETWORK ) {
		add_action( 'wpmu_options', __NAMESPACE__ . '\ms_settings' );
		add_action( 'admin_init', __NAMESPACE__ . '\ms_save_settings' );
	} else {
		add_action( 'admin_init', __NAMESPACE__ . '\register_settings' );
	}

	add_action( 'tenup_support_monitor_message_cron', __NAMESPACE__ . '\send_cron_messages' );
	add_action( 'admin_init', __NAMESPACE__ . '\setup_report_cron' );
	add_action( 'send_daily_report_cron', __NAMESPACE__ . '\send_daily_report' );
}

/**
 * Set options in multisite
 *
 * @since 1.7
 */
function ms_save_settings() {
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

	$setting = get_setting();

	if ( isset( $_POST['tenup_support_monitor_settings']['api_key'] ) ) {
		$setting['api_key'] = sanitize_text_field( $_POST['tenup_support_monitor_settings']['api_key'] );
	}

	if ( isset( $_POST['tenup_support_monitor_settings']['enable_support_monitor'] ) ) {
		$setting['enable_support_monitor'] = sanitize_text_field( $_POST['tenup_support_monitor_settings']['enable_support_monitor'] );
	}

	update_site_option( 'tenup_support_monitor_settings', $setting );
}

/**
 * Output multisite settings
 *
 * @since 1.7
 */
function ms_settings() {
	$setting = get_setting();
	?>
	<h2><?php esc_html_e( 'Support Monitor', 'tenup' ); ?>

	<?php setting_section_description(); ?>

	<table class="form-table" role="presentation">
		<tbody>
			<tr>
				<th scope="row">Enable</th>
				<td>
					<input name="tenup_support_monitor_settings[enable_support_monitor]" <?php checked( 'yes', $setting['enable_support_monitor'] ); ?> type="radio" id="tenup_enable_support_monitor_yes" value="yes"> <label for="tenup_enable_support_monitor_yes">Yes</label><br>
					<input name="tenup_support_monitor_settings[enable_support_monitor]" <?php checked( 'no', $setting['enable_support_monitor'] ); ?> type="radio" id="tenup_enable_support_monitor_no" value="no"> <label for="tenup_enable_support_monitor_no">No</label>
				</td>
			</tr>
			<tr>
				<th scope="row">API Key</th>
				<td>
					<input name="tenup_support_monitor_settings[api_key]" type="text" id="tenup_api_key" value="<?php echo esc_attr( $setting['api_key'] ); ?>" class="regular-text">
				</td>
			</tr>
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
function get_setting( $setting_key = null ) {
	$defaults = [
		'enable_support_monitor' => 'no',
		'api_key'                => '',
	];

	$settings = ( TENUP_EXPERIENCE_IS_NETWORK ) ? get_site_option( 'tenup_support_monitor_settings', [] ) : get_option( 'tenup_support_monitor_settings', [] );
	$settings = wp_parse_args( $settings, $defaults );

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
function get_queued_messages() {
	return ( TENUP_EXPERIENCE_IS_NETWORK ) ? get_site_option( 'tenup_support_monitor_messages', [] ) : get_option( 'tenup_support_monitor_messages', [] );
}

/**
 * Empty queued messages
 *
 * @since  1.7
 */
function reset_queued_messages() {
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
function queue_message( $message ) {
	$messages = get_queued_messages();

	$messages[] = $message;

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
function setting_section_description() {
	?>
	<p>
		<?php esc_html_e( '10up collects data on site health including plugin, WordPress, and system versions as well as general site issues to provide proactive support to your website. No proprietary data or user information is sent back to us. Although recommended, this functionality is optional and can be disabled.' ); ?>
	</p>
	<?php
}

/**
 * Register settings
 *
 * @since 1.0
 */
function register_settings() {
	add_settings_section(
		'tenup_support_monitor',
		esc_html__( '10up Support Monitor', 'tenup' ),
		__NAMESPACE__ . '\setting_section_description',
		'general'
	);

	register_setting(
		'general',
		'tenup_support_monitor_settings',
		[
			'sanitize_callback' => __NAMESPACE__ . '\sanitize_settings',
		]
	);

	add_settings_field(
		'enable_support_monitor',
		esc_html__( 'Enable', 'tenup' ),
		__NAMESPACE__ . '\enable_field',
		'general',
		'tenup_support_monitor'
	);

	add_settings_field(
		'api_key',
		esc_html__( 'API Key', 'tenup' ),
		__NAMESPACE__ . '\api_key_field',
		'general',
		'tenup_support_monitor'
	);

}

/**
 * Sanitize all settings
 *
 * @param  array $settings New settings
 * @since  1.7
 * @return array
 */
function sanitize_settings( $settings ) {
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
function enable_field() {
	$value = get_setting( 'enable_support_monitor' );
	?>
	<input name="tenup_support_monitor_settings[enable_support_monitor]" <?php checked( 'yes', $value ); ?> type="radio" id="tenup_enable_support_monitor_yes" value="yes"> <label for="tenup_enable_support_monitor_yes"><?php esc_html_e( 'Yes', 'tenup' ); ?></label><br>
	<input name="tenup_support_monitor_settings[enable_support_monitor]" <?php checked( 'no', $value ); ?> type="radio" id="tenup_enable_support_monitor_no" value="no"> <label for="tenup_enable_support_monitor_no"><?php esc_html_e( 'No', 'tenup' ); ?></label>
	<?php
}

/**
 * Output api key field
 *
 * @since 1.7
 */
function api_key_field() {
	$value = get_setting( 'api_key' );
	?>
	<input name="tenup_support_monitor_settings[api_key]" type="text" id="tenup_api_key" value="<?php echo esc_attr( $value ); ?>" class="regular-text">
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
function format_message( $data, $type = 'notice', $group = 'message' ) {
	$message = [
		'time'       => time(),
		'data'       => $data,
		'type'       => $type,
		'group'      => $group,
		'message_id' => md5( get_setting( 'api_key' ) . microtime() ),
	];

	return apply_filters( 'tenup_support_monitor_message', $message );
}

/**
 * Sends a message async one time
 *
 * @param  array  $data Arbitrary data
 * @param  string $type Message type. Can be notice, warning, or error.
 * @param  string $group Message group
 * @return boolean
 */
function send_message( $data, $type = 'notice', $group = 'message' ) {

	$setting = get_setting();

	if ( empty( $setting['api_key'] ) || 'yes' !== $setting['enable_support_monitor'] ) {
		wp_unschedule_hook( 'tenup_support_monitor_message_cron' );

		return false;
	}

	if ( ! wp_next_scheduled( 'tenup_support_monitor_message_cron' ) ) {
		queue_message( format_message( $data, $type ) );

		return wp_schedule_single_event( time(), 'tenup_support_monitor_message_cron' );
	}

	return true;
}

/**
 * Setup daily report cron
 *
 * @since  1.7
 */
function setup_report_cron() {
	$setting = get_setting();

	if ( empty( $setting['api_key'] ) || 'yes' !== $setting['enable_support_monitor'] ) {
		if ( wp_next_scheduled( 'send_daily_report_cron' ) ) {
			wp_unschedule_hook( 'send_daily_report_cron' );
		}

		return;
	}

	if ( ! wp_next_scheduled( 'send_daily_report_cron' ) ) {
		wp_schedule_event( time(), 'twicedaily', 'send_daily_report_cron' );
	}
}

/**
 * Send the daily report async
 *
 * @since 1.7
 */
function send_daily_report() {
	$setting = get_setting();

	if ( empty( $setting['api_key'] ) || 'yes' !== $setting['enable_support_monitor'] ) {
		return;
	}

	require_once ABSPATH . 'wp-admin/includes/plugin.php';
	require_once ABSPATH . 'wp-admin/includes/update.php';

	$messages = [
		format_message( get_plugin_report(), 'notice', 'plugins' ),
		format_message( get_wp_version(), 'notice', 'wp_core' ),
		format_message( get_php_version(), 'notice', 'system' ),
	];

	send_request( $messages );
}

/**
 * Send messages to hub
 *
 * @param  array $messages Array of messages
 * @since  1.7
 */
function send_request( $messages ) {
	$api_url = apply_filters( 'tenup_support_monitor_api_url', 'https://10up.com', $messages );

	wp_remote_request(
		$api_url,
		[
			'method'   => 'POST',
			'body'     => wp_json_encode( $messages ),
			'blocking' => false,
			'headers'  => [
				'X-Tenup-Support-Monitor-Key' => get_setting( 'api_key' ),
			],
		]
	);
}

/**
 * Send all the queued messages
 *
 * @since  1.7
 */
function send_cron_messages() {
	$messages = get_queued_messages();

	send_request( $messages );

	reset_queued_messages();
}

/**
 * Get WP plugins
 *
 * @since  1.7
 * @return array
 */
function get_plugin_report() {

	$plugins = [];

	$_plugins = get_mu_plugins();

	foreach ( $_plugins as $file => $plugin ) {
		$plugins[] = [
			'slug'    => get_plugin_name( $file ),
			'name'    => $plugin['Name'],
			'status'  => 'must-use',
			'version' => $plugin['Version'],
		];
	}

	$_plugins = get_plugins();

	foreach ( $_plugins as $file => $plugin ) {
		$plugins[] = [
			'slug'    => get_plugin_name( $file ),
			'name'    => $plugin['Name'],
			'status'  => get_status( $file ),
			'version' => $plugin['Version'],
		];
	}
	return $plugins;
}

/**
 * Get WP version
 *
 * @since  1.7
 * @return string
 */
function get_wp_version() {
	$data = get_preferred_from_update_core();
	return $data->version;
}

/**
 * Get name of plugin from file
 *
 * @param  string $basename File name
 * @since  1.7
 * @return string
 */
function get_plugin_name( $basename ) {
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
function get_status( $file ) {
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
function get_php_version() {
	return phpversion();
}


/**
 * Get users for site
 *
 * @since  1.7
 * @return array
 */
function get_users() {
	$users = [];

	$args = [
		'search'         => '*@get10up.com',
		'search_columns' => [ 'user_email' ],
		'number'         => '100',
	];

	$_users = get_users( $args );

	foreach ( $_users as $user ) {
		$users[] = [
			'email'        => $user->user_email,
			'display_name' => $user->display_name,
			'role'         => $user->roles,
		];
	}

	$args = [
		'search'         => '*@10up.com',
		'search_columns' => [ 'user_email' ],
		'number'         => '100',
	];

	$_users = get_users( $args );

	foreach ( $_users as $user ) {
		$users[] = [
			'email'        => $user->user_email,
			'display_name' => $user->display_name,
			'role'         => $user->roles,
		];
	}
	return $users;
}

