<?php
namespace tenup;

/**
 * Let's setup our 10up menu in the toolbar
 *
 * @param object $wp_admin_bar
 */
function add_about_menu( $wp_admin_bar ) {
	if ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
		$wp_admin_bar->add_menu( array(
			'id' => '10up',
			'title' => '<div class="tenup-icon ab-item svg"><span class="screen-reader-text">' . esc_html__( 'About 10up', 'tenup' ) . '</span></div>',
			'href' => admin_url( 'admin.php?page=10up-about' ),
			'meta' => array(
				'title' => '10up',
			),
		) );

		$wp_admin_bar->add_menu( array(
			'id' => '10up-about',
			'parent' => '10up',
			'title' => esc_html__( 'About 10up', 'tenup' ),
			'href' => esc_url( admin_url( 'admin.php?page=10up-about' ) ),
			'meta' => array(
				'title' => esc_html__( 'About 10up', 'tenup' ),
			),
		) );
	}

}
add_action( 'admin_bar_menu', __NAMESPACE__ . '\add_about_menu', 11 );
