<?php
/**
 * 10up Experience MU plugin
 */

namespace tenup;

/**
 * Let's setup our 10up menu in the toolbar
 *
 * @param object $wp_admin_bar
 */
function add_about_menu( $wp_admin_bar ) {
	if ( is_user_logged_in() ) {
		$wp_admin_bar->add_menu( array(
			'id' => '10up',
			'title' => '<span class="tenup-icon"></span>',
			'href' => admin_url( 'admin.php?page=10up-about' ),
			'meta' => array(
				'title' => '10up',
			),
		) );

		$wp_admin_bar->add_menu( array(
			'id' => '10up-about',
			'parent' => '10up',
			'title' => 'About 10up',
			'href' => admin_url( 'admin.php?page=10up-about' ),
			'meta' => array(
				'title' => 'About 10up',
			),
		) );
	}

}

add_action( 'admin_bar_menu', 'tenup\add_about_menu', 11 );

function enqueue_scripts() {
	global $pagenow;

	wp_enqueue_style( '10up-admin', content_url( 'mu-plugins/10up-experience/assets/css/admin.css' ) );

	if ( 'admin.php' === $pagenow && ! empty( $_GET['page'] ) && '10up-about' === $_GET['page'] ) {
		wp_enqueue_style( '10up-about', content_url( 'mu-plugins/10up-experience/assets/css/about.css' ) );
	}
}
add_action( 'admin_enqueue_scripts', 'tenup\enqueue_scripts' );

/**
 * Output about screen
 */
function about_screen() {
	?>
	<div class="wrap about-wrap">

		<h1>About 10up</h1>

		<div class="about-text">We make web publishing easy. Maybe even fun.</div>

		<div class="tenup-badge"></div>

		<h2 class="nav-tab-wrapper">
			<a href="about.php" class="nav-tab nav-tab-active">About</a>
			<a href="credits.php" class="nav-tab">Team</a>
		</h2>

	</div>
	<?php
}

/**
 * Register admin pages with output callbacks
 */
function register_admin_pages() {
	add_submenu_page( null, 'About 10up', 'About 10up', 'manage_options', '10up-about', 'tenup\about_screen' );
}
add_action( 'admin_menu', 'tenup\register_admin_pages' );

/**
 * Start plugin customizations
 */
function plugin_customizations() {

	/**
	 * Stream
	 */
	if ( is_plugin_active( 'stream/stream.php' ) ) {

		add_action( 'admin_init', function() {
			remove_menu_page( 'wp_stream' );
		}, 11 );
	}
}
add_action( 'admin_init', 'tenup\plugin_customizations' );

/**
 * Add 10up suggested tab to plugins install screen
 *
 * @param array $tabs
 * @return mixed
 */
function tenup_plugin_install_link( $tabs ) {
	$new_tabs = array(
		'tenup' => '10up Suggested'
	);

	foreach ( $tabs as $key => $value ) {
		$new_tabs[$key] = $value;
	}

	return $new_tabs;
}
add_action( 'install_plugins_tabs', 'tenup\tenup_plugin_install_link' );

/**
 * Filter the arguments passed to plugins_api() for 10up suggested page
 *
 * @param array $args
 * @return array
 */
function filter_install_plugin_args( $args ) {
	$args = array(
		'page' => 1,
		'per_page' => 60,
		'fields' => array(
			'last_updated' => true,
			'downloaded' => true,
			'icons' => true
		),
		'locale' => 'en_US',
		'user' => '10up',
	);

	return $args;
}
add_filter( 'install_plugins_table_api_args_tenup', 'tenup\filter_install_plugin_args' );

/**
 * Setup 10up suggested plugin display table
 */
add_action( 'install_plugins_tenup', 'display_plugins_table' );


function plugin_install_warning() {
	?>
	<div class="tenup-plugin-install-warning updated">
		<p>
			<?php printf( __( "We recommend you only install <a href=''%s'>10up Suggested</a> plugins.", 'tenup' ), admin_url( 'network/plugin-install.php?tab=tenup' ) ); ?>
		</p>
	</div>
	<?php
}
add_action( 'install_plugins_pre_featured', 'tenup\plugin_install_warning' );
add_action( 'install_plugins_pre_popular', 'tenup\plugin_install_warning' );
add_action( 'install_plugins_pre_favorites', 'tenup\plugin_install_warning' );
add_action( 'install_plugins_pre_beta', 'tenup\plugin_install_warning' );
add_action( 'install_plugins_pre_search', 'tenup\plugin_install_warning' );