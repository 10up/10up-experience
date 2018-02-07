<?php
namespace tenup;

/**
 * Register admin pages with output callbacks
 */
function register_admin_pages() {
	add_submenu_page( null, esc_html__( 'About 10up', 'tenup' ), esc_html__( 'About 10up', 'tenup' ), 'edit_posts', '10up-about', __NAMESPACE__ . '\main_screen' );
	add_submenu_page( null, esc_html__( 'Team 10up', 'tenup' ), esc_html__( 'Team 10up', 'tenup' ), 'edit_posts', '10up-team', __NAMESPACE__ . '\main_screen' );

	if ( defined( 'TENUP_SUPPORT' ) && 3 === TENUP_SUPPORT ) {
		add_submenu_page( null, esc_html__( 'Support', 'tenup' ), esc_html__( 'Support', 'tenup' ), 'edit_posts', '10up-support', __NAMESPACE__ . '\main_screen' );
	}
}
add_action( 'admin_menu', __NAMESPACE__ . '\register_admin_pages' );

/**
 * Output about screens
 */
function main_screen() {
	?>
	<div class="wrap about-wrap">

		<h1><?php esc_html_e( 'Welcome to 10up', 'tenup' ); ?></h1>

		<div class="about-text"><?php esc_html_e( 'We make web publishing easy. Maybe even fun.', 'tenup' ); ?></div>

		<a class="tenup-badge" href="http://10up.com" target="_blank"><span aria-label="<?php esc_html_e( 'Link to 10up.com', 'tenup' ); ?>">10up.com</span></a>

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
		<h2><?php esc_html_e( "We make web publishing and content management easy – maybe even fun.", 'tenup' ); ?></h2>

		<p><?php esc_html_e( "We make content management simple with our premiere web design &amp; development consulting services, by contributing to open platforms like WordPress, and by providing tools and products that make web publishing a cinch.", 'tenup' ); ?></p>

		<p><?php esc_html_e( "We’re a group of people built to solve problems; made to create; wired to delight. From beautiful pixels to beautiful code, we constantly improve the things around us, applying our passions to our clients’ projects and goals. Sometimes instead of resting, always instead of just getting it done.", 'tenup' ); ?></p>

		<img src="<?php echo esc_url( plugins_url( '/assets/img/10up-image-1.jpg', dirname( __FILE__ ) ) ); ?>" alt="">

		<h3><?php esc_html_e( "Building Without Boundaries", 'tenup' ); ?></h3>
		<p><?php esc_html_e( "The best talent isn’t found in a single zip code, and an international clientele requires a global perspective. From New York City to Salt Spring Island, our distributed model empowers us to bring in the best strategists, designers, and engineers, wherever they may be found. As of September 2014, 10up has over 80 full time staff; veterans of commercial agencies, universities, start ups, non profits, and international technology brands, our team has an uncommon breadth.", 'tenup' ); ?></p>

		<img src="<?php echo esc_url( plugins_url( '/assets/img/10up-image-2.jpg', dirname( __FILE__ ) ) ); ?>" alt="">

		<h3><?php esc_html_e( "Full Service Reach", 'tenup' ); ?></h3>

		<p><strong><?php esc_html_e( "Strategy:", 'tenup' ); ?></strong> <?php esc_html_e( "Should I build an app or a responsive website? Am I maximizing my ad revenue? Why don’t my visitors click “sign up”? How many 10uppers does it take to screw in a website? We don’t just build: we figure out the plan.", 'tenup' ); ?></p>

		<p><strong><?php esc_html_e( "Design:", 'tenup' ); ?></strong> <?php esc_html_e( "Inspiring design brings the functional and the beautiful; a delightful blend of art and engineering. We focus on the audience whimsy and relationship between brand and consumer, delivering design that works.", 'tenup' ); ?></p>

		<p><strong><?php esc_html_e( "Engineering:", 'tenup' ); ?></strong> <?php esc_html_e( "Please. Look under the hood. Our team of sought after international speakers provides expert code review for enterprise platforms like WordPress.com VIP. Because the best website you have is the one that’s up.", 'tenup' ); ?></p>

		<p class="center"><a href="<?php echo esc_url( admin_url( 'admin.php?page=10up-team' ) ); ?>" class="button button-large button-primary"><?php esc_html_e( "Learn more about 10up", 'tenup' ); ?></a></p>
	</div>
	<?php
}

/**
 * Output HTML for team screen
 */
function team_screen() {
	?>
	<div class="section section-team">

		<h2><?php esc_html_e( "Meet our executives", 'tenup' ); ?></h2>

		<div class="section-team-leadership">
			<a href="http://10up.com/about/#employee-jake-goldman" class="employee-link" target="_blank">
				<img src="<?php echo esc_url( plugins_url( '/assets/img/team/jake.jpg', dirname( __FILE__ ) ) ); ?>" alt="">
				<span>Jake&nbsp;Goldman<em><?php esc_html_e( "President &amp; Founder", 'tenup' ); ?></em></span>
			</a>

			<a href="http://10up.com/about/#employee-john-eckman" class="employee-link" target="_blank">
				<img src="<?php echo esc_url( plugins_url( '/assets/img/team/john.jpg', dirname( __FILE__ ) ) ); ?>" alt="">
				<span>John&nbsp;Eckman<em><?php esc_html_e( "Chief Executive Officer", 'tenup' ); ?></em></span>
			</a>

			<a href="http://10up.com/about/#employee-jess-jurick" class="employee-link" target="_blank">
				<img src="<?php echo esc_url( plugins_url( '/assets/img/team/jess.jpg', dirname( __FILE__ ) ) ); ?>" alt="">
				<span>Jess&nbsp;Jurick<em><?php esc_html_e( "Vice President, Consulting Services", 'tenup' ); ?></em></span>
			</a>

			<a href="http://10up.com/about/#employee-vasken-hauri" class="employee-link" target="_blank">
				<img src="<?php echo esc_url( plugins_url( '/assets/img/team/vasken.jpg', dirname( __FILE__ ) ) ); ?>" alt="">
				<span>Vasken&nbsp;Hauri<em><?php esc_html_e( "Vice President, Engineering", 'tenup' ); ?></em></span>
			</a>
		</div>

		<p><?php esc_html_e( "Influencing communities around the world, our team leads meetups, speaks at local events, and visits clients wherever they may be. A modest studio in Portland, Oregon hosts speakers, out of town guests, and the occasional workshop.", 'tenup' ); ?></p>

		<p><?php esc_html_e( "Independence from traditional “brick and mortar” offices, freedom from commutes, and flexible schedules across nearly a dozen time zones means our team works when and where they’re most inspired, available when our clients need them.", 'tenup' ); ?></p>

		<a href="http://10up.com/about/" class="section-team-header" target="_blank">
			<h2><?php esc_html_e( "Meet the rest of our team", 'tenup' ); ?></h2>
		</a>
	</div>
	<?php
}
