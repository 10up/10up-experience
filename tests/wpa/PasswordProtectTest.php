<?php
/**
 * Password protect tests
 *
 * @package 10up-experience
 */

/**
 * PHPUnit test class
 */
class PasswordProtectTest extends \WPAcceptance\PHPUnit\TestCase {

	/**
	 * @testdox I see the password protect setting in writing.
	 */
	public function testOptionInSettings() {
		$I = $this->openBrowserPage();

		$I->loginAs( 'admin' );
		$I->moveTo( 'wp-admin/options-writing.php' );
		$I->seeElement( '#password-protect' );

		$I->dontSeeCheckboxIsChecked( '#password-protect' );
	}

	/**
	 * @testdox I see no password protect option by default in post editor.
	 */
	public function testPasswordProtectDisabledDefault() {
		$I = $this->openBrowserPage();

		$I->loginAs( 'admin' );
		$I->moveTo( 'wp-admin/post-new.php' );

		try {
			$gutenberg_toggle = $I->getElement( '.edit-post-post-visibility__toggle' );
		} catch ( \WPAcceptance\Exception\ElementNotFound $e ) {
			$gutenberg_toggle = false;
		}

		try {
			$classic_toggle = $I->getElement( '#post-visibility-display' );
		} catch ( \WPAcceptance\Exception\ElementNotFound $e ) {
			$classic_toggle = false;
		}

		if ( $gutenberg_toggle && $I->elementIsVisible( $gutenberg_toggle ) ) {
			$I->waitUntilElementVisible( '.edit-post-post-visibility__toggle' );

			$I->click( '.edit-post-post-visibility__toggle' );

			$I->waitUntilElementVisible( '.editor-post-visibility__choice' );

			$I->dontSeeElement( '#editor-post-password-0-description' );
		} elseif ( $classic_toggle && $I->elementIsVisible( $classic_toggle ) ) {
			$I->click( '.edit-visibility span' );

			$I->waitUntilElementVisible( '#visibility-radio-public' );

			$I->dontSeeElement( '#visibility-radio-password' );
		} else {
			$this->assertTrue( false );
		}
	}

	/**
	 * @testdox I see password protect option when setting is enabled.
	 */
	public function testPasswordProtectEnabled() {
		$I = $this->openBrowserPage();

		$I->loginAs( 'admin' );
		$I->moveTo( 'wp-admin/options-writing.php' );

		$I->checkOptions( '#password-protect' );

		$I->click( '#submit' );

		$I->waitUntilElementVisible( '.notice' );

		$I->moveTo( 'wp-admin/post-new.php' );

		try {
			$gutenberg_toggle = $I->getElement( '.edit-post-post-visibility__toggle' );
		} catch ( \WPAcceptance\Exception\ElementNotFound $e ) {
			$gutenberg_toggle = false;
		}

		try {
			$classic_toggle = $I->getElement( '#post-visibility-display' );
		} catch ( \WPAcceptance\Exception\ElementNotFound $e ) {
			$classic_toggle = false;
		}

		if ( $gutenberg_toggle && $I->elementIsVisible( $gutenberg_toggle ) ) {
			$I->click( '.edit-post-post-visibility__toggle' );

			$I->waitUntilElementVisible( '.editor-post-visibility__choice' );

			$I->seeElement( '#editor-post-password-0-description' );
		} elseif ( $classic_toggle && $I->elementIsVisible( $classic_toggle ) ) {
			$I->click( '.edit-visibility span' );

			$I->waitUntilElementVisible( '#visibility-radio-password' );

			$I->seeElement( '#visibility-radio-password' );
		} else {
			$this->assertTrue( false );
		}
	}
}
