<?php
/**
 * Username functionality
 *
 * @package  10up-experience
 */

namespace TenUpExperience\Authentication;

use TenUpExperience\Singleton;
/**
 * Username extension functionality
 */
class Usernames extends Singleton {

	/**
	 * Setup hooks
	 *
	 * @since 1.7
	 */
	public function setup() {
		add_filter( 'authenticate', [ $this, 'prevent_common_username' ], 30, 3 );
	}

	/**
	 * Prevent users from authenticating if they are using a generic username
	 *
	 * @param WP_User $user User object
	 * @param string  $username Username
	 *
	 * @return \WP_User|\WP_Error
	 */
	public function prevent_common_username( $user, $username ) {
		$test_tlds = array( 'test', 'local', '' );
		$tld       = preg_replace( '#^.*\.(.*)$#', '$1', wp_parse_url( site_url(), PHP_URL_HOST ) );

		if ( in_array( ! $tld, $test_tlds, true ) && in_array( strtolower( trim( $username ) ), $this->reserved_usernames(), true ) ) {
			return new \WP_Error(
				'Auth Error',
				__( 'Please have an administor change your username in order to meet current security measures.', 'tenup' )
			);
		}

		return $user;
	}

	/**
	 * List of reserved usernames
	 *
	 * @return array
	 */
	public function reserved_usernames() {
		$common_usernames = array(
			'admin',
			'administrator',
			'user',
			'username',
			'demo',
			'sql',
			'guest',
			'root',
			'test',
			'mysql',
			'ftp',
			'www',
			'client',
		);

		return apply_filters( 'tenup_experience_reserved_usernames', $common_usernames );
	}
}
