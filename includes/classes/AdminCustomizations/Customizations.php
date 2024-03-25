<?php
/**
 * Admin customizations
 *
 * @package  10up-experience
 */

namespace TenUpExperience\AdminCustomizations;

use TenUpExperience\Singleton;

/**
 * Admin Customizations class
 */
class Customizations {

	use Singleton;

	/**
	 * Setup module
	 *
	 * @since 1.7
	 */
	public function setup() {
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_filter( 'admin_footer_text', [ $this, 'filter_admin_footer_text' ] );
		add_action( 'admin_bar_menu', [ $this, 'add_about_menu' ], 11 );
		add_action( 'admin_menu', [ $this, 'register_admin_pages' ] );
		add_filter( 'admin_title', [ $this, 'admin_title_fix' ], 10, 2 );
	}

	/**
	 * Register admin pages with output callbacks
	 */
	public function register_admin_pages() {
		add_submenu_page( 'admin.php', esc_html__( 'About 10up', 'tenup' ), esc_html__( 'About 10up', 'tenup' ), 'edit_posts', '10up-about', [ $this, 'main_screen' ] );
	}

	/**
	 * Ensure our admin pages get a proper title.
	 *
	 * Because of the empty page parent, the title doesn't get output as expected.
	 *
	 * @param  string $admin_title The page title, with extra context added.
	 * @param  string $title       The original page title.
	 * @return string              The altered page title.
	 */
	public function admin_title_fix( $admin_title, $title ) {
		$screen = get_current_screen();

		wp_enqueue_style( '10up-admin', plugins_url( '/dist/css/admin.css', TENUP_EXPERIENCE_FILE ), array(), TENUP_EXPERIENCE_VERSION );

		if ( 0 !== strpos( $screen->base, 'admin_page_10up-' ) ) {
			return $admin_title;
		}

		// There were previously multiple 10up pages - leave this basic structure here in case we return to that later.
		if ( 'admin_page_10up-about' === $screen->base ) {
			$admin_title = esc_html__( 'About 10up', 'tenup' ) . $admin_title;
		}

		return $admin_title;
	}

	/**
	 * Output about screens
	 */
	public function main_screen() {
		?>
		<div class="wrap about-wrap full-width-layout">

			<h1><?php esc_html_e( 'About 10up', 'tenup' ); ?></h1>

			<div class="about-text">
				<?php
					echo wp_kses_post(
						sprintf(
							// translators: %s is a link to 10up.com
							__( 'We&#8217;re a full-service digital agency making a better web with finely crafted websites, apps, and tools that drive business results. <a href="%s" target="_blank">Learn more →</a>', 'tenup' ),
							esc_url( 'https://10up.com' )
						)
					);
				?>
				</div>

			<a class="tenup-badge" href="https://10up.com" target="_blank"><span aria-label="<?php esc_attr_e( 'Link to 10up.com', 'tenup' ); ?>">10up.com</span></a>

			<div class="feature-section one-col">
				<h2><?php esc_html_e( 'Thanks for working with team 10up!', 'tenup' ); ?></h2>

				<p><?php esc_html_e( 'You have the 10up Experience plugin installed, which typically means 10up built or is supporting your site. The Experience plugin configures WordPress to better protect and inform our clients, including security precautions like blocking unauthenticated access to your content over the REST API, safety measures like preventing code-level changes from being made inside the admin, and some other resources, including a list of vetted plugins we recommend for common use cases and information about us.', 'tenup' ); ?></p>
			</div>

			<div class="feature-section one-col">
				<h3><?php esc_html_e( 'Making a Better Web', 'tenup' ); ?></h3>

					<p><?php esc_html_e( 'We make the internet better with consultative creative and engineering services, innovative tools, and dependable products that take the pain out of content creation and management, in service of digital experiences that advance business and marketing objectives. We’re a group of people built to solve problems, made to create, wired to delight.', 'tenup' ); ?></p>

					<p><?php esc_html_e( 'A customer-centric service model that covers every base, unrivaled leadership and investment in open platforms and tools for digital makers and content creators, and a forward-looking remote work culture make for a refreshing agency experience.', 'tenup' ); ?></p>
			</div>

			<div class="full-width-img">
				<img src="<?php echo esc_url( plugins_url( '/assets/img/10up-image-1.jpg', TENUP_EXPERIENCE_FILE ) ); ?>" alt="">
			</div>

			<div class="feature-section one-col">
				<h3><?php esc_html_e( 'Building Without Boundaries', 'tenup' ); ?></h3>
				<p><?php esc_html_e( 'The best talent isn’t found in a single zip code, and an international clientele requires a global perspective. From New York City, to the wilds of Idaho, to a dozen countries across Europe, our model empowers us to bring in the best strategists, designers, and engineers, wherever they may live. Veterans of commercial agencies, universities, start ups, nonprofits, and international technology brands, our team has an uncommon breadth.', 'tenup' ); ?></p>
			</div>

			<div class="full-width-img">
				<img src="<?php echo esc_url( plugins_url( '/assets/img/10up-image-2.jpg', TENUP_EXPERIENCE_FILE ) ); ?>" alt="">
			</div>

			<div class="feature-section one-col">
				<h3><?php esc_html_e( 'Full Service Reach', 'tenup' ); ?></h3>

				<p><strong><?php esc_html_e( 'Strategy:', 'tenup' ); ?></strong> <?php esc_html_e( 'Should I build an app or a responsive website? Am I maximizing my ad revenue? Why don’t my visitors click “sign up”? How many 10uppers does it take to screw in a website? We don’t just build: we figure out the plan.', 'tenup' ); ?></p>

				<p><strong><?php esc_html_e( 'Design:', 'tenup' ); ?></strong> <?php esc_html_e( 'Inspiring design brings the functional and the beautiful; a delightful blend of art and engineering. We focus on the audience whimsy and relationship between brand and consumer, delivering design that works.', 'tenup' ); ?></p>

				<p><strong><?php esc_html_e( 'Engineering:', 'tenup' ); ?></strong> <?php esc_html_e( 'Please. Look under the hood. Our team of sought after international speakers provides expert code review for enterprise platforms like WordPress.com VIP. Because the best website you have is the one that’s up.', 'tenup' ); ?></p>

				<p class="center"><a href="https://10up.com" class="button button-hero button-primary" target="_blank"><?php esc_html_e( 'Learn more about 10up', 'tenup' ); ?></a></p>
			</div>
			<hr>
		</div>
		<?php
	}


