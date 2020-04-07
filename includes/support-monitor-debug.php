<?php
/**
 * 10up suppport monitor debugger. This can be enabled by setting the following in wp-config.php:
 * define( 'SUPPORT_MONITOR_DEBUG', true );
 *
 * @since  1.7
 * @package 10up-experience
 */

namespace TenUpExperience\SupportMonitor\Debug;

/**
 * Support Monitor Log custom post type slug
 */
const CPT_SLUG = 'support_mon_debug';

/**
 * Setup module
 *
 * @since 1.7
 */
function setup() {

	// No need to run setup functions if debug is disabled
	if ( ! is_debug_enabled() ) {
		return;
	}

	add_action( 'init',       __NAMESPACE__ . '\register_debug_cpt' );
	add_action( 'admin_menu', __NAMESPACE__ . '\register_menu' );

}

/**
 * Register the post monitor debugger post type
 *
 * @return void
 */
function register_debug_cpt() {

	$args = array(
		'public'             => false,
		'publicly_queryable' => false,
		'show_in_rest'       => false,
		'show_ui'            => true,
		'show_in_menu'       => false,
		'query_var'          => false,
		'label'              => esc_html__( '10up Support Monitor Log', 'tenup' ),
		'has_archive'        => false,
		'supports'           => [ 'title', 'editor' ],
		'menu_icon'          => 'dashicons-book-alt',
		'taxonomies'         => [],
	);

	register_post_type( CPT_SLUG, $args );

}

/**
 * Logs an entry if the support monitor debugger has been enabled
 *
 * @param string $url - Full URL message was sent to
 * @param array $message - Array of message parts
 * @param array $response - Response of request to support monitor service
 * @return void
 */
function maybe_add_log_entry( $url, $message, $response ) {

	if ( ! is_debug_enabled() ) {
		return;
	}

	$post_content = sprintf(
		'<p>URL: %s</p><p>Message: %s</p><p>Response: %s</p>',
		$url,
		wp_json_encode( $message ),
		wp_json_encode( $response )
	);

	wp_insert_post( [
		'post_type'          => CPT_SLUG,
		'post_status'        => 'publish',
		'post_title'         => current_time( 'mysql' ),
		'post_content'       => $post_content,
	] );

}

/**
 * Regisers the Support Monitor log link under the 'Tools' menu
 *
 * @return void
 */
function register_menu() {

	add_submenu_page(
		'tools.php',
		esc_html__( '10up Support Monitor', 'tenup' ),
		esc_html__( '10up Support Monitor', 'tenup' ),
		'manage_options',
		'edit.php?post_type=' . CPT_SLUG
	);
}

/**
 * Determines whether the debugger has been enabled
 *
 * @return boolean - true if defined and set, false if disabled
 */
function is_debug_enabled() {
	return ( defined( 'SUPPORT_MONITOR_DEBUG' ) && SUPPORT_MONITOR_DEBUG );
}