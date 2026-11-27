<?php
/**
 * Bridge class.
 *
 * @package TenUp\WpViteDevServer\WpVite
 */

declare( strict_types = 1 );

namespace TenUp\WpViteDevServer\WpVite;

use WP_HTML_Tag_Processor;

/**
 * Repoints WordPress's enqueues at a running Vite dev server.
 *
 * WordPress prints the URLs a script, stylesheet or Script Module was
 * registered with. While a dev server is up, every one of those that points
 * into this build's dist directory is mapped back to its source file through
 * the `source` key of the `.asset.php` sidecar next to it, and served by Vite
 * instead:
 *
 *   - Classic scripts: `script_loader_src` for the URL, `script_loader_tag`
 *     to print them `type="module"`. Filters rather than a registry walk, so a
 *     script registered while printing is caught too.
 *   - Script Modules: `script_module_loader_src` (WP 6.5+), the single choke
 *     point for the module tag, its modulepreload link and the import map.
 *   - Stylesheets: dequeued and replaced with a module script, because Vite
 *     serves CSS through JavaScript in dev (that is how it hot-swaps them).
 *     `style_loader_src` rewrites any it missed to Vite's `?direct` CSS.
 *
 * It also enqueues `@vite/client` ahead of everything it repoints, and the
 * React Fast Refresh preamble when the hot file lists `vite:react-refresh`.
 */
final class Bridge {

	/** The dev-server plugin whose presence turns on the React refresh preamble. */
	private const REACT_REFRESH = 'vite:react-refresh';

	/**
	 * Script handle of Vite's HMR client: `<prefix>-vite-client`.
	 *
	 * @var string
	 */
	private readonly string $client;

	/**
	 * URL of the dist directory, or '' when WordPress can't build one. Null until computed.
	 *
	 * @var string|null
	 */
	private ?string $dist_url = null;

	/**
	 * Source path per asset URL already looked up (null: not ours).
	 *
	 * @var array<string, string|null>
	 */
	private array $sources = array();

	/**
	 * Script handles that have to print as `type="module"`.
	 *
	 * @var array<string, true>
	 */
	private array $modules = array();

	/**
	 * Whether the React refresh preamble has been attached.
	 *
	 * @var bool
	 */
	private bool $preamble_attached = false;

	/**
	 * Create a bridge for one dist directory.
	 *
	 * @param string  $dist_dir Absolute path of the dist directory.
	 * @param HotFile $hot      The running dev server.
	 * @param string  $prefix   Project prefix; namespaces the client handle, constant and filter.
	 */
	public function __construct(
		private readonly string $dist_dir,
		private readonly HotFile $hot,
		private readonly string $prefix,
	) {
		$this->client = $prefix . '-vite-client';
	}

	/**
	 * Start bridging a dist directory when a dev server is running for it.
	 *
	 * With no hot file this is a single `file_exists()` and no hooks.
	 *
	 * @param string $dist_dir Absolute path of the dist directory.
	 * @param string $hot_file Hot file name, relative to `$dist_dir`.
	 * @param string $prefix   Project prefix.
	 * @return self|null The registered bridge, or null when no dev server is running.
	 */
	public static function boot( string $dist_dir, string $hot_file, string $prefix ): ?self {
		$hot = HotFile::read( $dist_dir . '/' . $hot_file );

		if ( null === $hot ) {
			return null;
		}

		$bridge = new self( $dist_dir, $hot, $prefix );

		if ( $bridge->should_verify_dev_server() && ! $bridge->dev_server_responds() ) {
			return null;
		}

		$bridge->register_hooks();

		return $bridge;
	}

	/**
	 * Hook the bridge into WordPress's enqueue pipeline.
	 *
	 * @return void
	 */
	private function register_hooks(): void {
		foreach ( array( 'wp_enqueue_scripts', 'admin_enqueue_scripts' ) as $hook ) {
			add_action( $hook, array( $this, 'enqueue_client' ), -1000 );
			add_action( $hook, array( $this, 'prepare' ), PHP_INT_MAX );
		}

		add_filter( 'script_loader_src', array( $this, 'script_src' ), 10, 2 );
		add_filter( 'script_loader_tag', array( $this, 'script_tag' ), 10, 2 );
		add_filter( 'script_module_loader_src', array( $this, 'script_module_src' ) );
		add_filter( 'style_loader_src', array( $this, 'style_src' ) );
	}

