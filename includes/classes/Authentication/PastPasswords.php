<?php
/**
 * Store a users past passwords
 *
 * @package  10up-experience
 */

namespace TenUpExperience\Authentication;

use TenUpExperience\AdminCustomizations\PasswordPolicy;
use TenUpExperience\Singleton;

/**
 * Past Password functionality
 */
class PastPasswords extends Singleton {

	/**
	 * Meta key for list of past password hashes
	 */
	const METAKEY_PASSWORD = '_tenup_past_passwords';

	/**
	 * Meta key for last time password was updated
	 */
	const METAKEY_PASSWORD_EXPIRE = '_tenup_password_last_updated';

	/**
	 * Setup hook sand filters
	 */
	public function setup() {
		add_action( 'user_profile_update_errors', [ $this, 'update_profile' ], 10, 3 );

		// Run duplicate password check after password strength test in TenUpExperience\Authentication\PassWord
		add_action( 'validate_password_reset', [ $this, 'validate_password_reset' ], 11, 2 );
		add_action( 'password_reset', [ $this, 'password_reset' ], 10, 2 );
	}

	/**
	 * If the current users profile update includes changing their password
	 * let's confirm that their new password does not match the current
	 * or past passwords.
	 *
	 * @param WP_Error    $errors Errors object to add any custom errors to
	 * @param boolean|int $update true if updating an existing user, false if saving a new user
	 * @param object      $user   User object for user being edited
	 */
	public function update_profile( $errors, $update, $user ) {
		$new_password         = filter_input( INPUT_POST, 'pass1', FILTER_SANITIZE_STRING );
		$new_password_confirm = filter_input( INPUT_POST, 'pass2', FILTER_SANITIZE_STRING );
		if ( $update && ! empty( $new_password ) && $new_password === $new_password_confirm ) {

			$this->validate_password_reset( $errors, $user );

			if ( empty( $errors->get_error_codes() ) ) {
				$this->save_current_password( $user );
			}
		}
	}

	/**
	 * Confirm that the password being used in a reset does not match the
	 * existing or past passwords.
	 *
	 * @param WP_Error $errors Errors object to add any custom errors to
	 * @param object   $user   User object for user being edited
	 */
	public function validate_password_reset( $errors, $user ) {
		$new_password         = filter_input( INPUT_POST, 'pass1', FILTER_SANITIZE_STRING );
		$new_password_confirm = filter_input( INPUT_POST, 'pass2', FILTER_SANITIZE_STRING );

		if ( ! empty( $new_password ) && $new_password === $new_password_confirm ) {
			if ( $this->invalid_duplicate( $user, $new_password ) ) {
				$errors->add( 'duplicate_password', __( 'This password has previously been used, you must select a unique password', 'tenup' ) );
			}
		}
	}

	/**
	 * Store the users existing password before resetting their password.
	 *
	 * @param object $user         User object for user being edited
	 * @param string $new_password New password
	 */
	public function password_reset( $user, $new_password ) {
		$this->save_current_password( $user );
	}

	/**
	 * Check to see if the new password does not match the users existing
	 * password or one of their previous ones.
	 *
	 * @param object $user         User object for user being edited
	 * @param string $new_password New password
	 *
	 * @return bool
	 */
	private function invalid_duplicate( $user, $new_password ) {

		$is_invalid      = false;
		$old_passwords   = (array) get_user_meta( $user->ID, self::METAKEY_PASSWORD, true );
		$old_passwords[] = $this->get_current_password( $user );

		foreach ( $old_passwords as $old_password ) {
			if ( wp_check_password( $new_password, $old_password, $user->ID ) ) {
				$is_invalid = true;
				break;
			}
		}

		return $is_invalid;
	}

	/**
	 * Save the current users password to meta so we can use it
	 * in the future to make sure they are not using an existing password
	 *
	 * @param object $user User object for user being edited
	 */
	private function save_current_password( $user ) {
		if ( is_object( $user ) ) {
			$max_password  = (int) PasswordPolicy::instance()->get_setting( 'past_passwords' );
			$old_passwords = (array) get_user_meta( $user->ID, self::METAKEY_PASSWORD, true );

			$old_passwords[]    = $this->get_current_password( $user );
			$old_passwords      = array_filter( $old_passwords );
			$old_password_count = count( $old_passwords );

			// Limit the old password based on the password policy setting
			if ( $old_password_count > $max_password ) {
				array_splice( $old_passwords, $old_password_count - $max_password );
			}

			update_user_meta( $user->ID, self::METAKEY_PASSWORD, $old_passwords );
			update_user_meta( $user->ID, self::METAKEY_PASSWORD_EXPIRE, current_time( 'Y-m-d' ) );
		}
	}

	/**
	 * Return the current password for the user from the Database
	 *
	 * @param object $user User object for user being edited
	 *
	 * @return mixed
	 */
	private function get_current_password( $user ) {
		$user_data = get_user_by( 'id', $user->ID );

		return $user_data->user_pass;
	}
}
