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
require_once 'src/theme-options.php';
require_once 'src/theme-toggle-options.php';
require_once 'src/schema-organization.php';
require_once 'src/seo-helpers.php';

/**
 * Load admin features and customization
 */
require_once 'src/admin/customizer-options.php';
require_once 'src/admin/admin-dashboard.php';
require_once 'src/admin/product-performance.php';
require_once 'src/admin/analytics-integration.php';
require_once 'src/admin/bulk-product-editing.php';

/**
 * Load WooCommerce email customizations (if WooCommerce is active)
 * Deferred to woocommerce_loaded hook to ensure WC_Email class is available
 */
if ( class_exists( 'WooCommerce' ) ) {
	add_action( 'woocommerce_loaded', function() {
		if ( class_exists( 'WC_Email' ) && ! class_exists( 'Theme_Email_Abandoned_Cart' ) ) {
			require_once 'src/woocommerce-custom-emails.php';
		}
	});
}

/**
 * Load WooCommerce Wishlist functionality
 */
if ( class_exists( 'WooCommerce' ) ) {
	require_once 'src/woocommerce-wishlist.php';
}

/**
 * Load WooCommerce Product Comparison functionality
 */
if ( class_exists( 'WooCommerce' ) ) {
	require_once 'src/woocommerce-comparison.php';
}

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
 * SECURITY: Prevent non-admin users from accessing wp-admin
 * Clients and subscribers are redirected to the custom account page
 */
add_action( 'admin_init', function() {
	// Only block if user is not an admin or editor
	if ( is_user_logged_in() && ! current_user_can( 'manage_options' ) ) {
		// Allow access only to profile and account-related pages
		$allowed_screens = array( 'profile.php', 'profile' );
		$current_screen  = get_current_screen();
		
		// If trying to access restricted area, redirect to custom account page
		if ( $current_screen && ! in_array( $current_screen->base, $allowed_screens, true ) ) {
			wp_redirect( home_url( '/account' ) );
			exit;
		}
	}
} );

