<?php
/**
 * Limit Role
 *
 * @package  10up-experience
 */

namespace tenup;

/**
 * Confirm that the new users email and role are whitelisted if not throw
 * and error
 *
 * @param $error
 * @param $update
 * @param $user
 */
function confirm_user_email_is_not_whitelisted( $error, $update, $user ) {

	$new_role   = sanitize_text_field( $_POST['role'] );
	$can_create = can_create_user( $user, $new_role );

	if ( ! $can_create ) {
		$editable_roles = get_editable_roles();
		$role           = isset( $editable_roles[ $new_role ] ) ? $editable_roles[ $new_role ]['name'] : 'empty';
		$error->add( 'invalid_email', sprintf( __( '<strong>ERROR</strong>: Sorry, that email is not allowed to have the %s role.' ), esc_html( $role ) ) );
	}
}

add_action( 'user_profile_update_errors', __NAMESPACE__ . '\confirm_user_email_is_not_whitelisted', 10, 3 );

/**
 * Confirm that the users email and role are whitelisted before allowing
 * them to be added to a blog
 *
 * @param $boolean
 * @param $user_id
 * @param $role
 * @param $blog_id
 *
 * @return bool
 */
function confirm_user_email_is_not_whitelisted_add_to_blog( $boolean, $user_id, $role, $blog_id ) {

	$user    = get_user_by( 'id', $user_id );
	$boolean = can_create_user( $user, $role );

	return $boolean;
}

add_filter( 'can_add_user_to_blog', __NAMESPACE__ . '\confirm_user_email_is_not_whitelisted_add_to_blog', 10, 4 );

/**
 * Register limit role settings
 */
function limit_roles_settings() {

	register_setting(
		'tenup_limit_role_fields',
		'tenup_limit_roles',
		__NAMESPACE__ . '\sanitize_options'
	);

	add_settings_section(
		'limit_roles',
		'',
		'__return_false',
		'10up-limit-roles'
	);

	add_settings_field(
		'whitelisted_domains',
		__( 'Whitelisted domains', 'tenup' ),
		__NAMESPACE__ . '\domain_text_area',
		'10up-limit-roles',
		'limit_roles'
	);

	add_settings_field(
		'roles',
		__( 'Roles', 'tenup' ),
		__NAMESPACE__ . '\roles_checkbox',
		'10up-limit-roles',
		'limit_roles'
	);
}

add_action( 'admin_init', __NAMESPACE__ . '\limit_roles_settings' );

/**
 * output domain text area
 */
function domain_text_area() {
	$options = get_option( 'tenup_limit_roles', array() );
	$value   = ! empty( $options['whitelisted-domains'] ) ? $options['whitelisted-domains'] : '';
	printf( '<textarea id="whitelisted-domains" name="tenup_limit_roles[whitelisted-domains]" class="widefat" rows="10">%s</textarea>', esc_textarea( $value ) );
	printf( '<p class="description">%s</p>', esc_html__( 'Enter each domain you would like to whitelist on a new line.', 'tenup' ) );
}

/**
 * output list of roles available on the site
 */
function roles_checkbox() {
	$options        = get_option( 'tenup_limit_roles', array() );
	$selected_roles = ! empty( $options['roles'] ) ? array_flip( $options['roles'] ) : array();
	echo '<ul>';

	$editable_roles = get_editable_roles();
	foreach ( $editable_roles as $role => $details ) {
		$name    = translate_user_role( $details['name'] );
		$checked = isset( $selected_roles[ $role ] ) ? 'checked' : '';
		printf( '<li><input type="checkbox" id="role-%1$s" name="tenup_limit_roles[roles][]" value="%1$s" %3$s><label for="role-%1$s">%2$s</label></li>', esc_attr( $role ), esc_html( $name ), $checked );
	}

	echo '</ul>';

	printf( '<p class="description">%s</p>', esc_html__( 'Select each role you would like to be limited to the whitelist domains.', 'tenup' ) );
}

/**
 * Sanitize limit roles settings
 *
 * @param $input
 *
 * @return array
 */
function sanitize_options( $input ) {
	if ( ! empty( $input['whitelisted-domains'] ) ) {
		$input['whitelisted-domains'] = wp_kses_post( $input['whitelisted-domains'] );
	}

	if ( ! empty( $input['roles'] ) && is_array( $input['roles'] ) ) {
		$roles = array();
		foreach ( $input['roles'] as $role ) {
			$roles[] = sanitize_text_field( $role );
		}
		$input['roles'] = $roles;
	}

	return $input;
}

/**
 * Output limit role screens
 */
function limit_role_screen() {
	?>
	<div class="wrap limit-role-wrap full-width-layout">

		<h1><?php esc_html_e( '10up Limit Role', 'tenup' ); ?></h1>
		<p><?php esc_html_e( 'Limit which domains are allowed to be assigned specific roles.', 'tenup' ); ?></p>

		<form method="post" action="options.php">
			<?php
			settings_fields( 'tenup_limit_role_fields' );
			do_settings_sections( '10up-limit-roles' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Validate that the provided users email is allowed to be
 * used for the selected role
 *
 * @param $user
 * @param $role
 *
 * @return bool
 */
function can_create_user( $user, $role ) {
	$can_create = true;
	$options    = get_option( 'tenup_limit_roles' );

	if ( empty( $options ) || empty( $options['whitelisted-domains'] ) ) {
		return $can_create;
	}

	//whitelisted emails
	$emails = explode( PHP_EOL, $options['whitelisted-domains'] );

	//roles to be checked
	$roles = array_flip( $options['roles'] );

	//if current user is trying to be assigned a limited role
	if ( isset( $roles[ $role ] ) && is_array( $emails ) ) {
		foreach ( $emails as $email ) {
			//users email is does not match a whitelisted one
			if ( false === strpos( strtolower( $user->user_email ), '@' . strtolower( trim( $email ) ) ) ) {
				$can_create = false;
			} else {
				//users email does match a whitelisted one lets stop checking
				$can_create = true;
				break;
			}
		}
	}

	return $can_create;
}