<?php
/**
 * Pages class.
 *
 * @package TenUp\WpBoot\TenupExperience
 */

declare( strict_types = 1 );

namespace TenUp\WpBoot\TenupExperience;

use Closure;
use InvalidArgumentException;

/**
 * The `@wordpress/boot` admin pages one build produced, and where its
 * output lives.
 *
 * A port of the PHP `@wordpress/build` generates for its pages, the original
 * implementation (Gutenberg's packages/wp-build/templates; see the README's
 * "Origin"). Where wp-build writes one file per page and keeps routes in
 * globals, the build here emits a `build.php` that calls register() with the
 * pages as data; each page gets a Page per mode it was built for
 * ('full-page' and/or 'wp-admin').
 *
 * @link https://github.com/WordPress/gutenberg/tree/trunk/packages/wp-build
 *
 * @phpstan-type Route array{name: string, path: string, dir: string, has_content: bool, has_route: bool}
 * @phpstan-type Menu array{title: Closure(): string, parent?: string|false, capability?: string, position?: int, icon?: string}
 * @phpstan-type PageConfig array{slug: string, capability: string, modes: list<string>, init_modules: list<string>, rest_preload?: list<string|array{string, string}>, menu?: Menu, routes: list<Route>}
 */
final class Pages {

	/**
	 * Every registered page, by handle (`<slug>` or `<slug>-wp-admin`).
	 *
	 * @var array<string, Page>
	 */
	private static array $pages = array();

	/**
	 * Memoized result of `$build_url`.
	 *
	 * @var string|null
	 */
	private ?string $url = null;

	/**
	 * Describe one build's output.
	 *
	 * @param string  $build_dir     Absolute path of the build directory.
	 * @param Closure $build_url     Returns the build directory's URL. Called lazily, so
	 *                               filters added after load still apply.
	 * @param string  $prefix        Project prefix, for hook names.
	 * @param string  $handle_prefix Script Module id prefix for route modules.
	 *
	 * @phpstan-param Closure(): string $build_url
	 */
	public function __construct(
		private readonly string $build_dir,
		private readonly Closure $build_url,
		public readonly string $prefix,
		public readonly string $handle_prefix,
	) {}

	/**
	 * Register a build's pages and hook them into WordPress.
	 *
	 * When a page is built for both modes the wp-admin one owns the
	 * generated menu entry.
	 *
	 * @param string  $build_dir     Absolute path of the build directory.
	 * @param Closure $build_url     Returns the build directory's URL.
	 * @param string  $prefix        Project prefix, for hook names.
	 * @param string  $handle_prefix Script Module id prefix for route modules.
	 * @param array   $pages         The pages, as the build wrote them.
	 * @return void
	 *
	 * @phpstan-param Closure(): string $build_url
	 * @phpstan-param list<PageConfig> $pages
	 */
	public static function register( string $build_dir, Closure $build_url, string $prefix, string $handle_prefix, array $pages ): void {
		$build = new self( $build_dir, $build_url, $prefix, $handle_prefix );

		foreach ( $pages as $config ) {
			foreach ( $config['modes'] as $mode ) {
				$page      = new Page( $build, $config, $mode );
				$owns_menu = isset( $config['menu'] ) && ( Page::WP_ADMIN === $mode || ! in_array( Page::WP_ADMIN, $config['modes'], true ) );

				$page->register_hooks( $owns_menu );
				self::$pages[ $page->handle ] = $page;
			}
		}
	}

	/**
	 * A registered page, for the generated `<prefix>_register_<slug>_route()`
	 * style functions.
	 *
	 * @param string $handle Page handle: `<slug>` or `<slug>-wp-admin`.
	 * @return Page
	 * @throws InvalidArgumentException When no page has that handle.
	 */
	public static function get( string $handle ): Page {
		if ( ! isset( self::$pages[ $handle ] ) ) {
			throw new InvalidArgumentException( esc_html( "No admin page is registered as \"{$handle}\"." ) );
		}

		return self::$pages[ $handle ];
	}

	/**
	 * Absolute path of a file in the build directory.
	 *
	 * @param string $path Build-relative path.
	 * @return string
	 */
	public function path( string $path ): string {
		return $this->build_dir . '/' . $path;
	}

	/**
	 * URL of a file in the build directory.
	 *
	 * @param string $path Build-relative path.
	 * @return string
	 */
	public function url( string $path ): string {
		$this->url ??= ( $this->build_url )();

		return $this->url . $path;
	}
}
