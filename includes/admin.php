<?php
namespace tenup;


/**
 * Setup scripts for customized admin experience
 */
function admin_enqueue_scripts() {
	global $pagenow;

	wp_enqueue_style( '10up-admin', plugins_url( '/assets/css/admin.css', __FILE__ ) );

	if ( 'admin.php' === $pagenow && ! empty( $_GET['page'] ) && ( '10up-about' === $_GET['page'] || '10up-team' === $_GET['page'] || '10up-support' === $_GET['page'] ) ) {
		wp_enqueue_style( '10up-about', plugins_url( '/assets/css/tenup-pages.css', __FILE__ ) );
	}
}
add_action( 'admin_enqueue_scripts', 'tenup\admin_enqueue_scripts' );

function enqueue_scripts() {
	wp_enqueue_style( '10up-admin', plugins_url( '/assets/css/admin.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'tenup\enqueue_scripts' );

/**
 * Filter admin footer text "Thank you for creating..."
 *
 * @return string
 */
function filter_admin_footer_text() {
	$new_text = sprintf( __( 'Thank you for creating with <a href="https://wordpress.org">WordPress</a> and <a href="http://10up.com">10up</a>.', 'tenup' ) );
	return $new_text;
}
add_filter( 'admin_footer_text', 'tenup\filter_admin_footer_text' );

/**
 * Disable plugin/theme editor
 */
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', true );
}
