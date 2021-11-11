<?php
/**
 * Database Queries Monitor. A submodule of Support Monitor to report heavy SQL queries executed on staging.
 *
 * This feature is turned off in production environments but can be enabled
 * using `add_filter( 'tenup_experience_enable_query_monitor', '__return_true' );`
 *
 * The original purpose of this feature is to log any heavy SQL query performed, for example,
 * during a plugins upgrade. These are the queries logged:
 * - All create, alter, truncate, drop queries
 * - Insert, delete, update, and replace queries without a nested select and bigger than 20000 chars
 * - Transients and options operations are ignored by default
 *
 * Additional checks can be created using the `tenup_experience_log_query` filter.
 *
 * @since  x.x
 * @package 10up-experience
 */

namespace TenUpExperience\SupportMonitor;

/**
 * DBQueryMonitor class
 */
class DBQueryMonitor {

	/**
	 * The transient name
	 *
	 * This transient stores all the queries logged.
	 */
	const TRANSIENT_NAME = 'tenup_experience_db_queries';

	/**
	 * Minimum size of insert, delete, update, and replace queries to be logged.
	 * Queries with a nested select in it will always be logged.
	 *
	 * CUD instead of CRUD because select/(R)ead queries are not logged.
	 */
	const CUD_QUERY_SIZE = 20000;

	/**
	 * Setup module
	 */
	public function setup() {
		$production_environment = Monitor::instance()->get_setting( 'production_environment' );

		/**
		 * Filter if the Query Monitor should be enabled. Defaults to true on non-production environments.
		 *
		 * Having it as true does not mean all queries will be logged, as they will be checked as being
		 * heavy or not first.
		 *
		 * @since  x.x
		 * @hook tenup_experience_enable_query_monitor
		 * @param  {bool} $should_log Whether Query Monitor should be enabled.
		 * @return {bool} New value
		 */
		if ( ! apply_filters( 'tenup_experience_enable_query_monitor', 'no' === $production_environment ) ) {
			return;
		}

		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			add_action( 'network_admin_menu', [ $this, 'register_network_menu' ] );
		} else {
			add_action( 'admin_menu', [ $this, 'register_menu' ] );
		}

		add_action( 'admin_init', [ $this, 'empty_queries' ] );

