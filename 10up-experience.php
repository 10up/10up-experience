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
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=10up-about' ) ); ?>" class="nav-tab <?php if ( '10up-about' === $_GET['page'] ) : ?>nav-tab-active<?php endif; ?>"><?php esc_html_e( 'About Us', 'tenup' ); ?></a>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=10up-team' ) ); ?>" class="nav-tab <?php if ( '10up-team' === $_GET['page'] ) : ?>nav-tab-active<?php endif; ?>"><?php esc_html_e( 'Our Team', 'tenup' ); ?></a>
			<?php if ( defined( 'TENUP_SUPPORT' ) && 3 === TENUP_SUPPORT ) : ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=10up-support' ) ); ?>" class="nav-tab <?php if ( '10up-support' === $_GET['page'] ) : ?>nav-tab-active<?php endif; ?>"><?php esc_html_e( 'Support', 'tenup' ); ?></a>
			<?php endif; ?>
		</h2>

		<div class="section-wrapper">
			<?php if ( '10up-about' === $_GET['page'] ) : ?>
				<?php about_screen(); ?>
			<?php elseif ( '10up-support' === $_GET['page'] ) : ?>
				<div class="section section-support">
					<?php get_template_part( 'tenup', 'support' ); ?>
				</div>
			<?php else : ?>
				<?php team_screen(); ?>
			<?php endif; ?>
		</div>
		<hr>
	</div>
<?php
}

/**
 * Output HTML for about screen
 */
function about_screen() {
	?>
	<div class="section section-about">
		<h2>We make web publishing and content management easy – maybe even fun.</h2>

		<p>We make content management simple with our premiere web design &amp; development consulting services, by contributing to open platforms like WordPress, and by providing tools and products that make web publishing a cinch.</p>

		<p>We’re a group of people built to solve problems; made to create; wired to delight. From beautiful pixels to beautiful code, we constantly improve the things around us, applying our passions to our clients’ projects and goals. Sometimes instead of resting, always instead of just getting it done.</p>

		<img src="<?php echo esc_url( content_url( 'mu-plugins/10up-experience/assets/img/10up-image-1.jpg' ) ); ?>" alt="">

		<h3>Building Without Boundaries</h3>
		<p>The best talent isn’t found in a single zip code, and an international clientele requires a global perspective. From New York City to Salt Spring Island, our distributed model empowers us to bring in the best strategists, designers, and engineers, wherever they may be found. As of September 2014, 10up has over 80 full time staff; veterans of commercial agencies, universities, start ups, non profits, and international technology brands, our team has an uncommon breadth.</p>

		<img src="<?php echo esc_url( content_url( 'mu-plugins/10up-experience/assets/img/10up-image-2.jpg' ) ); ?>" alt="">

		<h3>Full Service Reach</h3>

		<p><strong>Strategy:</strong> Should I build an app or a responsive website? Am I maximizing my ad revenue? Why don’t my visitors click “sign up”? How many 10uppers does it take to screw in a website? We don’t just build: we figure out the plan.</p>

		<p><strong>Design:</strong> Inspiring design brings the functional and the beautiful; a delightful blend of art and engineering. We focus on the audience whimsy and relationship between brand and consumer, delivering design that works.</p>

		<p><strong>Engineering:</strong> Please. Look under the hood. Our team of sought after international speakers provides expert code review for enterprise platforms like WordPress.com VIP. Because the best website you have is the one that’s up.</p>
	</div>
	<?php
}

/**
 * Output HTML for team screen
 */
function team_screen() {
	?>
	<div class="section section-team">
		<div class="section-team-header">
			<h2>Our team</h2>
		</div>

		<p>Influencing communities around the world, our team leads meetups, speaks at local events, and visits clients wherever they may be. A modest studio in Portland, Oregon hosts speakers, out of town guests, and the occasional workshop.</p>

		<p>Independence from traditional “brick and mortar” offices, freedom from commutes, and flexible schedules across nearly a dozen time zones means our team works when and where they’re most inspired, available when our clients need them.</p>

		<a href="http://10up.com/about/#employee-jake-goldman" class="employee-link" target="_blank">
			<img src="<?php echo esc_url( content_url( 'mu-plugins/10up-experience/assets/img/team/jake.jpg' ) ); ?>" alt="">
			<span>Jake&nbsp;Goldman<em>President &amp; Founder</em></span>
		</a>

		<a href="http://10up.com/about/#employee-john-eckman" class="employee-link" target="_blank">
			<img src="<?php echo esc_url( content_url( 'mu-plugins/10up-experience/assets/img/team/john.jpg' ) ); ?>" alt="">
			<span>John&nbsp;Eckman<em>Chief Executive Officer</em></span>
		</a>

		<a href="http://10up.com/about/#employee-jess-jurick" class="employee-link" target="_blank">
			<img src="<?php echo esc_url( content_url( 'mu-plugins/10up-experience/assets/img/team/jess.jpg' ) ); ?>" alt="">
			<span>Jess&nbsp;Jurick<em>Vice President, Consulting Services</em></span>
		</a>

		<a href="http://10up.com/about/#employee-vasken-hauri" class="employee-link" target="_blank">
			<img src="<?php echo esc_url( content_url( 'mu-plugins/10up-experience/assets/img/team/vasken.jpg' ) ); ?>" alt="">
			<span>Vasken&nbsp;Hauri<em>Vice President, Engineering</em></span>
		</a>
	</div>
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