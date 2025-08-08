<?php
/**
 * Plugin extension functionality
 *
 * @package  10up-experience
 */

namespace TenUpExperience\Plugins;

use TenUpExperience\Singleton;

/**
 * Plugin class
 */
class Plugins {

	use Singleton;

	/**
	 * Setup module
	 *
	 * @since 1.7
	 */
	public function setup() {
		add_filter( 'install_plugins_tabs', [ $this, 'tenup_plugin_install_link' ] );
		add_filter( 'install_plugins_table_api_args_tenup', [ $this, 'filter_install_plugin_args' ] );

		/**
		 * Setup 10up suggested plugin display table
		 */
		add_action( 'install_plugins_tenup', 'display_plugins_table' );

		add_action( 'install_plugins_pre_featured', [ $this, 'add_admin_notice' ] );
		add_action( 'install_plugins_pre_popular', [ $this, 'add_admin_notice' ] );
		add_action( 'install_plugins_pre_favorites', [ $this, 'add_admin_notice' ] );
		add_action( 'install_plugins_pre_beta', [ $this, 'add_admin_notice' ] );
		add_action( 'install_plugins_pre_search', [ $this, 'add_admin_notice' ] );
		add_action( 'install_plugins_pre_dashboard', [ $this, 'add_admin_notice' ] );
		add_filter( 'plugin_row_meta', [ $this, 'plugin_meta' ], 100, 2 );
		add_action( 'admin_head-plugins.php', [ $this, 'plugin_deactivation_warning' ] );

		/**
		 * If we are disallowing plugin updates using the DISALLOW_FILE_MODS
		 * constant this will still allow the plugin update notification to
		 * show in the wp-admin plugins page.
		 */
		if ( defined( 'DISALLOW_FILE_MODS' ) && DISALLOW_FILE_MODS ) {
			add_action( 'load-plugins.php', [ $this, 'set_plugin_update_actions' ], 21 );
			add_action( 'admin_menu', [ $this, 'set_plugin_menu_update_count' ], 99 );
			add_action( 'network_admin_menu', [ $this, 'set_plugin_menu_update_count' ], 99 );
			add_filter( 'show_advanced_plugins', [ $this, 'set_global_plugin_data' ], 10 );
			add_filter( 'show_network_active_plugins', [ $this, 'set_global_plugin_data' ], 10 );
		}
	}

	/**
	 * Add 10up suggested tab to plugins install screen
	 *
	 * @param array $tabs Array of tabs.
	 * @return mixed
	 */
	public function tenup_plugin_install_link( $tabs ) {
		$new_tabs = array(
			'tenup' => esc_html__( '10up Suggested', 'tenup' ),
		);

		foreach ( $tabs as $key => $value ) {
			$new_tabs[ $key ] = $value;
		}

		return $new_tabs;
	}

	/**
	 * Filter the arguments passed to plugins_api() for 10up suggested page
	 *
	 * @param array $args Plugin arguments passed to api.
	 * @return array
	 */
	public function filter_install_plugin_args( $args ) {
		$args = array(
			'page'     => 1,
			'per_page' => 60,
			'fields'   => array(
				'last_updated'    => true,
				'active_installs' => true,
				'icons'           => true,
			),
			'locale'   => get_user_locale(),
			'user'     => '10up',
		);

		return $args;
	}

	/**
	 * Add admin notice
	 */
	public function add_admin_notice() {
		add_action( 'admin_notices', [ $this, 'plugin_install_warning' ] );
		add_action( 'network_admin_notices', [ $this, 'plugin_install_warning' ] );
	}

