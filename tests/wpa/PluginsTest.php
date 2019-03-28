<?php
/**
 * Test plugin install functionality
 *
 * @package 10up-experience
 */

/**
 * PHPUnit test class
 */
class PluginsTest extends \WPAcceptance\PHPUnit\TestCase {

	/**
	 * @testdox I see 10up suggested plugins
	 */
	public function test10upSuggestedWorking() {
		$I = $this->openBrowserPage();

		$I->loginAs( 'admin' );

		$I->moveTo( 'wp-admin/plugin-install.php' );

		$I->seeLink( '10up Suggested' );

		// Make sure ElasticPress is shown since this is definitely suggested

		$I->seeElement( '.plugin-card-elasticpress' );
		$I->seeText( 'ElasticPress' );
	}

	/**
	 * @testdox I see a warning when I look at non-suggested plugins
	 */
	public function testNonSuggestedWarning() {
		$I = $this->openBrowserPage();

		$I->loginAs( 'admin' );

		$I->moveTo( 'http://10upexperience.test/wp-admin/plugin-install.php?tab=popular' );

		$I->seeText( 'Some plugins may affect display, performance, and reliability' );
	}
}
