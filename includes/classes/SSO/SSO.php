<?php
/**
 * 10up SSO client. Previously this lived in a separate plugin.
 *
 * @package  10up-experience
 */

namespace TenUpExperience\SSO;

use TenUpExperience\Singleton;
use WP_Error;

/**
 * SSO class
 */
class SSO {

	use Singleton;

	/**
	 * Setup SSO
	 */
	public function setup() {

		// If using the old SSO plugin, do nothing.
		if ( function_exists( 'tenup_sso_add_login_errors' ) ) {
			return;
		}

		if ( defined( 'TENUPSSO_DISABLE' ) && TENUPSSO_DISABLE ) {
			return;
		}

		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			add_action( 'wpmu_options', [ $this, 'ms_settings' ] );
			add_action( 'admin_init', [ $this, 'ms_save_settings' ] );
		} else {
			add_action( 'admin_init', [ $this, 'single_site_setting' ] );
		}

		if ( 'yes' !== $this->get_setting() ) {
			return;
		}

		if ( defined( 'TENUPSSO_DISALLOW_ALL_DIRECT_LOGIN' ) && TENUPSSO_DISALLOW_ALL_DIRECT_LOGIN ) {
			add_filter( 'allow_password_reset', '__return_false' );
		}

		add_filter( 'wp_login_errors', [ $this, 'add_login_errors' ] );
		add_action( 'login_form_10up-login', [ $this, 'process_client_login' ] );
		add_action( 'login_form', [ $this, 'update_login_form' ] );
		add_action( 'login_head', [ $this, 'render_login_form_styles' ] );
		add_filter( 'authenticate', [ $this, 'prevent_standard_login_for_sso_user' ], 999 );
		add_action( 'admin_page_access_denied', [ $this, 'check_user_blog' ] );
	}

	/**
	 * Set options in multisite
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

		// We're only checking if the var exists here, so no need to sanitize.
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		if ( ! isset( $_POST['tenup_allow_sso'] ) ) {
			return;
		}

		$setting = $this->validate_sso_setting( sanitize_text_field( $_POST['tenup_allow_sso'] ) );

		update_site_option( 'tenup_allow_sso', $setting );
	}

	/**
	 * Output multisite settings
	 */
	public function ms_settings() {
		$setting = $this->get_setting();
		?>
		<h2><?php esc_html_e( 'Fueled SSO', 'tenup' ); ?></h2>
		<p><?php esc_html_e( 'This allows members of Fueled on your project team to log in via SSO. This is extremely important to streamline maintenance of your website.', '10up' ); ?></p>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><?php esc_html_e( 'Allow Fueled SSO', 'tenup' ); ?></th>
					<td>
						<input name="tenup_allow_sso" <?php checked( 'yes', $setting ); ?> type="radio" id="tenup_allow_sso_yes" value="yes"> <label for="tenup_allow_sso_yes"><?php esc_html_e( 'Yes', 'tenup' ); ?></label><br>
						<input name="tenup_allow_sso" <?php checked( 'no', $setting ); ?> type="radio" id="tenup_allow_sso_no" value="no"> <label for="tenup_allow_sso_no"><?php esc_html_e( 'No', 'tenup' ); ?></label>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Get setting
	 *
	 * @return array
	 */
	public function get_setting() {
		$setting = ( TENUP_EXPERIENCE_IS_NETWORK ) ? get_site_option( 'tenup_allow_sso', 'yes' ) : get_option( 'tenup_allow_sso', 'yes' );

		return $setting;
	}

	/**
	 * Register restrict REST API setting.
	 */
	public function single_site_setting() {

		$settings_args = array(
			'type'              => 'string',
			'sanitize_callback' => [ $this, 'validate_sso_setting' ],
		);

		register_setting( 'general', 'tenup_allow_sso', $settings_args );
		add_settings_field( 'tenup_allow_sso', esc_html__( 'Allow Fueled SSO', 'tenup' ), [ $this, 'sso_setting_field_output' ], 'general' );
	}

	/**
	 * Validate sso setting.
	 *
	 * @param  string $value Current restriction.
	 * @return string
	 */
	public function validate_sso_setting( $value ) {
		if ( in_array( $value, array( 'yes', 'no' ), true ) ) {
			return $value;
		}

		return 'yes';
	}

	/**
	 * Display UI for restrict REST API setting.
	 *
	 * @return void
	 */
	public function sso_setting_field_output() {
		$allow_sso = $this->get_setting();
		?>

		<input id="tenup-allow-sso-yes" name="tenup_allow_sso" type="radio" value="yes"<?php checked( $allow_sso, 'yes' ); ?> />
		<label for="tenup-allow-sso-yes">
			<?php esc_html_e( 'Yes', 'tenup' ); ?>
		</label><br>

		<input id="tenup-allow-sso-no" name="tenup_allow_sso" type="radio" value="no"<?php checked( $allow_sso, 'no' ); ?> />
		<label for="tenup-allow-sso-no">
			<?php esc_html_e( 'No', 'tenup' ); ?>
		</label>
		<p class="description"><?php esc_html_e( 'This allows members of Fueled on your project team to log in via SSO. This is extremely important to streamline maintenance of your website.', '10up' ); ?></p>
		<?php
	}

	/**
	 * Show login errors on form if any exist
	 *
	 * @param WP_Error $errors Current errors
	 * @return WP_Error
	 */
	public function add_login_errors( WP_Error $errors ) {
		global $tenup_login_failed;

		if ( $tenup_login_failed ) {
			$error_code = filter_input( INPUT_GET, 'error' );
			switch ( $error_code ) {
				case 'invalid_email_domain':
					$errors->add( '10up-sso-login', esc_html__( 'The email address is not allowed.', 'tenup' ) );
					break;
				case 'bad_permissions':
					$errors->add( '10up-sso-login', esc_html__( 'You do not have permission to log into this site.', 'tenup' ) );
					break;
				default:
					$errors->add( '10up-sso-login', esc_html__( 'Login failed.', 'tenup' ) );
					break;
			}
		}

		return $errors;
	}

	/**
	 * Process a login request
	 */
	public function process_client_login() {
		global $tenup_login_failed;

		$email = filter_input( INPUT_GET, 'email', FILTER_VALIDATE_EMAIL );

		if ( ! empty( $_GET['error'] ) ) {
			$tenup_login_failed = true;
		} elseif ( ! empty( $email ) ) {
			$verify = add_query_arg(
				array(
					'action'      => '10up-verify',
					'email'       => rawurlencode( $email ),
					'sso_version' => TENUP_EXPERIENCE_VERSION,
					'nonce'       => rawurlencode( filter_input( INPUT_GET, 'nonce' ) ),
				),
				TENUPSSO_PROXY_URL
			);

			$response = wp_remote_get( $verify );

			if ( wp_remote_retrieve_response_code( $response ) !== 200 ) {
				wp_safe_redirect( wp_login_url() );
				exit;
			}

			$user_id = false;
			$user    = get_user_by( 'email', $email );

			if ( ! $user ) {
				$short_email = str_replace( '@get10up.com', '@10up.com', $email );
				$user        = get_user_by( 'email', $short_email );
			}

			if ( ! $user && preg_match( '#@fueled\.com$#i', $email ) ) {
				// Check if fueled person had a 10up email
				$old_10up_email = str_replace( '@fueled.com', '@get10up.com', $email );
				$tenup_user     = get_user_by( 'email', $old_10up_email );

				if ( ! $tenup_user ) {
					$old_10up_email = str_replace( '@fueled.com', '@10up.com', $email );
					$tenup_user     = get_user_by( 'email', $old_10up_email );
				}

				if ( $tenup_user ) {
					// Turn off email change notification
					add_filter( 'send_email_change_email', '__return_false' );

					// Update tenup user to use fueled email
					wp_update_user(
						array(
							'ID'         => $tenup_user->ID,
							'user_email' => $email,
						)
					);

					$user = get_user_by( 'id', $tenup_user->ID );
				}
			}

			if ( ! $user ) {
				$default_role = defined( 'TENUPSSO_DEFAULT_ROLE' )
					? TENUPSSO_DEFAULT_ROLE
					: 'subscriber';

				$username = current( explode( '@', $email ) );

				if ( username_exists( $username ) ) {
					// Turn periods into dashes.
					$username = str_replace( '.', '-', $username );
					// Add the domain onto the end, so it's more unique.
					$username = sprintf(
						'%s-%s',
						$username,
						explode( '.', explode( '@', $email )[1], 2 )[0]
					);
				}

				$user_id = wp_insert_user(
					array(
						'user_login'   => $username,
						'user_pass'    => wp_generate_password(),
						'user_email'   => $email,
						'display_name' => filter_input( INPUT_GET, 'full_name' ),
						'first_name'   => filter_input( INPUT_GET, 'first_name' ),
						'last_name'    => filter_input( INPUT_GET, 'last_name' ),
						'role'         => $default_role,
					)
				);

				if ( ! is_wp_error( $user_id ) ) {
					add_user_meta( $user_id, '10up-sso', 1 );

					if ( is_multisite() ) {
						add_user_to_blog( get_current_blog_id(), $user_id, $default_role );
						if ( defined( 'TENUPSSO_GRANT_SUPER_ADMIN' ) && filter_var( TENUPSSO_GRANT_SUPER_ADMIN, FILTER_VALIDATE_BOOLEAN ) ) {
							require_once ABSPATH . 'wp-admin/includes/ms.php';
							grant_super_admin( $user_id );
						}
					}

					$user = get_user_by( 'id', $user_id );
				}
			} else {
				$user_id = $user->ID;
			}

			if ( ! empty( $user_id ) ) {
				add_filter( 'auth_cookie_expiration', [ $this, 'change_cookie_expiration' ], 1000 );
				wp_set_auth_cookie( $user_id );
				remove_filter( 'auth_cookie_expiration', [ $this, 'change_cookie_expiration' ], 1000 );

				$redirect_to           = admin_url();
				$requested_redirect_to = '';

				// We're only checking if the var exists here, so no need to sanitize.
				// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				if ( isset( $_REQUEST['redirect_to'] ) ) {
					$redirect_to           = sanitize_text_field( $_REQUEST['redirect_to'] );
					$requested_redirect_to = sanitize_text_field( $_REQUEST['redirect_to'] );
				}

				$redirect_to = apply_filters( 'login_redirect', $redirect_to, $requested_redirect_to, $user );
				if ( empty( $redirect_to ) ) {
					// If the user doesn't belong to a blog, send them to user admin. If the user can't edit posts, send them to their profile.
					if ( is_multisite() && ! get_active_blog_for_user( $user->ID ) && ! is_super_admin( $user->ID ) ) {
						$redirect_to = user_admin_url();
					} elseif ( is_multisite() && ! $user->has_cap( 'read' ) ) {
						$redirect_to = get_dashboard_url( $user->ID );
					} elseif ( ! $user->has_cap( 'edit_posts' ) ) {
						$redirect_to = admin_url( 'profile.php' );
					} else {
						// Just in case everything else fails, go home...
						$redirect_to = home_url();
					}
				}

				wp_safe_redirect( $redirect_to );
				exit;
			}

			$tenup_login_failed = true;
		} else {
			$redirect_url = wp_login_url();
			if ( isset( $_REQUEST['redirect_to'] ) && is_string( sanitize_text_field( $_REQUEST['redirect_to'] ) ) ) {
				$redirect_url = add_query_arg( 'redirect_to', rawurlencode( sanitize_text_field( $_REQUEST['redirect_to'] ) ), $redirect_url );
			}

			$proxy_url = add_query_arg(
				array(
					'action'      => '10up-login',
					'redirect'    => rawurlencode( $redirect_url ),
					'type'        => filter_input( INPUT_GET, 'type' ),
					'sso_version' => TENUP_EXPERIENCE_VERSION,
				),
				TENUPSSO_PROXY_URL
			);

			wp_redirect( $proxy_url );
			exit;
		}
	}

	/**
	 * Insert login button into login form
	 */
	public function update_login_form() {
		$google_login = add_query_arg( 'action', '10up-login', wp_login_url() );
		if ( isset( $_REQUEST['redirect_to'] ) ) {
			$google_login = add_query_arg( 'redirect_to', rawurlencode( sanitize_text_field( $_REQUEST['redirect_to'] ) ), $google_login );
		}

		$buttons_html = '<div class="sso"><div class="buttons">';

		$buttons_html .= '<a href="' . esc_url( add_query_arg( 'type', '10up', $google_login ) ) . '" class="tenup-button button"><img src="' . TENUP_EXPERIENCE_URL . 'assets/img/planet.png" alt="Fueled logo" width="20" height="20" />' .
		'<span>Sign in with Fueled</span></a>';

		$buttons_html .= '</div><span class="or"><span>or</span></span>';
		$buttons_html .= '</div>';

		?>
		<script type="text/javascript">
			(function() {
				document.getElementById('loginform').insertAdjacentHTML(
					'beforebegin',
					'<?php echo $buttons_html; // phpcs:ignore ?>'
				);
			})();
		</script>
		<?php
	}

	/**
	 * Render login form styles
	 */
	public function render_login_form_styles() {
		?>
		<style>
			.sso {
				font-weight: normal;
				overflow: hidden;

				margin-top: 20px;
				margin-left: 0;
				padding: 26px 24px 26px;
				font-weight: 400;
				overflow: hidden;
				background: #fff;
				border: 1px solid #c3c4c7;
				box-shadow: 0 1px 3px rgb(0 0 0 / 4%);
			}

			.sso .buttons {
				display: flex;
				justify-content: center;
				align-items: center;
			}

			.sso .button {
				float: none;
				display: block;
				font-weight: 600;
				color: #000;
				display: flex;
				align-items: center;
				justify-content: center;
				background-color: #fff;
				padding: 10px;
				border-radius: 20px;
				min-height: initial;
				line-height: 1.2;
				border: 2px solid #6353f6;
			}

			.sso .button img {
				width: 20px;
				height: 20px;
				margin-right: 8px;
			}

			.sso .button:hover {
				background-color: #6353f6;

				border: 2px solid #6353f6;
				color: #fff;
			}

			.sso .button svg {
				height: 30px;
				margin-right: 10px;
			}

			.sso .fueled-button svg {
				height: 28px;
			}

			.sso .fueled-button svg path {
				fill: #950001;
			}

			.sso .sep {
				margin: 0 1em;
				border-right: 1px solid rgba(0,0,0,0.13);
				height: 20px;
			}

			.sso .or {
				margin: .8em 0 2em 0;
				width: 100%;
				display: block;
				border-bottom: 1px solid rgba(0,0,0,0.13);
				text-align: center;
				line-height: 1;
			}

			.sso .or span {
				position: relative;
				top: 0.5em;
				background: white;
				padding: 0 1em;
				color: #72777c;
			}

			#loginform {
				margin-top: 0;
				border-top: 0;
				position: relative;
				top: -17px;
				padding-top: 0;
			}

			<?php if ( defined( 'TENUPSSO_DISALLOW_ALL_DIRECT_LOGIN' ) && TENUPSSO_DISALLOW_ALL_DIRECT_LOGIN ) : ?>
				#loginform,
				#nav,
				.sso .or {
					display: none;
				}
			<?php endif; ?>
		</style>
		<?php
	}

	/**
	 * If a user account was created via SSO, don't let them
	 * login via password.
	 *
	 * @param WP_User $user User object
	 * @return WP_User
	 */
	public function prevent_standard_login_for_sso_user( $user ) {
		if ( ! is_wp_error( $user ) && defined( 'TENUPSSO_DISALLOW_ALL_DIRECT_LOGIN' ) && TENUPSSO_DISALLOW_ALL_DIRECT_LOGIN ) {
			return new WP_Error( 'tenup-sso', esc_html__( 'Username/password authentication is disabled', 'tenup' ) );
		}

		// Check if user was created with SSO. If so, they must use SSO.
		if ( ! is_wp_error( $user ) ) {
			$is_10up_sso = get_user_meta( $user->ID, '10up-sso', true );
			if ( filter_var( $is_10up_sso, FILTER_VALIDATE_BOOLEAN ) ) {
				return new WP_Error( 'tenup-sso', esc_html__( 'This account can only be logged into using Fueled SSO.', 'tenup' ) );
			}
		}

		return $user;
	}

	/**
	 * New cookie expiration time
	 *
	 * @return integer
	 */
	public function change_cookie_expiration() {
		return DAY_IN_SECONDS;
	}

	/**
	 * Add user to blog in multisite if needed
	 */
	public function check_user_blog() {
		if ( ! is_user_logged_in() || is_network_admin() ) {
			return;
		}

		$user_id      = get_current_user_id();
		$has_10up_sso = get_user_meta( $user_id, '10up-sso', true );
		if ( ! filter_var( $has_10up_sso, FILTER_VALIDATE_BOOLEAN ) ) {
			return;
		}

		$current_blog = get_current_blog_id();
		$blogs        = get_blogs_of_user( $user_id );
		$user_blogs   = wp_list_filter( $blogs, array( 'userblog_id' => $current_blog ) );
		if ( ! empty( $user_blogs ) ) {
			return;
		}

		if ( is_multisite() ) {
			$default_role = defined( 'TENUPSSO_DEFAULT_ROLE' )
					? TENUPSSO_DEFAULT_ROLE
					: 'subscriber';

			add_user_to_blog( $current_blog, $user_id, $default_role );
			wp_cache_delete( $user_id, 'user_meta' );
		}
	}
}
