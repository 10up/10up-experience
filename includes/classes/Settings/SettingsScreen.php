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
use TenUpExperience\BootPages\AbstractBootPage;
use TenUpExperience\Singleton;
use TenUpExperience\SupportMonitor\Debug;
use TenUpExperience\SupportMonitor\Monitor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Settings screen class
 */
class SettingsScreen extends AbstractBootPage {

	use Singleton;

	/**
	 * Page slug used in the URL and as handle prefix.
	 */
	const PAGE_SLUG = 'fueled-experience';

	/**
	 * Page slug for the boot machinery.
	 *
	 * @return string
	 */
	protected function get_page_slug() {
		return self::PAGE_SLUG;
	}

	/**
	 * Built-assets directory for the boot machinery.
	 *
	 * @return string
	 */
	protected function get_module_dir() {
		return 'settings';
	}

	/**
	 * Admin hook suffix for the boot machinery.
	 *
	 * @return string
	 */
	protected function get_hook_suffix() {
		return 'settings_page_' . self::PAGE_SLUG;
	}

	/**
	 * Preload the settings endpoint the entity store reads and saves.
	 *
	 * @return array
	 */
	protected function get_preload_paths() {
		return [
			'/wp/v2/settings',
			[ '/wp/v2/settings', 'OPTIONS' ],
		];
	}

	/**
	 * Window global for the app config payload.
	 *
	 * @return string
	 */
	protected function get_config_var() {
		return 'tenupExperienceSettingsData';
	}

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
			[ $this, 'render_mount' ]
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
	protected function get_app_config() {
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
}
