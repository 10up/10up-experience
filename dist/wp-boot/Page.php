<?php
/**
 * Page class.
 *
 * @package TenUp\WpBoot\TenupExperience
 */

declare( strict_types = 1 );

namespace TenUp\WpBoot\TenupExperience;

/**
 * One `@wordpress/boot` admin page in one mode.
 *
 *   - 'full-page' takes over the screen: it intercepts `admin_init` for
 *     `?page=<slug>`, prints its own HTML document and boots with `init()`.
 *   - 'wp-admin' renders inside the regular admin chrome on
 *     `?page=<slug>-wp-admin` and boots with `initSinglePage()`.
 *
 * Both fire `<handle>_init` (register routes and menu items there) and filter
 * `<handle>_boot_dependencies`, the names `@wordpress/build` uses.
 *
 * Ported from `@wordpress/build`'s page.php.template (full-page) and
 * page-wp-admin.php.template (wp-admin), the original implementation; each
 * method notes the part it comes from. See the README's "Origin".
 *
 * @link https://github.com/WordPress/gutenberg/blob/trunk/packages/wp-build/templates/page.php.template
 * @link https://github.com/WordPress/gutenberg/blob/trunk/packages/wp-build/templates/page-wp-admin.php.template
 *
 * @phpstan-import-type PageConfig from Pages
 */
final class Page {

	/** Full-page takeover mode. */
	public const FULL_PAGE = 'full-page';

	/** wp-admin integrated mode. */
	public const WP_ADMIN = 'wp-admin';

	/**
	 * The site-entity request core-data makes on load, by the first WordPress
	 * version that asks for it. Its `_fields` must match the running
	 * core-data's exactly, same fields in the same order, or the preloaded
	 * response is never used.
	 *
	 * @var array<string, string>
	 */
	private const SITE_PRELOAD_PATHS = array(
		'7.2-alpha' => '/?_fields=description,generate_animated_image_subsizes,gmt_offset,home,image_max_bit_depth,image_sizes,image_size_threshold,image_strip_meta,name,site_icon,site_icon_url,site_logo,timezone_string,url,page_for_posts,page_on_front,show_on_front',
		'7.1-alpha' => '/?_fields=description,gmt_offset,home,image_max_bit_depth,image_sizes,image_size_threshold,image_strip_meta,name,site_icon,site_icon_url,site_logo,timezone_string,url,page_for_posts,page_on_front,show_on_front',
		'7.0-alpha' => '/?_fields=description,gmt_offset,home,image_sizes,image_size_threshold,image_output_formats,jpeg_interlaced,png_interlaced,gif_interlaced,name,site_icon,site_icon_url,site_logo,timezone_string,url,page_for_posts,page_on_front,show_on_front',
	);

	/**
	 * Script handle, Script Module id and hook prefix: `<slug>` or `<slug>-wp-admin`.
	 *
	 * @var string
	 */
	public readonly string $handle;

	/**
	 * Registered routes, handed to `@wordpress/boot`.
	 *
	 * @var list<array{path: string, content_module?: string, route_module?: string}>
	 */
	private array $routes = array();

	/**
	 * Registered menu items, handed to `@wordpress/boot` in full-page mode.
	 *
	 * @var list<array{id: string, label: string, to: string, parent?: string, parent_type?: string}>
	 */
	private array $menu_items = array();

	/**
	 * Create a page.
	 *
	 * @param Pages  $build  The build the page belongs to.
	 * @param array  $config The page, as the build wrote it.
	 * @param string $mode   Page::FULL_PAGE or Page::WP_ADMIN.
	 *
	 * @phpstan-param PageConfig $config
	 */
	public function __construct(
		private readonly Pages $build,
		private readonly array $config,
		private readonly string $mode,
	) {
		$this->handle = self::WP_ADMIN === $mode ? $config['slug'] . '-wp-admin' : $config['slug'];
	}

