<?php
/**
 * Welcome notification
 *
 * @package  10up-experience
 */

namespace TenUpExperience\Notifications;

use TenUpExperience\Singleton;

/**
 * Welcome notification class
 */
class Welcome {

	use Singleton;

	/**
	 * Setup module
	 *
	 * @since 1.7
	 */
	public function setup() {

		if ( ! TENUP_EXPERIENCE_IS_NETWORK ) {
			add_action( 'admin_notices', [ $this, 'notice' ] );
		} else {
			add_action( 'network_admin_notices', [ $this, 'notice' ] );
		}
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_ajax_tenup_dismiss_welcome', [ $this, 'ajax_dismiss' ] );
	}

	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( '10up-notices', plugins_url( '/dist/js/notices.js', TENUP_EXPERIENCE_FILE ), [ 'jquery' ], TENUP_EXPERIENCE_VERSION, true );

		wp_localize_script(
			'10up-notices',
			'tenupWelcome',
			[
				'nonce' => wp_create_nonce( 'tenup_welcome_dismiss' ),
			]
		);
	}

	/**
	 * Dismiss welcome message
	 */
	public function ajax_dismiss() {
		if ( ! check_ajax_referer( 'tenup_welcome_dismiss', 'nonce', false ) ) {
			wp_send_json_error();
			exit;
		}

		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			update_site_option( 'tenup_welcome_dismiss', true, false );
		} else {
			update_option( 'tenup_welcome_dismiss', true, false );
		}

		wp_send_json_success();
	}

	/**
	 * Output dismissable admin welcome notice
	 *
	 * @return void
	 */
	public function notice() {
		$dismissed = TENUP_EXPERIENCE_IS_NETWORK ? get_site_option( 'tenup_welcome_dismiss', false ) : get_option( 'tenup_welcome_dismiss', false );
		if ( ! empty( $dismissed ) ) {
			return;
		}

		?>
		<div class="notice notice-info notice-10up-experience-welcome is-dismissible">
			<p>
				<?php esc_html_e( 'Thank you for installing the Fueled Experience plugin.', 'tenup' ); ?>
			</p>

			<p>
				<?php echo wp_kses_post( __( '<strong>This plugin changes some WordPress default functionality</strong> e.g. requiring authentication for the REST API users endpoint. Make sure to look at the <a href="https://github.com/10up/10up-experience">readme</a> to understand all the changes it makes.', 'tenup' ) ); ?>
			</p>
		</div>
		<?php
	}
}
