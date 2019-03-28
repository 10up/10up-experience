<?php
/**
 * Required option failsafes
 *
 * @package  10up-experience
 */

namespace tenup;

/**
 * Ensures a list of required options have failsafes in place.
 *
 * In the event that WordPress is unable to establish momentary connection with the
 * database, a required option may unintentionally end up in the notoptions cache. Once
 * an option is in the notoptions cache, it will not be read from the database on any
 * future requests for that option.  This has the potential to bring down a site if a
 * critical option is not checked and returns false by default.
 *
 * This function adds default option filters on required options that ensure they will
 * not exist in the notoptions cache for long enough to bring a site down for more than
 * a few seconds.
 *
 * @return void
 */
function required_option_failsafes() {
	/**
	 * Filters the array of required options.
	 *
	 * @param array $required_options An array of required option keys.
	 */
	$required_options = apply_filters( 'tenup_experience_required_options', [ 'siteurl', 'home', 'wp_user_roles', 'rewrite_rules' ] );

	foreach ( $required_options as $option ) {
		add_filter( "default_option_{$option}", __NAMESPACE__ . '\\require_option_failsafe', 10, 3 );
	}
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\\required_option_failsafes' );

/**
 * Establish a failsafe for a required option.
 *
 * If WordPress is unable to retrieve a value for a required option, this filter
 * will ensure that the required option does not persist in the notoptions cache
 * for more than a period defined by the max age, by default, 10 seconds.
 *
 * @param mixed  $default        The default value.
 * @param string $option         The option name.
 * @param bool   $passed_default Whether the default was passed as a paremeter to get_option.
 * @return mixed
 */
function require_option_failsafe( $default, $option, $passed_default ) {
	// If we were explicitly passed a default, return the passed default value.
	if ( $passed_default ) {
		return $default;
	}

	// Did we look up this value recently?
	$last_lookup = (int) wp_cache_get( "option_last_checked_{$option}", 'options' );
	$time_since  = time() - $last_lookup;

	/**
	 * Filters the maximum age that a required option can be stored in the notoptions cache.
	 *
	 * @param int    $max_age The max age in seconds, default 10s.
	 * @param string $option  The name of the option that is being checked.
	 */
	$max_age = apply_filters( 'tenup_experience_required_option_max_age', 10, $option );

	// If we checked less than x seconds ago, return whatever we have.
	if ( $max_age >= $time_since ) {
		return $default;
	}

	// Remove the required option from notoptions array.
	$notoptions = wp_cache_get( 'notoptions', 'options' );
	if ( is_array( $notoptions ) ) {
		unset( $notoptions[ $option ] );
		wp_cache_set( 'notoptions', $notoptions, 'options' );
	}

	// Record current time as last checked time.
	wp_cache_set( "option_last_checked_{$option}", time(), 'options' );

	// Recheck for the option as it now will not be in the "notoptions" array.
	return get_option( $option );
}