	/**
	 * Warn user when installing non-10up suggested plugins
	 */
	public function plugin_install_warning() {
		?>
		<div class="notice notice-warning">
			<p>
				<?php
					echo wp_kses_post(
						sprintf(
							// translators: %s is a link to the 10up Suggested plugins screen
							__( "Some plugins may affect display, performance, and reliability. Please consider <a href='%s'>10up Suggestions</a> and consult your site team.", 'tenup' ),
							esc_url( network_admin_url( 'plugin-install.php?tab=tenup' ) )
						)
					);
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Add a "learn more" link to the plugin row that points to the admin page.
	 *
	 * @param array  $plugin_meta An array of the plugin's metadata,
	 *                            including the version, author,
	 *                            author URI, and plugin URI.
	 * @param string $plugin_file Path to the plugin file, relative to the plugins directory.
	 *
	 * @return array
	 */
	public function plugin_meta( $plugin_meta, $plugin_file ) {
		if ( '10up-experience/10up-experience.php' !== $plugin_file ) {
			return $plugin_meta;
		}

		$plugin_meta[] = '<a href="' . esc_url( admin_url( 'admin.php?page=10up-about' ) ) . '">' . esc_html__( 'Learn more', 'tenup' ) . '</a>';
		return $plugin_meta;
	}

	/**
	 * Inject a small script for an AYS on plugin deactivation.
	 *
	 * @return void
	 */
	public function plugin_deactivation_warning() {
		$message = esc_html__( "Warning: This plugin provides additional enterprise-grade protective measures such as REST API security and disabling file editing in the dashboard.\n\nAre you sure you want to deactivate?", 'tenup' );
		?>
		<script type="text/javascript">
		jQuery( document ).ready( function( $ ) {
			$( '.wp-list-table.plugins tr[data-slug="10up-experience"] .deactivate' ).on( 'click', function( e ) {
				if ( ! window.confirm( '<?php echo esc_js( $message ); ?>' ) ) {
					e.preventDefault();
				}
			});
		});
		</script>
		<?php
	}


	/**
	 * Set custom action which will output the update notification
	 * on all plugins which are needing to be updated.
	 *
	 * Because of the user capabilities set by the DISALLOW_FILE_MODS
	 * constant it is not possible to update any plugin.
	 */
	public function set_plugin_update_actions() {

		/**
		 * VIP has it's own update notification system that performs this same logic.
		 *
		 * As such, if we're on a VIP environment, we should not run this logic.
		 *
		 * See: https://github.com/Automattic/vip-go-mu-plugins/blob/develop/codebase-manager/plugins/plugins-manager.php#L65-L77
		 */
		if ( defined( 'VIP_GO_APP_ENVIRONMENT' ) ) {
			return;
		}

		$plugins = get_site_transient( 'update_plugins' );

		if ( isset( $plugins->response ) && is_array( $plugins->response ) ) {
			$plugins = array_keys( $plugins->response );

			foreach ( $plugins as $plugin_file ) {
				add_action( "after_plugin_row_$plugin_file", [ $this, 'set_custom_update_notification' ], 10, 2 );
			}
		}
	}

	/**
	 * Set the custom update notification for plugins which require
	 * updates.
	 *
	 * @param string $file        Plugin basename.
	 * @param array  $plugin_data Plugin information.
	 */
	public function set_custom_update_notification( $file, $plugin_data ) {
		$current = get_site_transient( 'update_plugins' );
		if ( ! isset( $current->response[ $file ] ) ) {
			return false;
		}

		$response            = $current->response[ $file ];
		$plugins_allowedtags = array(
			'a'       => array(
				'href'  => array(),
				'title' => array(),
			),
			'abbr'    => array( 'title' => array() ),
			'acronym' => array( 'title' => array() ),
			'code'    => array(),
			'em'      => array(),
			'strong'  => array(),
		);

		/**
		 * WP Plugins List Table
		 *
		 * @var WP_Plugins_List_Table $wp_list_table
		 */
		$wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );

		if ( is_network_admin() ) {
			$active_class = is_plugin_active_for_network( $file ) ? ' active' : '';
		} else {
			$active_class = is_plugin_active( $file ) ? ' active' : '';
		}

		printf(
			'<tr class="plugin-update-tr%s" id="%s" data-slug="%s" data-plugin="%s"><td colspan="%s" class="plugin-update colspanchange"><div class="update-message notice inline notice-warning notice-alt"><p>',
			esc_attr( $active_class ),
			esc_attr( $response->slug . '-update' ),
			esc_attr( $response->slug ),
			esc_attr( $file ),
			esc_attr( $wp_list_table->get_column_count() )
		);

		printf(
			// translators: %s is the plugin name
			esc_html__( 'There is a new version of %s available. ' ),
			wp_kses( $plugin_data['Name'], $plugins_allowedtags )
		);

		$url = $plugin_data['PluginURI'];

		if ( empty( $url ) ) {
			$url = $plugin_data['url'];
		}

		if ( empty( $url ) ) {
			printf(
				// translators: %s is the new version number
				esc_html__( 'Version number is %s.' ),
				esc_html( $response->new_version )
			);
		} else {
			printf(
				// translators: %1$s is the plugin URL, %2$s is the new version number
				'<a href="%1$s" target="_blank">' . esc_html__( 'View version %2$s details.' ) . '</a>',
				esc_url( $url ),
				esc_html( $response->new_version )
			);
		}

		print( '</p></div></td></tr>' );
	}

