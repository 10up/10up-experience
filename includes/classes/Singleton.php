<?php
/**
 * Singleton trait
 *
 * @since  1.7
 * @package  10up-experience
 */

namespace TenUpExperience;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Abstract class
 */
trait Singleton {
	/**
	 * Instance.
	 *
	 * @var object
	 */
	protected static $instance;

	/**
	 * Return instance of class
	 *
	 * @return self
	 */
	public static function instance() {
		if ( empty( static::$instance ) ) {
			$class = get_called_class();

			static::$instance = new $class();

			if ( method_exists( static::$instance, 'setup' ) ) {
				static::$instance->setup();
			}
		}

		return static::$instance;
	}
}
