<?php

/**
 * Gutenberg functionality customizations
 *
 * @package  10up-experience
 */

namespace tenup;

/**
 * Register 10up Gutenberg setting.
 */
function disable_gutenberg_editor_setting() {

	$settings_args = array(
		'type'              => 'integer',
		'sanitize_callback' => 'intval',
	);

	register_setting( 'writing', get_disable_gutenberg_key(), $settings_args );

	add_settings_field(
		get_disable_gutenberg_key(),
		__( 'Use Classic Editor', 'tenup' ),
		 __NAMESPACE__ . '\gutenberg_settings_ui',
		 'writing',
		 'default',
		 array(
			'label_for'	=> 'disable-gutenberg-editor'
		)
	);
}

add_action( 'admin_init', __NAMESPACE__ . '\disable_gutenberg_editor_setting' );

/**
 * Display UI for 10up custom Gutenberg settings.
 *
 * @return void
 */
function gutenberg_settings_ui() {
	$disable_editor = get_option( get_disable_gutenberg_key(), 0 );

	?>
	<fieldset>
		<legend class="screen-reader-text"><?php esc_html_e( 'Gutenberg Editor Settings', 'tenup' ); ?></legend>
		<p>
			<input id="disable-gutenberg-editor" name="<?php echo esc_attr( get_disable_gutenberg_key() ); ?>" type="checkbox" value="1" <?php checked( $disable_editor, 1 ); ?> />
		</label></p>
		<p class="description"><?php esc_html_e( 'Disables Gutenberg, the new editor introduced in WordPress 5.0, in favor of the prior writing experience.', 'tenup' ); ?></p>
	</fieldset>
	<?php
}

/**
 * Get the key for disabling Gutenberg
 */
function get_disable_gutenberg_key() {
	return 'tenup_disable_gutenberg';
}

/**
 * Check to see if the Gutenberg editor should be disabled
 */
function maybe_disable_gutenberg_editor() {

	$disable_editor = get_option( get_disable_gutenberg_key(), 0 );

	if ( 1 !== intval( $disable_editor ) ) {
		return;
	}

	// Disable Gutenberg editor for all posts and post types
	// WordPress 5.0+
	add_filter( 'use_block_editor_for_post', '__return_false' );

	// Gutenberg plugin
	add_filter( 'gutenberg_can_edit_post', '__return_false' );

	return;

}

add_action( 'admin_init', __NAMESPACE__ . '\maybe_disable_gutenberg_editor' );