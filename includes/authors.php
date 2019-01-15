<?php

/**
 * Gutenberg functionality customizations
 *
 * @package  10up-experience
 */

namespace tenup;

/**
 * Check to see if author archive page should be disabled for 10up user accounts
 */
function maybe_disable_author_archive() {

	if ( is_author() ){

		$is_author_disabled = false;
		$author 			= get_queried_object();

		// If the query object doesn't have a user e-mail address or the filter is allowing 10up authors, bail
		if ( empty( $author->data->user_email ) ||
			 true === apply_filters( 'tenup_experience_allow_tenupauthor_pages', false ) ) {
			return;
		}

		// E-mail addresses containing 10up.com (get10up.com inclusive) will be filtered out on the front-end
		if ( false !== stripos( $author->data->user_email, '10up.com' ) ) {
			$is_author_disabled = true;
		}

		// Filter tenup_experience_allow_tenupauthor_pages defaults to false. If set to true, allows 10up
		// author accounts to load
		if ( true  === $is_author_disabled ) {
				\wp_safe_redirect( '/', '301' );
				exit();
		}
	}

	return;

}

add_action( 'wp', __NAMESPACE__ . '\\maybe_disable_author_archive' );