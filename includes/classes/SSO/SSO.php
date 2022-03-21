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
class SSO extends Singleton {

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
					'email'       => urlencode( $email ),
					'sso_version' => TENUP_EXPERIENCE_VERSION,
					'nonce'       => urlencode( filter_input( INPUT_GET, 'nonce' ) ),
				),
				TENUPSSO_PROXY_URL
			);

			$response = wp_remote_get( $verify );
			if ( wp_remote_retrieve_response_code( $response ) !== 200 ) {
				wp_redirect( wp_login_url() );
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

				$user_id = wp_insert_user(
					array(
						'user_login'   => current( explode( '@', $email ) ),
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
			if ( isset( $_REQUEST['redirect_to'] ) ) {
				$redirect_url = add_query_arg( 'redirect_to', urlencode( $_REQUEST['redirect_to'] ), $redirect_url );
			}

			$proxy_url = add_query_arg(
				array(
					'action'      => '10up-login',
					'redirect'    => urlencode( $redirect_url ),
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
			$google_login = add_query_arg( 'redirect_to', urlencode( $_REQUEST['redirect_to'] ), $google_login );
		}

		?><script type="text/javascript">
			(function() {
				document.getElementById('loginform').insertAdjacentHTML(
					'beforebegin',
					'<div id="tenup_sso" class="tenup-sso">' +
						'<a href="<?php echo esc_url( $google_login ); ?>" class="button button-hero button-primary">' +
							'<?php esc_html_e( 'Login with 10up account', 'tenup' ); ?>' +
						'</a>' +
						'<span class="tenup-sso-or"><span><?php echo esc_html_e( 'or', 'tenup' ); ?></span></span>' +
					'</div>'
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
			.tenup-sso {
				font-weight: normal;
				overflow: hidden;
				text-align: center;

				margin-top: 20px;
				margin-left: 0;
				padding: 26px 24px 26px;
				font-weight: 400;
				overflow: hidden;
				background: #fff;
				border: 1px solid #c3c4c7;
				box-shadow: 0 1px 3px rgb(0 0 0 / 4%);

			}

			#loginform {
				margin-top: 0;
				border-top: 0;
				position: relative;
				top: -17px;
				padding-top: 0;
			}

			.tenup-sso .button-primary {
				float: none;
				text-transform: capitalize;
			}

			.tenup-sso-or {
				margin: 2em 0;
				width: 100%;
				display: block;
				border-bottom: 1px solid rgba(0,0,0,0.13);
				text-align: center;
				line-height: 1;
			}

			.tenup-sso-or span {
				position: relative;
				top: 0.5em;
				background: white;
				padding: 0 1em;
				color: #72777c;
			}

			<?php if ( defined( 'TENUPSSO_DISALLOW_ALL_DIRECT_LOGIN' ) && TENUPSSO_DISALLOW_ALL_DIRECT_LOGIN ) : ?>
				#loginform,
				#nav,
				.tenup-sso-or {
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
