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
 * Register settings
 *
 * @since 1.0
 */
function register_settings() {
	add_settings_section(
		'tenup_support_monitor',
		esc_html__( 'Support Monitor', 'tenup' ),
		'',
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

