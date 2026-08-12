<?php
/**
 * Force strong password extension functionality
 *
 * @package  10up-experience
 */

namespace TenUpExperience\Authentication;

use TenUpExperience\Singleton;
use ZxcvbnPhp\Zxcvbn;

/**
 * Password extension functionality
 */
class Passwords {

	use Singleton;

	/**
	 * Stores the Have I Been Pwned API URL
	 */
	const HIBP_API_URL   = 'https://api.pwnedpasswords.com/range/';
	const HIBP_CACHE_KEY = 'tenup_experience_hibp';

	/**
	 * Setup hooks
	 *
	 * @since 1.7
	 */
	public function setup() {
		// If Force Strong Passwords plugin is active, bail.
		if ( function_exists( 'slt_fsp_init' ) ) {
			return;
		}

		if ( $this->require_strong_passwords() ) {
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts_styles' ] );
			add_action( 'login_enqueue_scripts', [ $this, 'enqueue_scripts_styles' ] );
			add_action( 'user_profile_update_errors', [ $this, 'validate_profile_update' ], 0, 3 );
			add_action( 'validate_password_reset', [ $this, 'validate_strong_password' ], 10, 2 );
			add_action( 'resetpass_form', [ $this, 'validate_resetpass_form' ], 10 );
			add_filter( 'authenticate', [ $this, 'prevent_weak_password_auth' ], 30, 3 );
		}

		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			add_action( 'wpmu_options', [ $this, 'ms_settings' ] );
			add_action( 'admin_init', [ $this, 'ms_save_settings' ] );
		} else {
			add_action( 'admin_init', [ $this, 'register_setting' ] );
		}
	}

	/**
	 * Register setting.
	 *
	 * @since 1.7
	 */
	public function register_setting() {

		$settings_args = array(
			'type'              => 'integer',
			'sanitize_callback' => 'intval',
		);

		register_setting( 'general', 'tenup_require_strong_passwords', $settings_args );

		add_settings_field(
			'tenup_require_strong_passwords',
			esc_html__( 'Require Strong Passwords', 'tenup' ),
			[ $this, 'settings_ui' ],
			'general',
			'default'
		);
	}

	/**
	 * Are strong passwords enforced or not
	 *
	 * @since 1.7
	 * @return boolean
	 */
	public function require_strong_passwords() {
		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			$require_strong_passwords = get_site_option( 'tenup_require_strong_passwords', 1 );
		} else {
			$require_strong_passwords = get_option( 'tenup_require_strong_passwords', 1 );
		}

		return $require_strong_passwords;
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

		if ( ! isset( $_POST['tenup_require_strong_passwords'] ) ) {
			return;
		}

		update_site_option( 'tenup_require_strong_passwords', (int) $_POST['tenup_require_strong_passwords'] );
	}

	/**
	 * Output multisite settings
	 *
	 * @since 1.7
	 */
	public function ms_settings() {
		$require_strong_passwords = $this->require_strong_passwords();

		?>
		<h2><?php esc_html_e( 'Passwords', 'tenup' ); ?></h2>

		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row">
						<?php esc_html_e( 'Require Strong Passwords', 'tenup' ); ?>
					</th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span><?php esc_html_e( 'Require Strong Passwords', 'tenup' ); ?></span></legend>
							<label for="require-strong-passwords-yes">
								<input name="tenup_require_strong_passwords" <?php checked( 1, $require_strong_passwords ); ?> type="radio" id="require-strong-passwords-yes" value="1">
								<?php esc_html_e( 'Yes', 'tenup' ); ?>
							</label><br>
							<label for="require-strong-passwords-no">
								<input name="tenup_require_strong_passwords" <?php checked( 0, $require_strong_passwords ); ?> type="radio" id="require-strong-passwords-no" value="0">
								<?php esc_html_e( 'No', 'tenup' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Require all users to use strong passwords.', 'tenup' ); ?></p>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Display UI for the setting setting.
	 *
	 * @since 1.7
	 */
	public function settings_ui() {
		$require_strong_passwords = $this->require_strong_passwords();

		?>
		<fieldset>
			<legend class="screen-reader-text"><span><?php esc_html_e( 'Require Strong Passwords', 'tenup' ); ?></span></legend>
			<label for="require-strong-passwords-yes">
				<input name="tenup_require_strong_passwords" <?php checked( 1, $require_strong_passwords ); ?> type="radio" id="require-strong-passwords-yes" value="1">
				<?php esc_html_e( 'Yes', 'tenup' ); ?>
			</label><br>
			<label for="require-strong-passwords-no">
				<input name="tenup_require_strong_passwords" <?php checked( 0, $require_strong_passwords ); ?> type="radio" id="require-strong-passwords-no" value="0">
				<?php esc_html_e( 'No', 'tenup' ); ?>
			</label>
			<p class="description"><?php esc_html_e( 'Require all users to use strong passwords.', 'tenup' ); ?></p>
		</fieldset>
		<?php
	}

	/**
	 * Prevent users from authenticating if they are using a weak password
	 *
	 * @param WP_User $user User object
	 * @param string  $username Username
	 * @param string  $password Password
	 * @since 1.7
	 * @return \WP_User|\WP_Error
	 */
	public function prevent_weak_password_auth( $user, $username, $password ) {
		$test_tlds = array( 'test', 'local', '' );
		$tld       = preg_replace( '#^.*\.(.*)$#', '$1', wp_parse_url( site_url(), PHP_URL_HOST ) );

		if ( ! in_array( $tld, $test_tlds, true ) && in_array( strtolower( trim( $password ) ), $this->weak_passwords(), true ) ) {
			return new \WP_Error(
				'Auth Error',
				sprintf(
					'%s <a href="%s">%s</a> %s',
					esc_html__( 'Please', 'tenup' ),
					esc_url( wp_lostpassword_url() ),
					esc_html__( 'reset your password', 'tenup' ),
					esc_html__( 'in order to meet current security measures.', 'tenup' )
				)
			);
		}

		return $user;
	}

	/**
	 * List of popular weak passwords
	 *
	 * @since 1.7
	 * @return array
	 */
	public function weak_passwords() {
		return array(
			'123456',
			'Password',
			'password',
			'12345678',
			'qwerty',
			'12345',
			'123456789',
			'letmein',
			'1234567',
			'football',
			'iloveyou',
			'admin',
			'welcome',
			'monkey',
			'login',
			'abc123',
			'starwars',
			'123123',
			'dragon',
			'passw0rd',
			'master',
			'hello',
			'freedom',
			'whatever',
			'qazwsx',
			'trustno1',
			'654321',
			'jordan23',
			'harley',
			'password1',
			'1234',
			'robert',
			'matthew',
			'jordan',
			'daniel',
		);
	}

	/**
	 * Setup styles and scripts for passwords
	 *
	 * @since 1.7
	 */
	public function enqueue_scripts_styles() {
		wp_enqueue_script( '10up-passwords', plugins_url( '/dist/js/passwords.js', TENUP_EXPERIENCE_FILE ), [], TENUP_EXPERIENCE_VERSION, true );

		wp_localize_script(
			'10up-passwords',
			'tenupPasswords',
			[
				'message' => esc_html__( 'Passwords must be medium strength or greater.', 'tenup' ),
			]
		);

		wp_enqueue_style( '10up-passwords', plugins_url( '/dist/css/passwords-styles.css', TENUP_EXPERIENCE_FILE ), [], TENUP_EXPERIENCE_VERSION );
	}


	/**
	 * Check user profile update and throw an error if the password isn't strong.
	 *
	 * @param WP_Error $errors Current potential password errors
	 * @param boolean  $update Whether PW update or not
	 * @param WP_User  $user_data User being handled
	 * @since 1.7
	 * @return WP_Error
	 */
	public function validate_profile_update( $errors, $update, $user_data ) {
		return $this->validate_strong_password( $errors, $user_data );
	}

	/**
	 * Check password reset form and throw an error if the password isn't strong.
	 *
	 * @param WP_User $user_data User being handled
	 * @since 1.7
	 * @return WP_Error
	 */
	public function validate_resetpass_form( $user_data ) {
		return $this->validate_strong_password( false, $user_data );
	}


	/**
	 * Functionality used by both user profile and reset password validation.
	 *
	 * @param WP_Error $errors Current potential password errors
	 * @param WP_User  $user_data User being handled
	 * @since 1.7
	 * @return WP_Error
	 */
	public function validate_strong_password( $errors, $user_data ) {
		$password_ok = true;
		$enforce     = true;
		// This is being sanitized later in the function, no need to sanitize for isset().
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$password = ( isset( $_POST['pass1'] ) && trim( $_POST['pass1'] ) ) ? sanitize_text_field( $_POST['pass1'] ) : false;
		$role     = isset( $_POST['role'] ) ? sanitize_text_field( $_POST['role'] ) : false;
		$user_id  = isset( $user_data->ID ) ? sanitize_text_field( $user_data->ID ) : false;
		$username = isset( $_POST['user_login'] ) ? sanitize_text_field( $_POST['user_login'] ) : $user_data->user_login;

		// No password set?
		// Already got a password error?
		if ( ( false === $password ) || ( is_wp_error( $errors ) && $errors->get_error_data( 'pass' ) ) ) {
			return $errors;
		}

		// Validate the password against the Have I Been Pwned API.
		if ( ! $this->is_password_secure( $password ) && is_wp_error( $errors ) ) {
			$errors->add( 'password_reset_error', __( '<strong>ERROR:</strong> The password entered may have been included in a data breach and is not considered safe to use. Please choose another.', 'tenup' ) );
		}

		// Should a strong password be enforced for this user?
		if ( $user_id ) {

			// User ID specified.
			$enforce = $this->enforce_for_user( $user_id );

		} elseif ( $role && in_array( $role, apply_filters( 'tenup_experience_weak_roles', array( 'subscriber' ) ), true ) ) {
			$enforce = false;
		}

		// Enforce?
		if ( $enforce ) {
			// Zxcbn requires the mbstring PHP extension and min PHP 7.2, so we'll need to check for it before using
			if ( function_exists( 'mb_ord' ) && version_compare( PHP_VERSION, '7.2.0' ) >= 0 ) {
				$zxcvbn = new Zxcvbn();

				$pw = $zxcvbn->passwordStrength( $password );

				if ( 3 > (int) $pw['score'] ) {
					$password_ok = false;
				}
			}
		}

		if ( ! $password_ok && is_wp_error( $errors ) ) {
			$errors->add( 'pass', apply_filters( 'tenup_experience_password_error_message', __( '<strong>ERROR</strong>: Password must be medium strength or greater.', 'tenup' ) ) );
		}

		return $errors;
	}


	/**
	 * Check whether the given WP user should be forced to have a strong password
	 *
	 * @since   1.7
	 * @param   int $user_id A user ID.
	 * @return  boolean
	 */
	public function enforce_for_user( $user_id ) {
		$enforce = true;

		// Force strong passwords from network admin screens.
		if ( is_network_admin() ) {
			return $enforce;
		}

		$check_caps = apply_filters(
			'tenup_experience_strong_password_caps',
			[
				'edit_posts',
			]
		);

		if ( ! empty( $check_caps ) ) {
			$enforce = false; // Now we won't enforce unless the user has one of the caps specified.

			foreach ( $check_caps as $cap ) {
				if ( user_can( $user_id, $cap ) ) {
					$enforce = true;
					break;
				}
			}
		}

		return $enforce;
	}

	/**
	 * Check if password is secure by querying the Have I Been Pwned API.
	 *
	 * @param string $password Password to validate.
	 *
	 * @return bool True if password is ok, false if it shows up in a breach.
	 */
	protected function is_password_secure( $password ): bool {
		// Default
		$is_password_secure = true;

		// Allow opt-out of Have I Been Pwned check through a constant or filter.
		if (
			( defined( 'TENUP_EXPERIENCE_DISABLE_HIBP' ) && TENUP_EXPERIENCE_DISABLE_HIBP ) ||
			apply_filters( 'tenup_experience_disable_hibp', false, $password )
		) {
			return true;
		}

		$hash   = strtoupper( sha1( $password ) );
		$prefix = substr( $hash, 0, 5 );
		$suffix = substr( $hash, 5 );

		/**
		 * Filter the largest range response accepted from the HIBP API, in bytes.
		 *
		 * A padded range response is roughly 30-50KB. The cap bounds what a hijacked
		 * or proxied endpoint can stream into memory, and doubles as the truncation
		 * boundary below.
		 *
		 * @param int $limit Maximum response size in bytes.
		 */
		$limit = max( 1024, (int) apply_filters( 'tenup_experience_hibp_max_response_bytes', 128 * 1024 ) );

		$cache_key     = 'prefix_' . $prefix;
		$transient_key = self::HIBP_CACHE_KEY . '_' . strtolower( $cache_key );
		$body          = wp_cache_get( $cache_key, self::HIBP_CACHE_KEY );
		$cached        = false !== $body;

		if ( ! $cached ) {
			$body   = get_transient( $transient_key );
			$cached = false !== $body;

			// Warm the object cache from the transient so repeat lookups skip the DB read.
			if ( $cached ) {
				wp_cache_set( $cache_key, $body, self::HIBP_CACHE_KEY, 4 * HOUR_IN_SECONDS );
			}
		}

		if ( ! $cached ) {
			// wp_remote_get accepts a float timeout; casting to int truncates a fractional
			// filter value, and 0 (from an invalid/empty return) means "no timeout" — a hang
			// risk. Allow floats and floor invalid values.
			$timeout = (float) apply_filters( 'tenup_experience_hibp_request_timeout', 2 );
			if ( $timeout <= 0 ) {
				$timeout = 2;
			}

			$response = wp_remote_get(
				self::HIBP_API_URL . $prefix,
				[
					'timeout'             => $timeout,
					'limit_response_size' => $limit,
					'user-agent'          => '10up Experience WordPress Plugin',
					// Ask HIBP to pad the response so its size cannot reveal how many
					// real matches the prefix held. Padded rows carry a count of 0 and
					// are skipped when scanning for the suffix below.
					'headers'             => [ 'Add-Padding' => 'true' ],
				]
			);

			// If the request fails or times out, fail open: allow the password rather than
			// blocking the user on an unreachable third-party API. The result is not cached,
			// so the next attempt retries the check.
			if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
				return true;
			}

			$body = (string) wp_remote_retrieve_body( $response );

			// Check the transport boundary before trimming. A capped response can end
			// exactly on a CRLF, and trimming first would hide those bytes and make a
			// truncated range look complete enough to validate and cache. A truncated
			// range is the dangerous case: the missing tail is indistinguishable from
			// "not breached", so a real match would read as clean.
			if ( strlen( $body ) >= $limit ) {
				return true;
			}
		}

		$body = trim( (string) $body );

		// Treat an empty or malformed range as unavailable rather than parsing it as
		// "no match". Every line is a 35-character hash suffix, a colon, and a count;
		// an HTML error page served with a 200, or a partial final line, is not a
		// range response and must not be trusted or cached.
		if ( '' === $body || ! preg_match( '/\A[0-9A-F]{35}:[0-9]+(?:\r?\n[0-9A-F]{35}:[0-9]+)*\z/i', $body ) ) {
			return true;
		}

		if ( ! $cached ) {
			// Cache the validated prefix response only. Avoid storing full password hashes.
			wp_cache_set( $cache_key, $body, self::HIBP_CACHE_KEY, 4 * HOUR_IN_SECONDS );
			set_transient( $transient_key, $body, 4 * HOUR_IN_SECONDS );
		}

		foreach ( preg_split( '/\r\n|\n/', $body ) as $line ) {
			$parts = explode( ':', trim( $line ), 2 );

			if ( 2 !== count( $parts ) ) {
				continue;
			}

			// A row with a count of 0 is padding, not a breach match. Compare the
			// suffix case-insensitively: the API returns upper-case hex, but the
			// cached body is only guaranteed to match the validation pattern above.
			if ( (int) $parts[1] > 0 && 0 === strcasecmp( $parts[0], $suffix ) ) {
				$is_password_secure = false;
				break;
			}
		}

		return $is_password_secure;
	}
}
