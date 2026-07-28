<?php
/**
 * Boot-rendered About Fueled page.
 *
 * Provides the @wordpress/boot wiring for the About Fueled screen. The menu
 * registration, admin-bar entry, and legacy markup fallback live in
 * AdminCustomizations\Customizations — its render callback delegates here
 * when the boot script module is available.
 *
 * @package  10up-experience
 */

namespace TenUpExperience\BootPages;

use TenUpExperience\Singleton;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * About page class
 */
class AboutPage extends AbstractBootPage {

	use Singleton;

	/**
	 * Setup module
	 *
	 * @since 1.19
	 */
	public function setup() {
		if ( ! $this->is_boot_available() ) {
			return;
		}

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
	}

	/**
	 * Page slug for the boot machinery.
	 *
	 * @return string
	 */
	protected function get_page_slug() {
		return 'fueled-about';
	}

	/**
	 * Built-assets directory for the boot machinery.
	 *
	 * @return string
	 */
	protected function get_module_dir() {
		return 'about';
	}

	/**
	 * Admin hook suffix for the boot machinery.
	 *
	 * The page is registered by Customizations as a hidden submenu of
	 * admin.php with the historical `10up-about` slug.
	 *
	 * @return string
	 */
	protected function get_hook_suffix() {
		return 'admin_page_10up-about';
	}

	/**
	 * Window global for the app config payload.
	 *
	 * @return string
	 */
	protected function get_config_var() {
		return 'tenupExperienceAboutData';
	}

	/**
	 * Configuration payload handed to the React app.
	 *
	 * @return array
	 */
	protected function get_app_config() {
		return [
			'logoUrl'          => plugins_url( '/dist/img/fueled-logo.svg', TENUP_EXPERIENCE_FILE ),
			'wordpressLogoUrl' => admin_url( 'images/wordpress-logo.svg' ),
		];
	}
}
