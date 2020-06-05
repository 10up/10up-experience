<?php
/**
 * REST API functionality
 *
 * @package  10up-experience
 */

namespace TenUpExperience\API;

use TenUpExperience\Singleton;

/**
 * REST API customizations class
 */
class API extends Singleton {

	/**
	 * Default value for API restriction
	 *
	 * @var string
	 */
	public $option_default = 'users';

	/**
	 * Setup module
	 *
	 * @since 1.7
	 */
	public function setup() {
		// Make sure this runs somewhat late but before core's cookie auth at 100
		add_filter( 'rest_authentication_errors', [ $this, 'restrict_rest_api' ], 99 );
		add_filter( 'rest_endpoints', [ $this, 'restrict_user_endpoints' ] );
		add_action( 'admin_init', [ $this, 'restrict_rest_api_setting' ] );
	}

	/**
	 * Return a 403 status and corresponding error for unauthed REST API access.
	 *
	 * @param  WP_Error|null|bool $result Error from another authentication handler,
	 *                                    null if we should handle it, or another value
	 *                                    if not.
	 * @return WP_Error|null|bool
	 */
	public function restrict_rest_api( $result ) {
		// Respect other handlers
		if ( null !== $result ) {
			return $result;
		}

		$restrict = get_option( 'tenup_restrict_rest_api', $this->option_default );

		if ( 'all' === $restrict && ! $this->user_can_access_rest_api() ) {
			return new \WP_Error( 'rest_api_restricted', esc_html__( 'Authentication Required', 'tenup' ), array( 'status' => rest_authorization_required_code() ) );
		}

		return $result;
	}

	/**
	 * Remove user endpoints for unauthed users.
	 *
	 * @param  array $endpoints Array of endpoints
	 * @return array
	 */
	public function restrict_user_endpoints( $endpoints ) {
		$restrict = get_option( 'tenup_restrict_rest_api', $this->option_default );

		if ( 'none' === $restrict ) {
			return $endpoints;
		}

		if ( ! $this->user_can_access_rest_api() ) {
			$keys = preg_grep( '/\/wp\/v2\/users\b/', array_keys( $endpoints ) );

			foreach ( $keys as $key ) {
				unset( $endpoints[ $key ] );
			}

			return $endpoints;
		}

		return $endpoints;
	}

	/**
	 * Register restrict REST API setting.
	 */
	public function restrict_rest_api_setting() {
		// If the restriction has been lifted on the code level, don't display a UI option
		if ( ! has_filter( 'rest_authentication_errors', [ $this, 'restrict_rest_api' ] )
			|| ! has_filter( 'rest_endpoints', [ $this, 'restrict_user_endpoints' ] ) ) {
			return false;
		}

		$settings_args = array(
			'type'              => 'string',
			'sanitize_callback' => [ $this, 'validate_restrict_rest_api_setting' ],
		);

		register_setting( 'reading', 'tenup_restrict_rest_api', $settings_args );
		add_settings_field( 'tenup_restrict_rest_api', esc_html__( 'REST API Availability', 'tenup' ), [ $this, 'restrict_rest_api_ui' ], 'reading' );
	}

	/**
	 * Display UI for restrict REST API setting.
	 *
	 * @return void
	 */
	public function restrict_rest_api_ui() {
		$restrict = get_option( 'tenup_restrict_rest_api', $this->option_default );
		?>
		<fieldset>
			<legend class="screen-reader-text"><?php esc_html_e( 'REST API Availability', 'tenup' ); ?></legend>
			<p><label for="restrict-rest-api-all">
				<input id="restrict-rest-api-all" name="tenup_restrict_rest_api" type="radio" value="all"<?php checked( $restrict, 'all' ); ?> />
				<?php esc_html_e( 'Restrict all access to authenticated users', 'tenup' ); ?>
			</label></p>
			<p><label for="restrict-rest-api-users">
				<input id="restrict-rest-api-users" name="tenup_restrict_rest_api" type="radio" value="users"<?php checked( $restrict, 'users' ); ?> />
				<?php
					echo wp_kses_post(
						sprintf(
							// translators: %s is a link to the developer reference for the users endpoint
							__( "Restrict access to the <code><a href='%s'>users</a></code> endpoint to authenticated users", 'tenup' ),
							esc_url( 'https://developer.wordpress.org/rest-api/reference/users/' )
						)
					);
				?>
			</label></p>
			<p><label for="restrict-rest-api-n">
				<input id="restrict-rest-api-n" name="tenup_restrict_rest_api" type="radio" value="none"<?php checked( $restrict, 'none' ); ?> />
				<?php esc_html_e( 'Publicly accessible', 'tenup' ); ?>
			</label></p>
		</fieldset>
		<?php
	}

	/**
	 * Check if user can access REST API based on our criteria
	 *
	 * @param  int $user_id User ID
	 * @return bool         Whether the given user can access the REST API
	 */
	public function user_can_access_rest_api( $user_id = 0 ) {
		return is_user_logged_in();
	}

	/**
	 * Sanitize the `tenup_restrict_rest_api` setting.
	 *
	 * @param  string $value Current restriction.
	 * @return string
	 */
	public function validate_restrict_rest_api_setting( $value ) {
		if ( in_array( $value, array( 'all', 'users', 'none' ), true ) ) {
			return $value;
		}

		// Default to 'users' in case something wrong gets sent
		return 'users';
	}
}