/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site {
	/** Add timber support. */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_action( 'after_setup_theme', array( $this, 'create_default_menus' ), 15 );
		add_action( 'after_setup_theme', array( $this, 'create_login_register_pages' ), 20 );
		add_action( 'after_setup_theme', array( $this, 'create_account_page' ), 21 );
		add_action( 'after_setup_theme', array( $this, 'create_home_page' ), 22 );
		add_action( 'after_setup_theme', array( $this, 'create_blog_archive_page' ), 23 );
		add_action( 'after_setup_theme', array( $this, 'delete_default_posts' ), 24 );
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
		
		// Add breadcrumb context
		require_once 'src/helpers/breadcrumbs.php';
		$trail = \StarterTheme\Helpers\Breadcrumb::get_trail();
		$context['breadcrumb_trail'] = $trail;
		$context['breadcrumb_mobile'] = \StarterTheme\Helpers\Breadcrumb::get_abbreviated_trail( $trail );
		$context['breadcrumb_schema'] = \StarterTheme\Helpers\Breadcrumb::get_schema( $trail );
		
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
	 * Only creates pages if WooCommerce is active
	 */
	public function create_login_register_pages() {
		// Only create pages if WooCommerce is active
		if ( ! class_exists( 'WooCommerce' ) && ! ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'woocommerce/woocommerce.php' ) ) ) {
			return; // Skip if WooCommerce not active
		}

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

	/**
	 * Create My Account page with the account.php template
	 * Called during theme initialization
	 * Only creates page if WooCommerce is active
	 */
	public function create_account_page() {
		// Only create account page if WooCommerce is active
		if ( ! class_exists( 'WooCommerce' ) && ! ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'woocommerce/woocommerce.php' ) ) ) {
			return; // Skip if WooCommerce not active
		}

		// Create account page if it doesn't exist
		$account_page = get_page_by_path( 'account' );
		if ( ! $account_page ) {
			$account_page_id = wp_insert_post( array(
				'post_type'   => 'page',
				'post_title'  => 'My Account',
				'post_name'   => 'account',
				'post_status' => 'publish',
				'post_content' => '',
			) );
			
			if ( $account_page_id && ! is_wp_error( $account_page_id ) ) {
				// Assign the account.php template to this page
				update_post_meta( $account_page_id, '_wp_page_template', 'account.php' );
			}
		}
	}

	/**
	 * Create home page and set it as the static homepage
	 * Called during theme initialization
	 */
	public function create_home_page() {
		// Check if home page already exists
		$home_page = get_page_by_path( 'home' );
		
		if ( ! $home_page ) {
			// Create home page
			$home_page_id = wp_insert_post( array(
				'post_type'   => 'page',
				'post_title'  => 'Home',
				'post_name'   => 'home',
				'post_status' => 'publish',
				'post_content' => '',
			) );
			
			if ( $home_page_id && ! is_wp_error( $home_page_id ) ) {
				// Set the home page as the static homepage
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $home_page_id );
			}
		} else {
			// Ensure home page is set as the front page if it wasn't already
			$current_front_page = get_option( 'page_on_front' );
			if ( ! $current_front_page || $current_front_page !== $home_page->ID ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $home_page->ID );
			}
		}
	}

	/**
	 * Create blog archive page
	 * Called during theme initialization
	 */
	public function create_blog_archive_page() {
		// Check if blog archive page already exists
		$blog_page = get_page_by_path( 'blog' );
		
		if ( ! $blog_page ) {
			// Create blog archive page
			$blog_page_id = wp_insert_post( array(
				'post_type'   => 'page',
				'post_title'  => 'Blog',
				'post_name'   => 'blog',
				'post_status' => 'publish',
				'post_content' => '',
			) );
			
			if ( $blog_page_id && ! is_wp_error( $blog_page_id ) ) {
				// Set this page as the blog archive page
				update_option( 'page_for_posts', $blog_page_id );
			}
		} else {
			// Ensure blog page is set correctly if it wasn't already
			$current_posts_page = get_option( 'page_for_posts' );
			if ( ! $current_posts_page || $current_posts_page !== $blog_page->ID ) {
				update_option( 'page_for_posts', $blog_page->ID );
			}
		}
	}

	/**
	 * Delete default WordPress posts
	 * Removes all existing "post" type posts when blog post type is set up
	 */
	public function delete_default_posts() {
		// Get all existing posts of type 'post'
		$posts = get_posts( array(
			'post_type'   => 'post',
			'numberposts' => -1,
			'post_status' => 'any',
		) );

		// Delete each post permanently
		foreach ( $posts as $post ) {
			wp_delete_post( $post->ID, true ); // true = force delete (don't trash)
		}
	}

	/**
	 * Create default navigation menus
	 * Automatically creates Header and Footer menus on theme setup
	 */
	public function create_default_menus() {
		// Get all menus
		$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
		$menu_names = array();
		foreach ( $menus as $menu ) {
			$menu_names[] = $menu->name;
		}

		// Create Header Navigation menu if it doesn't exist
		if ( ! in_array( 'Header Navigation', $menu_names, true ) ) {
			$header_menu_id = wp_create_nav_menu( 'Header Navigation' );
			
			if ( ! is_wp_error( $header_menu_id ) ) {
				// Add Home item
				wp_update_nav_menu_item( $header_menu_id, 0, array(
					'menu-item-title'  => __( 'Home', 'starter-theme-1.x' ),
					'menu-item-url'    => home_url(),
					'menu-item-status' => 'publish',
				) );

				// Add Blog item
				wp_update_nav_menu_item( $header_menu_id, 0, array(
					'menu-item-title'  => __( 'Blog', 'starter-theme-1.x' ),
					'menu-item-url'    => home_url( '/blog' ),
					'menu-item-status' => 'publish',
				) );

				// Assign to header location
				$locations = get_theme_mod( 'nav_menu_locations' );
				$locations['header'] = $header_menu_id;
				set_theme_mod( 'nav_menu_locations', $locations );
			}
		}

		// Create Footer Navigation menu if it doesn't exist
		if ( ! in_array( 'Footer Navigation', $menu_names, true ) ) {
			$footer_menu_id = wp_create_nav_menu( 'Footer Navigation' );
			
			if ( ! is_wp_error( $footer_menu_id ) ) {
				// Add Home item
				wp_update_nav_menu_item( $footer_menu_id, 0, array(
					'menu-item-title'  => __( 'Home', 'starter-theme-1.x' ),
					'menu-item-url'    => home_url(),
					'menu-item-status' => 'publish',
				) );

				// Add Blog item
				wp_update_nav_menu_item( $footer_menu_id, 0, array(
					'menu-item-title'  => __( 'Blog', 'starter-theme-1.x' ),
					'menu-item-url'    => home_url( '/blog' ),
					'menu-item-status' => 'publish',
				) );

				// Add Contact item
				wp_update_nav_menu_item( $footer_menu_id, 0, array(
					'menu-item-title'  => __( 'Contact', 'starter-theme-1.x' ),
					'menu-item-url'    => home_url( '/contact' ),
					'menu-item-status' => 'publish',
				) );

				// Add Privacy Policy item
				wp_update_nav_menu_item( $footer_menu_id, 0, array(
					'menu-item-title'  => __( 'Privacy Policy', 'starter-theme-1.x' ),
					'menu-item-url'    => home_url( '/privacy-policy' ),
					'menu-item-status' => 'publish',
				) );

				// Assign to footer location
				$locations = get_theme_mod( 'nav_menu_locations' );
				$locations['footer'] = $footer_menu_id;
				set_theme_mod( 'nav_menu_locations', $locations );
			}
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
	require_once( 'src/woocommerce-product-meta.php' );
	require_once( 'src/woocommerce-category-meta.php' );
}

/**
 * Output SEO meta tags in <head>
 * 
 * Includes: Open Graph, Twitter Cards, Canonical tags, JSON-LD schemas
 */
add_action( 'wp_head', function() {
	// Organization schema (site-wide)
	theme_output_json_ld( theme_get_organization_schema() );
	
	// Open Graph meta tags
	theme_output_open_graph();
	
	// Twitter Card meta tags
	// UPDATE: Replace with your actual Twitter handle
	theme_output_twitter_card( array(
		'handle' => '@YourTwitterHandle',
	) );
	
	// Canonical tag
	theme_output_canonical();
}, 5 );

/**
 * Output article/product schema for singular pages
 */
add_action( 'wp_head', function() {
	if ( ! is_singular() ) {
		return;
	}
	
	// Article schema for posts and pages
	if ( is_singular( 'post' ) || is_singular( 'page' ) ) {
		theme_output_json_ld( theme_get_article_schema() );
	}
	
	// Product schema for WooCommerce products
	if ( is_singular( 'product' ) && function_exists( 'wc_get_product' ) ) {
		theme_output_json_ld( theme_get_product_schema() );
	}
}, 5 );

/**
 * Add lazy loading to product images
 * Adds loading="lazy" attribute to all WooCommerce product images
 */
function theme_add_lazy_loading_to_images( $html ) {
	// Add loading="lazy" to img tags that don't already have a loading attribute
	$html = preg_replace_callback(
		'/<img\s+([^>]*?)\/?>/',
		function( $matches ) {
			$img_tag = $matches[1];
			
			// Skip if loading attribute already exists
			if ( preg_match( '/\sloading=["\']/', $img_tag ) ) {
				return $matches[0];
			}
			
			// Add loading="lazy" before the closing /> or >
			return '<img ' . $img_tag . ' loading="lazy">';
		},
		$html
	);
	
	return $html;
}

// Apply lazy loading to WooCommerce product images
add_filter( 'woocommerce_product_get_image', 'theme_add_lazy_loading_to_images' );
add_filter( 'woocommerce_get_product_thumbnail', 'theme_add_lazy_loading_to_images' );

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
 * Allow SVG uploads for admin users
 * Handles pre-upload validation
 */
add_filter( 'wp_check_filetype_and_ext', function( $check, $file, $filename, $mimes ) {
	if ( current_user_can( 'manage_options' ) && preg_match( '/\.svg$/i', $filename ) ) {
		$check['ext']  = 'svg';
		$check['type'] = 'image/svg+xml';
	}
	return $check;
}, 10, 4 );

/**
 * Allow SVG uploads in media library
 */
add_filter( 'wp_handle_upload_prefilter', function( $file ) {
	if ( current_user_can( 'manage_options' ) && preg_match( '/\.svg$/i', $file['name'] ) ) {
		$file['name'] = sanitize_file_name( $file['name'] );
	}
	return $file;
} );

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
			'debug'        => defined( 'WP_DEBUG' ) && WP_DEBUG,
		)
	);

	// Note: Individual feature scripts (gallery, slider, video, accordion, etc.)
	// are bundled into site.js by Vite and don't need separate enqueues.
	// They are imported in scripts/index.js and included in the main bundle.
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