	/**
	 * Hook the page into WordPress.
	 *
	 * @param bool $owns_menu Whether this page registers the admin menu entry.
	 * @return void
	 */
	public function register_hooks( bool $owns_menu ): void {
		add_action( $this->handle . '_init', array( $this, 'register_route_modules' ) );

		if ( self::WP_ADMIN === $this->mode ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		} else {
			add_action( 'admin_init', array( $this, 'intercept_render' ) );
		}

		if ( $owns_menu ) {
			add_action( 'admin_menu', array( $this, 'register_menu' ) );
		}
	}

	/**
	 * Register a route.
	 *
	 * @param string      $path           Route path, e.g. '/types/$type/edit/$id'.
	 * @param string|null $content_module Script Module id of the route's content (stage/inspector).
	 * @param string|null $route_module   Script Module id of the route's lifecycle hooks.
	 * @return void
	 */
	public function register_route( string $path, ?string $content_module = null, ?string $route_module = null ): void {
		$route = array( 'path' => $path );

		if ( ! empty( $content_module ) ) {
			$route['content_module'] = $content_module;
		}
		if ( ! empty( $route_module ) ) {
			$route['route_module'] = $route_module;
		}

		$this->routes[] = $route;
	}

	/**
	 * Register a menu item.
	 *
	 * @param string $id          Menu item id.
	 * @param string $label       Display label.
	 * @param string $to          Route path to navigate to.
	 * @param string $parent_id   Parent menu item id.
	 * @param string $parent_type 'drilldown' or 'dropdown'. Full-page mode only.
	 * @return void
	 */
	public function register_menu_item( string $id, string $label, string $to, string $parent_id = '', string $parent_type = '' ): void {
		$menu_item = array(
			'id'    => $id,
			'label' => $label,
			'to'    => $to,
		);

		if ( ! empty( $parent_id ) ) {
			$menu_item['parent'] = $parent_id;
		}

		if ( self::FULL_PAGE === $this->mode && in_array( $parent_type, array( 'drilldown', 'dropdown' ), true ) ) {
			$menu_item['parent_type'] = $parent_type;
		}

		$this->menu_items[] = $menu_item;
	}

	/**
	 * The registered routes.
	 *
	 * @return list<array{path: string, content_module?: string, route_module?: string}>
	 */
	public function routes(): array {
		return $this->routes;
	}

	/**
	 * The registered menu items.
	 *
	 * @return list<array{id: string, label: string, to: string, parent?: string, parent_type?: string}>
	 */
	public function menu_items(): array {
		return $this->menu_items;
	}

	/**
	 * Register the Script Modules (and stylesheet) of every route the build
	 * produced, then register the routes with the page. Runs on
	 * `<handle>_init`.
	 *
	 * From routes-registration.php.template's `<prefix>_register_page_routes()`; the stylesheet is ours.
	 *
	 * Route modules are `<handle_prefix>/routes/<slug>/<route>/{content,route}`.
	 * Each is deregistered first, so a plugin can override a module core
	 * registered under the same id.
	 *
	 * @return void
	 */
	public function register_route_modules(): void {
		foreach ( $this->config['routes'] as $route ) {
			$content_module = null;
			$route_module   = null;
			$module_prefix  = $this->build->handle_prefix . '/routes/' . $this->config['slug'] . '/' . $route['name'];

			if ( $route['has_content'] ) {
				$asset = $this->asset( $route['dir'] . '/content.asset.php' );

				if ( null !== $asset ) {
					$content_module = $module_prefix . '/content';
					$this->register_module( $content_module, $route['dir'] . '/content.js', $asset );

					// The page stylesheet, when the entry imported any CSS.
					if ( file_exists( $this->build->path( $route['dir'] . '/content.css' ) ) ) {
						$style_handle = str_replace( '/', '-', $content_module );
						// After the carrier's styles (wp-components, wp-theme, …), so the page's own rules win ties.
						wp_register_style( $style_handle, $this->build->url( $route['dir'] . '/content.css' ), array( $this->carrier_handle() ), $asset['version'] ?? false );
						wp_enqueue_style( $style_handle );
					}
				}
			}

			if ( $route['has_route'] ) {
				$asset = $this->asset( $route['dir'] . '/route.asset.php' );

				if ( null !== $asset ) {
					$route_module = $module_prefix . '/route';
					$this->register_module( $route_module, $route['dir'] . '/route.js', $asset );
				}
			}

			$this->register_route( $route['path'], $content_module, $route_module );
		}
	}