	/**
	 * Let's setup our 10up menu in the toolbar
	 *
	 * @param object $wp_admin_bar Current WP Admin bar object
	 */
	public function add_about_menu( $wp_admin_bar ) {
		if ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
			$wp_admin_bar->add_menu(
				array(
					'id'    => '10up',
					'title' => '<div class="tenup-icon ab-item"><span class="screen-reader-text">' . esc_html__( 'About 10up', 'tenup' ) . '</span></div>',
					'href'  => admin_url( 'admin.php?page=10up-about' ),
					'meta'  => array(
						'title' => '10up',
					),
				)
			);

			$wp_admin_bar->add_menu(
				array(
					'id'     => '10up-about',
					'parent' => '10up',
					'title'  => esc_html__( 'About 10up', 'tenup' ),
					'href'   => esc_url( admin_url( 'admin.php?page=10up-about' ) ),
					'meta'   => array(
						'title' => esc_html__( 'About 10up', 'tenup' ),
					),
				)
			);
		}

	}

	/**
	 * Setup scripts for customized admin experience
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();

		wp_enqueue_style( '10up-admin', plugins_url( '/dist/css/admin.css', TENUP_EXPERIENCE_FILE ), array(), TENUP_EXPERIENCE_VERSION );

		if ( 0 === strpos( $screen->base, 'admin_page_10up-' ) ) {
			wp_enqueue_style( '10up-about', plugins_url( '/dist/css/tenup-pages.css', TENUP_EXPERIENCE_FILE ), array(), TENUP_EXPERIENCE_VERSION );
		}
	}

	/**
	 * Enqueue front end scripts
	 */
	public function enqueue_scripts() {
		// Only load css on front-end if the admin bar is showing.
		if ( is_admin_bar_showing() ) {
			wp_enqueue_style( '10up-admin', plugins_url( '/dist/css/admin.css', TENUP_EXPERIENCE_FILE ), array(), TENUP_EXPERIENCE_VERSION );
		}
	}

	/**
	 * Filter admin footer text "Thank you for creating..."
	 *
	 * @return string
	 */
	public function filter_admin_footer_text() {
		$new_text = sprintf( __( 'Thank you for creating with <a href="https://wordpress.org">WordPress</a> and <a href="https://10up.com">10up</a>.', 'tenup' ) );
		return $new_text;
	}
}
