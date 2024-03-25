<?php
/**
 * Disable Comments
 *
 * @package 10up-experience
 */

namespace TenUpExperience\Comments;

use TenUpExperience\Singleton;

/**
 * Comments class
 */
class Comments {

	use Singleton;

	/**
	 * Setup module
	 *
	 * @since 1.11.2
	 */
	public function setup() {
		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			add_action( 'wpmu_options', [ $this, 'disable_comments_settings' ] );
			add_action( 'admin_init', [ $this, 'save_disable_comments_settings' ] );
		} else {
			add_action( 'admin_init', [ $this, 'single_site_setting' ] );
		}

		// Remove comments support from posts and pages
		add_action( 'init', [ $this, 'disable_comments_post_types_support' ] );

		// Remove comments-related UI elements
		add_action( 'admin_menu', [ $this, 'remove_comments_admin_menus' ] );
		add_action( 'wp_before_admin_bar_render', [ $this, 'remove_comments_admin_bar_links' ] );
	}

	/**
	 * Get the setting
	 *
	 * @return array
	 */
	public function get_setting() {
		$setting = ( TENUP_EXPERIENCE_IS_NETWORK ) ? get_site_option( 'tenup_disable_comments', 'no' ) : get_option( 'tenup_disable_comments', 'no' );

		return $setting;
	}

	/**
	 * Register restrict REST API setting.
	 *
	 * @return void
	 */
	public function single_site_setting() {
		$settings_args = array(
			'type'              => 'string',
			'sanitize_callback' => [ $this, 'validate_setting' ],
		);

		register_setting( 'general', 'tenup_disable_comments', $settings_args );
		add_settings_field( 'tenup_disable_comments', esc_html__( 'Disable Comments', 'tenup' ), [ $this, 'diable_comments_setting_field_output' ], 'general' );
	}

	/**
	 * Display UI for restrict REST API setting.
	 *
	 * @return void
	 */
	public function diable_comments_setting_field_output() {
		$disable_comments = $this->get_setting();
		?>

		<input id="tenup-disable-comments-yes" name="tenup_disable_comments" type="radio" value="yes"<?php checked( $disable_comments, 'yes' ); ?> />
		<label for="tenup-disable-comments-yes">
			<?php esc_html_e( 'Yes', 'tenup' ); ?>
		</label><br>

		<input id="tenup-disable-comments-no" name="tenup_disable_comments" type="radio" value="no"<?php checked( $disable_comments, 'no' ); ?> />
		<label for="tenup-disable-comments-no">
			<?php esc_html_e( 'No', 'tenup' ); ?>
		</label>
		<p class="description"><?php esc_html_e( 'This will remove all the comments related Ui from the admin and frontend.', '10up' ); ?></p>
		<?php
	}

	/**
	 * Output multisite settings
	 *
	 * @return void
	 */
	public function disable_comments_settings() {
		$setting = $this->get_setting();
		?>
		<h2><?php esc_html_e( 'Disable Comments', 'tenup' ); ?></h2>
		<p><?php esc_html_e( 'This will remove all the comments related Ui from the admin and frontend.', '10up' ); ?></p>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><?php esc_html_e( 'Disable Comments', 'tenup' ); ?></th>
					<td>
						<input name="tenup_disable_comments" <?php checked( 'yes', $setting ); ?> type="radio" id="tenup_disable_comments_yes" value="yes"> <label for="tenup_disable_comments_yes"><?php esc_html_e( 'Yes', 'tenup' ); ?></label><br>
						<input name="tenup_disable_comments" <?php checked( 'no', $setting ); ?> type="radio" id="tenup_disable_comments_no" value="no"> <label for="tenup_disable_comments_no"><?php esc_html_e( 'No', 'tenup' ); ?></label>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Set options in multisite
	 *
	 * @return void
	 */
	public function save_disable_comments_settings() {
		global $pagenow;
		if ( ! is_network_admin() ) {
			return;
		}

		if ( 'settings.php' !== $pagenow ) {
			return;
		}

		if ( ! is_super_admin() ) {
			return;
		}

		// We're only checking if the nonce exists here, so no need to sanitize.
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'siteoptions' ) ) {
			return;
		}

		// We're only checking if the var exists here, so no need to sanitize.
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		if ( ! isset( $_POST['tenup_disable_comments'] ) ) {
			return;
		}

		$setting = $this->validate_setting( sanitize_text_field( $_POST['tenup_disable_comments'] ) );

		update_site_option( 'tenup_disable_comments', $setting );
	}

	/**
	 * Validate the setting.
	 *
	 * @param  string $value Current restriction.
	 * @return string
	 */
	public function validate_setting( $value ) {
		if ( in_array( $value, array( 'yes', 'no' ), true ) ) {
			return $value;
		}

		return 'yes';
	}

	/**
	 * Remove comments support from posts and pages
	 *
	 * @return void
	 */
	public function disable_comments_post_types_support() {
		$post_types = get_post_types();
		foreach ( $post_types as $post_type ) {
			if ( post_type_supports( $post_type, 'comments' ) ) {
				remove_post_type_support( $post_type, 'comments' );
				remove_post_type_support( $post_type, 'trackbacks' );
			}
		}
	}

	/**
	 * Remove comments admin menus
	 *
	 * @return void
	 */
	public function remove_comments_admin_menus() {
		remove_menu_page( 'edit-comments.php' );
	}

	/**
	 * Remove comments admin bar links
	 *
	 * @return void
	 */
	public function remove_comments_admin_bar_links() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'comments' );
	}
}
