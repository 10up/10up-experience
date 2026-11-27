<?php
/**
 * Admin customizations
 *
 * @package  10up-experience
 */

namespace TenUpExperience\AdminCustomizations;

use TenUpExperience\Singleton;

/**
 * Admin Customizations class
 */
class EnvironmentIndicator {

	use Singleton;

	/**
	 * Setup module
	 *
	 * @since 1.7
	 */
	public function setup() {
		add_action( 'admin_bar_menu', [ $this, 'add_toolbar_item' ], 7 );
		add_action( 'admin_head', [ $this, 'add_inline_styles' ] );
		add_action( 'wp_head', [ $this, 'add_inline_styles' ] );
	}

	/**
	 * Get the environments
	 *
	 * WordPress only allows four possible environment types: 'production', 'staging', 'development', and 'local'.
	 *
	 * @see https://developer.wordpress.org/reference/functions/wp_get_environment_type/
	 *
	 * @return array Array of environments.
	 */
	public function get_the_environments(): array {
		$default_environments = [
			'production' => [
				'label'            => __( 'Production', 'tenup' ),
				'icon'             => 'dashicons-admin-site',
				'background_color' => '#b92a2a',
				'text_color'       => '#fff',
			],
			'staging' => [
				'label'            => __( 'Staging', 'tenup' ),
				'icon'             => 'dashicons-admin-generic',
				'background_color' => '#d79d00',
				'text_color'       => '#fff',
			],
			'development' => [
				'label'            => __( 'Development', 'tenup' ),
				'icon'             => 'dashicons-admin-tools',
				'background_color' => '#34863b',
				'text_color'       => '#fff',
			],
			'local' => [
				'label'            => __( 'Local', 'tenup' ),
				'icon'             => 'dashicons-admin-home',
				'background_color' => '#0073aa',
				'text_color'       => '#fff',
			],
		];

		/**
		 * Filter environment indicator configurations
		 *
		 * Allows customization of environment indicator labels, icons, and colors.
		 * Can be used to modify existing environments or add custom environments.
		 *
		 * @param array $environments Environment configurations array.
		 *                       Each environment should have:
		 *                       - label (string): Human-readable label
		 *                       - icon (string): Dashicons class name (e.g., 'dashicons-warning')
		 *                       - background_color (string): Hex color code
		 *                       - text_color (string): Hex color code
		 */
		$environments = apply_filters( 'tenup_experience_environments', $default_environments );

		return is_array( $environments ) ? wp_parse_args( $environments, $default_environments ) : $default_environments;
	}


	/**
	 * Add environment indicator to admin bar
	 *
	 * @param WP_Admin_Bar $admin_bar Admin bar instance
	 *
	 * @return void
	 */
	public function add_toolbar_item( $admin_bar ) {
		$type = wp_get_environment_type();

		// If the const isn't set, and we're on a local URL, assume we're in a development environment.
		if ( ! defined( 'WP_ENVIRONMENT_TYPE' ) && $this->is_local_url() ) {
			$type = 'local';
		}

		$environments = $this->get_the_environments();
		if ( empty( $environments ) ) {
			return;
		}

		$environment = $environments[ $type ] ?? $environments['production'];

		$admin_bar->add_menu(
			[
				'id'     => 'tenup-experience-environment-indicator',
				'parent' => 'top-secondary',
				'title'  => $this->get_icon_markup( $environment['icon'] ) . sprintf( '<span class="ab-label">%s</span>', esc_html( $environment['label'] ) ),
				'meta'   => [
					'class' => esc_attr( "tenup-experience-environment-indicator tenup-experience-environment-indicator--$type" ),
				],
			]
		);
	}

	/**
	 * Markup for an environment's toolbar icon.
	 *
	 * Environments name a Dashicon. WordPress 7.2 replaced the toolbar's
	 * Dashicons with outlined SVG icons, so where core maps the Dashicon to
	 * one of those, use it; otherwise print the Dashicon.
	 *
	 * @param string $dashicon Dashicon class name, such as `dashicons-admin-home`.
	 * @return string
	 */
	private function get_icon_markup( $dashicon ) {
		if ( function_exists( 'wp_get_icon' ) && method_exists( 'WP_Icons_Registry', 'get_admin_icon_name_for_dashicon' ) ) {
			$icon_name = \WP_Icons_Registry::get_instance()->get_admin_icon_name_for_dashicon( $dashicon );
			$icon      = $icon_name ? wp_get_icon( $icon_name ) : '';

			if ( $icon ) {
				return '<span class="ab-icon svg-icon" aria-hidden="true">' . $icon . '</span>';
			}
		}

		return sprintf( '<span class="ab-icon dashicons %s" aria-hidden="true"></span>', esc_attr( $dashicon ) );
	}

	/**
	 * Add inline styles for environment indicator
	 *
	 * @return void
	 */
	public function add_inline_styles() {
		if ( ! is_admin_bar_showing() ) {
			return;
		}

		$environments = $this->get_the_environments();
		if ( empty( $environments ) ) {
			return;
		}

		$css = '';
		$css .= '.tenup-experience-environment-indicator { pointer-events: none; }';
		$css .= '.tenup-experience-environment-indicator .ab-icon.dashicons { top: 3px; }';

		foreach ( $environments as $type => $environment ) {
			$css .= sprintf(
				' .tenup-experience-environment-indicator--%s .ab-item { background-color: %s !important; color: %s !important; }',
				esc_attr( $type ),
				esc_attr( $environment['background_color'] ),
				esc_attr( $environment['text_color'] )
			);
		}

		printf( '<style id="tenup-experience-environment-indicator">%s</style>', esc_attr( wp_strip_all_tags( $css ) ) );
	}

	/**
	 * Check if the current URL is a local URL
	 *
	 * @return bool
	 */
	protected function is_local_url() {
		$home_url = untrailingslashit( home_url() );

		return $this->str_ends_with( $home_url, '.test' ) || $this->str_ends_with( $home_url, '.local' );
	}

	/**
	 * Check if a string ends with another string
	 *
	 * @param string $haystack Haystack string
	 * @param string $needle   Needle string
	 *
	 * @return bool
	 */
	protected function str_ends_with( $haystack, $needle ) {
		$length = strlen( $needle );
		if ( ! $length ) {
			return true;
		}

		return substr( $haystack, - $length ) === $needle;
	}
}
