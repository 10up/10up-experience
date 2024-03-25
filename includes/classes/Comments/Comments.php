<?php
/**
 * Disable Comments
 *
 * @package 10up-experience
 */

namespace TenUpExperience\Comments;

use TenUpExperience\Singleton;

/**
 * Comments class
 */
class Comments {

	use Singleton;

	/**
	 * Setup module
	 *
	 * @since 1.11.2
	 */
	public function setup() {
		// Remove comments support from posts and pages
		add_action( 'init', [ $this, 'disable_comments_post_types_support' ] );

		// Remove comments-related UI elements
		add_action( 'admin_menu', [ $this, 'remove_comments_admin_menus' ] );
		add_action( 'wp_before_admin_bar_render', [ $this, 'remove_comments_admin_bar_links' ] );
	}

	/**
	 * Remove comments support from posts and pages
	 *
	 * @return void
	 */
	public function disable_comments_post_types_support() {
		$post_types = get_post_types();
		foreach ( $post_types as $post_type ) {
			if ( post_type_supports( $post_type, 'comments' ) ) {
				remove_post_type_support( $post_type, 'comments' );
				remove_post_type_support( $post_type, 'trackbacks' );
			}
		}
	}

	/**
	 * Remove comments admin menus
	 *
	 * @return void
	 */
	public function remove_comments_admin_menus() {
		remove_menu_page( 'edit-comments.php' );
	}

	/**
	 * Remove comments admin bar links
	 *
	 * @return void
	 */
	public function remove_comments_admin_bar_links() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'comments' );
	}
}
