<?php
/**
 * Boot-rendered Fueled Experience Plugin page.
 *
 * Provides the @wordpress/boot wiring for the Experience Plugin screen. The
 * menu registration, admin-bar entry, legacy markup fallback, and the
 * configuration/feature data live in AdminCustomizations\Customizations —
 * its render callback delegates here when the boot script module is
 * available, and the current configuration is handed to the React app via
 * the config payload.
 *
 * @package  10up-experience
 */

namespace TenUpExperience\BootPages;

use TenUpExperience\AdminCustomizations\Customizations;
use TenUpExperience\Singleton;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Experience page class
 */
class ExperiencePage extends AbstractBootPage {

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
		return 'fueled-experience-plugin';
	}

	/**
	 * Built-assets directory for the boot machinery.
	 *
	 * @return string
	 */
	protected function get_module_dir() {
		return 'experience';
	}

	/**
	 * Admin hook suffix for the boot machinery.
	 *
	 * The page is registered by Customizations as a hidden submenu of
	 * admin.php with the historical `10up-experience` slug.
	 *
	 * @return string
	 */
	protected function get_hook_suffix() {
		return 'admin_page_10up-experience';
	}

	/**
	 * Window global for the app config payload.
	 *
	 * @return string
	 */
	protected function get_config_var() {
		return 'tenupExperiencePluginData';
	}

	/**
	 * Configuration payload handed to the React app.
	 *
	 * @return array
	 */
	protected function get_app_config() {
		$customizations = Customizations::instance();

		return [
			'logoUrl'       => plugins_url( '/dist/img/fueled-logo.svg', TENUP_EXPERIENCE_FILE ),
			'boltUrl'       => plugins_url( '/dist/img/fueled-bolt.svg', TENUP_EXPERIENCE_FILE ),
			'configuration' => $customizations->get_experience_configuration(),
			'features'      => $customizations->get_experience_features(),
		];
	}
}
