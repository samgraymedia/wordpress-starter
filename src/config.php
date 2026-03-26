<?php
/**
 * Theme Configuration
 *
 * Centralized configuration for theme settings, features, and constants.
 * This file serves as the single source of truth for all theme-wide settings.
 */

return array(
	/**
	 * Theme Metadata
	 */
	'theme'      => array(
		'name'        => 'Starter Theme',
		'textdomain'  => 'starter-theme-1.x',
		'description' => 'A modern, modular WordPress theme built with Timber and ACF',
		'version'     => '1.0.0',
	),

	/**
	 * Post Types Configuration
	 */
	'post_types' => array(
		'services'     => array(
			'slug'       => 'services',
			'menu_order' => 5,
		),
		'testimonials' => array(
			'slug'       => 'testimonials',
			'menu_order' => 6,
		),
	),

	/**
	 * Taxonomy Configuration
	 */
	'taxonomies' => array(
		// Add taxonomies here as needed
		// Example:
		// 'service_category' => array(
		//     'name'        => 'Service Category',
		//     'post_types'  => array( 'services' ),
		//     'hierarchical' => true,
		// ),
	),

	/**
	 * Menu Locations
	 */
	'menus'      => array(
		'header' => __( 'Header Navigation', 'starter-theme-1.x' ),
		'footer' => __( 'Footer Navigation', 'starter-theme-1.x' ),
	),

	/**
	 * Image Sizes
	 * Format: 'size_name' => array( width, height, crop )
	 */
	'image_sizes' => array(
		'thumbnail'      => array( 150, 150, true ),
		'medium'         => array( 300, 300, false ),
		'medium_large'   => array( 768, 0, false ),
		'large'          => array( 1024, 0, false ),
		'hero'           => array( 1200, 300, true ),
		'post_thumbnail' => array( 300, 200, true ),
		// WooCommerce image sizes (only registered if WooCommerce is active)
		'woocommerce_thumbnail' => array( 300, 300, true ),
		'woocommerce_single'    => array( 600, 600, true ),
		'woocommerce_gallery'   => array( 800, 800, true ),
	),

	/**
	 * Theme Support Features
	 */
	'theme_support' => array(
		'automatic-feed-links' => true,
		'title-tag'            => true,
		'post-thumbnails'      => true,
		'html5'                => array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		),
		'post-formats'         => array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
			'gallery',
			'audio',
		),
		'menus'                => true,
		// 'woocommerce'          => true, // Optional - plugin support enabled if WooCommerce is active
	),

	/**
	 * Sidebar/Widget Areas
	 */
	'sidebars'   => array(
		'primary' => array(
			'name'          => __( 'Primary Sidebar', 'starter-theme-1.x' ),
			'description'   => __( 'Main sidebar', 'starter-theme-1.x' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		),
	),

	/**
	 * ACF Settings
	 */
	'acf'        => array(
		'enable_options_page' => true,
		'save_json'           => false, // Set to path to enable JSON sync
		'load_json'           => false,
	),

	/**
	 * Timber Configuration
	 */
	'timber'     => array(
		'directories' => array( 'templates', 'views' ),
		'autoescape'  => false,
		'cache'       => false, // Enable in production
	),

	/**
	 * Stylesheet & Script Handles
	 * Used for enqueueing resources
	 */
	'assets'     => array(
		'main_style'   => 'starter-theme-css',
		'main_script'  => 'starter-theme-js',
		'jquery'       => 'jquery',
	),

	/**
	 * File Upload Settings
	 */
	'uploads'    => array(
		'allowed_types' => array(
			'svg' => 'image/svg+xml',
		),
	),

	/**
	 * WooCommerce Settings (Optional - only uses if WooCommerce plugin is active)
	 */
	'woocommerce' => array(
		'remove_defaults'  => true, // Remove default WooCommerce styles when active
		'disable_cart_fragments' => true, // Disable cart fragments AJAX when active
	),

	/**
	 * Development Settings
	 */
	'debug'      => array(
		'enable_twig_debug' => defined( 'WP_DEBUG' ) && WP_DEBUG,
	),
);
