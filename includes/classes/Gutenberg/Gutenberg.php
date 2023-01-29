<?php
/**
 * Gutenberg functionality customizations
 *
 * @package  10up-experience
 */

namespace TenUpExperience\Gutenberg;

use TenUpExperience\Singleton;

/**
 * Gutenberg class
 */
class Gutenberg {

	use Singleton;

	/**
	 * Setup module
	 *
	 * @since 1.7
	 */
	public function setup() {
		add_action( 'admin_init', [ $this, 'disable_gutenberg_editor_setting' ] );
		add_action( 'admin_init', [ $this, 'maybe_disable_gutenberg_editor' ] );
	}

	/**
	 * Register 10up Gutenberg setting.
	 */
	public function disable_gutenberg_editor_setting() {

		$settings_args = array(
			'type'              => 'integer',
			'sanitize_callback' => 'intval',
		);

		register_setting( 'writing', $this->get_disable_gutenberg_key(), $settings_args );

		add_settings_field(
			$this->get_disable_gutenberg_key(),
			esc_html__( 'Use Classic Editor', 'tenup' ),
			[ $this, 'gutenberg_settings_ui' ],
			'writing',
			'default',
			array(
				'label_for' => 'disable-gutenberg-editor',
			)
		);
	}

	/**
	 * Display UI for 10up custom Gutenberg settings.
	 *
	 * @return void
	 */
	public function gutenberg_settings_ui() {
		$disable_editor = get_option( $this->get_disable_gutenberg_key(), 0 );

		?>
		<fieldset>
			<legend class="screen-reader-text"><?php esc_html_e( 'Gutenberg Editor Settings', 'tenup' ); ?></legend>
			<label for="disable-gutenberg-editor">
				<input id="disable-gutenberg-editor" name="<?php echo esc_attr( $this->get_disable_gutenberg_key() ); ?>" type="checkbox" value="1" <?php checked( $disable_editor, 1 ); ?> />
				<?php esc_html_e( 'Disables Gutenberg, the new editor introduced in WordPress 5.0, in favor of the prior writing experience.', 'tenup' ); ?>
			</label>
		</fieldset>
		<?php
	}

	/**
	 * Get the key for disabling Gutenberg
	 */
	public function get_disable_gutenberg_key() {
		return 'tenup_disable_gutenberg';
	}

	/**
	 * Check to see if the Gutenberg editor should be disabled
	 */
	public function maybe_disable_gutenberg_editor() {

		$disable_editor = get_option( $this->get_disable_gutenberg_key(), 0 );

		if ( 1 !== intval( $disable_editor ) ) {
			return;
		}

		// Disable Gutenberg editor for all posts and post types
		// WordPress 5.0+
		add_filter( 'use_block_editor_for_post', '__return_false' );

		// Gutenberg plugin
		add_filter( 'gutenberg_can_edit_post', '__return_false' );

		// Disable Widgets block editor, by default it is enabled in WordPress 5.8
		add_filter( 'use_widgets_block_editor', '__return_false' );
	}
}
