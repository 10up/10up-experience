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
		// Add "About WordPress" link
		$wp_admin_bar->add_menu( array(
			'id' => '10up',
			'title' => '<span class="10up-icon">10up</span>',
			'meta' => array(
				'title' => '10up',
			),
		) );

		$wp_admin_bar->add_menu( array(
			'id' => '10up-about',
			'parent' => '10up',
			'title' => 'About',
			'href' => self_admin_url( 'admin.php?page=10up-about' ),
			'meta' => array(
				'title' => 'About 10up',
			),
		) );
	}

}

add_action( 'admin_bar_menu', 'tenup\add_about_menu', 11 );

function enqueue_scripts() {
	if ( ! empty( $_GET['page'] ) && '10up-about' === $_GET['page'] ) {
		wp_enqueue_style( '10up-about', content_url( 'mu-plugins/10up-experience/assets/css/admin.css' ) );
	}
}
add_action( 'admin_enqueue_scripts', 'tenup\enqueue_scripts' );

/**
 * Output about screen
 */
function about_screen() {
	?>
	<div class="wrap">

		<div class="quote">10up is a full-service digital agency specializing in building amazing digital experiences that make publishing simple and fun.</div>

		<div class="tenup-company-photo">
			<img src="<?php echo esc_url( content_url( 'mu-plugins/10up-experience/assets/img/2014-company-photo.png' ) ); ?>">
		</div>

		<p>With 60+ full-time employees, team 10up is composed of strategists, designers, developers, and systems specialists united in their mission to create dynamic web solutions that bring brands to life in the digital space. From intuitive user experiences to stunning designs to social media integration, 10up is uniquely qualified to build websites and plugins that look great, function flawlessly, and engage your target audiences, all while delivering an exceptional content management experience for your site administrators and publishers.</p>

		<p>10up Founder and President Jake Goldman is a widely-recognized leader in the development and support of websites powered by WordPress. From speaking at conferences to sharing his expertise in media, to advising both enterprise clients as well as non-profit organizations with cost-conscious web projects, Jake is a thought leader who also practices the craft of web development on a regular basis.</p>

		<h1>What We Do</h1>
		<ul>
			<li><strong>Strategy</strong> - Project planning, branding, and growth strategies to implement big ideas.</li>
			<li><strong>Design</strong> - Full range of services: IA, UI/UX, and our unique in-browser design process.</li>
			<li><strong>Engineering</strong> - World-class WordPress theme, plugin, and systems build & support.</li>
		</ul>

		<h1>What We Believe</h1>

		<div class="helen-photo">
			<img src="<?php echo esc_url( content_url( 'mu-plugins/10up-experience/assets/img/helen.png' ) ); ?>">
		</div>

		<p><strong>Community Involvement.</strong> All members of team 10up are active in WordPress conferences as speakers, organizers, and attendees. Combined, our team has developed over 30 highly rated WordPress plugins, including a handful of WordPress.com VIP-approved plugins.</p>

		<p>Several members of team 10up are core contributors or project leaders in the WordPress community. 10up Director of Platform Helen Hou-Sandí, Director of Platform Experience at 10up and release lead for the 4.0 version of WordPress, coming later in 2014.</p>

		<p>More than 40% of team 10up contribute core WordPress code; more than 70% are involved in planning, speaking at, and attending conferences and local technology meetups. Experience Helen Hou-Sandí is a major contributor and the release lead for WordPress 4.0 due later this summer; Nearly 75% of all staff also contribute to WordPress in various ways.</p>

		<p><strong>Collaborative Relationships.</strong> At 10up, we work collaboratively with our clients to build solutions aligned with their unique goals. There is no single blueprint or process that works for every client or project, so we approach every project with a fresh perspective and a commitment to discovering the best solution.</p>

		<p><strong>Trusted Partnerships.</strong> While 10up is a full-service agency, we love partnering with like-minded premium agencies and in-house teams including content strategists, design consultancies, marketing and public relations firms, and even other engineering agencies. Our previous and ongoing collaborators include Upstatement, Filament Group, Global Moxie, Edelman, and in-house teams at Aol, Time Inc., Conde Nast, and many others.</p>

	</div>
	<?php
}

/**
 * Register admin pages with output callbacks
 */
function register_admin_pages() {
	add_submenu_page( null, 'About 10up', 'About 10up', 'manage_options', '10up-about', 'tenup\about_screen' );
}
add_action('admin_menu', 'tenup\register_admin_pages');