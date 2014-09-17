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
	?><div class="wrap about-wrap">

		<h1>About 10up</h1>

		<div class="about-text">We make web publishing easy. Maybe even fun.</div>

		<div class="tenup-badge"></div>

		<h2 class="nav-tab-wrapper">
			<a href="about.php" class="nav-tab nav-tab-active">About</a>
			<a href="credits.php" class="nav-tab">Team</a>
		</h2>

		<div class="changelog">
			<div class="about-overview">
				<embed src="//v.wordpress.com/bUdzKMro" type="application/x-shockwave-flash" width="640" height="360" allowscriptaccess="always" allowfullscreen="true" wmode="transparent">
			</div>

			<hr>

			<div class="feature-section col two-col">
				<div class="col-1">
					<h3>Manage your media with style</h3>
					<p>Explore your uploads in a beautiful, endless grid. A new details preview makes viewing and editing any amount of media in sequence a snap.</p>
				</div>
				<div class="col-2 last-feature">
					<img src="//s.w.org/images/core/4.0/media.jpg">
				</div>
			</div>

			<hr>

			<div class="feature-section col two-col">
				<div class="col-1">
					<div class="about-video about-video-embed">
						<div style="width: 500px; height: 352px; " class="wp-video"><!--[if lt IE 9]><script>document.createElement('video');</script><![endif]-->
							<div id="mep_0" class="mejs-container svg wp-video-shortcode mejs-video" style="width: 463px; height: 325px;"><div class="mejs-inner"><div class="mejs-mediaelement"><video class="wp-video-shortcode" id="video-0-1" width="463" height="325" loop="1" autoplay="1" preload="metadata" src="//s.w.org/images/core/4.0/embed.mp4?_=1" style="width: 100%; height: 100%;"><source type="video/mp4" src="//s.w.org/images/core/4.0/embed.mp4?_=1"><source type="video/webm" src="//s.w.org/images/core/4.0/embed.webm?_=1"><source type="video/ogg" src="//s.w.org/images/core/4.0/embed.ogv?_=1"><a href="//s.w.org/images/core/4.0/embed.mp4">//s.w.org/images/core/4.0/embed.mp4</a></video></div><div class="mejs-layers"><div class="mejs-poster mejs-layer" style="display: none; width: 100%; height: 100%;"></div><div class="mejs-overlay mejs-layer" style="width: 100%; height: 100%; display: none;"><div class="mejs-overlay-loading"><span></span></div></div><div class="mejs-overlay mejs-layer" style="display: none; width: 100%; height: 100%;"><div class="mejs-overlay-error"></div></div><div class="mejs-overlay mejs-layer mejs-overlay-play" style="width: 100%; height: 296px; display: none;"><div class="mejs-overlay-button" style="margin-top: -35px;"></div></div></div><div class="mejs-controls" style="display: block; visibility: hidden;"><div class="mejs-button mejs-playpause-button mejs-pause"><button type="button" aria-controls="mep_0" title="Play/Pause" aria-label="Play/Pause"></button></div><div class="mejs-time mejs-currenttime-container"><span class="mejs-currenttime">00:12</span></div><div class="mejs-time-rail" style="width: 313px;"><span class="mejs-time-total" style="width: 303px;"><span class="mejs-time-buffering" style="display: none;"></span><span class="mejs-time-loaded" style="width: 303px;"></span><span class="mejs-time-current" style="width: 279px;"></span><span class="mejs-time-handle" style="left: 272px;"></span><span class="mejs-time-float"><span class="mejs-time-float-current">00:00</span><span class="mejs-time-float-corner"></span></span></span></div><div class="mejs-time mejs-duration-container"><span class="mejs-duration">00:13</span></div><div class="mejs-button mejs-volume-button mejs-mute"><button type="button" aria-controls="mep_0" title="Mute Toggle" aria-label="Mute Toggle"></button><div class="mejs-volume-slider" style="display: none;"><div class="mejs-volume-total"></div><div class="mejs-volume-current" style="height: 80px; top: 28px;"></div><div class="mejs-volume-handle" style="top: 25px;"></div></div></div><div class="mejs-button mejs-fullscreen-button"><button type="button" aria-controls="mep_0" title="Fullscreen" aria-label="Fullscreen"></button></div></div><div class="mejs-clear"></div></div></div></div>			</div>
				</div>
				<div class="col-2 last-feature">
					<h3>Working with embeds has never been easier</h3>
					<p>Paste in a YouTube URL on a new line, and watch it magically become an embedded video. Now try it with a tweet. Oh yeah — embedding has become a visual experience. The editor shows a true preview of your embedded content, saving you time and giving you confidence.</p>
					<p>We’ve expanded the services supported by default, too — you can embed videos from CollegeHumor, playlists from YouTube, and talks from TED. <a href="http://codex.wordpress.org/Embeds">Check out all of the embeds</a> that WordPress supports.</p>
				</div>
			</div>

			<hr>

			<div class="feature-section col two-col">
				<div class="col-1">
					<h3>Focus on your content</h3>
					<p>Writing and editing is smoother and more immersive with an editor that expands to fit your content as you write, and keeps the formatting tools available at all times.</p>
				</div>
				<div class="col-2 last-feature">
					<div class="about-video about-video-focus">
						<div style="width: 500px; height: 281px; " class="wp-video"><div id="mep_1" class="mejs-container svg wp-video-shortcode mejs-video" style="width: 463px; height: 260px;"><div class="mejs-inner"><div class="mejs-mediaelement"><video class="wp-video-shortcode" id="video-0-2" width="463" height="260" loop="1" autoplay="1" preload="metadata" src="//s.w.org/images/core/4.0/focus.mp4?_=2" style="width: 100%; height: 100%;"><source type="video/mp4" src="//s.w.org/images/core/4.0/focus.mp4?_=2"><source type="video/webm" src="//s.w.org/images/core/4.0/focus.webm?_=2"><source type="video/ogg" src="//s.w.org/images/core/4.0/focus.ogv?_=2"><a href="//s.w.org/images/core/4.0/focus.mp4">//s.w.org/images/core/4.0/focus.mp4</a></video></div><div class="mejs-layers"><div class="mejs-poster mejs-layer" style="display: none; width: 100%; height: 100%;"></div><div class="mejs-overlay mejs-layer" style="width: 100%; height: 100%; display: none;"><div class="mejs-overlay-loading"><span></span></div></div><div class="mejs-overlay mejs-layer" style="display: none; width: 100%; height: 100%;"><div class="mejs-overlay-error"></div></div><div class="mejs-overlay mejs-layer mejs-overlay-play" style="width: 100%; height: 230px; display: none;"><div class="mejs-overlay-button" style="margin-top: -35px;"></div></div></div><div class="mejs-controls" style="display: block; visibility: hidden;"><div class="mejs-button mejs-playpause-button mejs-pause"><button type="button" aria-controls="mep_1" title="Play/Pause" aria-label="Play/Pause"></button></div><div class="mejs-time mejs-currenttime-container"><span class="mejs-currenttime">00:01</span></div><div class="mejs-time-rail" style="width: 313px;"><span class="mejs-time-total" style="width: 303px;"><span class="mejs-time-buffering" style="display: none;"></span><span class="mejs-time-loaded" style="width: 303px;"></span><span class="mejs-time-current" style="width: 29px;"></span><span class="mejs-time-handle" style="left: 22px;"></span><span class="mejs-time-float"><span class="mejs-time-float-current">00:00</span><span class="mejs-time-float-corner"></span></span></span></div><div class="mejs-time mejs-duration-container"><span class="mejs-duration">00:10</span></div><div class="mejs-button mejs-volume-button mejs-mute"><button type="button" aria-controls="mep_1" title="Mute Toggle" aria-label="Mute Toggle"></button><div class="mejs-volume-slider" style="display: none;"><div class="mejs-volume-total"></div><div class="mejs-volume-current" style="height: 80px; top: 28px;"></div><div class="mejs-volume-handle" style="top: 25px;"></div></div></div><div class="mejs-button mejs-fullscreen-button"><button type="button" aria-controls="mep_1" title="Fullscreen" aria-label="Fullscreen"></button></div></div><div class="mejs-clear"></div></div></div></div>			</div>
				</div>
			</div>

			<hr>

			<div class="feature-section col two-col">
				<div class="col-1">
					<img src="//s.w.org/images/core/4.0/plugins.png">
				</div>
				<div class="col-2 last-feature">
					<h3 class="higher">Finding the right plugin</h3>
					<p>There are more than 30,000 free and open source plugins in the WordPress plugin directory. WordPress 4.0 makes it easier to find the right one for your needs, with new metrics, improved search, and a more visual browsing experience.</p>
					<a href="http://local.wordpress-trunk.dev/wp-admin/plugin-install.php" class="button button-large button-primary">Browse plugins</a>
				</div>
			</div>
		</div>

		<hr>

		<div class="changelog under-the-hood">
			<h3>Under the Hood</h3>

			<div class="feature-section col three-col">
				<div>
					<h4>Customizer API</h4>
					<p>Contexts, panels, and a wider array of controls are now supported in the customizer.</p>
				</div>
				<div>
					<h4>Query Ordering</h4>
					<p>Developers have more flexibility creating <code>ORDER&nbsp;BY</code> clauses through <code>WP_Query</code>.</p>
				</div>
				<div class="last-feature">
					<h4>External Libraries</h4>
					<p>Updated libraries: TinyMCE&nbsp;4.1.3, jQuery&nbsp;1.11.1, MediaElement&nbsp;2.15.</p>
				</div>
			</div>

			<hr>

			<div class="return-to-dashboard">
				<a href="http://local.wordpress-trunk.dev/wp-admin/">Go to Dashboard → Home</a>
			</div>

		</div>

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
			<?php printf( __( "We recommend you only install plugins from the <a href=''%s'>10up Suggested</a> tab.", 'tenup' ), admin_url( 'network/plugin-install.php?tab=tenup' ) ); ?>
		</p>
	</div>
	<?php
}
add_action( 'install_plugins_pre_featured', 'tenup\plugin_install_warning' );
add_action( 'install_plugins_pre_popular', 'tenup\plugin_install_warning' );
add_action( 'install_plugins_pre_favorites', 'tenup\plugin_install_warning' );
add_action( 'install_plugins_pre_beta', 'tenup\plugin_install_warning' );
add_action( 'install_plugins_pre_search', 'tenup\plugin_install_warning' );