	/**
	 * Set the update count in the WP Admin Plugin menu item
	 * when the DISALLOW_FILE_MODS constant is set. This will indicate
	 * when plugins are needing to be updated.
	 *
	 * @global $menu The list of WP Admin menu items.
	 */
	public function set_plugin_menu_update_count() {
		global $menu;

		$menu_index = 65; // wp-admin single site or site on network
		if ( is_multisite() ) {
			$menu_index = 20; // wp-admin network settings
		}

		$update_data    = wp_get_update_data();
		$update_plugins = get_site_transient( 'update_plugins' );
		if ( ! empty( $update_plugins->response ) ) {
			$update_data['counts']['plugins'] = count( $update_plugins->response );
		}

		if ( 1 > $update_data['counts']['plugins'] ) {
			return;
		}

		$count = sprintf(
			'<span class="update-plugins count-%d"><span class="plugin-count">%d</span></span>',
			esc_attr( $update_data['counts']['plugins'] ),
			number_format_i18n( $update_data['counts']['plugins'] )
		);

		// Ensure the core Plugins menu item is set to the correct index.
		if ( isset( $menu[ $menu_index ][0] ) && ! preg_match( '#^' . esc_html__( 'Plugins' ) . '#i', $menu[ $menu_index ][0] ) ) {
			return;
		}

		// phpcs:disable
		$menu[ $menu_index ][0] = sprintf( __( 'Plugins %s' ), $count );
		// phpcs:enable
	}

	/**
	 * Set plugin update total counts.
	 *
	 * When the DISALLOW_FILE_MODS is set all plugin counts
	 * are set to 0. This sets the plugin update totals so
	 * that the counts are displayed in the wp-admin.
	 *
	 * @param array $update_data An array of counts for available plugin, theme, and WordPress updates.
	 *
	 * @return array $update_data.
	 */
	public function set_plugin_update_totals( $update_data ) {
		$counts = $update_data['counts'];
		$titles = $update_data['title'];

		$update_plugins = get_site_transient( 'update_plugins' );
		if ( ! empty( $update_plugins->response ) ) {
			$counts['plugins'] = count( $update_plugins->response );
			$plugins_title     = sprintf(
				// translators: %d is the number of plugin updates
				_n( '%d Plugin Update', '%d Plugin Updates', intval( $counts['plugins'] ) ),
				intval( $counts['plugins'] )
			);
			$titles = ! empty( $titles ) ? $titles . ', ' . esc_attr( $plugins_title ) : esc_attr( $plugins_title );
		}
		$counts['total'] = $counts['total'] + $counts['plugins'];

		return array(
			'counts' => $counts,
			'title'  => $titles,
		);
	}

	/**
	 * Set the upgrade data for the global plugins variable when
	 * DISALLOW_FILE_MODS constant is true.
	 *
	 * Leverages two filters in `prepare_items1 of class-wp-plugins-list-table.php
	 * hackishly.
	 *
	 * @param  boolean $value Original filter value.
	 * @return boolean
	 */
	public function set_global_plugin_data( $value ) {
		global $plugins;

		if ( ! isset( $plugins['all'] ) ) {
			return;
		}

		$current = get_site_transient( 'update_plugins' );

		foreach ( (array) $plugins['all'] as $plugin_file => $plugin_data ) {
			if ( isset( $current->response[ $plugin_file ] ) ) {
				// phpcs:disable
				$plugins['all'][ $plugin_file ]['update'] = true;
				$plugins['upgrade'][ $plugin_file ]       = $plugins['all'][ $plugin_file ];
				// phpcs:enable
			}
		}

		add_filter( 'wp_get_update_data', [ $this, 'set_plugin_update_totals' ], 10 );

		return $value;
	}
}