/**
 * Filter menu items - hide Login/Register if WooCommerce not active
 * Removes Login and Create Account menu items when WooCommerce is disabled
 */
function theme_filter_menu_items( $items ) {
	// Only filter if WooCommerce is not active
	if ( class_exists( 'WooCommerce' ) || function_exists( 'is_plugin_active' ) && is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
		return $items; // Keep all items if WooCommerce is active
	}

	// Filter out Login and Create Account items
	$filtered_items = array_filter( $items, function( $item ) {
		$title = isset( $item->title ) ? $item->title : '';
		return ! in_array( strtolower( $title ), array( 'login', 'create account', 'register' ), true );
	});

	return $filtered_items;
}
add_filter( 'wp_nav_menu_objects', 'theme_filter_menu_items' );

/**
 * Unregister the default "post" post type
 * Since we're using a custom "blog" post type instead
 */
function theme_unregister_default_post_type() {
	// Unregister the default post type
	unregister_post_type( 'post' );
}
add_action( 'init', 'theme_unregister_default_post_type', 5 ); // Priority 5 - runs before post type registration

/**
 * Remove default WordPress blocks and only allow custom blocks
 * Automatically discovers all registered ACF blocks
 * Restricts editor to theme-specific ACF blocks only
 */
function theme_allowed_block_types( $allowed_blocks, $editor_context ) {
	// Get all registered block types
	$registry = WP_Block_Type_Registry::get_instance();
	$all_blocks = $registry->get_all_registered();
	
	// Filter to only ACF blocks (acf/*)
	$custom_blocks = array();
	foreach ( $all_blocks as $block ) {
		if ( 0 === strpos( $block->name, 'acf/' ) ) {
			$custom_blocks[] = $block->name;
		}
	}
	
	return $custom_blocks;
}
// Run at priority 20 (after acf/init at 10 so blocks are registered)
add_filter( 'allowed_block_types_all', 'theme_allowed_block_types', 20, 2 );


