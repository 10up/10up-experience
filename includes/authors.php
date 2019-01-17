<?php

/**
 * Author customizations
 *
 * @package  10up-experience
 */

namespace tenup;

/**
 * Check to see if author archive page should be disabled for 10up user accounts
 */
function maybe_disable_author_archive() {

	if ( ! is_author() ) {
		return;
	}

	$is_author_disabled = false;
	$author 			 = get_queried_object();
	$current_domain		 = parse_url( get_site_url(), PHP_URL_HOST );

	// Domain names that are whitelisted allowed to index 10up users to be indexed
	$whitelisted_domains = [
		'10up.com',
		'elasticpress.io',
		'10uplabs.com',
	];

	// Perform partial match on domains to catch subdomains or variation of domain name
	$filtered_domains = array_filter( $whitelisted_domains, function( $domain ) use ( $current_domain ) {
		return false !== stripos( $current_domain, $domain );
	} );

	// If the query object doesn't have a user e-mail address or the filter is allowing 10up authors, bail
	if ( ! empty( $filtered_domains ) ||
		 empty( $author->data->user_email ) ||
		 true === apply_filters( 'tenup_experience_allow_tenupauthor_pages', false ) ) {

		return;

	}

	// E-mail addresses containing 10up.com (get10up.com inclusive) will be filtered out on the front-end
	if ( false !== stripos( $author->data->user_email, '10up.com' ) ) {
		$is_author_disabled = true;
	}

	if ( true  === $is_author_disabled ) {
		\wp_safe_redirect( '/', '301' );
		exit();
	}

	return;

}

add_action( 'wp', __NAMESPACE__ . '\\maybe_disable_author_archive' );