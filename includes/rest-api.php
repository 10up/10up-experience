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

	$restrict = get_option( 'tenup_restrict_rest_api', 'all' );

	if ( 'none' === $restrict ) {
		return $result;
	}

	if ( ! user_can_access_rest_api() ) {
		if ( 'users' === $restrict ) {
			add_filter( 'rest_endpoints', function( $endpoints ) {
				$keys = preg_grep( '/\/wp\/v2\/users\b/', array_keys( $endpoints ) );

				foreach( $keys as $key ) {
					unset( $endpoints[ $key ] );
				}

				return $endpoints;
			} );
		} else {
			return new \WP_Error( 'rest_api_restricted', __( 'Authentication Required', 'tenup' ), array( 'status' => rest_authorization_required_code() ) );
		}
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
		'type'              => 'string',
		'sanitize_callback' => __NAMESPACE__ . '\validate_restrict_rest_api_setting',
	);

	register_setting( 'reading', 'tenup_restrict_rest_api', $settings_args );
	add_settings_field( 'tenup_restrict_rest_api', __( 'Public REST API Access', 'tenup' ), __NAMESPACE__ . '\restrict_rest_api_ui', 'reading' );
}
add_action( 'admin_init', __NAMESPACE__ . '\restrict_rest_api_setting' );

/**
 * Display UI for restrict REST API setting.
 *
 * @return void
 */
function restrict_rest_api_ui() {
	$restrict = get_option( 'tenup_restrict_rest_api', 'all' );
?>
<fieldset>
	<legend class="screen-reader-text"><?php esc_html_e( 'Public REST API Access', 'tenup' ); ?></legend>
	<p><label for="restrict-rest-api-all"><input id="restrict-rest-api-all" name="tenup_restrict_rest_api" type="radio" value="all"<?php checked( $restrict, 'all' ); ?> /> <?php esc_html_e( 'Restrict REST API access to authenticated users', 'tenup' ); ?></label></p>
	<p><label for="restrict-rest-api-users"><input id="restrict-rest-api-users" name="tenup_restrict_rest_api" type="radio" value="users"<?php checked( $restrict, 'users' ); ?> /> <?php esc_html_e( 'Restrict access to the users endpoint to authenticated users', 'tenup' ); ?></label></p>
	<p><label for="restrict-rest-api-n"><input id="restrict-rest-api-n" name="tenup_restrict_rest_api" type="radio" value="none"<?php checked( $restrict, 'none' ); ?> /> <?php esc_html_e( 'Allow public access to the REST API', 'tenup' ); ?></label></p>
</fieldset>
<?php
}

/**
 * Check if user can access REST API based on our criteria
 * @param  int $user_id User ID
 * @return bool         Whether the given user can access the REST API
 */
function user_can_access_rest_api( $user_id = 0 ) {
	return is_user_logged_in();
}

/**
 * Sanitize the `tenup_restrict_rest_api` setting.
 *
 * @param  string $value
 * @return string
 */
function validate_restrict_rest_api_setting( $value ) {
	if ( in_array( $value, array( 'all', 'users', 'none' ), true ) ) {
		return $value;
	}

	// Default to 'all' in case something wrong gets sent
	return 'all';
}
