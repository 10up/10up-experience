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
		// Add an admin bar item if in wp-admin.
		add_action( 'admin_bar_menu', [ $this, 'add_toolbar_item' ], 7 );
	}

	/**
	 * Add environment indicator to admin bar
	 *
	 * @param WP_Admin_Bar $admin_bar Admin bar instance
	 */
	public function add_toolbar_item( $admin_bar ) {
		$environment = wp_get_environment_type();

		$admin_bar->add_menu(
			[
				'id'     => 'tenup-experience-environment-indicator',
				'parent' => 'top-secondary',
				'title'  => '<span class="ab-icon" aria-hidden="true"></span><span class="ab-label">' . esc_html( $this->get_environment_label( $environment ) ) . '</span>',
				'meta'    => [
					'class' => "tenup-$environment",
				],
			]
		);
	}

	/**
	 * Get human readable label for environment
	 *
	 * @param string $environment Environment type
	 * @return string
	 */
	public function get_environment_label( $environment ) {
		switch ( $environment ) {
			case 'development':
			case 'local':
				$label = __( 'Development', 'tenup' );
				break;
			case 'staging':
				$label = __( 'Staging', 'tenup' );
				break;
			default:
				$label = __( 'Production', 'tenup' );
				break;
		}

		return $label;
	}
}
