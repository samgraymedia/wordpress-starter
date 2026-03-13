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
		add_action( 'after_setup_theme', array( $this, 'create_login_register_pages' ), 20 );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		// Handle custom login and register pages at wp_loaded with high priority
		add_action( 'wp_loaded', array( $this, 'handle_custom_login_register' ), 1 );
		// Force Timber templates for WooCommerce pages (only if WooCommerce is active)
		if ( $this->is_woocommerce_active() ) {
			add_filter( 'template_include', array( $this, 'force_woocommerce_timber_templates' ), 999 );
		}
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
		
		// Add user context
		$context['user'] = wp_get_current_user();
		$context['user_id'] = get_current_user_id();
		$context['is_user_logged_in'] = is_user_logged_in();
		
		return $context;
	}

	/**
	 * Handle custom login and register pages
	 * Load custom templates instead of WordPress defaults
	 */
	public function handle_custom_login_register( $wp ) {
		// Check the current request URI
		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			$request_uri = $_SERVER['REQUEST_URI'];
			$home_path    = wp_parse_url( home_url(), PHP_URL_PATH );
			$request_path = str_replace( $home_path, '', $request_uri );
			$request_path = trim( $request_path, '/' );
			
			// Handle /login request
			if ( $request_path === 'login' || $request_path === 'login/' ) {
				$login_file = get_stylesheet_directory() . '/login.php';
				if ( file_exists( $login_file ) ) {
					include( $login_file );
					exit;
				}
			}
			
			// Handle /register request
			if ( $request_path === 'register' || $request_path === 'register/' ) {
				$register_file = get_stylesheet_directory() . '/register.php';
				if ( file_exists( $register_file ) ) {
					include( $register_file );
					exit;
				}
			}
		}
	}

	/**
	 * Create login and register pages if they don't exist
	 * Called during theme initialization
	 */
	public function create_login_register_pages() {
		// Create login page if it doesn't exist
		$login_page = get_page_by_path( 'login' );
		if ( ! $login_page ) {
			wp_insert_post( array(
				'post_type'   => 'page',
				'post_title'  => 'Login',
				'post_name'   => 'login',
				'post_status' => 'publish',
				'post_content' => '',
			) );
		}
		
		// Create register page if it doesn't exist
		$register_page = get_page_by_path( 'register' );
		if ( ! $register_page ) {
			wp_insert_post( array(
				'post_type'   => 'page',
				'post_title'  => 'Create Account',
				'post_name'   => 'register',
				'post_status' => 'publish',
				'post_content' => '',
			) );
		}
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

	/**
	 * Check if WooCommerce is active
	 * 
	 * @return bool True if WooCommerce plugin is active.
	 */
	private function is_woocommerce_active(): bool {
		return in_array(
			'woocommerce/woocommerce.php',
			get_option( 'active_plugins', array() ),
			true
		) || ( is_multisite() && array_key_exists( 'woocommerce/woocommerce.php', get_site_option( 'active_sitewide_plugins', array() ) ) );
	}

	/**
	 * Force Timber templates for WooCommerce pages and custom pages
	 * Override the default templates to use our Timber templates
	 * Only runs if WooCommerce is active
	 */
	public function force_woocommerce_timber_templates( $template ) {
		global $wp_query;
		
		// Get the current request to check for login/register pages
		if ( isset( $wp_query->query_vars['pagename'] ) ) {
			$page = $wp_query->query_vars['pagename'];
			
			if ( $page === 'login' ) {
				$custom_template = get_stylesheet_directory() . '/login.php';
				if ( file_exists( $custom_template ) ) {
					return $custom_template;
				}
			}
			
			if ( $page === 'register' ) {
				$custom_template = get_stylesheet_directory() . '/register.php';
				if ( file_exists( $custom_template ) ) {
					return $custom_template;
				}
			}
		}
		
		if ( function_exists( 'is_shop' ) && ( is_shop() || is_product() || is_product_taxonomy() ) ) {
			// Force use of our index.php which has Timber template routing
			return get_stylesheet_directory() . '/index.php';
		}
		return $template;
	}

	/** This is where you can add your own functions to twig.
	 *
	 * @param string $twig get extension.
	 */
	public function add_to_twig( $twig ) {
		$twig->addExtension( new Twig\Extension\StringLoaderExtension() );
		$twig->addFilter( new Twig\TwigFilter( 'myfoo', array( $this, 'myfoo' ) ) );
		
		// Add WordPress functions to Twig
		$twig->addFunction( new Twig\TwigFunction( 'wp_title', 'wp_title' ) );
		$twig->addFunction( new Twig\TwigFunction( 'bloginfo', 'bloginfo' ) );
		$twig->addFunction( new Twig\TwigFunction( '_e', '_e' ) );
		$twig->addFunction( new Twig\TwigFunction( '__', '__' ) );
		$twig->addFunction( new Twig\TwigFunction( 'esc_attr', 'esc_attr' ) );
		$twig->addFunction( new Twig\TwigFunction( 'wp_footer', 'wp_footer' ) );
		$twig->addFunction( new Twig\TwigFunction( 'action', 'do_action' ) );
		$twig->addFunction( new Twig\TwigFunction( 'get_search_query', 'get_search_query' ) );
		$twig->addFunction( new Twig\TwigFunction( 'wp_logout_url', 'wp_logout_url' ) );
		$twig->addFunction( new Twig\TwigFunction( 'wp_login_url', 'wp_login_url' ) );
		$twig->addFunction( new Twig\TwigFunction( 'wp_registration_url', 'wp_registration_url' ) );
		$twig->addFunction( new Twig\TwigFunction( 'get_option', 'get_option' ) );
		$twig->addFunction( new Twig\TwigFunction( 'wp_nonce_field', 'wp_nonce_field' ) );
		
		// Add safe filter for HTML output
		$twig->addFilter( new Twig\TwigFilter( 'safe', function( $string ) {
			return $string;
		}, array( 'is_safe' => array( 'html' ) ) ) );
		
		return $twig;
	}

}
require_once( 'src/timber-acf-wp-blocks.php' );
require_once( 'src/post-types-registry.php' );
require_once( 'src/taxonomies-registry.php' );
require_once( 'src/page-templates-registry.php' );

