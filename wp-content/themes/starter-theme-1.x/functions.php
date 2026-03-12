<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

/**
 * Load theme configuration and helper classes
 */
require_once 'src/theme-config.php';

/**
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 */
$composer_autoload = __DIR__ . '/vendor/autoload.php';
if ( file_exists( $composer_autoload ) ) {
	require_once $composer_autoload;
	$timber = new Timber\Timber();
}

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if ( ! class_exists( 'Timber' ) ) {

	add_action(
		'admin_notices',
		function() {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		}
	);

	add_filter(
		'template_include',
		function( $template ) {
			return get_stylesheet_directory() . '/static/no-timber.html';
		}
	);
	return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 * Using configuration from Theme_Config
 */
$timber_config = Theme_Config::get_timber();
Timber::$dirname = $timber_config['directories'];

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = $timber_config['autoescape'];


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site {
	/** Add timber support. */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		parent::__construct();
	}
	/** This is where you can register custom post types. */
	public function register_post_types() {

	}
	/** This is where you can register custom taxonomies. */
	public function register_taxonomies() {

	}

	/** This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context( $context ) {
		$context['foo']   = 'bar';
		$context['stuff'] = 'I am a value set in your functions.php file';
		$context['notes'] = 'These values are available everytime you call Timber::context();';
		$context['menu']  = new Timber\Menu();
		$context['site']  = $this;
		return $context;
	}

	public function theme_supports(): void {
		$theme_support = Theme_Config::get_theme_support();

		// Add theme support based on configuration
		foreach ( $theme_support as $feature => $args ) {
			if ( true === $args ) {
				// Boolean support
				add_theme_support( $feature );
			} elseif ( is_array( $args ) && ! empty( $args ) ) {
				// Support with arguments
				add_theme_support( $feature, $args );
			}
		}

		// Register menus from configuration
		$menus = Theme_Config::get_menus();
		if ( ! empty( $menus ) ) {
			register_nav_menus( $menus );
		}
	}

	/** This Would return 'foo bar!'.
	 *
	 * @param string $text being 'foo', then returned 'foo bar!'.
	 */
	public function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	/** This is where you can add your own functions to twig.
	 *
	 * @param string $twig get extension.
	 */
	public function add_to_twig( $twig ) {
		$twig->addExtension( new Twig\Extension\StringLoaderExtension() );
		$twig->addFilter( new Twig\TwigFilter( 'myfoo', array( $this, 'myfoo' ) ) );
		return $twig;
	}

}
require_once( 'src/timber-acf-wp-blocks.php' );
require_once( 'src/post-types-registry.php' );

// Auto-load blocks
foreach ( glob( __DIR__ . '/src/blocks/*.php' ) as $block_file ) {
	require_once $block_file;
}

/**
 * Register Image Sizes
 * Uses configuration from Theme_Config
 */
function theme_register_image_sizes(): void {
	$image_sizes = Theme_Config::get_image_sizes();
	
	foreach ( $image_sizes as $size_name => $dimensions ) {
		add_image_size( $size_name, ...$dimensions );
	}
}
add_action( 'after_setup_theme', 'theme_register_image_sizes' );

/**
 * Register Sidebars/Widget Areas
 * Uses configuration from Theme_Config
 */
function theme_register_sidebars(): void {
	$sidebars = Theme_Config::get_sidebars();
	
	foreach ( $sidebars as $sidebar_id => $sidebar_args ) {
		register_sidebar(
			array_merge(
				array( 'id' => $sidebar_id ),
				$sidebar_args
			)
		);
	}
}
add_action( 'widgets_init', 'theme_register_sidebars' );

/**
 * Add custom file types to uploads
 * Uses configuration from Theme_Config
 */
function theme_allow_custom_file_types( array $file_types ): array {
	$allowed_types = Theme_Config::get( 'uploads' )['allowed_types'] ?? array();
	return array_merge( $file_types, $allowed_types );
}
add_filter( 'upload_mimes', 'theme_allow_custom_file_types' );

/**
 * Initialize ACF Options Page
 * Uses configuration from Theme_Config
 */
function theme_initialize_acf(): void {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	$acf_config = Theme_Config::get_acf();
	
	if ( $acf_config['enable_options_page'] ?? false ) {
		acf_add_options_page();
	}
}
add_action( 'acf/init', 'theme_initialize_acf' );

new StarterSite();
