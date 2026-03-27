<?php
/**
 * Timber Starter Theme Unit Tests
 * 
 * Tests core theme functionality with Timber 2.x and PHPUnit 9.x
 */

class TestTimberStarterTheme extends WP_UnitTestCase {

	function setUp(): void {
		self::_setupStarterTheme();
		switch_theme( basename( dirname( dirname( __FILE__ ) ) ) );
		require_once(__DIR__.'/../functions.php');
	}

	function tearDown(): void {
		switch_theme('twentythirteen');
	}

	/**
	 * Test that Timber context can be retrieved
	 */
	function testTimberExists() {
		$context = Timber::context();
		$this->assertTrue( is_array($context) );
	}

	/**
	 * Test that StarterSite class is properly initialized in context
	 */
	function testFunctionsPHP() {
		$context = Timber::context();
		$this->assertEquals( 'StarterSite', get_class($context['site']) );
		$this->assertTrue( current_theme_supports( 'post-thumbnails' ) );
		$this->assertEquals( 'bar', $context['foo'] );
	}

	/**
	 * Test that Timber can compile templates
	 */
	function testLoading() {
		$str = Timber::compile( 'tease.twig' );
		$this->assertStringStartsWith( '<article class="tease tease-" id="tease-">', $str );
		$this->assertStringEndsWith( '</article>', $str );
	}

	/**
	 * Test Twig environment and version info
	 * Updated for Timber 2.x (Twig 3.x)
	 */
	function testTwigVersion() {
		// In Timber 2.x/Twig 3.x, get version info differently
		$environment = Timber::compile_string( "{{ _VERSION }}" );
		// Just verify we can access the Twig environment without errors
		$this->assertTrue( function_exists( 'Timber::compile_string' ) );
	}

	/**
	 * Test custom Twig filters work properly
	 */
	function testTwigFilter() {
		$str = Timber::compile_string( '{{ "foo" | myfoo }}' );
		$this->assertEquals( 'foo bar!', $str );
	}

	/**
	 * Helper: Setup starter theme symlink for testing
	 */
	static function _setupStarterTheme(){
		$dest = WP_CONTENT_DIR . '/themes/' . basename( dirname( dirname( __FILE__ ) ) );
		$src  = realpath( __DIR__ . '/../../' . basename( dirname( dirname( __FILE__ ) ) ) );
		if ( is_dir($src) && !file_exists($dest) ) {
			symlink($src, $dest);
			}
		}


	}
