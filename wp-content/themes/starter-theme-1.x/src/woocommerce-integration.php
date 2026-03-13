<?php
/**
 * WooCommerce Integration
 * 
 * Integrates WooCommerce with Timber and the theme architecture.
 * Removes default WooCommerce styles and uses theme styling instead.
 * 
 * @package starter-theme-1.x
 */

/**
 * WooCommerce Support
 * Only runs if WooCommerce plugin is active
 */
function theme_woocommerce_support(): void {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	$woo_config = Theme_Config::get( 'woocommerce' );

	// Remove default WooCommerce styles
	if ( $woo_config['remove_defaults'] ?? true ) {
		add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
		// Also prevent WooCommerce blocks from enqueuing styles
		add_filter( 'should_load_separate_core_block_assets', '__return_false' );
	}

	// Remove cart fragments AJAX (improves performance)
	if ( $woo_config['disable_cart_fragments'] ?? true ) {
		add_filter( 'woocommerce_cart_hash', function() {
			return wp_hash( wp_json_encode( WC()->cart->get_cart() ) );
		}, 99 );
	}

	// Custom product gallery support
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Register WooCommerce specific image sizes
	add_image_size( 'woocommerce_thumbnail', 300, 300, true );
	add_image_size( 'woocommerce_single', 600, 600, true );
}
add_action( 'after_setup_theme', 'theme_woocommerce_support' );

/**
 * Add WooCommerce data to Timber context
 * 
 * @param array $context The Timber context array.
 * @return array Modified context.
 */
function theme_add_woocommerce_to_context( array $context ): array {
	// Only add WooCommerce context if WooCommerce is fully loaded
	if ( ! function_exists( 'WC' ) || ! function_exists( 'wc_get_cart_url' ) ) {
		return $context;
	}

	// Add WooCommerce functions to context - use only core functions that exist
	try {
		$context['woocommerce'] = array(
			'cart_url'        => wc_get_cart_url(),
			'checkout_url'    => wc_get_checkout_url(),
			'cart_count'      => WC()->cart ? WC()->cart->get_cart_contents_count() : 0,
			'cart_total'      => WC()->cart ? WC()->cart->get_cart_total() : '',
		);
	} catch ( \Exception $e ) {
		// If WooCommerce isn't fully loaded yet, just skip
	}

	return $context;
}
add_filter( 'timber/context', 'theme_add_woocommerce_to_context' );

/**
 * Remove all WooCommerce default styles
 * Uses a high priority hook to ensure removal happens after WooCommerce enqueues
 */
function theme_remove_woocommerce_styles(): void {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	// Remove WooCommerce styles by handle
	wp_dequeue_style( 'woocommerce-general' );
	wp_dequeue_style( 'woocommerce-layout' );
	wp_dequeue_style( 'woocommerce-smallscreen' );
	wp_dequeue_style( 'woocommerce_frontend_styles' );
	wp_dequeue_style( 'woocommerce-blockstyles' );
	wp_dequeue_style( 'wc-block-style' );
	wp_dequeue_style( 'wc-blocks-style' );
	wp_dequeue_style( 'wc-blocks-style-css' );
	wp_dequeue_style( 'wc-blocks-compat-notices-style' );
	
	// Also deregister to prevent re-enqueue
	wp_deregister_style( 'wc-blocks-style-css' );
	wp_deregister_style( 'wc-blocks-compat-notices-style' );
	wp_deregister_style( 'woocommerce-blockstyles' );
	
	// Remove all WooCommerce and WC styles via handle prefix
	global $wp_styles;
	if ( isset( $wp_styles ) && is_object( $wp_styles ) ) {
		foreach ( $wp_styles->registered as $handle => $stylesheet ) {
			if ( strpos( $handle, 'woocommerce' ) !== false || strpos( $handle, 'wc-' ) === 0 ) {
				wp_dequeue_style( $handle );
				wp_deregister_style( $handle );
			}
		}
	}
}
add_action( 'wp_enqueue_scripts', 'theme_remove_woocommerce_styles', 999 );
add_action( 'wp_print_styles', 'theme_remove_woocommerce_styles', 999 );

/**
 * Also remove WooCommerce block styles inline
 */
function theme_remove_woocommerce_block_styles(): void {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	// Remove WooCommerce block styles that might be printed inline
	remove_action( 'wp_footer', array( 'WC_Frontend_Scripts', 'print_deferred_styles' ) );
}
add_action( 'wp', 'theme_remove_woocommerce_block_styles' );

/**
 * Enqueue WooCommerce theme styles
 */
function theme_enqueue_woocommerce_styles(): void {
	if ( ! is_woocommerce() ) {
		return;
	}

	// Your WooCommerce styles are already included in style.css
	// This function ensures CSS loads on WooCommerce pages
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_woocommerce_styles', 15 );

/**
 * Remove default WooCommerce page wrappers
 * Timber handles the layout, so we don't need WooCommerce's wrappers
 */
function theme_remove_woocommerce_wrappers(): void {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	// Remove default product loops
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

	// Remove default product query handling (Timber does this)
	remove_action( 'pre_get_posts', array( WC()->query, 'pre_get_posts' ) );
}
add_action( 'init', 'theme_remove_woocommerce_wrappers', 20 );

/**
 * Add WooCommerce-specific Twig filters
 * 
 * @param Twig\Environment $twig The Twig environment.
 * @return Twig\Environment Modified Twig environment.
 */
function theme_add_woocommerce_to_twig( Twig\Environment $twig ): Twig\Environment {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return $twig;
	}

	// Filter to format price
	$twig->addFilter( new Twig\TwigFilter( 'price', function( $price ) {
		return wc_price( $price );
	} ) );

	// Filter to get product image
	$twig->addFilter( new Twig\TwigFilter( 'product_image', function( $product, $size = 'woocommerce_thumbnail' ) {
		if ( is_numeric( $product ) ) {
			$product = wc_get_product( $product );
		}
		return $product ? $product->get_image( 'woocommerce_thumbnail' ) : '';
	} ) );

	// Filter to check if product is on sale
	$twig->addFilter( new Twig\TwigFilter( 'is_on_sale', function( $product ) {
		if ( is_numeric( $product ) ) {
			$product = wc_get_product( $product );
		}
		return $product ? $product->is_on_sale() : false;
	} ) );

	return $twig;
}
add_filter( 'timber/twig', 'theme_add_woocommerce_to_twig' );

/**
 * Override WooCommerce template hooks
 * This allows templates to use Timber without wrapper divs
 */
function theme_woocommerce_hooks(): void {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	// Remove WooCommerce breadcrumb output to prevent structured data conflicts
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
	
	// Remove WooCommerce structured data hooks that conflict with Timber
	remove_action( 'wp_footer', 'WC_Structured_Data::output_structured_data' );

	// Hook into product single page
	if ( is_product() ) {
		// Let Timber handle everything
	}

	// Hook into shop page
	if ( is_shop() || is_product_taxonomy() ) {
		// Let Timber handle everything
	}
}
add_action( 'wp', 'theme_woocommerce_hooks' );
