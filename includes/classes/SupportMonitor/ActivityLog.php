<?php
/**
 * Logs critical user activities inside Support Monitor
 *
 * @since  1.9
 * @package 10up-experience
 */

namespace TenUpExperience\SupportMonitor;

use TenUpExperience\Singleton;

/**
 * Activity log class
 */
class ActivityLog extends Singleton {
	/**
	 * Setup module
	 *
	 * @since 1.7
	 */
	public function setup() {

		add_action( 'profile_update', [ $this, 'profile_update' ], 10, 3 );
	}

	/**
	 * Log profile update
	 *
	 * @param int     $user_id       User ID.
	 * @param WP_User $old_user_data Object containing user's data prior to update.
	 * @param array   $userdata      The raw array of data passed to wp_insert_user(
	 */
	public function profile_update( $user_id, $old_user_data, $userdata ) {
		Monitor::instance()->log(
			'User ' . $user_id . ' updated their profile.',
			'profile'
		);
	}
}
