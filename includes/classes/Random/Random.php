<?php
/**
 * Random content customizations
 *
 * @package  10up-experience
 */

namespace TenUpExperience\Random;

use TenUpExperience\Singleton;

/**
 * Disables the WordPress 7.2 random content redirect (/random/).
 */
class Random {

	use Singleton;

	/**
	 * Setup module
	 *
	 * @since 1.19.0
	 */
	public function setup() {
		add_filter( 'wp_enable_random_content_redirect', [ $this, 'filter_random_content_redirect' ] );
		add_filter( 'register_post_type_args', [ $this, 'filter_post_type_args' ] );
	}

	/**
	 * Whether the random content feature should be disabled.
	 *
	 * @return bool
	 */
	public function is_disabled() {
		/**
		 * Filters whether to disable the WordPress random content redirect.
		 *
		 * @since 1.19.0
		 *
		 * @param bool $disabled Whether to disable random content. Default true.
		 */
		return (bool) apply_filters( 'tenup_experience_disable_random_content', true );
	}

	/**
	 * Turn off the /random/ rewrite rules, permalink setting and query var.
	 *
	 * @param bool $enabled Whether the random content redirect is enabled.
	 * @return bool
	 */
	public function filter_random_content_redirect( $enabled ) {
		return $this->is_disabled() ? false : $enabled;
	}

	/**
	 * Exclude every post type from random content.
	 *
	 * @param array $args Post type registration arguments.
	 * @return array
	 */
	public function filter_post_type_args( $args ) {
		if ( $this->is_disabled() ) {
			$args['randomable'] = false;
		}

		return $args;
	}
}