/**
 * Delete login/register pages when WooCommerce is deactivated
 * Keeps them clean if WooCommerce is turned off
 */
function theme_cleanup_login_register_pages() {
	// Only delete if WooCommerce is NOT active
	if ( class_exists( 'WooCommerce' ) || ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'woocommerce/woocommerce.php' ) ) ) {
		return; // Don't delete if WooCommerce is still active
	}

	// Delete login page
	$login_page = get_page_by_path( 'login' );
	if ( $login_page ) {
		wp_delete_post( $login_page->ID, true );
	}

	// Delete register page
	$register_page = get_page_by_path( 'register' );
	if ( $register_page ) {
		wp_delete_post( $register_page->ID, true );
	}
}
// Run cleanup on plugin activation/deactivation
add_action( 'deactivated_plugin', function() {
	theme_cleanup_login_register_pages();
});

/**
 * Contact Form 7 accessibility fix
 * Ensures all form inputs have IDs that match their corresponding labels
 */
add_filter( 'wpcf7_form_elements', function( $html ) {
	if ( empty( $html ) ) {
		return $html;
	}

	// First, add IDs to inputs that don't have them
	$html = preg_replace_callback(
		'/(<input[^>]*type=["\'](?:email|text|tel)["\'][^>]*)(?!id=)((?:[^>]*?)>)/i',
		function( $matches ) {
			$input_tag = $matches[1] . $matches[2];
			
			// Check if the input already has an id
			if ( preg_match( '/id=["\']([^"\']+)["\']/', $input_tag ) ) {
				return $input_tag;
			}

			// Extract the name attribute to generate an ID
			if ( preg_match( '/name=["\']([^"\']+)["\']/', $input_tag, $name_match ) ) {
				$id_value = sanitize_html_class( $name_match[1] );
			} else {
				// Fallback to random ID if no name attribute
				$id_value = 'input-' . wp_rand( 100000, 999999 );
			}

			// Add the ID attribute before the closing >
			return str_replace( '>', ' id="' . esc_attr( $id_value ) . '">', $input_tag );
		},
		$html
	);

	// Second, update labels to reference the correct input IDs
	$html = preg_replace_callback(
		'/<label[^>]*for=["\']([^"\']*)["\'][^>]*>([^<]*)<\/label>/i',
		function( $matches ) {
			$label_for = $matches[1];
			$label_text = $matches[2];

			// Check if this input exists in the HTML
			if ( preg_match( '/id=["\']' . preg_quote( $label_for ) . '["\']/', $matches[0] ) ) {
				return $matches[0]; // Label already references existing input
			}

			// If the for attribute is empty and this is a "Your email" label, try to find the email input
			if ( empty( $label_for ) && strpos( $label_text, 'email' ) !== false ) {
				// Try to match with email input name
				// Return the label with a proper for attribute
				return str_replace( 'for=""', 'for="your-email"', $matches[0] );
			}

			return $matches[0];
		},
		$html
	);

	return $html;
} );

new StarterSite();
