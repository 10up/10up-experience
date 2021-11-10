<?php
/**
 * Database Queries Monitor. A submodule of Support Monitor to report heavy SQL queries executed on staging.
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
	 * Setup module
	 */
	public function setup() {
		$production_environment = Monitor::instance()->get_setting( 'production_environment' );
		if ( 'no' === $production_environment || apply_filters( 'tenup_experience_log_heavy_queries', false ) ) {
			add_filter( 'query', [ $this, 'maybe_log_query' ] );
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

		if ( apply_filters( 'tenup_experience_log_query', true, $query ) ) {
			$this->log_query( $query );
		}

		return $query;
	}

	/**
	 * Get the logged queries. Also removes from the transient all queries logged for more than 7 days.
	 *
	 * @return array
	 */
	public function get_report() {
		$date_limit = strtotime( '7 days ago' );

		$stored_queries = array_filter(
			(array) get_transient( self::TRANSIENT_NAME ),
			function ( $query_date ) use ( $date_limit ) {
				return strtotime( $query_date ) > $date_limit;
			},
			ARRAY_FILTER_USE_KEY
		);

		set_transient( self::TRANSIENT_NAME, $stored_queries );

		return $stored_queries;
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
	 *            'count' => 1,
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
				'query' => stripslashes( $query ),
				'file'  => $main_caller['file'],
				'line'  => $main_caller['line'],
				'count' => 1,
			];
		}

		set_transient( self::TRANSIENT_NAME, $stored_queries );
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
}
