<?php
/**
 * Force strong password extension functionality
 *
 * @package  10up-experience
 */

namespace TenUpExperience\Passwords;
use ZxcvbnPhp\Zxcvbn;

/**
 * Setup hooks
 *
 * @since 1.7
 */
function setup() {
	//add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\enqueue_scripts_styles' );
	//add_action( 'login_enqueue_scripts', __NAMESPACE__ . '\enqueue_scripts_styles' );

	add_action( 'user_profile_update_errors', __NAMESPACE__ . '\validate_profile_update', 0, 3 );
	add_action( 'validate_password_reset', __NAMESPACE__ . '\validate_strong_password', 10, 2 );
	add_action( 'resetpass_form', __NAMESPACE__ . '\validate_resetpass_form', 10 );
	/*$zxcvbn = new Zxcvbn();
	$weak = $zxcvbn->passwordStrength('password');
	echo $weak['score']; // will print 0
	exit;*/

	load_zxcvbn();
}

/**
 * Load Zxcvbn files manually
 */
function load_zxcvbn() {
	require_once __DIR__ . '/vendor/bjeavons/zxcvbn-php/src/Zxcvbn.php';
	require_once __DIR__ . '/vendor/bjeavons/zxcvbn-php/src/Matcher.php';
	require_once __DIR__ . '/vendor/bjeavons/zxcvbn-php/src/Scorer.php';
	require_once __DIR__ . '/vendor/bjeavons/zxcvbn-php/src/TimeEstimator.php';
	require_once __DIR__ . '/vendor/bjeavons/zxcvbn-php/src/Feedback.php';

	$zxcvbn = new Zxcvbn();
	$weak = $zxcvbn->passwordStrength('password');
	echo $weak['score']; // will print 0
}

/**
 * Setup styles and scripts for passwords
 */
function enqueue_scripts_styles() {
	wp_enqueue_script( '10up-passwords', plugins_url( '/dist/js/passwords.js', __DIR__ ), [], TENUP_EXPERIENCE_VERSION, true );

	wp_localize_script(
		'10up-passwords',
		'tenupPasswords',
		[
			'message' => esc_html__( 'Passwords must be medium strength or greater.' ),
		]
	);

	wp_enqueue_style( '10up-passwords', plugins_url( '/dist/css/passwords-styles.css', __DIR__ ), [], TENUP_EXPERIENCE_VERSION );
}


/**
 * Check user profile update and throw an error if the password isn't strong.
 *
 * @param WP_Error $errors Current potential password errors
 * @param boolean  $update Whether PW update or not
 * @param WP_User  $user_data User being handled
 * @return WP_Error
 */
function validate_profile_update( $errors, $update, $user_data ) {
	return validate_strong_password( $errors, $user_data );
}

/**
 * Check password reset form and throw an error if the password isn't strong.
 *
 * @param WP_User $user_data User being handled
 * @return WP_Error
 */
function validate_resetpass_form( $user_data ) {
	return validate_strong_password( false, $user_data );
}


/**
 * Functionality used by both user profile and reset password validation.
 * @param WP_Error $errors Current potential password errors
 * @param WP_User  $user_data User being handled
 * @return WP_Error
 */
function validate_strong_password( $errors, $user_data ) {
	$password_ok = true;
	$enforce     = true;
	$password    = ( isset( $_POST['pass1'] ) && trim( $_POST['pass1'] ) ) ? sanitize_text_field( $_POST['pass1'] ) : false;
	$role        = isset( $_POST['role'] ) ? sanitize_text_field( $_POST['role'] ) : false;
	$user_id     = isset( $user_data->ID ) ? sanitize_text_field( $user_data->ID ) : false;
	$username    = isset( $_POST['user_login'] ) ? sanitize_text_field( $_POST['user_login'] ) : $user_data->user_login;

	// No password set?
	// Already got a password error?
	if ( ( false === $password ) || ( is_wp_error( $errors ) && $errors->get_error_data( 'pass' ) ) ) {
		return $errors;
	}

	// Should a strong password be enforced for this user?
	if ( $user_id ) {

		// User ID specified.
		$enforce = enforce_for_user( $user_id );

	} else {

		// No ID yet, adding new user - omit check for "weaker" roles.
		if ( $role && in_array( $role, apply_filters( 'tenup_experience_weak_roles', array( 'subscriber' ) ), true ) ) {
			$enforce = false;
		}
	}

	// Enforce?
	if ( $enforce ) {
		$zxcvbn = new Zxcvbn();

		// Check the strength passed from the zxcvbn meter.
		$compare_strong       = html_entity_decode( __( 'strong' ), ENT_QUOTES, 'UTF-8' );
		$compare_strong_reset = html_entity_decode( __( 'hide-if-no-js strong' ), ENT_QUOTES, 'UTF-8' );
		if ( ! in_array( $_POST['slt-fsp-pass-strength-result'], array( null, $compare_strong, $compare_strong_reset ), true ) ) {
			$password_ok = false;
		}
	}

	if ( ! $password_ok && is_wp_error( $errors ) ) {
		$errors->add( 'pass', apply_filters( 'tenup_experience_password_error_message', __( '<strong>ERROR</strong>: Please make the password a strong one.', 'tenup-experience' ) ) );
	}

	return $errors;
}


/**
 * Check whether the given WP user should be forced to have a strong password
 *
 * @since   1.1
 * @param   int $user_id A user ID.
 * @return  boolean
 */
function enforce_for_user( $user_id ) {
	$enforce = true;

	// Force strong passwords from network admin screens.
	if ( is_network_admin() ) {
		return $enforce;
	}

	$check_caps = apply_filters(
		'tenup_experience_strong_password_caps',
		[
			'edit_posts',
		]
	);

	if ( ! empty( $check_caps ) ) {
		$enforce = false; // Now we won't enforce unless the user has one of the caps specified.

		foreach ( $check_caps as $cap ) {
			if ( user_can( $user_id, $cap ) ) {
				$enforce = true;
				break;
			}
		}
	}

	return $enforce;
}
