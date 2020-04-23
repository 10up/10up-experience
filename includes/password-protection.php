<?php
/**
 * Optionally disable password protection
 *
 * @package  10up-experience
 */

namespace TenUpExperience\PasswordProtection;

/**
 * Setup module
 *
 * @since 1.7
 */
function setup() {
	add_action( 'admin_init', __NAMESPACE__ . '\password_protection_setting' );
	add_action( 'admin_print_footer_scripts', __NAMESPACE__ . '\print_admin_css' );
}

/**
 * Register setting.
 */
function password_protection_setting() {

	$settings_args = array(
		'type'              => 'integer',
		'sanitize_callback' => 'intval',
	);

	register_setting( 'writing', 'tenup_password_protect', $settings_args );

	add_settings_field(
		'tenup_password_protect',
		esc_html__( 'Enable Password Protected Content', 'tenup' ),
		__NAMESPACE__ . '\settings_ui',
		'writing',
		'default',
		array(
			'label_for' => 'password-protect',
		)
	);
}

/**
 * Display UI the setting.
 *
 * @return void
 */
function settings_ui() {
	$password_protect = get_option( 'tenup_password_protect', 0 );

	?>
	<fieldset>
		<legend class="screen-reader-text"><?php esc_html_e( 'Password Protected Content', 'tenup' ); ?></legend>
		<p>
			<input id="password-protect" name="tenup_password_protect" type="checkbox" value="1" <?php checked( $password_protect, 1 ); ?> />
		</p>
		<p class="description"><?php esc_html_e( 'Enables password protected content. WordPress default password protected post functionality is insecure and does not work with page caching.', 'tenup' ); ?></p>
	</fieldset>
	<?php
}

/**
 * Disable password protect optionally
 */
function print_admin_css() {
	global $pagenow, $post;

	if ( empty( $pagenow ) || ( 'post.php' !== $pagenow && 'post-new.php' !== $pagenow ) ) {
		return;
	}

	$password_protect = (bool) get_option( 'tenup_password_protect', 0 );

	if ( ! empty( $password_protect ) || ! empty( $post->post_password ) ) {
		return;
	}

	?>
	<style type="text/css">
	#visibility-radio-password,
	label[for="visibility-radio-password"] {
		display: none;
	}

	#editor-post-password-0,
	label[for="editor-post-password-0"],
	#editor-post-password-0-description {
		display: none;
	}
	</style>
	<?php
}
