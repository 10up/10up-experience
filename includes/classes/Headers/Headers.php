<?php
/**
 * Header customizations
 *
 * @package  10up-experience
 */

namespace TenUpExperience\Headers;

use TenUpExperience\Singleton;

/**
 * Headers class
 */
class Headers extends Singleton {
	/**
	 * Setup module
	 */
	public function setup() {
		add_action( 'wp_headers', [ $this, 'maybe_set_frame_option_header' ], 99, 1 );
	}

	/**
	 * Set the X-Frame-Options header to 'SAMEORIGIN' to prevent clickjacking attacks
	 *
	 * @param string $headers Headers
	 */
	public function maybe_set_frame_option_header( $headers ) {
		$header_value               = apply_filters( 'tenup_experience_x_frame_options', 'SAMEORIGIN' );
		$headers['X-Frame-Options'] = $header_value;
		return $headers;
	}
}
