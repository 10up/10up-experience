<?php
/**
 * HotFile class.
 *
 * @package TenUp\WpViteDevServer\WpVite
 */

declare( strict_types = 1 );

namespace TenUp\WpViteDevServer\WpVite;

/**
 * The hot file a running Vite dev server writes next to the bridge
 * (`.wp-vite-dev.json`): where the server is, and which of its plugins PHP
 * has to react to.
 *
 * Its presence is the whole protocol. The dev server writes it on
 * 'listening' and deletes it on exit, so reading it is one `file_exists()`
 * when no server is running.
 */
final class HotFile {

	/**
	 * Create a hot file from its already-normalized values.
	 *
	 * @param string   $origin  Dev-server origin, without a trailing slash.
	 * @param string   $base    Vite's `base`, with a leading and trailing slash.
	 * @param string[] $plugins Names of the dev-server plugins PHP reacts to.
	 *
	 * @phpstan-param non-empty-string $origin
	 */
	public function __construct(
		public readonly string $origin,
		public readonly string $base,
		public readonly array $plugins,
	) {}

	/**
	 * Read and normalize a hot file.
	 *
	 * @param string $path Absolute path of the hot file.
	 * @return self|null Null when the file is missing or has no usable origin.
	 */
	public static function read( string $path ): ?self {
		if ( ! file_exists( $path ) ) {
			return null;
		}

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$contents = file_get_contents( $path );
		$decoded  = is_string( $contents ) ? json_decode( $contents, true ) : null;

		$origin = is_array( $decoded ) && isset( $decoded['origin'] ) && is_string( $decoded['origin'] ) ? rtrim( $decoded['origin'], '/' ) : '';

		if ( '' === $origin ) {
			return null;
		}

		$base = isset( $decoded['base'] ) && is_string( $decoded['base'] ) ? $decoded['base'] : '/';
		$base = '/' . ltrim( $base, '/' );
		if ( ! str_ends_with( $base, '/' ) ) {
			$base .= '/';
		}

		$plugins = isset( $decoded['plugins'] ) && is_array( $decoded['plugins'] ) ? $decoded['plugins'] : array();

		return new self(
			$origin,
			$base,
			array_values( array_filter( $plugins, 'is_string' ) )
		);
	}

	/**
	 * The URL the dev server serves a project-relative path at.
	 *
	 * @param string $path Project-relative path, e.g. an `.asset.php` 'source'.
	 * @return string
	 *
	 * @phpstan-return non-empty-string
	 */
	public function url( string $path ): string {
		return $this->origin . $this->base . ltrim( $path, '/' );
	}

	/**
	 * Whether the dev server reported a plugin by name.
	 *
	 * @param string $name Plugin name, e.g. 'vite:react-refresh'.
	 * @return bool
	 */
	public function has_plugin( string $name ): bool {
		return in_array( $name, $this->plugins, true );
	}
}