	/**
	 * Preload the page's REST paths into `wp.apiFetch`: the page's
	 * `restPreload`, else what core's own pages preload.
	 *
	 * From both templates' `<prefix>_<slug>_preload_data()`; the filter is page-wp-admin.php.template's, here in both modes.
	 *
	 * @return void
	 */
	public function preload_data(): void {
		$paths = $this->config['rest_preload'] ?? array( self::site_preload_path(), array( '/wp/v2/settings', 'OPTIONS' ) );

		/**
		 * Filters the REST API paths preloaded for this page, the name
		 * `@wordpress/build` uses: `<prefix>_<slug>[_wp_admin]_preload_paths`.
		 *
		 * Each entry is a path (GET) or a `[ path, method ]` pair.
		 *
		 * @param array $paths Paths to preload.
		 */
		$paths = apply_filters( str_replace( '-', '_', $this->build->prefix . '_' . $this->handle ) . '_preload_paths', $paths );

		$preload_data = array();
		foreach ( $paths as $path ) {
			// Core also takes array( $path, $method ), though its docblock says string.
			$preload_data = rest_preload_api_request( $preload_data, $path );
		}

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
	 * The site-entity preload path for the running WordPress version.
	 *
	 * From the `_fields` path both templates hardcode, one per WordPress version.
	 *
	 * With the Gutenberg plugin active, core-data comes from the plugin and
	 * may ask for other fields; pages can set `restPreload` for that.
	 *
	 * @return string
	 */
	public static function site_preload_path(): string {
		$version = get_bloginfo( 'version' );

		foreach ( self::SITE_PRELOAD_PATHS as $since => $path ) {
			if ( version_compare( $version, $since, '>=' ) ) {
				return $path;
			}
		}

		return self::SITE_PRELOAD_PATHS['7.0-alpha'];
	}

	/**
	 * Boot a wp-admin page on its own screen. Runs on `admin_enqueue_scripts`.
	 *
	 * From page-wp-admin.php.template's `<prefix>_<slug>_enqueue_scripts()`.
	 *
	 * @return void
	 */
	public function enqueue_scripts(): void {
		if ( $this->is_current_page() ) {
			$this->enqueue_boot();
		}
	}

	/**
	 * Whether the current admin request is for this page.
	 *
	 * By default: `?page=<handle>`, or (wp-admin mode) a screen whose id is
	 * the page's slug. The `<handle>_is_current_page` filter lets a project
	 * serve the page from admin pages it registers itself, under menu slugs
	 * of its own: an existing screen's slug that bookmarks and links already
	 * point at, or several entries (one per post type, say) sharing one
	 * page. Point their menu callback at render_page().
	 *
	 * Full-page mode asks on `admin_init`, before the screen is set.
	 *
	 * @return bool
	 */
	public function is_current_page(): bool {
		$requested = isset( $_GET['page'] ) && is_string( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$is_ours   = $this->handle === $requested;

		if ( ! $is_ours && self::WP_ADMIN === $this->mode && function_exists( 'get_current_screen' ) ) {
			$screen  = get_current_screen();
			$is_ours = $screen && $this->config['slug'] === $screen->id;
		}

		/**
		 * Filters whether the current admin request is for this page,
		 * `<handle>_is_current_page`.
		 *
		 * @param bool   $is_ours   Whether the request is for this page.
		 * @param string $requested The sanitized `?page=` query arg, or ''.
		 */
		return (bool) apply_filters( $this->handle . '_is_current_page', $is_ours, $requested );
	}

	/**
	 * Render a full-page page early, before WordPress prints its own admin
	 * template. Runs on `admin_init`.
	 *
	 * From page.php.template's `<prefix>_<slug>_intercept_render()`.
	 *
	 * This bypasses the menu callback's own checks, and `admin_init` also
	 * fires for logged-out requests (admin-post.php), so it checks login and
	 * the page's capability itself.
	 *
	 * @return void
	 */
	public function intercept_render(): void {
		if ( ! $this->is_current_page() ) {
			return;
		}

		if ( ! is_user_logged_in() ) {
			auth_redirect();
		}

		if ( ! current_user_can( $this->config['capability'] ) ) {
			// Core's own string, so core's translation applies.
			wp_die( esc_html__( 'Sorry, you are not allowed to access this page.' ), 403 ); // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
		}

		$this->render_page();
	}

	/**
	 * The page's render callback: what the admin menu entry points at.
	 *
	 * Full-page mode prints a whole HTML document and exits.
	 *
	 * @return void
	 */
	public function render_page(): void {
		if ( self::WP_ADMIN === $this->mode ) {
			$this->render_wp_admin();
			return;
		}

		$this->render_document();
		exit;
	}

	/**
	 * Register the page's admin menu entry. Runs on `admin_menu`.
	 *
	 * No `parent` (or 'options-general.php') adds it under Settings, `false`
	 * or 'toplevel' adds a top-level entry, anything else is a submenu.
	 *
	 * @return void
	 */
	public function register_menu(): void {
		if ( ! isset( $this->config['menu'] ) ) {
			return;
		}

		$menu       = $this->config['menu'];
		$title      = ( $menu['title'] )();
		$capability = $menu['capability'] ?? $this->config['capability'];
		$parent     = $menu['parent'] ?? 'options-general.php';
		$callback   = array( $this, 'render_page' );

		if ( false === $parent || 'toplevel' === $parent ) {
			add_menu_page( $title, $title, $capability, $this->handle, $callback, $menu['icon'] ?? 'dashicons-admin-generic', $menu['position'] ?? null );
		} elseif ( 'options-general.php' === $parent ) {
			add_options_page( $title, $title, $capability, $this->handle, $callback );
		} else {
			add_submenu_page( $parent, $title, $title, $capability, $this->handle, $callback );
		}
	}

	/**
	 * Print the full-page document: WordPress's admin head and footer
	 * printing around an empty mount point.
	 *
	 * From page.php.template's `<prefix>_<slug>_render_page()`.
	 *
	 * @return void
	 */
	public function render_document(): void {
		set_current_screen();

		remove_action( 'admin_head', 'wp_admin_bar_header' );

		// Drop whatever `admin_init` enqueued.
		foreach ( wp_scripts()->queue as $script ) {
			wp_dequeue_script( $script );
		}
		foreach ( wp_styles()->queue as $style ) {
			wp_dequeue_style( $style );
		}

		$this->enqueue_boot();

		?>
		<!DOCTYPE html>
		<html <?php language_attributes(); ?>>
		<head>
			<meta charset="<?php bloginfo( 'charset' ); ?>">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<title><?php echo esc_html( get_admin_page_title() ); ?></title>
			<style>
				html {
					background: #f1f1f1;
					color: #444;
					font-family: -apple-system, system-ui, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
					font-size: 13px;
					line-height: 1.4em;
				}
				body {
					margin: 0;
				}
				#wpadminbar { display: none; }
				/* wp-admin's common.css, which this document doesn't load, has this rule. */
				.js .hide-if-js { display: none; }
			</style>
		<?php
		global $hook_suffix;
		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$hook_suffix = $this->handle;

		// BEGIN see wp-admin/admin-header.php.
		print_admin_styles();
		print_head_scripts();

		/** This action is documented in wp-admin/admin-header.php */
		do_action( "admin_head-{$hook_suffix}" ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores

		/** This action is documented in wp-admin/admin-header.php */
		do_action( 'admin_head' );
		// END see wp-admin/admin-header.php.
		?>
		</head>
		<body class="<?php echo esc_attr( $this->handle ); ?> no-js">
			<?php // See wp-admin/admin-header.php. ?>
			<script type="text/javascript">
				document.body.className = document.body.className.replace( 'no-js', 'js' );
			</script>
			<?php $this->render_no_js_notice( 'margin: 20px;' ); ?>
			<div id="<?php echo esc_attr( $this->mount_id() ); ?>" style="height: 100vh; box-sizing: border-box;"></div>
		<?php
		// BEGIN see wp-admin/admin-footer.php.

		/** This action is documented in wp-admin/admin-footer.php */
		do_action( 'admin_footer', $hook_suffix );

		// Print the import map first so it's available to inline scripts.
		wp_script_modules()->print_import_map();
		print_footer_scripts();
		wp_script_modules()->print_enqueued_script_modules();
		wp_script_modules()->print_script_module_preloads();
		wp_script_modules()->print_script_module_data();

		/** This action is documented in wp-admin/admin-footer.php */
		do_action( "admin_footer-{$hook_suffix}" ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
		// END see wp-admin/admin-footer.php.
		?>
		</body>
		</html>
		<?php
	}

	/**
	 * Print the wp-admin mount point, with the critical styles that keep the
	 * layout from shifting.
	 *
	 * From page-wp-admin.php.template's `<prefix>_<slug>_render_page()`.
	 *
	 * @return void
	 */
	private function render_wp_admin(): void {
		?>
		<style>
			/* Critical styles to prevent layout shifts - inlined for immediate application */

			#wpwrap {
				overflow-y: auto;
			}
			body.js {
				background: #fff;
			}

			/* Reset wp-admin padding */
			body.js #wpcontent {
				padding-inline-start: 0;
			}
			body.js #wpbody-content {
				padding-bottom: 0;
			}

			/* Hide legacy admin elements */
			body.js #wpbody-content > div:not(#<?php echo esc_attr( $this->mount_id() ); ?>):not(#screen-meta) {
				display: none;
			}
			body.js #wpfooter {
				display: none;
			}

