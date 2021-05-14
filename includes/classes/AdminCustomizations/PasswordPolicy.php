<?php
/**
 * Admin password Policy
 *
 * @package  10up-experience
 */

namespace TenUpExperience\AdminCustomizations;

use TenUpExperience\Singleton;

/**
 * Admin PasswordPolicy class
 */
class PasswordPolicy extends Singleton {

	/**
	 * Password policy option name
	 */
	const PASSWORD_POLICY_OPTION_NAME = 'tenup_password_policy_settings';

	/**
	 * Setup module
	 *
	 * @since 1.7
	 */
	public function setup() {
		if ( apply_filters( 'tenup_experience_enable_password_policy', true ) ) {
			add_action( 'admin_menu', [ $this, 'register_admin_pages' ] );
			add_filter( 'admin_init', [ $this, 'register_password_policy_settings' ], 10, 2 );
		}
	}

	/**
	 * Register admin pages with output callbacks
	 */
	public function register_admin_pages() {
		add_users_page( esc_html__( 'Password Policy', 'tenup' ), esc_html__( 'Password Policy', 'tenup' ), 'manage_options', '10up-password-policy', [ $this, 'password_policy_screen' ] );
	}

	/**
	 * Register password policy settings
	 *
	 * @return void
	 */
	public function register_password_policy_settings() {
		register_setting(
			self::PASSWORD_POLICY_OPTION_NAME,
			self::PASSWORD_POLICY_OPTION_NAME,
			[
				'sanitize_callback' => [ $this, 'sanitize_settings' ],
			]
		);

		add_settings_section(
			self::PASSWORD_POLICY_OPTION_NAME,
			'',
			'__return_empty_string',
			self::PASSWORD_POLICY_OPTION_NAME
		);

		$settings = [
			'enabled'        => [
				'label' => __( 'Enable Password Policy', 'tenup' ),
				'type'  => 'checkbox',
			],
			'expires'        => [
				'label'       => __( 'Password Expires', 'tenup' ),
				'type'        => 'number',
				'description' => __( 'The number of days a passwords is good for before it needs to be changed.', 'tenup' ),
			],
			'reminder'       => [
				'label'       => __( 'Send Password Reminder', 'tenup' ),
				'type'        => 'number',
				'description' => __( 'The number of days before a password need to be changed reminder email.', 'tenup' ),
			],
			'past_passwords' => [
				'label'       => __( 'Past Passwords', 'tenup' ),
				'type'        => 'number',
				'description' => __( 'The number of past passwords a user can repeat.', 'tenup' ),
			],
			'reminder_email' => [
				'label' => __( 'Reminder Email', 'tenup' ),
				'type'  => 'tinymce',
			],
		];

		foreach ( $settings as $setting_id => $setting ) {
			$options = [
				'name'        => self::PASSWORD_POLICY_OPTION_NAME . "[$setting_id]",
				'id'          => $setting_id,
				'type'        => $setting['type'] ?? 'text',
				'description' => $setting['description'] ?? '',
			];

			add_settings_field(
				$setting_id,
				$setting['label'],
				[ $this, 'field' ],
				self::PASSWORD_POLICY_OPTION_NAME,
				self::PASSWORD_POLICY_OPTION_NAME,
				$options
			);
		}
	}

	/**
	 * Output setting fields
	 *
	 * @param array $args field options
	 */
	public function field( $args ) {
		$settings = get_option( self::PASSWORD_POLICY_OPTION_NAME, [] );
		$value    = $settings[ $args['id'] ] ?? '';

		if ( 'checkbox' === $args['type'] ) {
			printf( '<input type="%s" id="%s" name="%s" value="on" %s/>', esc_attr( $args['type'] ), esc_attr( $args['id'] ), esc_attr( $args['name'] ), esc_attr( checked( 'on', $value, false ) ) );
			if ( ! empty( $args['description'] ) ) {
				printf( '<label for="%s">%s</label>', esc_attr( $args['id'] ), esc_html( $args['description'] ) );
			}
		} elseif ( 'tinymce' === $args['type'] ) {
			wp_editor(
				$value,
				$args['id'],
				[
					'media_buttons' => false,
					'textarea_name' => $args['name'],
				]
			);
			if ( ! empty( $args['description'] ) ) {
				printf( '<p class="description">%s</p>', wp_kses_post( $args['description'] ) );
			}
		} else {
			printf( '<input type="%s" id="%s" name="%s" value="%s" class="regular-text"/>', esc_attr( $args['type'] ), esc_attr( $args['id'] ), esc_attr( $args['name'] ), esc_attr( $value ) );

			if ( ! empty( $args['description'] ) ) {
				printf( '<p class="description">%s</p>', esc_html( $args['description'] ) );
			}
		}
	}

	/**
	 * Sanitize settings fields
	 *
	 * @param array $settings setting being saved
	 *
	 * @return array
	 */
	public function sanitize_settings( $settings ) {
		$clean_settings = array();
		foreach ( $settings as $key => $setting ) {
			if ( in_array( $key, [ 'reminder_email', 'token_email' ], true ) ) {
				$clean_settings[ $key ] = wp_kses_post( $setting );
			} else {
				$clean_settings[ $key ] = sanitize_text_field( $setting );
			}
		}

		return $clean_settings;
	}

	/**
	 * Password policy screen
	 *
	 * @return void
	 */
	public function password_policy_screen() {
		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Password Policy', 'tenup' ); ?></h2>

			<?php settings_errors(); ?>

			<form action="options.php" method="POST">
				<?php
				settings_fields( self::PASSWORD_POLICY_OPTION_NAME );
				do_settings_sections( self::PASSWORD_POLICY_OPTION_NAME );
				submit_button( __( 'Save Settings', 'tenup' ) );
				?>
			</form>
		</div>
		<?php

	}

	/**
	 * Return password policy settings
	 *
	 * @param string $name Setting key
	 *
	 * @return string
	 */
	public function get_setting( $name = '' ) {
		$settings = get_option( self::PASSWORD_POLICY_OPTION_NAME, [] );

		if ( empty( $name ) ) {
			return $settings;
		}

		return $settings[ $name ] ?? '';
	}

	/**
	 * Is the password policy feature enabled
	 *
	 * @return bool
	 */
	public function is_enabled() {
		return ! empty( $this->get_setting( 'enabled' ) ) && apply_filters( 'tenup_experience_enable_password_policy', true );
	}

}
