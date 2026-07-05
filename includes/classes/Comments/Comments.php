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
	 * Get the list of comment types that should bypass disable comments filters.
	 *
	 * This allows certain comment types (like Block Notes introduced in WordPress 6.9)
	 * to continue functioning even when traditional comments are disabled.
	 *
	 * Block Notes are stored as WP_Comments with a comment_type of 'note' and are used
	 * for collaborative feedback within the block editor. They rely on edit_post capability
	 * rather than comment capabilities.
	 *
	 * @since 1.18.0
	 * @see https://make.wordpress.org/core/2025/11/15/notes-feature-in-wordpress-6-9/
	 *
	 * @return array Array of comment types that should be allowed.
	 */
	protected function get_allowed_comment_types() {
		$allowed_types = array( 'note' );

		/**
		 * Filter the list of comment types that bypass the disable comments feature.
		 *
		 * This allows plugins to extend the list of comment types that should continue
		 * to function when traditional comments are disabled.
		 *
		 * @since 1.18.0
		 *
		 * @param array $allowed_types Array of comment type strings.
		 */
		$allowed_types = apply_filters( 'tenup_experience_disable_comments_allowed_types', $allowed_types );

		return is_array( $allowed_types ) ? $allowed_types : array();
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
		add_settings_field( 'tenup_disable_comments', esc_html__( 'Disable Comments', 'tenup' ), [ $this, 'disable_comments_setting_field_output' ], 'general' );
	}

	/**
	 * Display UI for restrict REST API setting.
	 *
	 * @return void
	 */
	public function disable_comments_setting_field_output() {
		$disable_comments = $this->comments_are_disabled();
		?>
		<fieldset>
			<legend class="screen-reader-text"><span><?php esc_html_e( 'Disable Comments', 'tenup' ); ?></span></legend>
			<label for="tenup-disable-comments-yes">
				<input id="tenup-disable-comments-yes" name="tenup_disable_comments" type="radio" value="yes" <?php checked( $disable_comments, true ); ?> <?php disabled( $this->is_ui_disabled() ); ?>>
				<?php esc_html_e( 'Yes', 'tenup' ); ?>
			</label><br>
			<label for="tenup-disable-comments-no">
				<input id="tenup-disable-comments-no" name="tenup_disable_comments" type="radio" value="no" <?php checked( $disable_comments, false ); ?> <?php disabled( $this->is_ui_disabled() ); ?>>
				<?php esc_html_e( 'No', 'tenup' ); ?>
			</label>
			<p class="description"><?php esc_html_e( 'This will remove all the comments related Ui from the admin and frontend.', 'tenup' ); ?></p>
		</fieldset>
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
						<fieldset>
							<legend class="screen-reader-text"><span><?php esc_html_e( 'Disable Comments', 'tenup' ); ?></span></legend>
							<label for="tenup_disable_comments_yes">
								<input name="tenup_disable_comments" <?php checked( $disable_comments, true ); ?> <?php disabled( $this->is_ui_disabled() ); ?> type="radio" id="tenup_disable_comments_yes" value="yes">
								<?php esc_html_e( 'Yes', 'tenup' ); ?>
							</label><br>
							<label for="tenup_disable_comments_no">
								<input name="tenup_disable_comments" <?php checked( $disable_comments, false ); ?> <?php disabled( $this->is_ui_disabled() ); ?> type="radio" id="tenup_disable_comments_no" value="no">
								<?php esc_html_e( 'No', 'tenup' ); ?>
							</label>
						</fieldset>
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
	 * This filter preserves certain comment types (like Block Notes) while hiding
	 * traditional comments from the frontend.
	 *
	 * @since 1.11.2
	 *
	 * @param array $comments Array of comments.
	 * @param int   $post_id  Post ID.
	 *
	 * @return array Filtered array of comments.
	 */
	public function disable_comments_hide_existing_comments( $comments, $post_id ) {
		$allowed_types = $this->get_allowed_comment_types();

		// If no allowed types, return empty array (original behavior).
		if ( empty( $allowed_types ) ) {
			return array();
		}

		// Filter to only include allowed comment types (e.g., Block Notes).
		return array_filter(
			$comments,
			function ( $comment ) use ( $allowed_types ) {
				return in_array( $comment->comment_type, $allowed_types, true );
			}
		);
	}

	/**
	 * Disable commenting
	 *
	 * Block Notes do not rely on the comments_open or pings_open filters,
	 * so this can safely return false for all contexts.
	 *
	 * @since 1.11.2
	 *
	 * @param bool $open    Whether comments are open.
	 * @param int  $post_id Post ID.
	 *
	 * @return bool Always returns false to disable commenting.
	 */
	public function disable_comments_status( $open, $post_id ) {
		return false;
	}

	/**
	 * Short-circuit WP_Comment_Query
	 *
	 * This filter prevents the default (untyped) comment queries from executing,
	 * but allows queries that explicitly request a specific comment type to run.
	 *
	 * Block Notes (WordPress 6.9+) are stored as WP_Comments with comment_type='note'
	 * and must be able to query the database for the Notes feature to work. We also
	 * honour explicit queries for the standard 'comment' type: disabling comments
	 * removes the UI and frontend display, but should not silently break code that
	 * deliberately queries for comments.
	 *
	 * Both the 'type' and 'type__in' query vars are inspected, and either may be a
	 * string or an array.
	 *
	 * @since 1.11.2
	 * @see https://make.wordpress.org/core/2025/11/15/notes-feature-in-wordpress-6-9/
	 *
	 * @param array|int|null    $comment_data Comment data (null to allow query to proceed).
	 * @param \WP_Comment_Query $query        The WP_Comment_Query instance.
	 *
	 * @return array|int|null Returns $comment_data unchanged to allow the query, or array/int to short-circuit.
	 */
	public function filter_comments_pre_query( $comment_data, $query ) {

		// Only handle WP_Comment_Query instances.
		if ( ! is_a( $query, '\WP_Comment_Query' ) ) {
			return array();
		}

		/*
		 * Comment types that may be queried even when comments are disabled.
		 *
		 * In addition to the allowed types (e.g. Block Notes), the standard
		 * 'comment' type is explicitly queryable so that code which deliberately
		 * queries for comments still receives results. Note that 'comment' is
		 * intentionally NOT part of get_allowed_comment_types(), so traditional
		 * comments remain hidden from the frontend display.
		 */
		$queryable_types = array_merge( $this->get_allowed_comment_types(), array( 'comment' ) );

		// Collect the comment types explicitly requested by the query. Both the
		// 'type' and 'type__in' query vars may hold a string or an array.
		$requested_types = array();

		foreach ( array( 'type', 'type__in' ) as $type_var ) {
			$value = $query->query_vars[ $type_var ] ?? '';

			if ( '' === $value || null === $value || array() === $value ) {
				continue;
			}

			$requested_types = array_merge( $requested_types, (array) $value );
		}

		// Allow queries that explicitly request a queryable comment type to run as normal.
		if ( ! empty( array_intersect( $requested_types, $queryable_types ) ) ) {
			return $comment_data;
		}

		// If this is a count query, return 0 instead of letting WP run the full query.
		if ( ! empty( $query->query_vars['count'] ) ) {
			return 0;
		}

		// Short-circuit all other comment queries.
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
	 * @param bool|string[] $allowed_block_types Array of block type slugs, or boolean to enable/disable all.
	 *
	 * @return bool|string[] Filtered array of block type slugs, or false if all blocks are disabled.
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

		// Respect other filters that have disabled all blocks.
		if ( false === $allowed_block_types ) {
			return false;
		}

		// Get all registered blocks if all blocks are allowed or no explicit list is set.
		if ( true === $allowed_block_types || ! is_array( $allowed_block_types ) || empty( $allowed_block_types ) ) {
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
