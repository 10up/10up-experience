<?php
/**
 * React-powered Fueled Experience settings screen.
 *
 * Consolidates the plugin's settings — otherwise spread across the classic
 * General, Writing, and Reading screens — onto a single admin page rendered
 * with the @wordpress/boot script module (WordPress 6.9+). Settings are
 * saved through the core /wp/v2/settings REST endpoint using the entity
 * store, so the page gets the same "Review changes" save flow as the Site
 * Editor. On older WordPress versions the page is not registered and the
 * classic settings fields remain the only UI.
 *
 * @package  10up-experience
 */

namespace TenUpExperience\Settings;

use TenUpExperience\API\API;
use TenUpExperience\Singleton;
use TenUpExperience\SupportMonitor\Debug;
use TenUpExperience\SupportMonitor\Monitor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Settings screen class
 */
class SettingsScreen {

	use Singleton;

	/**
	 * Page slug used in the URL and as handle prefix.
	 */
	const PAGE_SLUG = 'fueled-experience';

	/**
	 * Directory name inside dist/modules/ for this page's built assets.
	 */
	const MODULE_DIR = 'settings';

	/**
	 * Setup module
	 *
	 * @since 1.19
	 */
	public function setup() {
		// Network installs manage these settings on the classic network
		// settings screen; the core /wp/v2/settings endpoint only exposes
		// site options, so the boot page is single-site only.
		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			return;
		}

		add_action( 'init', [ $this, 'register_settings' ] );

