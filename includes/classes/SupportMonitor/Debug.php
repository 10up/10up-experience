<?php
/**
 * 10up monitor debugger. This can be enabled by setting the following in wp-config.php:
 * define( 'SUPPORT_MONITOR_DEBUG', true );
 *
 * @since  1.7
 * @package 10up-experience
 */

namespace TenUpExperience\SupportMonitor;

use TenUpExperience\Singleton;

/**
 * Gutenberg class
 */
class Debug {

	use Singleton;

	/**
	 * Setup module
	 *
	 * @since 1.7
	 */
	public function setup() {

		// No need to run setup functions if debug is disabled
		if ( ! $this->is_debug_enabled() ) {
			return;
		}

		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			add_action( 'network_admin_menu', [ $this, 'register_network_menu' ] );
		} else {
			add_action( 'admin_menu', [ $this, 'register_menu' ] );
		}

		add_action( 'admin_init', [ $this, 'empty_log' ] );
		add_action( 'admin_init', [ $this, 'test_message' ] );
	}


	/**
	 * Regisers the Monitor log link under the 'Tools' menu
	 *
	 * @since 1.7
	 */
	public function register_menu() {

		add_submenu_page(
			'tools.php',
			esc_html__( 'Monitor Debug', 'tenup' ),
			esc_html__( 'Monitor Debug', 'tenup' ),
			'manage_options',
			'tenup_support_monitor',
			[ $this, 'debug_screen' ]
		);
	}

	/**
	 * Regisers the Monitor log link under the network settings
	 *
	 * @since 1.7
	 */
	public function register_network_menu() {

		add_submenu_page(
			'settings.php',
			esc_html__( 'Monitor Debug', 'tenup' ),
			esc_html__( 'Monitor Debug', 'tenup' ),
			'manage_network_options',
			'tenup_support_monitor',
			[ $this, 'debug_screen' ]
		);
	}

	/**
	 * Empty message log
	 *
	 * @since 1.7
	 */
	public function empty_log() {
		// We're only checking if the nonce exists here, so no need to sanitize.
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		if ( empty( $_GET['tenup_support_monitor_nonce'] ) || ! wp_verify_nonce( $_GET['tenup_support_monitor_nonce'], 'tenup_sm_empty_action' ) ) {
			return;
		}

		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			delete_site_option( 'tenup_support_monitor_log' );

			wp_safe_redirect( network_admin_url( 'settings.php?page=tenup_support_monitor' ) );
			exit;
		} else {
			delete_option( 'tenup_support_monitor_log' );

			wp_safe_redirect( admin_url( 'tools.php?page=tenup_support_monitor' ) );
			exit;
		}
	}

	/**
	 * Send test message
	 *
	 * @since 1.7
	 */
	public function test_message() {
		// We're only checking if the nonce exists here, so no need to sanitize.
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		if ( empty( $_GET['tenup_support_monitor_nonce'] ) || ! wp_verify_nonce( $_GET['tenup_support_monitor_nonce'], 'tenup_sm_test_message_action' ) ) {
			return;
		}

		Monitor::instance()->send_daily_report();

		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			wp_safe_redirect( network_admin_url( 'settings.php?page=tenup_support_monitor' ) );
			exit;
		} else {
			wp_safe_redirect( admin_url( 'tools.php?page=tenup_support_monitor' ) );
			exit;
		}
	}

	/**
	 * Output debug screen
	 *
	 * @since 1.7
	 */
	public function debug_screen() {
		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			$log = get_site_option( 'tenup_support_monitor_log' );
		} else {
			$log = get_option( 'tenup_support_monitor_log' );
		}
		?>

		<div class="wrap">
			<h2><?php esc_html_e( 'Monitor Message Log', 'tenup' ); ?></h2>

			<p>
				<a href="<?php echo esc_url( add_query_arg( 'tenup_support_monitor_nonce', wp_create_nonce( 'tenup_sm_empty_action' ) ) ); ?>" class="button"><?php esc_html_e( 'Empty Log', 'tenup' ); ?></a>
				<a href="<?php echo esc_url( add_query_arg( 'tenup_support_monitor_nonce', wp_create_nonce( 'tenup_sm_test_message_action' ) ) ); ?>" class="button"><?php esc_html_e( 'Send Test Message', 'tenup' ); ?></a>
			</p>

			<?php if ( ! empty( $log ) ) : ?>
				<?php foreach ( $log as $message ) : ?>
					<div>
						<strong><?php echo esc_html( gmdate( 'F j, Y, g:i a', $message['time'] ) ); ?>:</strong><br>
						<strong><?php esc_html_e( 'API URL:', 'tenup' ); ?></strong> <?php echo esc_html( $message['url'] ); ?><br>
						<strong><?php esc_html_e( 'Response Code:', 'tenup' ); ?></strong> <?php echo esc_html( $message['message_response'] ); ?><br>
						<pre><?php echo esc_html( wp_json_encode( $message['message'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) ); ?></pre>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				<p><?php esc_html_e( 'No messages.', 'tenup' ); ?></p>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Logs an entry if the monitor debugger has been enabled
	 *
	 * @param string $url - Full URL message was sent to
	 * @param array  $message - Single message
	 * @param array  $response_code - Response code
	 * @since 1.7
	 * @return void
	 */
	public function maybe_add_log_entry( $url, $message, $response_code ) {

		if ( ! $this->is_debug_enabled() ) {
			return;
		}

		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			$log = get_site_option( 'tenup_support_monitor_log', [] );
		} else {
			$log = get_option( 'tenup_support_monitor_log', [] );
		}

		$prepared = [
			'message'          => $message,
			'time'             => time(),
			'message_response' => $response_code,
			'url'              => $url,
		];

		array_unshift( $log, $prepared );

		// If mb_strlen is available, check the size of the log and ensure we keep it under 1mb.
		if ( function_exists( 'mb_strlen' ) ) {
			// 1mb in bytes
			$max_size = apply_filters( 'tenup_support_monitor_max_debug_log_size', 1048576 );

			// If the log is larger than 1mb, remove the oldest entry
			while ( mb_strlen( serialize( (array) $log ), '8bit' ) > $max_size ) { // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.serialize_serialize
				array_pop( $log );
			}
		}

		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			update_site_option( 'tenup_support_monitor_log', $log );
		} else {
			update_option( 'tenup_support_monitor_log', $log, false );
		}
	}

	/**
	 * Determines whether the debugger has been enabled
	 *
	 * @since 1.7
	 * @return boolean - true if defined and set, false if disabled
	 */
	public function is_debug_enabled() {
		return ( defined( 'SUPPORT_MONITOR_DEBUG' ) && SUPPORT_MONITOR_DEBUG );
	}
}
