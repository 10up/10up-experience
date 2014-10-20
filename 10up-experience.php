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
			'title' => '<span class="ab-icon tenup-icon"></span>',
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

		$wp_admin_bar->add_menu( array(
			'id' => '10up-team',
			'parent' => '10up',
			'title' => esc_html__( 'Team', 'tenup' ),
			'href' => esc_url( admin_url( 'admin.php?page=10up-team' ) ),
			'meta' => array(
				'title' => esc_html__( 'Team', 'tenup' ),
			),
		) );

		if ( defined( 'TENUP_SUPPORT' ) && 3 === TENUP_SUPPORT ) {
			$wp_admin_bar->add_menu( array(
				'id' => '10up-support',
				'parent' => '10up',
				'title' => esc_html__( 'Support', 'tenup' ),
				'href' => esc_url( admin_url( 'admin.php?page=10up-support' ) ),
				'meta' => array(
					'title' => esc_html__( 'Support', 'tenup' ),
				),
			) );
		}
	}

}
add_action( 'admin_bar_menu', 'tenup\add_about_menu', 11 );

/**
 * Setup scripts for customized admin experience
 */
function admin_enqueue_scripts() {
	global $pagenow;

	wp_enqueue_style( '10up-admin', content_url( 'mu-plugins/10up-experience/assets/css/admin.css' ) );

	if ( 'admin.php' === $pagenow && ! empty( $_GET['page'] ) && ( '10up-about' === $_GET['page'] || '10up-team' === $_GET['page'] || '10up-support' === $_GET['page'] ) ) {
		wp_enqueue_style( '10up-about', content_url( 'mu-plugins/10up-experience/assets/css/tenup-pages.css' ) );
	}
}
add_action( 'admin_enqueue_scripts', 'tenup\admin_enqueue_scripts' );

function enqueue_scripts() {
	wp_enqueue_style( '10up-admin', content_url( 'mu-plugins/10up-experience/assets/css/admin.css' ) );
}
add_action( 'wp_enqueue_scripts', 'tenup\enqueue_scripts' );

/**
 * Output about screens
 */
function main_screen() {
	?>
	<div class="wrap about-wrap">

		<h1><?php esc_html_e( 'Welcome to 10up', 'tenup' ); ?></h1>

		<div class="about-text"><?php esc_html_e( 'We make web publishing easy. Maybe even fun.', 'tenup' ); ?></div>

		<div class="tenup-badge"></div>

		<h2 class="nav-tab-wrapper">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=10up-about' ) ); ?>" class="nav-tab <?php if ( '10up-about' === $_GET['page'] ) : ?>nav-tab-active<?php endif; ?>"><?php esc_html_e( 'About', 'tenup' ); ?></a>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=10up-team' ) ); ?>" class="nav-tab <?php if ( '10up-team' === $_GET['page'] ) : ?>nav-tab-active<?php endif; ?>"><?php esc_html_e( 'Team', 'tenup' ); ?></a>
			<?php if ( defined( 'TENUP_SUPPORT' ) && 3 === TENUP_SUPPORT ) : ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=10up-support' ) ); ?>" class="nav-tab <?php if ( '10up-support' === $_GET['page'] ) : ?>nav-tab-active<?php endif; ?>"><?php esc_html_e( 'Support', 'tenup' ); ?></a>
			<?php endif; ?>
		</h2>

		<?php if ( '10up-about' === $_GET['page'] ) : ?>
			<?php about_screen(); ?>
		<?php elseif ( '10up-support' === $_GET['page'] ) : ?>
			<div class="section">
				<?php get_template_part( 'tenup', 'support' ); ?>
			</div>
			<hr>
		<?php else : ?>
			<?php team_screen(); ?>
		<?php endif; ?>

	</div>
<?php
}

/**
 * Output HTML for about screen
 */
function about_screen() {
	?>
	<div class="section center">
		<h3><?php esc_html_e( "We make content management easy.", 'tenup' ); ?><br><strong><?php esc_html_e( "Maybe even fun.", 'tenup' ); ?></strong></h3>
		<p><?php esc_html_e( "We make content management simple with our premiere web design & development consulting services, by contributing to open platforms like WordPress, and by providing tools and products that make web publishing a cinch.", 'tenup' ); ?></p>

		<p><?php esc_html_e( "At 10up, we don’t just “make” things – we engineer them. We’re a group of people built to solve problems; made to create; wired to delight. From beautiful pixels to beautiful code, we constantly improve the things around us, applying our passions to our clients’ projects and goals. Sometimes instead of resting, always instead of just getting it done.", 'tenup' ); ?></p>
	</div>
	<hr>
	<?php
}

/**
 * Output HTML for team screen
 */
function team_screen() {
	?>
	<div class="section">
		Team screen
	</div>
	<hr>
	<?php
}

/**
 * Register admin pages with output callbacks
 */
function register_admin_pages() {
	add_submenu_page( null, esc_html__( 'About 10up', 'tenup' ), esc_html__( 'About 10up', 'tenup' ), 'manage_options', '10up-about', 'tenup\main_screen' );
	add_submenu_page( null, esc_html__( 'Team 10up', 'tenup' ), esc_html__( 'Team 10up', 'tenup' ), 'manage_options', '10up-team', 'tenup\main_screen' );

	if ( defined( 'TENUP_SUPPORT' ) && 3 === TENUP_SUPPORT ) {
		add_submenu_page( null, esc_html__( 'Support', 'tenup' ), esc_html__( 'Support', 'tenup' ), 'manage_options', '10up-support', 'tenup\main_screen' );
	}
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
		'tenup' => esc_html__( '10up Suggested', 'tenup' ),
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

/**
 * Warn user when installing non-10up suggested plugins
 */
function plugin_install_warning() {
	?>
	<div class="tenup-plugin-install-warning updated">
		<p>
			<?php printf( __( "We recommend you only install <a href=''%s'>10up Suggested</a> plugins.", 'tenup' ), esc_url( admin_url( 'network/plugin-install.php?tab=tenup' ) ) ); ?>
		</p>
	</div>
	<?php
}
add_action( 'install_plugins_pre_featured', 'tenup\plugin_install_warning' );
add_action( 'install_plugins_pre_popular', 'tenup\plugin_install_warning' );
add_action( 'install_plugins_pre_favorites', 'tenup\plugin_install_warning' );
add_action( 'install_plugins_pre_beta', 'tenup\plugin_install_warning' );
add_action( 'install_plugins_pre_search', 'tenup\plugin_install_warning' );
add_action( 'install_plugins_pre_dashboard', 'tenup\plugin_install_warning' );