		if ( is_admin() && $this->is_boot_available() ) {
			add_action( 'admin_menu', [ $this, 'add_menu_page' ] );
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
		}
	}

	/**
	 * Whether WordPress ships the @wordpress/boot script module (6.9+).
	 *
	 * @return bool
	 */
	public function is_boot_available() {
		return function_exists( 'wp_register_script_module' ) && file_exists( $this->get_boot_asset_path() );
	}

	/**
	 * Path to core's boot script-module asset manifest.
	 *
	 * @return string
	 */
	private function get_boot_asset_path() {
		return ABSPATH . WPINC . '/js/dist/script-modules/boot/index.min.asset.php';
	}

	/**
	 * Register the plugin settings on the core REST settings endpoint.
	 *
	 * The classic screens re-register these same options on `admin_init`
	 * (which runs after `init` in the admin, and not at all during REST
	 * requests), so both registrations coexist: this one drives the REST
	 * endpoint, the classic ones drive options.php form saves.
	 *
	 * @return void
	 */
	public function register_settings() {
		if ( ! defined( 'TENUPSSO_DISABLE' ) || ! TENUPSSO_DISABLE ) {
			register_setting(
				'tenup_experience',
				'tenup_allow_sso',
				[
					'type'              => 'string',
					'label'             => __( 'Allow Fueled SSO', 'tenup' ),
					'default'           => 'yes',
					'sanitize_callback' => [ $this, 'sanitize_yes_no' ],
					'show_in_rest'      => true,
				]
			);
		}

		// Force Strong Passwords plugin takes over when active.
		if ( ! function_exists( 'slt_fsp_init' ) ) {
			register_setting(
				'tenup_experience',
				'tenup_require_strong_passwords',
				[
					'type'              => 'integer',
					'label'             => __( 'Require Strong Passwords', 'tenup' ),
					'default'           => 1,
					'sanitize_callback' => 'intval',
					'show_in_rest'      => true,
				]
			);
		}

		register_setting(
			'tenup_experience',
			'tenup_restrict_rest_api',
			[
				'type'              => 'string',
				'label'             => __( 'REST API Availability', 'tenup' ),
				'default'           => 'users',
				'sanitize_callback' => [ API::instance(), 'validate_restrict_rest_api_setting' ],
				'show_in_rest'      => true,
			]
		);

		register_setting(
			'tenup_experience',
			'tenup_disable_comments',
			[
				'type'              => 'string',
				'label'             => __( 'Disable Comments', 'tenup' ),
				'default'           => 'no',
				'sanitize_callback' => [ $this, 'sanitize_yes_no_default_no' ],
				'show_in_rest'      => true,
			]
		);

		register_setting(
			'tenup_experience',
			'tenup_disable_gutenberg',
			[
				'type'              => 'integer',
				'label'             => __( 'Use Classic Editor', 'tenup' ),
				'default'           => 0,
				'sanitize_callback' => 'intval',
				'show_in_rest'      => true,
			]
		);

		register_setting(
			'tenup_experience',
			'tenup_password_protect',
			[
				'type'              => 'integer',
				'label'             => __( 'Enable Password Protected Content', 'tenup' ),
				'default'           => 0,
				'sanitize_callback' => 'intval',
				'show_in_rest'      => true,
			]
		);

		register_setting(
			'tenup_experience',
			'tenup_support_monitor_settings',
			[
				'type'              => 'object',
				'label'             => __( 'Support Monitor', 'tenup' ),
				'default'           => [
					'enable_support_monitor' => 'no',
					'api_key'                => '',
					'server_url'             => '',
				],
				'sanitize_callback' => [ Monitor::instance(), 'sanitize_settings' ],
				'show_in_rest'      => [
					'schema' => [
						'type'                 => 'object',
						'properties'           => [
							'enable_support_monitor' => [ 'type' => 'string' ],
							'api_key'                => [ 'type' => 'string' ],
							'server_url'             => [ 'type' => 'string' ],
						],
						'additionalProperties' => false,
					],
				],
			]
		);
	}

	/**
	 * Sanitize a yes/no setting, defaulting to yes.
	 *
	 * @param string $value Submitted value.
	 * @return string
	 */
	public function sanitize_yes_no( $value ) {
		return in_array( $value, [ 'yes', 'no' ], true ) ? $value : 'yes';
	}

	/**
	 * Sanitize a yes/no setting, defaulting to no.
	 *
	 * @param string $value Submitted value.
	 * @return string
	 */
	public function sanitize_yes_no_default_no( $value ) {
		return in_array( $value, [ 'yes', 'no' ], true ) ? $value : 'no';
	}

	/**
	 * Register the settings page under the WordPress Settings menu.
	 *
	 * @return void
	 */
	public function add_menu_page() {
		add_options_page(
			__( 'Fueled Experience', 'tenup' ),
			__( 'Fueled Experience', 'tenup' ),
			'manage_options',
			self::PAGE_SLUG,
			[ $this, 'render_page' ]
		);
	}

	/**
	 * Render the boot mount point with critical inline CSS.
	 *
	 * @return void
	 */
	public function render_page() {
		$mount_id = self::PAGE_SLUG . '-app';
		?>
		<style>
			/* Critical styles to prevent layout shifts — inlined for immediate application */
			#wpwrap { background: var(--wpds-color-fg-content-neutral, #1e1e1e); overflow-y: auto; }
			body { background: #fff; }
			#wpcontent { padding-left: 0; }
			#wpbody-content { padding-bottom: 0; }
			#wpbody-content > div:not(.boot-layout-container):not(#screen-meta) { display: none; }
			#wpfooter { display: none; }
			.a11y-speak-region { left: -1px; top: -1px; }
			ul#adminmenu a.wp-has-current-submenu::after,
			ul#adminmenu > li.current > a.current::after { border-right-color: #fff; }
			@media (min-width: 782px) { #wpwrap { overflow-y: initial; } }
		</style>
		<div id="<?php echo esc_attr( $mount_id ); ?>" class="boot-layout-container"></div>
		<?php
	}

	/**
	 * Enqueue admin assets for the boot page.
	 *
	 * @param string $hook_suffix Current admin page hook suffix.
	 * @return void
	 */
	public function enqueue_admin_assets( $hook_suffix ) {
		if ( 'settings_page_' . self::PAGE_SLUG !== $hook_suffix ) {
			return;
		}

		$this->preload_rest_data();
		$this->register_prerequisites();
		$this->register_script_modules();
		$this->enqueue_styles();

		wp_enqueue_script( self::PAGE_SLUG . '-prerequisites' );
		wp_enqueue_script_module( self::PAGE_SLUG );
		wp_enqueue_style( self::PAGE_SLUG . '-prerequisites' );
	}

	/**
	 * Preload REST API data for the page.
	 *
	 * @return void
	 */
	private function preload_rest_data() {
		$preload_paths = [
			'/wp/v2/settings',
			[ '/wp/v2/settings', 'OPTIONS' ],
		];

		$preload_data = array_reduce( $preload_paths, 'rest_preload_api_request', [] );

		wp_add_inline_script(
			'wp-api-fetch',
			sprintf(
				'wp.apiFetch.use( wp.apiFetch.createPreloadingMiddleware( %s ) );',
				wp_json_encode( $preload_data )
			),
			'after'
		);
	}

	/**
	 * Configuration payload handed to the React app.
	 *
	 * Flags settings that are forced by constants or filters so the UI can
	 * hide them and explain why, mirroring the disabled states the classic
	 * settings fields render.
	 *
	 * @return array
	 */
	private function get_app_config() {
		$api = API::instance();

		return [
			'logoUrl'         => plugins_url( '/dist/img/fueled-logo.svg', TENUP_EXPERIENCE_FILE ),
			'sso'             => [
				'available' => ! defined( 'TENUPSSO_DISABLE' ) || ! TENUPSSO_DISABLE,
			],
			'strongPasswords' => [
				'available' => ! function_exists( 'slt_fsp_init' ),
			],
			'restApi'         => [
				'available' => (bool) ( has_filter( 'rest_authentication_errors', [ $api, 'restrict_rest_api' ] ) && has_filter( 'rest_endpoints', [ $api, 'restrict_user_endpoints' ] ) ),
			],
			'comments'        => [
				'locked' => defined( 'TENUP_DISABLE_COMMENTS' ) || has_filter( 'tenup_experience_disable_comments' ),
			],
			'monitor'         => [
				'enableLocked'       => defined( 'SUPPORT_MONITOR_ENABLE' ),
				'apiKeyLocked'       => defined( 'SUPPORT_MONITOR_API_KEY' ),
				'serverUrlVisible'   => Debug::instance()->is_debug_enabled() && ! defined( 'SUPPORT_MONITOR_SERVER_URL' ),
				'isLocalEnvironment' => Monitor::instance()->is_local_environment(),
			],
		];
	}

	/**
	 * Register the synthetic "prerequisites" script + style handles that
	 * bootstrap the React boot app.
	 *
	 * The settings page renders from an ES module (`@wordpress/boot`), but
	 * boot depends on a long list of classic scripts (`wp-components`,
	 * `wp-editor`, etc.) and stylesheets that are emitted by core via
	 * `wp_enqueue_script` / `wp_enqueue_style` — not the script-module
	 * loader. Script modules can't depend on classic scripts directly, so
	 * we register an empty-src "carrier" handle whose only job is to (a)
	 * pull in those classic deps via `dependencies`, (b) host the inline
	 * config payload, and (c) host the inline dynamic-import call that
	 * actually boots the app once those classic deps have executed. The
	 * matching empty-src style handle does the same for boot's CSS deps.
	 *
	 * @return void
	 */
	private function register_prerequisites() {
		$boot_asset = require $this->get_boot_asset_path();

		$modules_path  = TENUP_EXPERIENCE_DIR . '/dist/modules/' . self::MODULE_DIR . '/';
		$content_asset = $this->read_asset_file( $modules_path . 'content.asset.php' );

		$prerequisites_deps = array_values(
			array_unique(
				array_merge(
					$boot_asset['dependencies'],
					$content_asset['dependencies']
				)
			)
		);

		wp_register_script(
			self::PAGE_SLUG . '-prerequisites',
			'',
			$prerequisites_deps,
			$boot_asset['version'],
			true
		);

		wp_add_inline_script(
			self::PAGE_SLUG . '-prerequisites',
			sprintf(
				'window.tenupExperienceSettingsData = %s;',
				wp_json_encode( $this->get_app_config() )
			),
			'before'
		);

		$mount_id = self::PAGE_SLUG . '-app';
		$routes   = [
			[
				'path'           => '/',
				'content_module' => self::PAGE_SLUG . '-content',
				'route_module'   => self::PAGE_SLUG . '-route',
			],
		];

		// Defer the dynamic import until DOMContentLoaded. The inline script is
		// emitted as a classic <script> during parsing, which can win the race
		// against later-emitted classic deps like wp-theme/wp-components (which
		// register wp.theme.privateApis used by @wordpress/boot). Waiting for
		// DOMContentLoaded guarantees all parser-blocking classic scripts have
		// executed before boot is imported and evaluated.
		wp_add_inline_script(
			self::PAGE_SLUG . '-prerequisites',
			sprintf(
				'(function(){var i=function(){import("@wordpress/boot").then(function(m){m.initSinglePage({mountId:"%s",routes:%s});});};if(document.readyState==="loading"){document.addEventListener("DOMContentLoaded",i);}else{i();}})();',
				$mount_id,
				wp_json_encode( $routes, JSON_HEX_TAG | JSON_UNESCAPED_SLASHES )
			)
		);

		$style_deps = array_values(
			array_filter(
				$boot_asset['dependencies'],
				function ( $handle ) {
					return wp_style_is( $handle, 'registered' );
				}
			)
		);

		wp_register_style(
			self::PAGE_SLUG . '-prerequisites',
			false,
			$style_deps,
			$boot_asset['version']
		);
	}

	/**
	 * Register route, content, and loader script modules.
	 *
	 * @return void
	 */
	private function register_script_modules() {
		$modules_path = TENUP_EXPERIENCE_DIR . '/dist/modules/' . self::MODULE_DIR . '/';
		$modules_url  = plugins_url( '/dist/modules/' . self::MODULE_DIR . '/', TENUP_EXPERIENCE_FILE );

		$route_asset = $this->read_asset_file( $modules_path . 'route.asset.php' );
		wp_register_script_module(
			self::PAGE_SLUG . '-route',
			$modules_url . 'route.js',
			[],
			$route_asset['version']
		);

		$content_asset = $this->read_asset_file( $modules_path . 'content.asset.php' );
		wp_register_script_module(
			self::PAGE_SLUG . '-content',
			$modules_url . 'content.js',
			[],
			$content_asset['version']
		);

		wp_register_script_module(
			self::PAGE_SLUG,
			$modules_url . 'loader.js',
			[
				[
					'import' => 'static',
					'id'     => '@wordpress/boot',
				],
				[
					'import' => 'static',
					'id'     => self::PAGE_SLUG . '-route',
				],
				[
					'import' => 'dynamic',
					'id'     => self::PAGE_SLUG . '-content',
				],
			]
		);
	}

	/**
	 * Enqueue the bundled content CSS (page layout + DataForm styles).
	 *
	 * @return void
	 */
	private function enqueue_styles() {
		$modules_path = TENUP_EXPERIENCE_DIR . '/dist/modules/' . self::MODULE_DIR . '/';
		$css_path     = $modules_path . 'content.css';

		if ( file_exists( $css_path ) ) {
			$content_asset = $this->read_asset_file( $modules_path . 'content.asset.php' );
			wp_enqueue_style(
				self::PAGE_SLUG . '-content',
				plugins_url( '/dist/modules/' . self::MODULE_DIR . '/content.css', TENUP_EXPERIENCE_FILE ),
				[ 'wp-components' ],
				$content_asset['version']
			);
		}
	}

	/**
	 * Read a *.asset.php manifest emitted by the bundler.
	 *
	 * @param string $path Asset file path.
	 * @return array{dependencies: string[], version: string}
	 */
	private function read_asset_file( $path ) {
		if ( ! file_exists( $path ) ) {
			return [
				'dependencies' => [],
				'version'      => TENUP_EXPERIENCE_VERSION,
			];
		}

		$asset = require $path;

		return [
			'dependencies' => isset( $asset['dependencies'] ) ? $asset['dependencies'] : [],
			'version'      => isset( $asset['version'] ) ? $asset['version'] : TENUP_EXPERIENCE_VERSION,
		];
	}
}
