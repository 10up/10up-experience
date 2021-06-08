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
		add_action( 'init', [ $this, 'maybe_schedule_event' ] );
		add_action( 'tenup_notify_expired_passwords', [ $this, 'notify_expired_passwords' ] );
		add_action( 'user_profile_update_errors', [ $this, 'update_profile' ], 10, 3 );

		// Run duplicate password check after password strength test in TenUpExperience\Authentication\PassWord
		add_action( 'validate_password_reset', [ $this, 'validate_password_reset' ], 11, 2 );
		add_action( 'password_reset', [ $this, 'password_reset' ], 10, 2 );
		add_filter( 'wp_authenticate_user', [ $this, 'prevent_login_for_expired_passwords' ], 10, 2 );
	}

	/**
	 * Remove cron event when plugin is deactivated
	 *
	 * @return void
	 */
	public function deactivate() {
		wp_clear_scheduled_hook( 'tenup_notify_expired_passwords' );
	}

	/**
	 * Schedule tenup_notify_expired_passwords cron event
	 *
	 * @return void
	 */
	public function maybe_schedule_event() {
		if ( ! wp_next_scheduled( 'tenup_notify_expired_passwords' ) ) {
			$time = new \DateTime( current_datetime()->format( 'Y-m-d' ) . ' 1:00:00' ); // Run at 1AM everyday.
			$time->modify( '+1 day' );
			wp_schedule_event( $time->getTimestamp(), 'daily', 'tenup_notify_expired_passwords' );
		}
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

		if ( ! empty( $new_password ) && $new_password === $new_password_confirm && is_array( $user->roles ) && ! empty( array_intersect( $user->roles, $this->get_password_expire_roles() ) ) ) {
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
		if ( is_object( $user ) && is_array( $user->roles ) && ! empty( array_intersect( $user->roles, $this->get_password_expire_roles() ) ) ) {
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
			update_user_meta( $user->ID, self::METAKEY_PASSWORD_EXPIRE, current_datetime()->format( 'Y-m-d' ) );
		}
	}

	/**
	 * Prevent users from authenticating if their current password is expired
	 *
	 * @param WP_User $user User object
	 * @param string  $password current password
	 *
	 * @return \WP_User|\WP_Error
	 */
	public function prevent_login_for_expired_passwords( $user, $password ) {
		$last_updated_password = get_user_meta( $user->ID, self::METAKEY_PASSWORD_EXPIRE, true );
		$password_expiration   = $this->get_password_expired_date();

		if ( empty( $last_updated_password ) || $last_updated_password > $password_expiration && is_array( $user->roles ) && ! empty( array_intersect( $user->roles, $this->get_password_expire_roles() ) ) ) {
			return new \WP_Error(
				'Password Expired',
				// translators: URL to the reset password screen
				sprintf( __( 'Your password has expired please <a href="%s">reset your password</a>.', 'tenup' ), esc_url( wp_lostpassword_url() ) )
			);
		}

		return $user;
	}

	/**
	 * Notify all users that have an expired password
	 *
	 * @return void
	 */
	public function notify_expired_passwords() {
		$today         = current_datetime();
		$reminder_date = $this->get_password_reminder_date();
		$reminder_days = (int) PasswordPolicy::instance()->get_setting( 'reminder' );

		if ( (int) $today->diff( new \DateTime( $reminder_date ) )->format( '%a' ) !== $reminder_days ) {
			return;
		}

		$users = new \WP_User_Query(
			array(
				'role__in'   => $this->get_password_expire_roles(),
				'meta_query' => array(
					array(
						'key'     => self::METAKEY_PASSWORD_EXPIRE,
						'value'   => $this->get_password_expired_date(),
						'compare' => '=',
					),
				),
				'number'     => apply_filters( 'tenup_number_user_query', 250 ),
				'field'      => array( 'user_email', 'user_login' ),
			)
		);

		if ( ! empty( $users->get_results() ) ) {
			$blog_name       = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
			$expiration_text = current_datetime()->modify( "+$reminder_days day" )->format( 'F dS, Y' );
			$message         = PasswordPolicy::instance()->get_setting( 'reminder_email' );
			$message        .= sprintf( '<p>%s</p>', esc_html__( 'To reset your password, visit the following address:', 'tenup' ) );
			// translators: %1$s is the URL to the reset password screen
			$message .= sprintf( '<p><a href="%1$s">%1$s</a></p>', esc_url( wp_lostpassword_url() ) );
			// translators:  %1$s the site url and %2$d Numbers of days a uses password is still good for.
			$subject = sprintf( _n( '[%1$s] Password expires in %2$d day', '[%1$s] Password expires in %2$d days', $reminder_days, 'tenup' ), $blog_name, number_format_i18n( $reminder_days ) );

			foreach ( $users->get_results() as $user ) {
				$custom_message = $message;
				$custom_message = str_replace( '###USERNAME###', $user->user_login, $custom_message );
				$custom_message = str_replace( '###ADMIN_EMAIL###', get_option( 'admin_email' ), $custom_message );
				$custom_message = str_replace( '###EMAIL###', $user->user_email, $custom_message );
				$custom_message = str_replace( '###SITENAME###', $blog_name, $custom_message );
				$custom_message = str_replace( '###SITEURL###', home_url(), $custom_message );
				$custom_message = str_replace( '###DAYSLEFT###', $reminder_days, $custom_message );
				$custom_message = str_replace( '###EXPIRATIONDATE###', $expiration_text, $custom_message );

				wp_mail( $user->user_email, $subject, $custom_message, array( 'Content-Type: text/html; charset=UTF-8' ) );
			}
		}
	}

	/**
	 * List of roles that qualify for password policy
	 *
	 * @return array
	 */
	private function get_password_expire_roles() {
		return apply_filters( 'tenup_password_expire_roles', array( 'administrator', 'editor', 'author' ) );
	}

	/**
	 * Get date for todays expired passwords
	 *
	 * @return string
	 */
	private function get_password_expired_date() {
		$today                     = current_datetime();
		$days_password_is_good_for = (int) PasswordPolicy::instance()->get_setting( 'expires' );
		return $today->modify( "-$days_password_is_good_for day" )->format( 'Y-m-d' );
	}

	/**
	 * Get date for todays passwords reminders
	 *
	 * @return string
	 */
	private function get_password_reminder_date() {
		$today         = current_datetime();
		$reminder_days = (int) PasswordPolicy::instance()->get_setting( 'reminder' );
		return $today->modify( "-$reminder_days day" )->format( 'Y-m-d' );
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