	/**
	 * Whether to probe the dev server before trusting the hot file.
	 *
	 * Off by default. A `kill -9`'d dev server leaves its hot file behind,
	 * pointing at a socket nothing answers on; catching that costs an HTTP
	 * request on every request, so it is opt-in through the
	 * `<PREFIX>_WP_VITE_CHECK_DEV_SERVER` constant or the
	 * `<prefix>_wp_vite_check_dev_server` filter.
	 *
	 * @return bool
	 */
	private function should_verify_dev_server(): bool {
		$prefix   = str_replace( '-', '_', $this->prefix );
		$constant = strtoupper( $prefix ) . '_WP_VITE_CHECK_DEV_SERVER';
		$default  = defined( $constant ) && (bool) constant( $constant );

		/**
		 * Filters whether the dev-server bridge checks the server is reachable
		 * before trusting the hot file.
		 *
		 * @param bool $verify Default from the `<PREFIX>_WP_VITE_CHECK_DEV_SERVER` constant, else false.
		 */
		return (bool) apply_filters( $prefix . '_wp_vite_check_dev_server', $default );
	}

	/**
	 * Whether `@vite/client` answers within 200ms.
	 *
	 * @return bool
	 */
	private function dev_server_responds(): bool {
		$response = wp_remote_head(
			$this->hot->url( '@vite/client' ),
			array( 'timeout' => 0.2 )
		);

		$status = is_wp_error( $response ) ? 0 : (int) wp_remote_retrieve_response_code( $response );

		return $status >= 200 && $status < 400;
	}

