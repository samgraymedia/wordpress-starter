<?php
/**
 * Unit Tests: Helpers - Validation
 * 
 * Tests the validation helper functions
 */

class Test_Validation_Helper extends WP_UnitTestCase {

	/**
	 * Test that validation functions exist
	 */
	public function test_validation_functions_exist() {
		$this->assertTrue( function_exists( 'theme_validate_email' ) );
		$this->assertTrue( function_exists( 'theme_validate_phone' ) );
		$this->assertTrue( function_exists( 'theme_validate_url' ) );
		$this->assertTrue( function_exists( 'theme_is_valid_zip_code' ) );
	}

	/**
	 * Test email validation
	 */
	public function test_validate_email_valid() {
		$valid_emails = [
			'test@example.com',
			'user.name@example.co.uk',
			'test+tag@example.com',
		];

		foreach ( $valid_emails as $email ) {
			$this->assertTrue( 
				theme_validate_email( $email ),
				"Email '$email' should be valid"
			);
		}
	}

	/**
	 * Test email validation with invalid emails
	 */
	public function test_validate_email_invalid() {
		$invalid_emails = [
			'invalid.email',
			'@example.com',
			'test@',
			'test @example.com',
		];

		foreach ( $invalid_emails as $email ) {
			$this->assertFalse( 
				theme_validate_email( $email ),
				"Email '$email' should be invalid"
			);
		}
	}

	/**
	 * Test phone number validation
	 */
	public function test_validate_phone_valid() {
		$valid_phones = [
			'123-456-7890',
			'(123) 456-7890',
			'+1-123-456-7890',
			'1234567890',
		];

		foreach ( $valid_phones as $phone ) {
			$this->assertTrue( 
				theme_validate_phone( $phone ),
				"Phone '$phone' should be valid"
			);
		}
	}

	/**
	 * Test URL validation
	 */
	public function test_validate_url_valid() {
		$valid_urls = [
			'https://example.com',
			'http://www.example.com',
			'https://example.com/path',
		];

		foreach ( $valid_urls as $url ) {
			$this->assertTrue( 
				theme_validate_url( $url ),
				"URL '$url' should be valid"
			);
		}
	}

	/**
	 * Test zip code validation
	 */
	public function test_validate_zip_code_valid() {
		$valid_zips = [
			'12345',
			'12345-6789',
		];

		foreach ( $valid_zips as $zip ) {
			$this->assertTrue( 
				theme_is_valid_zip_code( $zip ),
				"Zip code '$zip' should be valid"
			);
		}
	}

	/**
	 * Test zip code validation with invalid codes
	 */
	public function test_validate_zip_code_invalid() {
		$invalid_zips = [
			'1234',
			'abcde',
			'12345-67',
		];

		foreach ( $invalid_zips as $zip ) {
			$this->assertFalse( 
				theme_is_valid_zip_code( $zip ),
				"Zip code '$zip' should be invalid"
			);
		}
	}
}
