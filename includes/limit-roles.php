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
 * @param \WP_Error $error  Errors object to add any custom errors to
 * @param boolean   $update true if updating an existing user, false if saving a new user
 * @param \WP_User  $user   User object for user being edited
 */
function confirm_user_email_is_not_whitelisted( $error, $update, $user ) {

	$new_role     = sanitize_text_field( $_POST['role'] );
	$email        = sanitize_text_field( $_POST['email'] );
	$email_domain = substr( strrchr( $email, '@' ), 1 );
	$can_create   = can_create_user( $user, $new_role );

	if ( ! $can_create ) {
		$editable_roles = get_editable_roles();
		$role           = isset( $editable_roles[ $new_role ] ) ? $editable_roles[ $new_role ]['name'] : 'empty';

		$edit_link = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'users.php?page=10up-limit-roles' ) ), esc_html__( 'update your whitelisted domains', 'tenup' ) );
		/* translators: %s is a placeholder for the current role trying to be assigned to a user */
		$error->add( 'invalid_email', sprintf( __( '<strong>ERROR</strong>: Sorry, the domain "%1$s" is ineligible for the %2$s role. Please %3$s or talk to an Administrator.', 'tenup' ), esc_html( $email_domain ), esc_html( $role ), $edit_link ) );
	}
}

add_action( 'user_profile_update_errors', __NAMESPACE__ . '\confirm_user_email_is_not_whitelisted', 10, 3 );

/**
 * Confirm that the users email and role are whitelisted before allowing
 * them to be added to a blog
 *
 * @param bool|WP_Error $boolean True if the user should be added to the site, false
 *                               or error object otherwise.
 * @param int           $user_id User ID.
 * @param string        $role    User role.
 * @param int           $blog_id Site ID.
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
		__( 'Role(s)', 'tenup' ),
		__NAMESPACE__ . '\roles_checkbox',
		'10up-limit-roles',
		'limit_roles'
	);
}

add_action( 'admin_init', __NAMESPACE__ . '\limit_roles_settings' );

/**
 * Output domain text area
 */
function domain_text_area() {
	$options = get_option( 'tenup_limit_roles', array() );
	$value   = ! empty( $options['whitelisted-domains'] ) ? $options['whitelisted-domains'] : '';
	printf( '<textarea id="whitelisted-domains" name="tenup_limit_roles[whitelisted-domains]" class="regular-text" rows="10">%s</textarea>', esc_textarea( $value ) );
	printf( '<p class="description">%s</p>', esc_html__( 'Enter each domain on a new line.', 'tenup' ) );
}

/**
 * Output list of roles available on the site
 */
function roles_checkbox() {
	$options        = get_option( 'tenup_limit_roles', array() );
	$selected_roles = ! empty( $options['roles'] ) ? array_flip( $options['roles'] ) : array();
	echo '<ul>';

	$editable_roles = get_editable_roles();
	foreach ( $editable_roles as $role => $details ) {
		$name    = translate_user_role( $details['name'] );
		$checked = isset( $selected_roles[ $role ] ) ? 'checked' : '';
		printf( '<li><input type="checkbox" id="role-%1$s" name="tenup_limit_roles[roles][]" value="%1$s" %3$s><label for="role-%1$s">%2$s</label></li>', esc_attr( $role ), esc_html( $name ), esc_attr( $checked ) );
	}

	echo '</ul>';
}

/**
 * Sanitize limit roles settings
 *
 * @param array $input List of settings getting saved
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
		<p><?php esc_html_e( 'Limit the roles that may be assigned to users from specific domains.', 'tenup' ); ?></p>

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
 * @param \WP_User $user User that is trying to get updated or added
 * @param string   $role The role the user is trying to be assigned
 *
 * @return bool
 */
function can_create_user( $user, $role ) {
	$can_create = true;
	$options    = get_option( 'tenup_limit_roles' );

	if ( empty( $options ) || empty( $options['whitelisted-domains'] ) || empty( $options['roles'] ) ) {
		return $can_create;
	}

	// whitelisted emails
	$emails = explode( PHP_EOL, $options['whitelisted-domains'] );

	// roles to be checked
	$roles = array_flip( $options['roles'] );

	// if current user is trying to be assigned a limited role
	if ( isset( $roles[ $role ] ) && is_array( $emails ) ) {
		foreach ( $emails as $email ) {
			// users email is does not match a whitelisted one
			if ( false === strpos( strtolower( $user->user_email ), '@' . strtolower( trim( $email ) ) ) ) {
				$can_create = false;
			} else {
				// users email does match a whitelisted one lets stop checking
				$can_create = true;
				break;
			}
		}
	}

	return $can_create;
}
