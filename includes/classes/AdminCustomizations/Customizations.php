<?php
/**
 * Admin customizations
 *
 * @package  10up-experience
 */

namespace TenUpExperience\AdminCustomizations;

use TenUpExperience\API\API;
use TenUpExperience\Authentication\Passwords;
use TenUpExperience\Comments\Comments;
use TenUpExperience\Gutenberg\Gutenberg;
use TenUpExperience\Singleton;
use TenUpExperience\SSO\SSO;
use TenUpExperience\SupportMonitor\Monitor;

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
		add_action( 'network_admin_menu', [ $this, 'register_admin_pages' ] );
		add_filter( 'admin_title', [ $this, 'admin_title_fix' ], 10, 1 );
	}

	/**
	 * Register admin pages with output callbacks
	 */
	public function register_admin_pages() {
		add_submenu_page( 'admin.php', esc_html__( 'About Fueled', 'tenup' ), esc_html__( 'About Fueled', 'tenup' ), 'edit_posts', '10up-about', [ $this, 'about_screen' ] );

		if ( ! is_network_admin() ) {
			add_submenu_page( 'admin.php', esc_html__( 'Fueled Experience Plugin', 'tenup' ), esc_html__( 'Fueled Experience Plugin', 'tenup' ), 'edit_posts', '10up-experience', [ $this, 'experience_screen' ] );
		}
	}

	/**
	 * Ensure our admin pages get a proper title.
	 *
	 * Because of the empty page parent, the title doesn't get output as expected.
	 *
	 * @param  string $admin_title The page title, with extra context added.
	 *
	 * @return string              The altered page title.
	 */
	public function admin_title_fix( $admin_title ) {
		$screen = get_current_screen();

		wp_enqueue_style( '10up-admin', plugins_url( '/dist/css/admin.css', TENUP_EXPERIENCE_FILE ), array(), TENUP_EXPERIENCE_VERSION );

		if ( 0 !== strpos( $screen->base, 'admin_page_10up-' ) ) {
			return $admin_title;
		}

		$page_titles = array(
			'admin_page_10up-about'      => esc_html__( 'About Fueled', 'tenup' ),
			'admin_page_10up-experience' => esc_html__( 'Fueled Experience Plugin', 'tenup' ),
		);

		if ( isset( $page_titles[ $screen->base ] ) && false === strpos( $admin_title, $page_titles[ $screen->base ] ) ) {
			$admin_title = $page_titles[ $screen->base ] . $admin_title;
		}

		return $admin_title;
	}

	/**
	 * Output the About Fueled screen.
	 */
	public function about_screen() {
		?>
		<div class="wrap about-wrap about-fueled-wrap full-width-layout">
			<section class="about-hero" aria-labelledby="about-fueled-heading">
				<div class="about-hero__content">
					<h1 id="about-fueled-heading" class="fueled-page-title fueled-page-title--inverse">
						<span><?php esc_html_e( 'About', 'tenup' ); ?></span>
						<img class="fueled-page-title__logo" src="<?php echo esc_url( plugins_url( '/dist/img/fueled-logo.svg', TENUP_EXPERIENCE_FILE ) ); ?>" alt="<?php esc_attr_e( 'Fueled', 'tenup' ); ?>">
					</h1>

					<h2 class="about-hero__headline"><?php esc_html_e( 'Digital done right.', 'tenup' ); ?></h2>

					<p class="about-hero__text">
						<?php
						echo wp_kses_post(
							sprintf(
								// translators: %s: Fueled and 10up rebrand announcement URL.
								__( 'Fueled (<a href="%s" target="_blank" rel="noopener noreferrer">previously 10up</a>) is a global digital agency that helps ambitious organizations build digital experiences that win users over and move their businesses forward.', 'tenup' ),
								esc_url( 'https://10up.com/blog/2025/new-fueled-brand-10up-becomes-wordpress-practice/' )
							)
						);
						?>
					</p>

					<p class="about-actions about-hero__actions">
						<a href="https://fueled.com" class="button button-primary" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Visit Fueled', 'tenup' ); ?></a>
						<a href="https://fueled.com/wordpress/" class="button" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Explore our WordPress work', 'tenup' ); ?></a>
					</p>
				</div>
			</section>

			<section class="about-capabilities" aria-labelledby="about-capabilities-heading">
				<div class="about-capabilities__copy">
					<p class="about-eyebrow"><?php esc_html_e( 'One team, end to end', 'tenup' ); ?></p>
					<h2 id="about-capabilities-heading"><?php esc_html_e( 'From strategy through production and growth. Under one virtual roof.', 'tenup' ); ?></h2>
					<p><?php esc_html_e( 'We bring strategy, design, engineering, growth, and AI together with standout craftsmanship to create digital products and platforms built for impact.', 'tenup' ); ?></p>
				</div>

				<ol class="about-capability-list" aria-label="<?php esc_attr_e( 'Fueled capabilities', 'tenup' ); ?>">
					<li><span>01</span><?php esc_html_e( 'Strategy', 'tenup' ); ?></li>
					<li><span>02</span><?php esc_html_e( 'Design', 'tenup' ); ?></li>
					<li><span>03</span><?php esc_html_e( 'Build', 'tenup' ); ?></li>
					<li><span>04</span><?php esc_html_e( 'Grow', 'tenup' ); ?></li>
					<li><span>05</span><?php esc_html_e( 'AI', 'tenup' ); ?></li>
				</ol>
			</section>

			<div class="about-proof-grid">
				<section class="about-proof-card about-proof-card--violet" aria-labelledby="about-team-heading">
					<p class="about-eyebrow"><?php esc_html_e( 'Our team', 'tenup' ); ?></p>
					<h2 id="about-team-heading"><?php esc_html_e( '300+ experts', 'tenup' ); ?></h2>
					<p><?php esc_html_e( 'A globally distributed team bringing strategy, design, engineering, growth, and AI together.', 'tenup' ); ?></p>
				</section>

				<section class="about-proof-card about-proof-card--dark" aria-labelledby="about-enterprise-heading">
					<p class="about-eyebrow"><?php esc_html_e( 'Platform expertise', 'tenup' ); ?></p>
					<h2 id="about-enterprise-heading"><?php esc_html_e( 'Enterprise-grade WordPress', 'tenup' ); ?></h2>
					<p><?php esc_html_e( 'Deep architecture and multisite expertise for component-based builds, editorial workflows, and hosting integrations.', 'tenup' ); ?></p>
				</section>

				<section class="about-proof-card about-proof-card--clients" aria-labelledby="about-clients-heading">
					<div class="about-proof-card__content">
						<p class="about-eyebrow"><?php esc_html_e( 'Battle-tested experience', 'tenup' ); ?></p>
						<h2 id="about-clients-heading"><?php esc_html_e( 'Proven with the world’s most demanding brands.', 'tenup' ); ?></h2>
						<p><?php esc_html_e( 'Fueled teams have partnered with category-defining brands, global publishers, and public institutions to solve complex digital challenges at scale.', 'tenup' ); ?></p>
					</div>

					<div class="about-client-logos" role="img" aria-label="<?php esc_attr_e( 'Selected clients, including Google, Apple, The Wall Street Journal, Campbell’s, Under Armour, The White House, Clinique, Salesforce, and Hilton.', 'tenup' ); ?>">
						<?php for ( $logo_index = 0; $logo_index < 18; $logo_index++ ) : ?>
							<span aria-hidden="true"></span>
						<?php endfor; ?>
					</div>
				</section>

				<section class="about-proof-card about-proof-card--open" aria-labelledby="about-open-heading">
					<div class="about-proof-card__wordpress-mark" aria-hidden="true">
						<img src="<?php echo esc_url( admin_url( 'images/wordpress-logo.svg' ) ); ?>" alt="">
					</div>

					<div class="about-proof-card__content">
						<p class="about-eyebrow"><?php esc_html_e( 'We don’t just make WordPress sites.', 'tenup' ); ?></p>
						<h2 id="about-open-heading"><?php esc_html_e( 'We make WordPress.', 'tenup' ); ?></h2>

						<p>
							<?php
								echo wp_kses_post(
									sprintf(
										// translators: %1$s, %2$s, and %3$s are links to Fueled WordPress products.
										__( 'Our team helps lead major WordPress releases and the official WordPress Core AI team, contributing code, tools, and ideas back to the project. We also build open-source products used across the ecosystem, including <a href="%1$s" target="_blank" rel="noopener noreferrer">Distributor</a>, <a href="%2$s" target="_blank" rel="noopener noreferrer">ClassifAI</a>, and <a href="%3$s" target="_blank" rel="noopener noreferrer">ElasticPress</a>.', 'tenup' ),
										esc_url( 'https://distributorplugin.com/' ),
										esc_url( 'https://classifaiplugin.com/' ),
										esc_url( 'https://www.elasticpress.io/' )
									)
								);
							?>
						</p>
					</div>
				</section>
			</div>

			<p class="about-actions about-actions--footer">
				<a href="https://fueled.com" class="button button-primary" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Visit Fueled', 'tenup' ); ?></a>
				<a href="https://fueled.com/contact/" class="button" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Contact Fueled', 'tenup' ); ?></a>
			</p>
		</div>
		<?php
	}

	/**
	 * Output the Fueled Experience Plugin screen.
	 */
	public function experience_screen() {
		$configuration = $this->get_experience_configuration();
		$features      = $this->get_experience_features();
		?>
		<div class="wrap about-wrap experience-wrap full-width-layout">
			<section class="experience-hero" aria-labelledby="experience-hero-heading">
				<div class="experience-hero__content">
					<div class="fueled-page-title fueled-page-title--inverse">
						<img class="fueled-page-title__logo" src="<?php echo esc_url( plugins_url( '/dist/img/fueled-logo.svg', TENUP_EXPERIENCE_FILE ) ); ?>" alt="<?php esc_attr_e( 'Fueled', 'tenup' ); ?>">
					</div>

					<h1 id="experience-hero-heading" class="experience-hero__headline"><?php esc_html_e( 'Experience Plugin', 'tenup' ); ?></h1>

					<p class="experience-hero__text">
						<?php
							echo wp_kses_post(
								sprintf(
									// translators: 1: Fueled website URL. 2: Fueled and 10up rebrand announcement URL.
									__( 'You’re seeing this page because the Fueled Experience Plugin is installed on this site. That usually means the site was built by or is supported by <a href="%1$s" target="_blank" rel="noopener noreferrer">Fueled</a> (<a href="%2$s" target="_blank" rel="noopener noreferrer">previously 10up</a>). It brings the safeguards, configuration choices, and support signals we use to help care for ambitious WordPress sites.', 'tenup' ),
									esc_url( 'https://fueled.com' ),
									esc_url( 'https://10up.com/blog/2025/new-fueled-brand-10up-becomes-wordpress-practice/' )
								)
							);
						?>
					</p>
				</div>

				<div class="experience-hero__visual" aria-hidden="true">
					<img src="<?php echo esc_url( plugins_url( '/dist/img/fueled-bolt.svg', TENUP_EXPERIENCE_FILE ) ); ?>" alt="">
				</div>
			</section>

			<section id="experience-configuration" class="experience-configuration" aria-labelledby="experience-configuration-heading">
				<div class="experience-section-heading">
					<h2 id="experience-configuration-heading"><?php esc_html_e( 'Configuration & features', 'tenup' ); ?></h2>
				</div>

				<p class="experience-group-label"><?php esc_html_e( 'Configured for this site', 'tenup' ); ?></p>
				<div class="experience-status-grid">
					<?php foreach ( $configuration as $item ) : ?>
						<article class="experience-status">
							<h3><?php echo esc_html( $item['label'] ); ?></h3>
							<p class="experience-status__value <?php echo esc_attr( $item['class'] ); ?>"><?php echo esc_html( $item['value'] ); ?></p>
							<p><?php echo esc_html( $item['description'] ); ?></p>
							<?php if ( ! empty( $item['settings_url'] ) || ! empty( $item['learn_more_url'] ) ) : ?>
								<p class="experience-status__action">
									<?php if ( ! empty( $item['settings_url'] ) ) : ?>
										<a href="<?php echo esc_url( $item['settings_url'] ); ?>"><?php esc_html_e( 'Manage setting', 'tenup' ); ?></a>
									<?php endif; ?>
									<?php if ( ! empty( $item['learn_more_url'] ) ) : ?>
										<a href="<?php echo esc_url( $item['learn_more_url'] ); ?>"><?php esc_html_e( 'Learn more', 'tenup' ); ?></a>
									<?php endif; ?>
								</p>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>

				<p class="experience-group-label experience-group-label--features"><?php esc_html_e( 'Additional protection', 'tenup' ); ?></p>
				<div class="experience-status-grid">
					<?php foreach ( $features as $feature ) : ?>
						<article class="experience-status">
							<h3><?php echo esc_html( $feature['label'] ); ?></h3>
							<p><?php echo esc_html( $feature['description'] ); ?></p>
						</article>
					<?php endforeach; ?>
				</div>
			</section>

			<section id="experience-monitor" class="experience-monitor" aria-labelledby="experience-monitor-heading">
				<div class="experience-monitor__intro">
					<p class="about-eyebrow"><?php esc_html_e( 'Proactive support', 'tenup' ); ?></p>
					<h2 id="experience-monitor-heading"><?php esc_html_e( 'Monitor helps us spot problems sooner.', 'tenup' ); ?></h2>
					<p><?php esc_html_e( 'When enabled, Support Monitor sends Fueled a daily technical snapshot. That gives our team a consistent view of site health and important administrative changes without collecting the content your organization publishes or receives.', 'tenup' ); ?></p>
					<p class="experience-monitor__action">
						<a href="https://fueled.com/blog/proactive-site-support-fueled10ups-monitor/" class="button button-primary" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'How Monitor supports client sites', 'tenup' ); ?></a>
					</p>
				</div>

				<div class="experience-monitor__details">
					<article>
						<p class="experience-monitor__label"><?php esc_html_e( 'Shared when enabled', 'tenup' ); ?></p>
						<h3><?php esc_html_e( 'Technical health signals', 'tenup' ); ?></h3>
						<p><?php esc_html_e( 'Site and hosting details, WordPress and PHP versions, plugin and theme status, selected system indicators, administrative events, and Fueled team accounts associated with the site.', 'tenup' ); ?></p>
					</article>

					<article>
						<p class="experience-monitor__label"><?php esc_html_e( 'Never shared', 'tenup' ); ?></p>
						<h3><?php esc_html_e( 'Content & customer data', 'tenup' ); ?></h3>
						<p><?php esc_html_e( 'No post or page content, media, form submissions, or names and email addresses for non-Fueled customer accounts are included in the daily report. Administrative events may reference a site-local user ID.', 'tenup' ); ?></p>
					</article>

					<p class="experience-monitor__note"><?php esc_html_e( 'Support monitoring is optional and is automatically disabled in local environments unless explicitly enabled in configuration.', 'tenup' ); ?></p>
				</div>
			</section>

			<section class="experience-source" aria-labelledby="experience-source-heading">
				<div>
					<p class="about-eyebrow"><?php esc_html_e( 'Built for transparency', 'tenup' ); ?></p>
					<h2 id="experience-source-heading"><?php esc_html_e( 'Want the technical details?', 'tenup' ); ?></h2>
					<p><?php esc_html_e( 'Review the source code and technical documentation on GitHub, or talk with Fueled about how this site is supported.', 'tenup' ); ?></p>
				</div>

				<p class="experience-actions">
					<a href="https://github.com/10up/10up-experience" class="button button-primary" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'View source on GitHub', 'tenup' ); ?></a>
					<a href="https://fueled.com/contact/" class="button" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Contact Fueled', 'tenup' ); ?></a>
				</p>
			</section>
		</div>
		<?php
	}

	/**
	 * Get current Experience Plugin configuration values.
	 *
	 * @return array[] Configuration items.
	 */
	private function get_experience_configuration() {
		$rest_setting         = get_option( 'tenup_restrict_rest_api', API::instance()->option_default );
		$rest_values          = array(
			'all'   => esc_html__( 'Authenticated users only', 'tenup' ),
			'users' => esc_html__( 'User endpoints protected', 'tenup' ),
			'none'  => esc_html__( 'Publicly accessible', 'tenup' ),
		);
		$network_settings_url = TENUP_EXPERIENCE_IS_NETWORK && current_user_can( 'manage_network_options' ) ? network_admin_url( 'settings.php' ) : '';
		$general_settings_url = ! TENUP_EXPERIENCE_IS_NETWORK && current_user_can( 'manage_options' ) ? admin_url( 'options-general.php' ) : '';
		$writing_settings_url = current_user_can( 'manage_options' ) ? admin_url( 'options-writing.php' ) : '';
		$reading_settings_url = current_user_can( 'manage_options' ) ? admin_url( 'options-reading.php' ) : '';
		$shared_settings_url  = TENUP_EXPERIENCE_IS_NETWORK ? $network_settings_url : $general_settings_url;
		$strong_passwords     = (bool) Passwords::instance()->require_strong_passwords();
		$sso_enabled          = ( ! defined( 'TENUPSSO_DISABLE' ) || ! TENUPSSO_DISABLE ) && 'yes' === SSO::instance()->get_setting();
		$monitor              = Monitor::instance();
		$monitor_enabled      = 'yes' === $monitor->get_setting( 'enable_support_monitor' );
		$comments_disabled    = Comments::instance()->comments_are_disabled();
		$classic_editor       = 1 === (int) get_option( Gutenberg::instance()->get_disable_gutenberg_key(), 0 );
		$post_passwords       = (bool) get_option( 'tenup_password_protect', 0 );

		if ( $monitor->is_local_environment() && ! $monitor_enabled ) {
			$monitor_value = esc_html__( 'Disabled locally', 'tenup' );
		} else {
			$monitor_value = $monitor_enabled ? esc_html__( 'Enabled', 'tenup' ) : esc_html__( 'Disabled', 'tenup' );
		}

		return array(
			array(
				'label'        => esc_html__( 'Environment', 'tenup' ),
				'value'        => ucfirst( wp_get_environment_type() ),
				'class'        => 'is-informational',
				'description'  => esc_html__( 'We identify this environment in the WordPress admin toolbar to help prevent accidental changes in the wrong place.', 'tenup' ),
				'settings_url' => '',
			),
			array(
				'label'        => esc_html__( 'REST API', 'tenup' ),
				'value'        => $rest_values[ $rest_setting ] ?? $rest_values['users'],
				'class'        => 'none' === $rest_setting ? 'is-muted' : 'is-active',
				'description'  => esc_html__( 'We add a simple setting to restrict REST API access, helping prevent unintended exposure of potentially sensitive information.', 'tenup' ),
				'settings_url' => $reading_settings_url,
			),
			array(
				'label'        => esc_html__( 'Strong passwords', 'tenup' ),
				'value'        => $strong_passwords ? esc_html__( 'Required', 'tenup' ) : esc_html__( 'Not required', 'tenup' ),
				'class'        => $strong_passwords ? 'is-active' : 'is-muted',
				'description'  => esc_html__( 'We can require stronger passwords and check them against breach data during sign-in or password changes.', 'tenup' ),
				'settings_url' => $shared_settings_url,
			),
			array(
				'label'        => esc_html__( 'Fueled SSO', 'tenup' ),
				'value'        => $sso_enabled ? esc_html__( 'Enabled', 'tenup' ) : esc_html__( 'Disabled', 'tenup' ),
				'class'        => $sso_enabled ? 'is-active' : 'is-muted',
				'description'  => esc_html__( 'Fueled SSO lets our experts jump in when needed and keeps out anyone who should no longer have the keys.', 'tenup' ),
				'settings_url' => $shared_settings_url,
			),
			array(
				'label'          => esc_html__( 'Support Monitor', 'tenup' ),
				'value'          => $monitor_value,
				'class'          => $monitor_enabled ? 'is-active' : 'is-muted',
				'description'    => esc_html__( 'Support Monitor shares a daily technical snapshot so Fueled can spot maintenance and support issues sooner.', 'tenup' ),
				'settings_url'   => $shared_settings_url,
				'learn_more_url' => '#experience-monitor',
			),
			array(
				'label'        => esc_html__( 'Traditional comments', 'tenup' ),
				'value'        => $comments_disabled ? esc_html__( 'Disabled', 'tenup' ) : esc_html__( 'Available', 'tenup' ),
				'class'        => $comments_disabled ? 'is-active' : 'is-informational',
				'description'  => esc_html__( 'When disabled, comment forms, displays, feeds, widgets, and admin controls are removed while Block Notes remain available.', 'tenup' ),
				'settings_url' => $shared_settings_url,
			),
			array(
				'label'        => esc_html__( 'Content editor', 'tenup' ),
				'value'        => $classic_editor ? esc_html__( 'Classic Editor', 'tenup' ) : esc_html__( 'Block Editor', 'tenup' ),
				'class'        => 'is-informational',
				'description'  => esc_html__( 'Sites that haven\'t upgraded to the Block Editor can retain the Classic Editor with one click.', 'tenup' ),
				'settings_url' => $writing_settings_url,
			),
			array(
				'label'        => esc_html__( 'Password-protected content', 'tenup' ),
				'value'        => $post_passwords ? esc_html__( 'Available', 'tenup' ) : esc_html__( 'Hidden by default', 'tenup' ),
				'class'        => $post_passwords ? 'is-informational' : 'is-active',
				'description'  => esc_html__( 'Controls whether editors can use WordPress post passwords, which are not compatible with common page-caching strategies.', 'tenup' ),
				'settings_url' => $writing_settings_url,
			),
		);
	}

	/**
	 * Get Experience Plugin features that do not have a site setting.
	 *
	 * @return array[] Feature descriptions.
	 */
	private function get_experience_features() {
		return array(
			array(
				'label'       => esc_html__( 'Safer access defaults', 'tenup' ),
				'description' => esc_html__( 'Checks for common high-risk usernames on public environments and disables dashboard file editing when WordPress has not already defined that behavior.', 'tenup' ),
			),
			array(
				'label'       => esc_html__( 'Clickjacking protection', 'tenup' ),
				'description' => esc_html__( 'Adds a same-origin framing policy by default, helping prevent the site from being invisibly embedded inside a malicious interface.', 'tenup' ),
			),
			array(
				'label'       => esc_html__( 'Plugin change guidance', 'tenup' ),
				'description' => esc_html__( 'Highlights Fueled-recommended plugins and adds clear warnings before changes that could affect performance, reliability, or support.', 'tenup' ),
			),
			array(
				'label'       => esc_html__( 'Update visibility and response', 'tenup' ),
				'description' => esc_html__( 'Keeps important update notices visible on managed sites and gives Fueled a consistent path to ship targeted safeguards through normal plugin releases.', 'tenup' ),
			),
		);
	}

	/**
	 * Get an internal Experience Plugin admin page URL.
	 *
	 * @param string $page Page slug.
	 * @return string Admin page URL.
	 */
	private function get_admin_page_url( $page ) {
		$base_url = is_network_admin() ? network_admin_url( 'admin.php' ) : admin_url( 'admin.php' );

		return add_query_arg( 'page', $page, $base_url );
	}


	/**
	 * Let's setup our agency menu in the toolbar
	 *
	 * @param object $wp_admin_bar Current WP Admin bar object
	 */
	public function add_about_menu( $wp_admin_bar ) {
		if ( is_user_logged_in() && current_user_can( 'edit_posts' ) ) {
			$about_url      = $this->get_admin_page_url( '10up-about' );
			$experience_url = admin_url( 'admin.php?page=10up-experience' );

			$wp_admin_bar->add_menu(
				array(
					'id'    => '10up',
					'title' => '<div class="tenup-icon"><span class="screen-reader-text">' . esc_html__( 'About Fueled', 'tenup' ) . '</span></div>',
					'href'  => $about_url,
					'meta'  => array(
						'title' => 'Fueled',
					),
				)
			);

			$wp_admin_bar->add_menu(
				array(
					'id'     => '10up-about',
					'parent' => '10up',
					'title'  => esc_html__( 'About Fueled', 'tenup' ),
					'href'   => esc_url( $about_url ),
					'meta'   => array(
						'title' => esc_html__( 'About Fueled', 'tenup' ),
					),
				)
			);

			$wp_admin_bar->add_menu(
				array(
					'id'     => '10up-experience',
					'parent' => '10up',
					'title'  => esc_html__( 'Experience Plugin', 'tenup' ),
					'href'   => esc_url( $experience_url ),
					'meta'   => array(
						'title' => esc_html__( 'Fueled Experience Plugin', 'tenup' ),
					),
				)
			);

			$wp_admin_bar->add_group(
				array(
					'id'     => '10up-external',
					'parent' => '10up',
					'meta'   => array(
						'class' => 'ab-sub-secondary',
					),
				)
			);

			$wp_admin_bar->add_menu(
				array(
					'id'     => '10up-fueled',
					'parent' => '10up-external',
					'title'  => esc_html__( 'Fueled.com', 'tenup' ),
					'href'   => 'https://fueled.com/',
					'meta'   => array(
						'target' => '_blank',
						'rel'    => 'noopener noreferrer',
						'title'  => esc_html__( 'Visit Fueled.com', 'tenup' ),
					),
				)
			);

			$wp_admin_bar->add_menu(
				array(
					'id'     => '10up-contact',
					'parent' => '10up-external',
					'title'  => esc_html__( 'Contact Fueled', 'tenup' ),
					'href'   => 'https://fueled.com/contact/',
					'meta'   => array(
						'target' => '_blank',
						'rel'    => 'noopener noreferrer',
						'title'  => esc_html__( 'Contact Fueled', 'tenup' ),
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
		$new_text = sprintf( __( 'Thank you for creating with <a href="https://wordpress.org">WordPress</a> and <a href="https://fueled.com">Fueled</a>.', 'tenup' ) );
		return $new_text;
	}
}
