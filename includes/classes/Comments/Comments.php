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

		if ( ! $this->comments_are_disabled() ) {
			return;
		}

		// Remove comments support from posts and pages
		add_action( 'init', [ $this, 'disable_comments_post_types_support' ] );

		// Remove comments-related UI elements
		add_action( 'admin_menu', [ $this, 'remove_comments_admin_menus' ] );
		add_action( 'wp_before_admin_bar_render', [ $this, 'remove_comments_admin_bar_links' ] );

		// Hide any existing comments on front end
		add_filter( 'comments_array', [ $this, 'disable_comments_hide_existing_comments' ], 10, 2 );
		add_filter( 'comments_open', [ $this, 'disable_comments_status' ], 20, 2 );
		add_filter( 'pings_open', [ $this, 'disable_comments_status' ], 20, 2 );

		// Short-circuit WP_Comment_Query.
		add_filter( 'comments_pre_query', [ $this, 'filter_comments_pre_query' ], 10, 2 );

		// Remove comment feeds.
		add_filter( 'feed_links_show_comments_feed', '__return_false' );
		add_filter( 'feed_links_extra_show_post_comments_feed', '__return_false' );

		// Remove the comment widget.
		add_action( 'widgets_init', [ $this, 'remove_comment_widget' ], 1 );

		// Remove the comment blocks.
		add_action( 'allowed_block_types_all', [ $this, 'remove_comment_blocks' ], PHP_INT_MAX );
	}

	/**
	 * Get the setting
	 *
	 * @return boolean
	 */
	public function comments_are_disabled() {
		// If the constant is defined, use it.
		if ( defined( 'TENUP_DISABLE_COMMENTS' ) ) {
			return boolval( TENUP_DISABLE_COMMENTS );
		}

		// If the filter is set, use it.
		if ( has_filter( 'tenup_experience_disable_comments' ) ) {
			return boolval( apply_filters( 'tenup_experience_disable_comments', false ) );
		}

		// Otherwise, check the setting.
		$setting = ( TENUP_EXPERIENCE_IS_NETWORK ) ? get_site_option( 'tenup_disable_comments', 'no' ) : get_option( 'tenup_disable_comments', 'no' );

		return 'yes' === $setting;
	}

	/**
	 * Check if the UI is disabled
	 *
	 * @return boolean
	 */
	protected function is_ui_disabled() {
		return defined( 'TENUP_DISABLE_COMMENTS' ) || has_filter( 'tenup_experience_disable_comments' );
	}

	/**
	 * Register restrict REST API setting.
	 *
	 * @return void
	 */
	public function single_site_setting() {
		$settings_args = [
			'type'              => 'string',
			'sanitize_callback' => [ $this, 'validate_setting' ],
		];

		register_setting( 'general', 'tenup_disable_comments', $settings_args );
		add_settings_field( 'tenup_disable_comments', esc_html__( 'Disable Comments', 'tenup' ), [ $this, 'diable_comments_setting_field_output' ], 'general' );
	}

	/**
	 * Display UI for restrict REST API setting.
	 *
	 * @return void
	 */
	public function diable_comments_setting_field_output() {
		$disable_comments = $this->comments_are_disabled();
		?>

		<input id="tenup-disable-comments-yes" name="tenup_disable_comments" type="radio" value="yes"
		<?php
		checked( $disable_comments, true );
		disabled( $this->is_ui_disabled() );
		?>
		 />
		<label for="tenup-disable-comments-yes">
			<?php esc_html_e( 'Yes', 'tenup' ); ?>
		</label><br>

		<input id="tenup-disable-comments-no" name="tenup_disable_comments" type="radio" value="no"
		<?php
		checked( $disable_comments, false );
		disabled( $this->is_ui_disabled() );
		?>
		 />
		<label for="tenup-disable-comments-no">
			<?php esc_html_e( 'No', 'tenup' ); ?>
		</label>
		<p class="description"><?php esc_html_e( 'This will remove all the comments related Ui from the admin and frontend.', 'tenup' ); ?></p>
		<?php
	}

	/**
	 * Output multisite settings
	 *
	 * @return void
	 */
	public function disable_comments_settings() {
		$disable_comments = $this->comments_are_disabled();
		?>
		<h2><?php esc_html_e( 'Disable Comments', 'tenup' ); ?></h2>
		<p><?php esc_html_e( 'This will remove all the comments related Ui from the admin and frontend.', 'tenup' ); ?></p>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><?php esc_html_e( 'Disable Comments', 'tenup' ); ?></th>
					<td>
						<input name="tenup_disable_comments"
						<?php
						checked( $disable_comments, true );
						disabled( $this->is_ui_disabled() );
						?>
						 type="radio" id="tenup_disable_comments_yes" value="yes"> <label for="tenup_disable_comments_yes"><?php esc_html_e( 'Yes', 'tenup' ); ?></label><br>
						<input name="tenup_disable_comments"
						<?php
						checked( $disable_comments, false );
						disabled( $this->is_ui_disabled() );
						?>
						 type="radio" id="tenup_disable_comments_no" value="no"> <label for="tenup_disable_comments_no"><?php esc_html_e( 'No', 'tenup' ); ?></label>
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
		if ( in_array( $value, [ 'yes', 'no' ], true ) ) {
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

	/**
	 * Hide any existing comments on front end
	 *
	 * @return array
	 */
	public function disable_comments_hide_existing_comments() {
		return [];
	}

	/**
	 * Disable commenting
	 *
	 * @return boolean
	 */
	public function disable_comments_status() {
		return false;
	}

	/**
	 * Short-circuit WP_Comment_Query
	 *
	 * @param array $comment_data Comment data.
	 * @param array $query        Query data.
	 *
	 * @return array|int|null
	 */
	public function filter_comments_pre_query( $comment_data, $query ) {

		if ( is_a( $query, '\WP_Comment_Query' ) && $query->query_vars['count'] ) {
			return 0;
		}

		return array();
	}

	/**
	 * Remove the comment widget
	 *
	 * @return void
	 */
	public function remove_comment_widget() {
		unregister_widget( 'WP_Widget_Recent_Comments' );
	}

	/**
	 * Remove the comment blocks
	 *
	 * @param array $allowed_block_types Array of allowed block types.
	 *
	 * @return array
	 */
	public function remove_comment_blocks( $allowed_block_types ) {
		// A list of disallowed comment blocks.
		$disallowed_blocks = [
			'core/comment-author-name',
			'core/comment-content',
			'core/comment-date',
			'core/comment-edit-link',
			'core/comment-reply-link',
			'core/comment-template',
			'core/comments',
			'core/comments-pagination',
			'core/comments-pagination-next',
			'core/comments-pagination-numbers',
			'core/comments-pagination-previous',
			'core/comments-title',
			'core/post-comments',
			'core/post-comments-form',
			'core/latest-comments',
		];

		/**
		 * Filter the list of disallowed comment blocks.
		 *
		 * @param array $disallowed_blocks Array of disallowed comment blocks.
		 */
		$disallowed_blocks = apply_filters( 'tenup_experience_disable_comments_disallowed_blocks', $disallowed_blocks );

		// Get all registered blocks if $allowed_block_types is not already set.
		if ( ! is_array( $allowed_block_types ) || empty( $allowed_block_types ) ) {
			$registered_blocks   = \WP_Block_Type_Registry::get_instance()->get_all_registered();
			$allowed_block_types = array_keys( $registered_blocks );

		}

		// Create a new array for the allowed blocks.
		$filtered_blocks = array();

		// Loop through each block in the allowed blocks list.
		foreach ( $allowed_block_types as $block ) {

			// Check if the block is not in the disallowed blocks list.
			if ( ! in_array( $block, $disallowed_blocks, true ) ) {

				// If it's not disallowed, add it to the filtered list.
				$filtered_blocks[] = $block;
			}
		}

		// Return the filtered list of allowed blocks
		return $filtered_blocks;
	}
}
