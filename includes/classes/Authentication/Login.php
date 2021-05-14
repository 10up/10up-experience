<?php
/**
 * Enforce password policy on login
 *
 * @package  10up-experience
 */

namespace TenUpExperience\Authentication;

use TenUpExperience\Singleton;

/**
 * Login screen password policy enforcement functionality
 */
class Login extends Singleton {

	/**
	 * Max login attempts
	 */
	const MAX_ATTEMPTS = 5;

	/**
	 * Option key for login blacklist
	 */
	const BLACKLIST_KEY = 'tenup_login_blacklist';

	/**
	 * If the user is attempting to reset their password.
	 *
	 * @var boolean
	 */
	private $is_resetpass = false;

	/**
	 * Class constructor.
	 */
	public function __construct() {
		$action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );

		$this->is_resetpass = 'resetpass' === $action;
	}

	/**
	 * Setup hooks and filters
	 *
	 * @return void
	 */
	public function setup() {
		add_action( 'wp_login_failed', array( $this, 'log_attempt' ) );
		add_action( 'init', array( $this, 'cron_clean_blacklist' ) );
		add_action( 'clean_ip_blacklist', array( $this, 'clean_blacklist' ) );

		add_filter( 'authenticate', array( $this, 'authenticate' ), PHP_INT_MAX );
		add_filter( 'login_errors', array( $this, 'login_errors' ) );
		add_filter( 'shake_error_codes', array( $this, 'shake_error_codes' ) );

	}

	/**
	 * Log failed attempt.
	 *
	 * @return void
	 */
	public function log_attempt() {
		$this->increment_attempts();

		if ( $this->attempts_remaining() <= 0 ) {
			$this->add_to_blacklist();
		}
	}

	/**
	 * Filter login errors.
	 *
	 * @param  string $error_message Error message.
	 * @return string
	 */
	public function login_errors( $error_message ) {
		if ( ! $this->is_blacklisted() && ! $this->is_resetpass ) {
			/* translators: %d is the number of remaining attempts. */
			$error_message .= '<br/><strong>' . sprintf( __( '%d Login Attempts Remaining', 'tenup' ), $this->attempts_remaining() ) . '</strong>';
		}

		if ( 0 === $this->attempts_remaining() ) {
			$error_message .= $this->blacklisted_message();
		}

		return $error_message;
	}

	/**
	 * Disallow authentication if user IP is blacklisted.
	 *
	 * @param object $user User object.
	 * @return object|WP_Error
	 */
	public function authenticate( $user ) {
		$error = new \WP_Error();

		if ( $this->is_blacklisted() ) {
			$error->add( 'max_attempts_reached', $this->blacklisted_message() );
			return $error;
		}

		if ( ! is_wp_error( $user ) && $user instanceof \WP_User ) {
			$this->clear_attempts();
			$this->remove_from_blacklist();
		}

		return $user;
	}

	/**
	 * Add shake error code when max attempts are reached.
	 *
	 * @param array $codes Error codes.
	 * @return array
	 */
	public function shake_error_codes( $codes ) {
		$codes[] = 'max_attempts_reached';

		return $codes;
	}

	/**
	 * Cron task to clean blacklist daily.
	 *
	 * @return void
	 */
	public function cron_clean_blacklist() {
		if ( ! wp_next_scheduled( 'clean_ip_blacklist' ) ) {
			wp_schedule_event( time(), 'daily', 'clean_ip_blacklist' );
		}
	}

	/**
	 * Get request IP address and anonymize for storage.
	 *
	 * @return string
	 */
	private function get_ip() {
		return md5( $_SERVER['REMOTE_ADDR'] );
	}

	/**
	 * Get transient key.
	 *
	 * @return string
	 */
	private function get_key() {
		return 'failed_login' . $this->get_ip();
	}

	/**
	 * Get the number of attempts an IP has made to login.
	 *
	 * @return int
	 */
	private function get_attempts() {
		return ( false === get_transient( $this->get_key() ) ) ? 0 : get_transient( $this->get_key() );
	}

	/**
	 * Get the number of remaining attempts.
	 *
	 * @return int
	 */
	private function attempts_remaining() {
		$max_attempts = (int) apply_filters( 'tenup_max_login_attemps', self::MAX_ATTEMPTS );
		return ( $max_attempts - $this->get_attempts() );
	}

	/**
	 * Increment attempts for IP.
	 *
	 * @return bool
	 */
	private function increment_attempts() {
		$attempts = $this->get_attempts() + 1;
		return set_transient( $this->get_key(), $attempts, DAY_IN_SECONDS );
	}

	/**
	 * Clear attempts for IP.
	 *
	 * @return bool
	 */
	private function clear_attempts() {
		return delete_transient( $this->get_key() );
	}

	/**
	 * Get blacklisted IP.
	 *
	 * @return array
	 */
	private function get_blacklist() {
		return ( false === get_transient( self::BLACKLIST_KEY ) ) ? [] : get_transient( self::BLACKLIST_KEY );
	}

	/**
	 * Add IP to blacklist.
	 *
	 * @return bool
	 */
	private function add_to_blacklist() {
		$blacklist                    = $this->get_blacklist();
		$blacklist[ $this->get_ip() ] = time() + ( 24 * HOUR_IN_SECONDS );

		return set_transient( self::BLACKLIST_KEY, $blacklist );
	}

	/**
	 * Remove IPs that have expired from blacklist.
	 *
	 * @return bool
	 */
	public function clean_blacklist() {
		$blacklist = $this->get_blacklist();
		$clean     = array_filter(
			$blacklist,
			function( $expiration ) {
				return $expiration > time();
			}
		);

		return set_transient( self::BLACKLIST_KEY, $clean );
	}

	/**
	 * Remove IP from blacklist
	 *
	 * @return bool
	 */
	private function remove_from_blacklist() {
		$blacklist = $this->get_blacklist();

		unset( $blacklist[ $this->get_ip() ] );

		return set_transient( self::BLACKLIST_KEY, $blacklist );
	}

	/**
	 * Check if IP is blacklisted.
	 *
	 * @return bool
	 */
	private function is_blacklisted() {
		$blacklisted = array_key_exists( $this->get_ip(), $this->get_blacklist() );

		if ( $blacklisted ) {
			$expiration = $this->get_blacklist()[ $this->get_ip() ];

			if ( time() > $expiration ) {
				$this->remove_from_blacklist();
				return false;
			}
			return true;
		}
		return false;
	}

	/**
	 * Get blacklisted message.
	 *
	 * @return string
	 */
	private function blacklisted_message() {
		$output  = '<strong>' . __( 'There were too many failed login attempts.', 'tenup' ) . '</strong><br/>';
		$output .= __( 'Try again ', 'tenup' );

		if ( $this->is_blacklisted() ) {
			$expiration = $this->get_blacklist()[ $this->get_ip() ];
			$remaining  = $expiration - time();
			$hours      = floor( $remaining / 3600 );
			$minutes    = floor( ( $remaining / 60 ) % 60 );
			/* translators: %d is the number of hours remaining until IP can retry logging in */
			$htext = sprintf( _n( '%d hour', 'in %d hours', absint( $hours ), 'tenup' ), absint( $hours ) );
			/* translators: %d is the number of minutes remaining until IP can retry logging in */
			$mtext = sprintf( _n( '%d minute', '%d minutes', absint( $minutes ), 'tenup' ), absint( $minutes ) );

			if ( $hours || $minutes ) {
				$output .= absint( $hours ) ? $htext . __( ' and ', 'tenup' ) : __( 'in ', 'tenup' );
				$output .= $mtext . '.';
			} else {
				$output .= __( ' later.', 'tenup' );
			}
		}

		return $output;
	}
}
