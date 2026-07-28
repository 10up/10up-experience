<?php
/**
 * Abstract base class for @wordpress/boot admin pages.
 *
 * Contains the common boot-wiring machinery (script-module registration,
 * classic-script "prerequisites" carrier, REST preloading, mount point with
 * critical CSS) so concrete pages only supply their slug, module directory,
 * hook suffix, and config payload. Pages render only when WordPress ships
 * the boot script module (6.9+); callers should fall back to their classic
 * markup otherwise.
 *
 * @package  10up-experience
 */

namespace TenUpExperience\BootPages;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * AbstractBootPage class
 */
abstract class AbstractBootPage {

	/**
	 * Page slug, used as the handle prefix for scripts, styles, and modules.
	 *
	 * @return string
	 */
	abstract protected function get_page_slug();

	/**
	 * Directory name inside dist/modules/ for this page's built assets.
	 *
	 * @return string
	 */
	abstract protected function get_module_dir();

	/**
	 * Admin hook suffix that identifies this page (gates asset enqueueing).
	 *
	 * @return string
	 */
	abstract protected function get_hook_suffix();

	/**
	 * REST paths to preload for the page. Empty for pages that don't read
	 * REST data.
	 *
	 * @return array
	 */
	protected function get_preload_paths() {
		return [];
	}

	/**
	 * Name of the window global holding the page's config payload, or empty
	 * string to skip the inline data script.
	 *
	 * @return string
	 */
	protected function get_config_var() {
		return '';
	}

	/**
	 * Configuration payload handed to the React app via the config var.
	 *
	 * @return array
	 */
	protected function get_app_config() {
		return [];
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
	protected function get_boot_asset_path() {
		return ABSPATH . WPINC . '/js/dist/script-modules/boot/index.min.asset.php';
	}

	/**
	 * Render the boot mount point with critical inline CSS.
	 *
	 * @return void
	 */
	public function render_mount() {
		$mount_id = $this->get_page_slug() . '-app';
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
		if ( $this->get_hook_suffix() !== $hook_suffix || ! $this->is_boot_available() ) {
			return;
		}

		$this->preload_rest_data();
		$this->register_prerequisites();
		$this->register_script_modules();
		$this->enqueue_styles();

		wp_enqueue_script( $this->get_page_slug() . '-prerequisites' );
		wp_enqueue_script_module( $this->get_page_slug() );
		wp_enqueue_style( $this->get_page_slug() . '-prerequisites' );
	}

	/**
	 * Preload REST API data for the page.
	 *
	 * @return void
	 */
	protected function preload_rest_data() {
		$preload_paths = $this->get_preload_paths();

		if ( empty( $preload_paths ) ) {
			return;
		}

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
	 * Register the synthetic "prerequisites" script + style handles that
	 * bootstrap the React boot app.
	 *
	 * The page renders from an ES module (`@wordpress/boot`), but boot
	 * depends on a long list of classic scripts (`wp-components`,
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
	protected function register_prerequisites() {
		$boot_asset = require $this->get_boot_asset_path();

		$slug          = $this->get_page_slug();
		$modules_path  = TENUP_EXPERIENCE_DIR . '/dist/modules/' . $this->get_module_dir() . '/';
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
			$slug . '-prerequisites',
			'',
			$prerequisites_deps,
			$boot_asset['version'],
			true
		);

		$config_var = $this->get_config_var();

		if ( $config_var ) {
			wp_add_inline_script(
				$slug . '-prerequisites',
				sprintf(
					'window.%s = %s;',
					$config_var,
					wp_json_encode( $this->get_app_config() )
				),
				'before'
			);
		}

		$mount_id = $slug . '-app';
		$routes   = [
			[
				'path'           => '/',
				'content_module' => $slug . '-content',
				'route_module'   => $slug . '-route',
			],
		];

		// Defer the dynamic import until DOMContentLoaded. The inline script is
		// emitted as a classic <script> during parsing, which can win the race
		// against later-emitted classic deps like wp-theme/wp-components (which
		// register wp.theme.privateApis used by @wordpress/boot). Waiting for
		// DOMContentLoaded guarantees all parser-blocking classic scripts have
		// executed before boot is imported and evaluated.
		wp_add_inline_script(
			$slug . '-prerequisites',
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
			$slug . '-prerequisites',
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
	protected function register_script_modules() {
		$slug         = $this->get_page_slug();
		$modules_path = TENUP_EXPERIENCE_DIR . '/dist/modules/' . $this->get_module_dir() . '/';
		$modules_url  = plugins_url( '/dist/modules/' . $this->get_module_dir() . '/', TENUP_EXPERIENCE_FILE );

		$route_asset = $this->read_asset_file( $modules_path . 'route.asset.php' );
		wp_register_script_module(
			$slug . '-route',
			$modules_url . 'route.js',
			[],
			$route_asset['version']
		);

		$content_asset = $this->read_asset_file( $modules_path . 'content.asset.php' );
		wp_register_script_module(
			$slug . '-content',
			$modules_url . 'content.js',
			[],
			$content_asset['version']
		);

		wp_register_script_module(
			$slug,
			$modules_url . 'loader.js',
			[
				[
					'import' => 'static',
					'id'     => '@wordpress/boot',
				],
				[
					'import' => 'static',
					'id'     => $slug . '-route',
				],
				[
					'import' => 'dynamic',
					'id'     => $slug . '-content',
				],
			]
		);
	}

	/**
	 * Enqueue the bundled content CSS (page layout + DataForm styles), when
	 * the module emitted one.
	 *
	 * @return void
	 */
	protected function enqueue_styles() {
		$modules_path = TENUP_EXPERIENCE_DIR . '/dist/modules/' . $this->get_module_dir() . '/';
		$css_path     = $modules_path . 'content.css';

		if ( file_exists( $css_path ) ) {
			$content_asset = $this->read_asset_file( $modules_path . 'content.asset.php' );
			wp_enqueue_style(
				$this->get_page_slug() . '-content',
				plugins_url( '/dist/modules/' . $this->get_module_dir() . '/content.css', TENUP_EXPERIENCE_FILE ),
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
	protected function read_asset_file( $path ) {
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
