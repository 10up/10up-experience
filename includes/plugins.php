<?php
namespace tenup;

/**
 * Start plugin customizations
 */
function plugin_customizations() {

	/**
	 * Stream
	 */
	if ( is_plugin_active( 'stream/stream.php' ) ) {

		add_action( 'admin_init', function() {
			remove_menu_page( 'wp_stream' );
		}, 11 );
	}
}
add_action( 'admin_init', __NAMESPACE__ . '\plugin_customizations' );

/**
 * Add 10up suggested tab to plugins install screen
 *
 * @param array $tabs
 * @return mixed
 */
function tenup_plugin_install_link( $tabs ) {
	$new_tabs = array(
		'tenup' => esc_html__( '10up Suggested', 'tenup' ),
	);

	foreach ( $tabs as $key => $value ) {
		$new_tabs[ $key ] = $value;
	}

	return $new_tabs;
}
add_action( 'install_plugins_tabs', __NAMESPACE__ . '\tenup_plugin_install_link' );

/**
 * Filter the arguments passed to plugins_api() for 10up suggested page
 *
 * @param array $args
 * @return array
 */
function filter_install_plugin_args( $args ) {
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
add_filter( 'install_plugins_table_api_args_tenup', __NAMESPACE__ . '\filter_install_plugin_args' );

/**
 * Setup 10up suggested plugin display table
 */
add_action( 'install_plugins_tenup', 'display_plugins_table' );

/**
 * Warn user when installing non-10up suggested plugins
 */
function plugin_install_warning() {
	?>
	<div class="tenup-plugin-install-warning updated">
		<p>
			<?php
				printf(
					// translators: %s is a link to the 10up Suggested plugins screen
					__( "Some plugins may affect display, performance, and reliability. Please consider <a href='%s'>10up Suggestions</a> and consult your site team.", 'tenup' ),
					esc_url( network_admin_url( 'plugin-install.php?tab=tenup' ) )
				);
			?>
		</p>
	</div>
	<?php
}
add_action( 'install_plugins_pre_featured', __NAMESPACE__ . '\plugin_install_warning' );
add_action( 'install_plugins_pre_popular', __NAMESPACE__ . '\plugin_install_warning' );
add_action( 'install_plugins_pre_favorites', __NAMESPACE__ . '\plugin_install_warning' );
add_action( 'install_plugins_pre_beta', __NAMESPACE__ . '\plugin_install_warning' );
add_action( 'install_plugins_pre_search', __NAMESPACE__ . '\plugin_install_warning' );
add_action( 'install_plugins_pre_dashboard', __NAMESPACE__ . '\plugin_install_warning' );

/**
 * Inject a small script for an AYS on plugin deactivation.
 *
 * @return void
 */
function plugin_deactivation_warning() {
	$message = __( 'Warning: This plugin provides additional enterprise-grade protective measures such as REST API security and disabling file editing in the dashboard.\n\nAre you sure you want to deactivate?', 'tenup' );
?>
<script type="text/javascript">
jQuery( document ).ready( function( $ ) {
	$( '.wp-list-table.plugins tr[data-slug="10up-experience"] .deactivate' ).on( 'click', function( e ) {
		if ( ! window.confirm( '<?php esc_html_e( $message ); ?>' ) ) {
			e.preventDefault();
		}
	});
});
</script>
<?php
}
add_action( 'admin_head-plugins.php', __NAMESPACE__ . '\plugin_deactivation_warning' );
