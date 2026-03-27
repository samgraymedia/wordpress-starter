<?php
/**
 * Test Bootstrap File
 * Loads WordPress test library and Timber for testing
 * 
 * Supports both Timber as plugin and Timber as composer dependency
 */

$_tests_dir = getenv('WP_TESTS_DIR');
if ( !$_tests_dir ) $_tests_dir = '/tmp/wordpress-tests-lib';

require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load plugins for testing
 * Prioritizes Timber as composer dependency, falls back to plugin
 */
function _manually_load_plugin() {
	$theme_dir = dirname( __FILE__ ) . '/..';
	
	// Try to load Timber from composer first (Timber 2.x approach)
	$composer_autoload = $theme_dir . '/vendor/autoload.php';
	if ( file_exists( $composer_autoload ) ) {
		require_once $composer_autoload;
		// Initialize Timber via composer
		try {
			new \Timber\Timber();
		} catch ( Exception $e ) {
			// Timber already initialized, that's fine
		}
		return;
	}
	
	// Fall back to loading Timber as a plugin
	$plugins_dir = dirname( $theme_dir ) . '/../plugins';
	
	// Try Timber in /plugins/timber/
	$timber_file = $plugins_dir . '/timber/timber.php';
	if ( file_exists( $timber_file ) ) {
		require_once $timber_file;
		return;
	}
	
	// Try Timber in /plugins/timber-library/ (older naming)
	$timber_legacy = $plugins_dir . '/timber-library/timber.php';
	if ( file_exists( $timber_legacy ) ) {
		require_once $timber_legacy;
		return;
	}
	
	// If Timber is not found, log a warning
	trigger_error( 'Timber plugin not found. PHPUnit tests may not work correctly.', E_USER_WARNING );
}

tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );
require $_tests_dir . '/includes/bootstrap.php';
