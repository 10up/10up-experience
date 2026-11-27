<?php
/**
 * Utility functions
 *
 * @since  1.7
 * @package  10up-experience
 */

namespace TenUpExperience\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Whether plugin is network activated
 *
 * Determines whether plugin is network activated or just on the local site.
 *
 * @since 1.7
 * @param string $plugin the plugin base name.
 * @return bool True if network activated or false.
 */
function is_network_activated( $plugin ) {
	$plugins = get_site_option( 'active_sitewide_plugins' );

	return is_multisite() && isset( $plugins[ $plugin ] );
}

/**
 * Whether WordPress ships the experimental @wordpress/boot script module
 * (first bundled in WordPress 7.0).
 *
 * Feature-detected rather than version-checked: boot is experimental and may
 * move.
 *
 * @return bool
 */
function is_boot_available() {
	return function_exists( 'wp_register_script_module' )
		&& file_exists( ABSPATH . WPINC . '/js/dist/script-modules/boot/index.min.asset.php' );
}

/**
 * Name of the render function dist/build.php generates for a boot page.
 *
 * @param string $page Page directory under assets/js/admin-pages.
 * @return string
 */
function boot_page_render_function( $page ) {
	return 'tenup_experience_' . str_replace( '-', '_', $page ) . '_wp_admin_render_page';
}

/**
 * Whether a boot page can render: WordPress ships boot, and the build
 * registered the page (dist/build.php is present and PHP is 8.1+).
 *
 * @param string $page Page directory under assets/js/admin-pages.
 * @return bool
 */
function is_boot_page_available( $page ) {
	return is_boot_available() && function_exists( boot_page_render_function( $page ) );
}

/**
 * Render a boot page's mount point, if it can render.
 *
 * Menu callbacks call this and fall back to their classic markup when it
 * returns false.
 *
 * @param string $page Page directory under assets/js/admin-pages.
 * @return bool Whether the page was rendered.
 */
function render_boot_page( $page ) {
	if ( ! is_boot_page_available( $page ) ) {
		return false;
	}

	call_user_func( boot_page_render_function( $page ) );

	return true;
}

/**
 * Serve a boot page from an admin page registered under its own menu slug,
 * so existing `?page=` URLs keep working.
 *
 * The generated page answers `?page=<page>-wp-admin` by default; this claims
 * `$menu_slug` for it too, but only where WordPress ships boot, so older
 * versions keep their classic screen.
 *
 * @param string $page      Page directory under assets/js/admin-pages.
 * @param string $menu_slug Menu slug the screen is registered under.
 * @return void
 */
function serve_boot_page( $page, $menu_slug ) {
	add_filter(
		$page . '-wp-admin_is_current_page',
		static function ( $is_ours, $requested ) use ( $menu_slug ) {
			return $is_ours || ( $menu_slug === $requested && is_boot_available() );
		},
		10,
		2
	);
}

/**
 * Hand a boot page its server data as `window.<$variable>`.
 *
 * The page's `_init` action fires before its prerequisites script is
 * registered, so the payload rides a script handle of its own.
 *
 * @param string   $page     Page directory under assets/js/admin-pages.
 * @param string   $variable Window global the page reads.
 * @param callable $data     Returns the payload; only called on the page.
 * @return void
 */
function add_boot_page_data( $page, $variable, $data ) {
	add_action(
		$page . '-wp-admin_init',
		static function () use ( $page, $variable, $data ) {
			$handle = $page . '-wp-admin-data';

			wp_register_script( $handle, false, [], TENUP_EXPERIENCE_VERSION, true );
			wp_add_inline_script(
				$handle,
				sprintf( 'window.%s = %s;', $variable, wp_json_encode( call_user_func( $data ), JSON_HEX_TAG | JSON_UNESCAPED_SLASHES ) )
			);
			wp_enqueue_script( $handle );
		}
	);
}
