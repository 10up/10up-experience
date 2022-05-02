<?php
/**
 * 10up Support Monitor Code. This module logs non-PII actions for debugging and troubleshooting
 *
 * @since  1.9
 * @package 10up-experience
 */

namespace TenUpExperience\SupportMonitor;

use TenUpExperience\Singleton;

/**
 * Activity Logging class
 */
class ActivityLogger extends Singleton {
	/**
	 * Setup module
	 *
	 * @since 1.9
	 */
	public function setup() {

		if ( TENUP_EXPERIENCE_IS_NETWORK ) {
			add_action( 'wpmu_options', [ $this, 'ms_settings' ] );
			add_action( 'admin_init', [ $this, 'ms_save_settings' ] );
		} else {
			add_action( 'admin_init', [ $this, 'register_settings' ] );
		}

		if ( defined( 'TENUP_DISABLE_ACTIVITYLOG' ) && TENUP_DISABLE_ACTIVITYLOG ) {
			return;
		}
		
		/**
		 * Things that are being logged:
		 * User Changes (type: user)
			* User logged in
			* User changes another user
			* Check for deactivated user when logging data and flag if a deactivated user performs an action (uses existing REST API endpoint https://supportmonitor.10up.com/wp-json/tenup/support-monitor/v1/is_user_deactivated )
			* 
			* 
			* Plugins (type: plugin)
			* 
			* Plugin activated
			* Plugin deactivated
			* Plugin removed
			* Plugin added (through dashboard)
			* 
			* 
			* Themes (type: theme)
			* 
			* Theme activated
			* Theme deactivated
			* Theme removed
			* Theme added (through dashboard)
			* 
			* 
			* Site Settings
			* 
			* WordPress Address changed
			* Site Address changed
			*  Administration email address changed
	 	 */


		
	}

}
