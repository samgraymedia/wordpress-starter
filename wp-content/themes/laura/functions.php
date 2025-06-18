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
 */
Timber::$dirname = array( 'templates', 'views' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


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
		$context['footer_menu'] = new Timber\Menu('footer_menu');
		$context['site']  = $this;
		$context['acf_options'] = get_fields('option');
		return $context;
	}

	public function theme_supports() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// Add excerpt support for pages
		add_post_type_support( 'page', 'excerpt' );

		// Set excerpt length
		add_filter('excerpt_length', function($length) {
			return 30;
		});

		// Change excerpt more string
		add_filter('excerpt_more', function($more) {
			return '...';
		});

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			)
		);

		add_theme_support( 'menus' );
		
		// Register menu locations
		register_nav_menus(array(
			'footer_menu' => __('Footer Menu', 'laura')
		));
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

	$twig = new \Twig\Environment($loader, [
    'debug' => true,
    // ...
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

function add_file_types_to_uploads($file_types){
$new_filetypes = array();
$new_filetypes['svg'] = 'image/svg+xml';
$file_types = array_merge($file_types, $new_filetypes );
return $file_types;
}
add_filter('upload_mimes', 'add_file_types_to_uploads');
if( function_exists('acf_add_options_page') ) {

    acf_add_options_page();

}

// Enqueue cookie consent script
function enqueue_cookie_consent_script() {
    wp_enqueue_script(
        'cookie-consent',
        get_template_directory_uri() . '/src/js/cookie-consent.js',
        array(),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'enqueue_cookie_consent_script');

// Enqueue mobile navigation script
function enqueue_mobile_nav_script() {
    wp_enqueue_script(
        'mobile-nav',
        get_template_directory_uri() . '/src/js/mobile-nav.js',
        array(),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'enqueue_mobile_nav_script');

// Add cookie consent banner to the context
add_filter('timber/context', function($context) {
    // Get the privacy policy page URL if it exists
    $privacy_policy_id = get_option('wp_page_for_privacy_policy');
    $context['privacy_policy_url'] = $privacy_policy_id ? get_permalink($privacy_policy_id) : null;
    
    // Add cookie consent template to context
    $context['cookie_consent'] = Timber::compile('components/cookie-consent.twig', array(
        'privacy_policy_url' => $context['privacy_policy_url']
    ));
    
    return $context;
});

// Add cookie consent banner to footer
add_action('wp_footer', function() {
    $context = Timber::context();
    echo $context['cookie_consent'];
});

add_filter( 'allowed_block_types_all', function( $allowed_blocks, $editor_context ) {
    // Check if it's editing a page
    if ( $editor_context->post && $editor_context->post->post_type === 'page' ) {
        // Remove the core/paragraph block
        $key = array_search( 'core/paragraph', $allowed_blocks );
        if ( false !== $key ) {
            unset( $allowed_blocks[$key] );
        }
    }
    return $allowed_blocks;
}, 10, 2 );

new StarterSite();

// Add custom excerpt generation
function custom_wp_trim_excerpt($text = '') {
    $raw_excerpt = $text;
    if ('' == $text) {
        // Retrieve the post content
        $text = get_the_content('');
 
        // Delete all shortcode tags from the content
        $text = strip_shortcodes($text);
 
        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]&gt;', $text);
 
        // Define excerpt length
        $excerpt_length = apply_filters('excerpt_length', 30); 
 
        // Define excerpt more string
        $excerpt_more = apply_filters('excerpt_more', ' [...]');
 
        $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
        if (count($words) > $excerpt_length) {
            array_pop($words);
            $text = implode(' ', $words);
            $text = $text . $excerpt_more;
        } else {
            $text = implode(' ', $words);
        }
    }
    return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
}
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'custom_wp_trim_excerpt');
