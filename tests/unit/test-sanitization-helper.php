<?php
/**
 * Unit Tests: Helpers - Sanitization
 * 
 * Tests the sanitization helper functions
 */

class Test_Sanitization_Helper extends WP_UnitTestCase {

	/**
	 * Test that sanitization functions exist
	 */
	public function test_sanitization_functions_exist() {
		$this->assertTrue( function_exists( 'theme_sanitize_text' ) );
		$this->assertTrue( function_exists( 'theme_sanitize_html' ) );
		$this->assertTrue( function_exists( 'theme_sanitize_email' ) );
		$this->assertTrue( function_exists( 'theme_sanitize_url' ) );
	}

	/**
	 * Test text sanitization
	 */
	public function test_sanitize_text() {
		$test_cases = [
			[
				'input' => 'Hello <script>alert("xss")</script>',
				'expected' => 'Hello alert("xss")',
			],
			[
				'input' => 'Normal text',
				'expected' => 'Normal text',
			],
			[
				'input' => '  Padded text  ',
				'expected' => 'Padded text',
			],
		];

		foreach ( $test_cases as $case ) {
			$result = theme_sanitize_text( $case['input'] );
			$this->assertStringContainsString(
				$case['expected'],
				$result,
				"Text sanitization failed for: {$case['input']}"
			);
		}
	}

	/**
	 * Test HTML sanitization
	 */
	public function test_sanitize_html_allows_safe_tags() {
		$input = '<p>Safe paragraph</p><strong>Bold text</strong>';
		$result = theme_sanitize_html( $input );

		$this->assertStringContainsString( '<p>', $result );
		$this->assertStringContainsString( 'Safe paragraph', $result );
		$this->assertStringContainsString( '<strong>', $result );
	}

	/**
	 * Test HTML sanitization removes unsafe tags
	 */
	public function test_sanitize_html_removes_dangerous_tags() {
		$input = '<p>Safe</p><script>dangerous()</script><iframe src="evil.com"></iframe>';
		$result = theme_sanitize_html( $input );

		$this->assertStringNotContainsString( '<script>', $result );
		$this->assertStringNotContainsString( '<iframe>', $result );
	}

	/**
	 * Test email sanitization
	 */
	public function test_sanitize_email_valid() {
		$email = 'test@example.com';
		$result = theme_sanitize_email( $email );
		$this->assertEquals( $email, $result );
	}

	/**
	 * Test email sanitization removes invalid characters
	 */
	public function test_sanitize_email_invalid_chars() {
		$email = 'test<script>@example.com';
		$result = theme_sanitize_email( $email );
		$this->assertStringNotContainsString( '<', $result );
		$this->assertStringNotContainsString( '>', $result );
	}

	/**
	 * Test URL sanitization
	 */
	public function test_sanitize_url_valid() {
		$url = 'https://example.com/path?query=value';
		$result = theme_sanitize_url( $url );
		$this->assertStringStartsWith( 'https://', $result );
		$this->assertStringContainsString( 'example.com', $result );
	}

	/**
	 * Test URL sanitization removes dangerous protocols
	 */
	public function test_sanitize_url_removes_javascript() {
		$url = 'javascript:alert("xss")';
		$result = theme_sanitize_url( $url );
		$this->assertStringNotContainsString( 'javascript:', $result );
	}
}