		add_filter( 'query', [ $this, 'maybe_log_query' ] );
	}

	/**
	 * Registers the Query Monitor link under the 'Tools' menu
	 *
	 * @since x.x
	 */
	public function register_menu() {
		add_submenu_page(
			'tools.php',
			esc_html__( '10up Query Monitor', 'tenup' ),
			esc_html__( '10up Query Monitor', 'tenup' ),
			'manage_options',
			'tenup_query_monitor',
			[ $this, 'queries_list_screen' ]
		);
	}

	/**
	 * Registers the Query Monitor link under the network settings
	 *
	 * @since x.x
	 */
	public function register_network_menu() {
		add_submenu_page(
			'settings.php',
			esc_html__( '10up Query Monitor', 'tenup' ),
			esc_html__( '10up Query Monitor', 'tenup' ),
			'manage_network_options',
			'tenup_query_monitor',
			[ $this, 'queries_list_screen' ]
		);
	}

	/**
	 * Output the queries screen
	 *
	 * @since x.x
	 */
	public function queries_list_screen() {
		$queries_per_date = $this->get_transient();
		?>

		<div class="wrap">
			<h2><?php esc_html_e( 'Query Monitor', 'tenup' ); ?></h2>

			<p>
				<a href="<?php echo esc_url( add_query_arg( 'tenup_query_monitor_nonce', wp_create_nonce( 'tenup_qm_empty_action' ) ) ); ?>" class="button"><?php esc_html_e( 'Empty Queries', 'tenup' ); ?></a>
			</p>

			<?php if ( ! empty( $queries_per_date ) ) : ?>
				<?php foreach ( $queries_per_date as $date => $queries ) : ?>
					<h3><?php echo esc_html( date_i18n( 'F j, Y', strtotime( $date ) ) ); ?></h3>
					<?php foreach ( $queries as $query ) : ?>
						<div>
							<strong><?php esc_html_e( 'Query:', 'tenup' ); ?></strong> <code><?php echo esc_html( $query['query'] ); ?></code><br>
							<strong><?php esc_html_e( 'File:', 'tenup' ); ?></strong> <?php echo esc_html( $query['file'] ); ?><br>
							<strong><?php esc_html_e( 'Line:', 'tenup' ); ?></strong> <?php echo esc_html( $query['line'] ); ?><br>
							<strong><?php esc_html_e( 'Count:', 'tenup' ); ?></strong> <?php echo esc_html( $query['count'] ); ?><br><br>
						</div>
					<?php endforeach; ?>
				<?php endforeach; ?>
			<?php else : ?>
				<p><?php esc_html_e( 'No queries.', 'tenup' ); ?></p>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Empty queries
	 *
	 * @since x.x
	 */
	public function empty_queries() {
		if ( empty( $_GET['tenup_query_monitor_nonce'] ) || ! wp_verify_nonce( $_GET['tenup_query_monitor_nonce'], 'tenup_qm_empty_action' ) ) {
			return;
		}

		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			delete_site_transient( self::TRANSIENT_NAME );

			wp_safe_redirect( network_admin_url( 'settings.php?page=tenup_query_monitor' ) );
		} else {
			delete_transient( self::TRANSIENT_NAME );

			wp_safe_redirect( admin_url( 'tools.php?page=tenup_query_monitor' ) );
		}
	}

	/**
	 * Conditionally log a query
	 *
	 * Get all potential heavy queries (CREATE, ALTER, etc.) and store it,
	 * ignoring transients and options by default.
	 *
	 * @param string $query The SQL query.
	 * @return string
	 */
	public function maybe_log_query( $query ) {
		global $wpdb;

		if ( ! preg_match( '/^\s*(create|alter|truncate|drop|insert|delete|update|replace)\s/i', $query ) ) {
			return $query;
		}

		if ( false !== strpos( $query, 'transient_' ) ) {
			return $query;
		}

		if ( false !== strpos( $query, $wpdb->options ) ) {
			return $query;
		}

		// For INSERT, DELETE, UPDATE, and REPLACE queries, only log nested SELECTs or big queries.
		if ( preg_match( '/^\s*(insert|delete|update|replace)\s/i', $query ) &&
			false === strpos( $query, 'select' ) &&
			strlen( $query ) < self::CUD_QUERY_SIZE ) {
			return $query;
		}

		/**
		 * Filter if a specific SQL query should be logged. Defaults to true.
		 *
		 * If code reached this filter, it means the query already passed the plugin default checks.
		 *
		 * @since  x.x
		 * @hook tenup_experience_log_query
		 * @param  {bool} $should_log Whether the query should be logged or not.
		 * @param  {string} $query The SQL query.
		 * @return {bool} New value of $should_log
		 */
		if ( apply_filters( 'tenup_experience_log_query', true, $query ) ) {
			$this->log_query( $query );
		}

		return $query;
	}

	/**
	 * Generate the response for the Support Monitor.
	 *
	 * Also, as this is called periodically, removes from the transient all old queries.
	 *
	 * @see cleanup_queries()
	 *
	 * @return bool Whether queries were logged recently or not.
	 */
	public function get_report() {
		$this->cleanup_queries();

		$queries_per_date = $this->get_transient();

		return ( ! empty( $queries_per_date ) );
	}

	/**
	 * Remove old queries from the transient.
	 *
	 * @return void
	 */
	protected function cleanup_queries() {
		$date_limit = strtotime( '7 days ago' );

		$stored_queries = array_filter(
			$this->get_transient(),
			function ( $query_date ) use ( $date_limit ) {
				return strtotime( $query_date ) > $date_limit;
			},
			ARRAY_FILTER_USE_KEY
		);

		$this->set_transient( $stored_queries );
	}

	/**
	 * Log/store a SQL query
	 *
	 * Queries are stored like:
	 *
	 *    'YYYY-MM-DD' => [
	 *        'a5be998feee8968155052c4d332a7223' => [ // md5 of file:line:query
	 *            'query' => 'ALTER TABLE wp_my_db_heavy_plugin CHANGE COLUMN `id` id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT',
	 *            'file' => '/var/www/html/wp-content/plugins/my-db-heavy-plugin/my-db-heavy-plugin.php',
	 *            'line' => 53,
	 *            'count' => 3, // how many times the same query was sent in a day
	 *        ]
	 *    ]
	 *
	 * @param string $query The SQL query.
	 */
	protected function log_query( $query ) {
		static $stored_queries;
		if ( empty( $stored_queries ) ) {
			$stored_queries = get_transient( self::TRANSIENT_NAME ) ?? [];
		}

		$current_date = date_i18n( 'Y-m-d' );

		if ( ! isset( $stored_queries[ $current_date ] ) ) {
			$stored_queries[ $current_date ] = [];
		}

		$main_caller = $this->find_main_caller();

		$key = md5(
			$main_caller['file'] . ':' .
			$main_caller['line'] . ':' .
			$query
		);

		if ( isset( $stored_queries[ $current_date ][ $key ] ) ) {
			$stored_queries[ $current_date ][ $key ]['count']++;
		} else {
			$stored_queries[ $current_date ][ $key ] = [
				'query' => $query,
				'file'  => $main_caller['file'],
				'line'  => $main_caller['line'],
				'count' => 1,
			];
		}

		$this->set_transient( $stored_queries );
	}

	/**
	 * Based on the debug backtrace, try to find the main caller, i.e., the plugin/theme
	 * that fired the query.
	 *
	 * Simple SQL queries generally come from wp-db.php. dbDelta calls come from upgrade.php.
	 * We are usually interested in the caller immediately before those.
	 *
	 * @return array
	 */
	protected function find_main_caller() {
		$debug_backtrace = debug_backtrace(); // phpcs:ignore

		// Remove this plugin references of the backtrace.
		array_shift( $debug_backtrace );
		array_shift( $debug_backtrace );

		$main_caller = null;

		$wp_db_found = false;
		foreach ( $debug_backtrace as $caller ) {
			$is_wp_db_file = ( false !== strpos( $caller['file'], 'wp-db.php' ) || false !== strpos( $caller['file'], 'upgrade.php' ) );
			if ( $is_wp_db_file ) {
				$wp_db_found = true;
			}
			if ( ! $wp_db_found || $is_wp_db_file ) {
				continue;
			}
			$main_caller = $caller;
			break;
		}

		// If a caller was not found simply get the first item of the backtrace.
		if ( ! $main_caller ) {
			$main_caller = array_shift( $debug_backtrace );
		}

		return $main_caller;
	}

	/**
	 * Get the transient value.
	 *
	 * @return array
	 */
	protected function get_transient() {
		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			$transient = get_site_transient( self::TRANSIENT_NAME );
		} else {
			$transient = get_transient( self::TRANSIENT_NAME );
		}

		if ( ! is_array( $transient ) ) {
			$transient = [];
		}

		return $transient;
	}

	/**
	 * Set the transient value.
	 *
	 * @param array $queries Queries to be stored.
	 */
	protected function set_transient( $queries ) {
		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			set_site_transient( self::TRANSIENT_NAME, $queries );
		} else {
			set_transient( self::TRANSIENT_NAME, $queries );
		}
	}
}