	/**
	 * Register and enqueue Vite's HMR client before anything else.
	 *
	 * It opens the websocket, applies hot updates and renders the error
	 * overlay.
	 *
	 * @return void
	 */
	public function enqueue_client(): void {
		wp_register_script( $this->client, $this->hot->url( '@vite/client' ), array(), null, false ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		wp_enqueue_script( $this->client );
		$this->modules[ $this->client ] = true;

		$this->enqueue_react_refresh_preamble();
	}

	/**
	 * Attach the React Fast Refresh preamble to the client handle, when the
	 * dev server runs `@vitejs/plugin-react`.
	 *
	 * The preamble must run before any component module evaluates. Attaching
	 * it to the client, which every repointed classic script depends on and
	 * which prints before any Script Module, gets that ordering for free.
	 *
	 * @return void
	 */
	private function enqueue_react_refresh_preamble(): void {
		if ( $this->preamble_attached || ! $this->hot->has_plugin( self::REACT_REFRESH ) ) {
			return;
		}

		$this->preamble_attached = true;

		$preamble = sprintf(
			'import RefreshRuntime from "%1$s"
RefreshRuntime.injectIntoGlobalHook(window)
window.$RefreshReg$ = () => {}
window.$RefreshSig$ = () => (type) => type
window.__vite_plugin_react_preamble_installed__ = true',
			esc_js( $this->hot->url( '@react-refresh' ) )
		);

		wp_add_inline_script( $this->client, $preamble, 'after' );
		add_filter( 'wp_inline_script_attributes', array( $this, 'react_refresh_inline_attributes' ) );
	}

	/**
	 * Print the preamble `type="module"`: it is a top-level `import`.
	 *
	 * Matches the inline block's own `<handle>-js-after` id, so other inline
	 * scripts on the client handle stay classic.
	 *
	 * @param mixed $attributes Attributes of the inline script about to print.
	 * @return mixed
	 */
	public function react_refresh_inline_attributes( mixed $attributes ): mixed {
		if ( is_array( $attributes ) && isset( $attributes['id'] ) && $this->client . '-js-after' === $attributes['id'] ) {
			$attributes['type'] = 'module';
		}

		return $attributes;
	}

	/**
	 * The pass that needs the queues still mutable: make the client a
	 * dependency of every script about to be repointed, and swap this
	 * directory's queued stylesheets for the JS modules Vite serves them as.
	 *
	 * Runs last on the enqueue hooks.
	 *
	 * @return void
	 */
	public function prepare(): void {
		$claimed    = array();
		$has_client = wp_script_is( $this->client, 'registered' );

		foreach ( wp_scripts()->registered as $handle => $script ) {
			if ( $this->client === $handle || ! is_string( $script->src ) || '' === $script->src ) {
				continue;
			}

			$source = $this->source( $script->src );

			if ( null === $source ) {
				continue;
			}

			$claimed[ $source ] = true;

			// A missing dependency makes WordPress drop the script, so only
			// depend on a client that is there.
			if ( $has_client && ! in_array( $this->client, $script->deps, true ) ) {
				$script->deps[] = $this->client;
			}
		}

		$styles = wp_styles();

		foreach ( $styles->queue as $handle ) {
			$src = isset( $styles->registered[ $handle ] ) ? $styles->registered[ $handle ]->src : null;

			if ( ! is_string( $src ) || '' === $src ) {
				continue;
			}

			$source = $this->source( $src );

			if ( null === $source ) {
				continue;
			}

			wp_dequeue_style( $handle );

			// An entry that imports its own CSS already carries it in dev.
			if ( isset( $claimed[ $source ] ) ) {
				continue;
			}

			$claimed[ $source ] = true;
			$shim               = $handle . '-vite-dev';

			wp_register_script( $shim, $this->hot->url( $source ), $has_client ? array( $this->client ) : array(), null, false ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			wp_enqueue_script( $shim );
			$this->modules[ $shim ] = true;
		}
	}

	/**
	 * Repoint a classic script.
	 *
	 * @param mixed  $src    Script URL.
	 * @param string $handle Script handle.
	 * @return mixed The dev-server URL, or `$src` unchanged.
	 */
	public function script_src( mixed $src, string $handle ): mixed {
		$source = $this->source( $src );

		if ( null === $source ) {
			return $src;
		}

		// script_loader_src always runs before script_loader_tag for the same
		// handle, so marking it here is enough.
		$this->modules[ $handle ] = true;

		return $this->hot->url( $source );
	}

	/**
	 * Print the scripts this bridge repointed as `type="module"`.
	 *
	 * @param mixed  $tag    Script tag HTML.
	 * @param string $handle Script handle.
	 * @return mixed
	 */
	public function script_tag( mixed $tag, string $handle ): mixed {
		if ( ! is_string( $tag ) || ! isset( $this->modules[ $handle ] ) ) {
			return $tag;
		}

		$processor = new WP_HTML_Tag_Processor( $tag );

		while ( $processor->next_tag( array( 'tag_name' => 'script' ) ) ) {
			// Inline blocks attached to the same handle stay classic.
			if ( null !== $processor->get_attribute( 'src' ) ) {
				$processor->set_attribute( 'type', 'module' );
			}
		}

		return $processor->get_updated_html();
	}

	/**
	 * Repoint a Script Module.
	 *
	 * @param mixed $src Script Module URL.
	 * @return mixed
	 */
	public function script_module_src( mixed $src ): mixed {
		$source = $this->source( $src );

		return null === $source ? $src : $this->hot->url( $source );
	}

	/**
	 * Repoint a stylesheet prepare() never saw (registered later, or pulled
	 * in as another stylesheet's dependency) at Vite's plain-CSS `?direct`
	 * URL: correct styles, no hot reload.
	 *
	 * @param mixed $src Stylesheet URL.
	 * @return mixed
	 */
	public function style_src( mixed $src ): mixed {
		$source = $this->source( $src );

		return null === $source ? $src : $this->hot->url( $source ) . '?direct';
	}

	/**
	 * Map an asset URL in the dist directory to the source file Vite serves.
	 *
	 * Strips the query and the dist URL, swaps the extension for
	 * `.asset.php` and reads that sidecar's 'source' key.
	 *
	 * @param mixed $url Asset URL as WordPress is about to print it.
	 * @return string|null Project-relative source path, or null when the URL is
	 *                     not in the dist directory or has no sidecar.
	 */
	private function source( mixed $url ): ?string {
		if ( ! is_string( $url ) || '' === $url ) {
			return null;
		}

		$dist_url = $this->dist_url();

		if ( '' === $dist_url ) {
			return null;
		}

		// The sidecar lives at the unversioned path.
		$query = strpos( $url, '?' );
		if ( false !== $query ) {
			$url = substr( $url, 0, $query );
		}

		if ( ! array_key_exists( $url, $this->sources ) ) {
			$this->sources[ $url ] = $this->lookup_source( $url, $dist_url );
		}

		return $this->sources[ $url ];
	}

	/**
	 * The uncached half of source().
	 *
	 * @param string $url      Asset URL without its query.
	 * @param string $dist_url URL of the dist directory.
	 * @return string|null
	 */
	private function lookup_source( string $url, string $dist_url ): ?string {
		$relative = null;

		// Compare without the scheme (an http/https mismatch is routine), then
		// by path alone, for root-relative and CDN-fronted srcs.
		$candidates = array(
			array( self::strip_scheme( $dist_url ), self::strip_scheme( $url ) ),
			array( self::strip_host( $dist_url ), self::strip_host( $url ) ),
		);

		foreach ( $candidates as list( $prefix, $candidate ) ) {
			if ( '' !== $prefix && str_starts_with( $candidate, $prefix ) ) {
				$relative = substr( $candidate, strlen( $prefix ) );
				break;
			}
		}

		// The path came out of a URL and reaches a require(): only the shape a
		// build emits gets through.
		if ( null === $relative || ! preg_match( '#^[A-Za-z0-9_][A-Za-z0-9_./-]*$#', $relative ) || str_contains( $relative, '..' ) ) {
			return null;
		}

		$asset_file = $this->dist_dir . '/' . preg_replace( '/\.(js|mjs|css)$/', '', $relative ) . '.asset.php';

		if ( ! file_exists( $asset_file ) ) {
			return null;
		}

		$asset = require $asset_file;

		return is_array( $asset ) && ! empty( $asset['source'] ) && is_string( $asset['source'] ) ? $asset['source'] : null;
	}

	/**
	 * A URL without its scheme: `//host/path`.
	 *
	 * @param string $url URL.
	 * @return string
	 */
	private static function strip_scheme( string $url ): string {
		return (string) preg_replace( '#^[a-z][a-z0-9+.-]*:#i', '', $url );
	}

	/**
	 * A URL without its scheme and host: `/path`.
	 *
	 * @param string $url URL.
	 * @return string
	 */
	private static function strip_host( string $url ): string {
		return (string) preg_replace( '#^([a-z][a-z0-9+.-]*:)?//[^/]*#i', '', $url );
	}

	/**
	 * URL of the dist directory, derived from its path.
	 *
	 * Tried most-specific first: a theme under a custom theme root, or a
	 * directory reached through a symlink, is only spelled right by its own
	 * helper.
	 *
	 * @return string Trailing-slashed URL, or '' when the directory is outside
	 *                every location WordPress can build a URL for.
	 */
	private function dist_url(): string {
		if ( null !== $this->dist_url ) {
			return $this->dist_url;
		}

		$this->dist_url = '';
		$dir            = rtrim( str_replace( '\\', '/', $this->dist_dir ), '/' );
		$roots          = array(
			array( get_stylesheet_directory(), get_stylesheet_directory_uri() ),
			array( get_template_directory(), get_template_directory_uri() ),
			array( WPMU_PLUGIN_DIR, WPMU_PLUGIN_URL ),
			array( WP_PLUGIN_DIR, WP_PLUGIN_URL ),
			array( WP_CONTENT_DIR, content_url() ),
		);

		foreach ( $roots as list( $root_dir, $root_url ) ) {
			$root_dir = rtrim( str_replace( '\\', '/', $root_dir ), '/' );

			if ( '' === $root_dir || '' === $root_url || ! str_starts_with( $dir . '/', $root_dir . '/' ) ) {
				continue;
			}

			$this->dist_url = rtrim( $root_url, '/' ) . substr( $dir, strlen( $root_dir ) ) . '/';
			break;
		}

		return $this->dist_url;
	}
}