// Only load WooCommerce integration if WooCommerce plugin is active
if ( in_array( 'woocommerce/woocommerce.php', get_option( 'active_plugins', array() ), true ) 
	|| ( is_multisite() && array_key_exists( 'woocommerce/woocommerce.php', get_site_option( 'active_sitewide_plugins', array() ) ) ) ) {
	require_once( 'src/woocommerce-integration.php' );
}

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

/**
 * Enqueue Theme Styles and Scripts
 * 
 * Loads theme stylesheets, Bootstrap JS, and main theme JavaScript.
 * Main script includes all components and utilities via ES6 modules.
 */
function theme_enqueue_assets(): void {
	$theme_uri = get_template_directory_uri();
	$theme_version = wp_get_theme()->get( 'Version' ) ?? '1.0.0';

	// Main theme stylesheet (includes Bootstrap CSS)
	wp_enqueue_style(
		'starter-theme-css',
		$theme_uri . '/style.css',
		array(),
		$theme_version,
		'all'
	);

	// Bootstrap JavaScript (from node_modules, compiled)
	wp_enqueue_script(
		'bootstrap-js',
		$theme_uri . '/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
		array(),
		'5.3.0',
		true // Load in footer
	);

	// Main theme JavaScript (includes all components and utilities)
	// In development, point to scripts/index.js
	// In production, point to dist/site.js (generated by Vite)
	$main_script_url = $theme_uri . '/dist/site.js';
	$main_script_path = get_template_directory() . '/dist/site.js';

	// Fallback to scripts/index.js if dist doesn't exist (development mode)
	if ( ! file_exists( $main_script_path ) ) {
		$main_script_url = $theme_uri . '/scripts/index.js';
	}

	// Note: Bootstrap JS must load before theme script since modules load independently
	wp_enqueue_script(
		'starter-theme-js',
		$main_script_url,
		array( 'bootstrap-js' ), // Bootstrap must load before our module
		$theme_version,
		true // Load in footer
	);

	// Localize script - pass WordPress data to JavaScript
	wp_localize_script(
		'starter-theme-js',
		'themeConfig',
		array(
			'siteUrl'      => site_url(),
			'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
			'nonce'        => wp_create_nonce( 'theme-nonce' ),
			'isLoggedIn'   => is_user_logged_in(),
			'currentUser'  => get_current_user_id(),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_assets' );

/**
 * Mark theme script as ES6 module
 * Filters the script tag to add type="module" for ES6 imports
 */
function theme_script_loader_tag( $tag, $handle ) {
	if ( 'starter-theme-js' === $handle ) {
		$tag = str_replace( '<script', '<script type="module"', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'theme_script_loader_tag', 10, 2 );

new StarterSite();
