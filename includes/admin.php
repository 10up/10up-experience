<?php
namespace tenup;

/**
 * Disable plugin/theme editor
 */
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', true );
}

/**
 * Setup scripts for customized admin experience
 */
function admin_enqueue_scripts() {
	$screen = get_current_screen();

	wp_enqueue_style( '10up-admin', plugins_url( '/assets/css/admin.css', dirname( __FILE__ ) ) );

	if ( 0 === strpos( $screen->base, 'admin_page_10up-') ) {
		wp_enqueue_style( '10up-about', plugins_url( '/assets/css/tenup-pages.css', dirname( __FILE__ ) ) );
	}
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\admin_enqueue_scripts' );

function enqueue_scripts() {
	wp_enqueue_style( '10up-admin', plugins_url( '/assets/css/admin.css', dirname( __FILE__ ) ) );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_scripts' );

/**
 * Filter admin footer text "Thank you for creating..."
 *
 * @return string
 */
function filter_admin_footer_text() {
	$new_text = sprintf( __( 'Thank you for creating with <a href="https://wordpress.org">WordPress</a> and <a href="http://10up.com">10up</a>.', 'tenup' ) );
	return $new_text;
}
add_filter( 'admin_footer_text', __NAMESPACE__ . '\filter_admin_footer_text' );
