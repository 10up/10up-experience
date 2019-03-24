<?php
/**
 * Menu tests
 *
 * @package 10up-experience
 */

/**
 * PHPUnit test class
 */
class MenuTest extends \WPAcceptance\PHPUnit\TestCase {

	/**
	 * @testdox I see 10up logo in admin bar
	 */
	public function testAdminBar10upLogo() {
		$I = $this->openBrowserPage();

		$I->loginAs( 'admin' );
		$I->seeElement( '#wp-admin-bar-10up' );
	}
}
