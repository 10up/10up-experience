<?php
namespace tenup;

/**
 * Return a 403 status and corresponding error for unauthed REST API access.
 * @param  WP_Error|null|bool $result Error from another authentication handler,
 *                                    null if we should handle it, or another value
 *                                    if not.
 * @return WP_Error|null|bool
 */
function restrict_rest_api( $result ) {
	// Respect other handlers
	if ( null !== $result ) {
		return $result;
	}

	$restrict = get_option( 'tenup_restrict_rest_api', true );

	if ( filter_var( $restrict, FILTER_VALIDATE_BOOLEAN ) && ! is_user_logged_in() ) {
		return new \WP_Error( 'rest_api_restricted', __( 'Authentication Required', 'tenup' ), array( 'status' => 403 ) );
	}

	return $result;
}
// Make sure this runs somewhat late but before core's cookie auth at 100
add_filter( 'rest_authentication_errors', __NAMESPACE__ . '\restrict_rest_api', 99 );

/**
 * Register restrict REST API setting.
 *
 * @return void
 */
function restrict_rest_api_setting() {
	// If the restriction has been lifted on the code level, don't display a UI option
	if ( ! has_filter( 'rest_authentication_errors', __NAMESPACE__ . '\restrict_rest_api' ) ) {
		return false;
	}

	$settings_args = array(
		'type'              => 'boolean',
		'sanitize_callback' => __NAMESPACE__ . '\sanitize_checkbox_bool',
	);

	register_setting( 'reading', 'tenup_restrict_rest_api', $settings_args );
	add_settings_field( 'tenup_restrict_rest_api', __( 'REST API Access', 'tenup' ), __NAMESPACE__ . '\restrict_rest_api_ui', 'reading' );
}
add_action( 'admin_init', __NAMESPACE__ . '\restrict_rest_api_setting' );

/**
 * Display UI for restrict REST API setting.
 *
 * @return void
 */
function restrict_rest_api_ui() {
	$restrict = get_option( 'tenup_restrict_rest_api', true );
?>
<fieldset>
	<legend class="screen-reader-text"><?php esc_html_e( 'REST API Access', 'tenup' ); ?></legend>
	<p><label for="restrict-rest-api-y"><input id="restrict-rest-api-y" name="tenup_restrict_rest_api" type="radio" value="1"<?php checked( $restrict ); ?> /> <?php esc_html_e( 'Restrict REST API access to authenticated users', 'tenup' ); ?></label></p>
	<p><label for="restrict-rest-api-n"><input id="restrict-rest-api-n" name="tenup_restrict_rest_api" type="radio" value="0"<?php checked( $restrict, false ); ?> /> <?php esc_html_e( 'Allow public access to the REST API', 'tenup' ); ?></label></p>
</fieldset>
<?php
}
