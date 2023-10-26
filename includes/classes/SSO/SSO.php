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

		if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'siteoptions' ) ) {
			return;
		}

		if ( ! isset( $_POST['tenup_allow_sso'] ) ) {
			return;
		}

		$setting = $this->validate_sso_setting( $_POST['tenup_allow_sso'] );

		update_site_option( 'tenup_allow_sso', $setting );
	}

	/**
	 * Output multisite settings
	 */
	public function ms_settings() {
		$setting = $this->get_setting();
		?>
		<h2><?php esc_html_e( '10up SSO', 'tenup' ); ?></h2>
		<p><?php esc_html_e( 'This allows members of 10up on your project team to log in via SSO. This is extremely important to streamline maintenance of your website.', '10up' ); ?></p>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><?php esc_html_e( 'Allow 10up SSO', 'tenup' ); ?></th>
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
		add_settings_field( 'tenup_allow_sso', esc_html__( 'Allow 10up SSO', 'tenup' ), [ $this, 'sso_setting_field_output' ], 'general' );
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
		<p class="description"><?php esc_html_e( 'This allows members of 10up on your project team to log in via SSO. This is extremely important to streamline maintenance of your website.', '10up' ); ?></p>
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

				if ( isset( $_REQUEST['redirect_to'] ) ) {
					$redirect_to           = $_REQUEST['redirect_to'];
					$requested_redirect_to = $_REQUEST['redirect_to'];
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
			if ( isset( $_REQUEST['redirect_to'] ) && is_string( $_REQUEST['redirect_to'] ) ) {
				$redirect_url = add_query_arg( 'redirect_to', rawurlencode( $_REQUEST['redirect_to'] ), $redirect_url );
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
			$google_login = add_query_arg( 'redirect_to', rawurlencode( $_REQUEST['redirect_to'] ), $google_login );
		}

		$buttons_html = '<div class="sso"><div class="buttons">';

		$buttons_html .= '<a href="' . esc_url( add_query_arg( 'type', '10up', $google_login ) ) . '" class="tenup-button button"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 235.84 269.94"><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path d="M60.93,4.5,0,34.5,12.08,48.92V186.13l48.85-48.87Z" fill="#df2b26"></path><path d="M117.5,215.8c0,7.53-5.09,10.75-10.75,10.75-7.51,0-9.26-4.57-9.26-9.54V173.88h-.32L73,198v24.64c0,13.57,7.26,25.52,24,25.52A30.65,30.65,0,0,0,117.5,240v6.58H142V173.88H117.5Zm84.25-43.4a28.58,28.58,0,0,0-20.69,8.33v-6.85H156.48v96.06h24.58V240a29.6,29.6,0,0,0,20.69,8.19c20.29,0,32.93-16.25,32.93-37.88,0-21.36-12.64-37.89-32.93-37.89Zm-6.58,54.82c-9.4,0-14.11-7.8-14.11-17.06s4.58-16.93,14.11-16.93c9.28,0,13.57,7.78,13.57,16.93C208.74,219.16,204.45,227.22,195.17,227.22Z" fill="#000"></path><path d="M157.09,0A78.6,78.6,0,0,0,85.93,112.26l.82.86L135.4,64.47,120.1,49.18h66.56v66.56l-15.3-15.3-48.92,48.92A78.71,78.71,0,1,0,157.09,0Z" fill="#df2b26"></path></g></g></svg>' .
		'<span>Login</span></a>';

		if ( true ) {
			$buttons_html .= '<span class="sep">|</span><a href="' . esc_url( add_query_arg( 'type', 'fueled', $google_login ) ) . '" class="fueled-button button"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="30" viewBox="0 0 25 30" class="m-logo__svg"><path fill-rule="evenodd" d="M21 7v1h2v2l-1 1v3l1 1v9h-2V14l-1-1h-2V3l-3-3H5L2 3v25H0v2h20v-2h-2V14h2v10l1 1h2l1-1v-9l1-1V9l-2-2h-2zm-5 6H4V3l1-1h10l1 1v10z"></path></svg>' .
			'<span>Login</span></a>';
		}

		$buttons_html .= '</div><span class="or"><span>or</span></span>';
		$buttons_html .= '</div>';

		?><script type="text/javascript">
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
				border: 0;
				color: #50575e;
				display: flex;
				align-items: center;
				justify-content: center;
				background-color: transparent;
				padding: 10px;
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
				color: rgba(0,0,0,0.13);
			}

			.sso .or {
				margin: 1em 0 2em 0;
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
		if ( defined( 'TENUPSSO_DISALLOW_ALL_DIRECT_LOGIN' ) && TENUPSSO_DISALLOW_ALL_DIRECT_LOGIN ) {
			return new WP_Error( 'tenup-sso', esc_html__( 'Username/password authentication is disabled', 'tenup' ) );
		}

		// Check if user was created with SSO. If so, they must use SSO.
		if ( ! is_wp_error( $user ) ) {
			$is_10up_sso = get_user_meta( $user->ID, '10up-sso', true );
			if ( filter_var( $is_10up_sso, FILTER_VALIDATE_BOOLEAN ) ) {
				return new WP_Error( 'tenup-sso', esc_html__( 'This account can only be logged into using 10up SSO.', 'tenup' ) );
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