			/* Accessibility regions */
			.a11y-speak-region {
				inset-inline-start: -1px;
				top: -1px;
			}

			/* Admin menu indicators */
			ul#adminmenu a.wp-has-current-submenu::after,
			ul#adminmenu > li.current > a.current::after {
				border-inline-end-color: #fff;
			}

			/* Media frame fix */
			.media-frame select.attachment-filters:last-of-type {
				width: auto;
				max-width: 100%;
			}

			/* Responsive overflow fix for #wpwrap */
			@media (min-width: 782px) {
				#wpwrap {
					overflow-y: initial;
				}
			}
		</style>
		<?php
		$this->render_no_js_notice();
		// Boot's layout before its CSS Modules (Gutenberg #81756) sizes the
		// viewport by this class; drop it once WordPress 7.2 is the minimum.
		?>
		<div id="<?php echo esc_attr( $this->mount_id() ); ?>" class="boot-layout-container"></div>
		<?php
	}

	/**
	 * Print what the page shows without JavaScript: its title and an error
	 * notice. `hide-if-js` hides it once the page boots.
	 *
	 * From the `wrap hide-if-js` block of both templates.
	 *
	 * @param string $style Inline style for the wrapper.
	 * @return void
	 */
	private function render_no_js_notice( string $style = '' ): void {
		?>
		<div class="wrap hide-if-js"<?php echo $style ? ' style="' . esc_attr( $style ) . '"' : ''; ?>>
			<h1 class="wp-heading-inline"><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<?php
			wp_admin_notice(
				// Core's own string, so core's translation applies.
				esc_html__( 'This screen requires JavaScript. Enable JavaScript in your browser settings and reload the page.' ), // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
				array( 'type' => 'error' )
			);
			?>
		</div>
		<?php
	}

	/**
	 * Fire `<handle>_init`, then enqueue everything that boots the page.
	 *
	 * From the enqueue half of both templates' render/enqueue functions.
	 *
	 * The prerequisites carrier is an empty-src classic script depending on
	 * every classic handle the page's modules read off `window.*` (from the
	 * routes' `.asset.php` sidecars and core's boot module), so those exist by
	 * the time the modules run. It also hosts the inline bootstrap. The boot
	 * graph hangs off the `loader.js` anchor module. Nothing is enqueued when
	 * the build left no sidecars.
	 *
	 * @return void
	 */
	private function enqueue_boot(): void {
		/**
		 * Fires when the page initializes, so extensions can register routes
		 * and menu items.
		 */
		do_action( $this->handle . '_init' );

		if ( self::FULL_PAGE === $this->mode && function_exists( 'wp_enqueue_command_palette_assets' ) ) {
			wp_enqueue_command_palette_assets();
		}

		$this->preload_data();

		$script_dependencies = array();
		$version             = false;
		$has_assets          = false;

		foreach ( $this->asset_files() as $asset_file ) {
			$asset = $this->asset( $asset_file );

			if ( null === $asset ) {
				continue;
			}

			$has_assets          = true;
			$script_dependencies = array_merge( $script_dependencies, $asset['dependencies'] ?? array() );

			if ( false === $version && ! empty( $asset['version'] ) ) {
				$version = $asset['version'];
			}
		}

		if ( ! $has_assets ) {
			return;
		}

		// Core's boot module carries classic dependencies of its own.
		$boot_asset_file = ABSPATH . WPINC . '/js/dist/script-modules/boot/index.min.asset.php';
		if ( file_exists( $boot_asset_file ) ) {
			$boot_asset          = require $boot_asset_file;
			$script_dependencies = array_merge( $script_dependencies, $boot_asset['dependencies'] ?? array() );
		}

		/**
		 * Filters the classic scripts the page's modules need loaded first,
		 * `<handle>_script_dependencies`: every route's sidecar dependencies
		 * plus core boot's.
		 *
		 * A page whose routes don't all run on every request (an editor route
		 * among list routes, say) can leave the heavy ones to the request that
		 * lands on them.
		 *
		 * @param list<string> $script_dependencies Script handles.
		 */
		$script_dependencies = apply_filters( $this->handle . '_script_dependencies', array_values( array_unique( $script_dependencies ) ) );
		$script_dependencies = array_values( array_unique( array_filter( $script_dependencies, static fn ( string $handle ): bool => '' !== $handle ) ) );
		$carrier             = $this->carrier_handle();

		// A `false` src, not '': an empty-src carrier script.
		wp_register_script( $carrier, false, $script_dependencies, $version, true );
		wp_add_inline_script( $carrier, $this->bootstrap_script() );

		$style_dependencies = array_values( array_filter( $script_dependencies, static fn ( string $handle ): bool => wp_style_is( $handle, 'registered' ) ) );

		/**
		 * Filters the stylesheets the page loads ahead of its own,
		 * `<handle>_style_dependencies`: every classic dependency that is
		 * also a registered style.
		 *
		 * Some of core's stylesheets bundle their own copy of a package the
		 * page bundles too (`wp-edit-site` carries `@wordpress/dataviews`'
		 * CSS, for one); a page can drop those here.
		 *
		 * @param list<string> $style_dependencies Style handles.
		 */
		$style_dependencies = apply_filters( $this->handle . '_style_dependencies', $style_dependencies );

		wp_register_style( $carrier, false, array_values( array_unique( $style_dependencies ) ), $version );

		// Dummy Script Module whose dependencies are the page's boot graph.
		wp_register_script_module( $this->handle, $this->build->url( 'modules/' . $this->config['slug'] . '/loader.js' ), $this->boot_dependencies() );

		wp_enqueue_script( $carrier );
		wp_enqueue_script_module( $this->handle );
		wp_enqueue_style( $carrier );
	}

	/**
	 * The inline script that boots the app.
	 *
	 * From the `wp_add_inline_script()` calls of both templates.
	 *
	 * Full-page mode calls `init()` right away. wp-admin mode defers the
	 * `initSinglePage()` import to DOMContentLoaded, so every classic
	 * dependency of `@wordpress/boot` has run first; a modulepreloaded boot
	 * module can otherwise evaluate before `wp.theme.privateApis` exists and
	 * throw "Cannot unlock an undefined object" (core #65103).
	 *
	 * @return string
	 */
	private function bootstrap_script(): string {
		$json_flags = JSON_HEX_TAG | JSON_UNESCAPED_SLASHES;

		if ( self::FULL_PAGE === $this->mode ) {
			return sprintf(
				'import("@wordpress/boot").then(mod => mod.init({mountId: "%s", menuItems: %s, routes: %s, initModules: %s, dashboardLink: "%s"}));',
				$this->mount_id(),
				wp_json_encode( $this->menu_items, $json_flags ),
				wp_json_encode( $this->routes, $json_flags ),
				wp_json_encode( $this->config['init_modules'], $json_flags ),
				esc_url( admin_url( '/' ) )
			);
		}

		$init = <<<'JS'
		( mountId, routes, initModules ) => {
			const run = async () => {
				const mod = await import( "@wordpress/boot" );
				mod.initSinglePage( { mountId, routes, initModules } );
			};
			if ( document.readyState === "loading" ) {
				document.addEventListener( "DOMContentLoaded", run );
			} else {
				run();
			}
		}
		JS;

		return sprintf(
			'( %s )( %s, %s, %s );',
			$init,
			wp_json_encode( $this->mount_id(), $json_flags ),
			wp_json_encode( $this->routes, $json_flags ),
			wp_json_encode( $this->config['init_modules'], $json_flags )
		);
	}

	/**
	 * The page's Script Module graph: `@wordpress/boot` and the init modules
	 * statically, each route's `route` module statically and its `content`
	 * module dynamically.
	 *
	 * From both templates' `$boot_dependencies`.
	 *
	 * @return array<int, array{import: string, id: string}>
	 */
	private function boot_dependencies(): array {
		$dependencies = array(
			array(
				'import' => 'static',
				'id'     => '@wordpress/boot',
			),
		);

		foreach ( $this->config['init_modules'] as $module ) {
			$dependencies[] = array(
				'import' => 'static',
				'id'     => $module,
			);
		}

		foreach ( $this->routes as $route ) {
			if ( isset( $route['route_module'] ) ) {
				$dependencies[] = array(
					'import' => 'static',
					'id'     => $route['route_module'],
				);
			}
			if ( isset( $route['content_module'] ) ) {
				$dependencies[] = array(
					'import' => 'dynamic',
					'id'     => $route['content_module'],
				);
			}
		}

		/**
		 * Filters the page's boot Script Module dependencies.
		 *
		 * Append entries with 'import' ('static' or 'dynamic') and 'id' (Script
		 * Module id) keys to load more modules with the page.
		 *
		 * @param array $dependencies Boot dependencies for the page.
		 */
		return apply_filters( $this->handle . '_boot_dependencies', $dependencies );
	}

	/**
	 * Build-relative paths of every route's `.asset.php` sidecars.
	 *
	 * @return list<string>
	 */
	private function asset_files(): array {
		$files = array();

		foreach ( $this->config['routes'] as $route ) {
			if ( $route['has_content'] ) {
				$files[] = $route['dir'] . '/content.asset.php';
			}
			if ( $route['has_route'] ) {
				$files[] = $route['dir'] . '/route.asset.php';
			}
		}

		return $files;
	}

	/**
	 * Read a `.asset.php` sidecar.
	 *
	 * @param string $file Build-relative path.
	 * @return array<string, mixed>|null Null when the build didn't write it.
	 */
	private function asset( string $file ): ?array {
		$path = $this->build->path( $file );

		if ( ! file_exists( $path ) ) {
			return null;
		}

		$asset = require $path;

		return is_array( $asset ) ? $asset : null;
	}

	/**
	 * Register one route Script Module from its sidecar.
	 *
	 * @param string               $id    Script Module id.
	 * @param string               $file  Build-relative module path.
	 * @param array<string, mixed> $asset The module's `.asset.php` sidecar.
	 * @return void
	 */
	private function register_module( string $id, string $file, array $asset ): void {
		wp_deregister_script_module( $id );
		wp_register_script_module( $id, $this->build->url( $file ), $asset['module_dependencies'] ?? array(), $asset['version'] ?? false );
	}

	/**
	 * Handle of the empty-src classic script and style that carry the
	 * page's dependencies.
	 *
	 * @return non-empty-string
	 */
	private function carrier_handle(): string {
		return $this->handle . '-prerequisites';
	}

	/**
	 * The id of the element the app mounts into.
	 *
	 * @return string
	 */
	private function mount_id(): string {
		return $this->handle . '-app';
	}
}
