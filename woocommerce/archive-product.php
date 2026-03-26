<?php
/**
 * Shop/Product Archive Template
 * 
 * WooCommerce will render this template for shop pages and product archives.
 * Uses Timber to render templates/shop.twig
 * 
 * @package starter-theme-1.x
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Ensure WooCommerce is active
if ( ! class_exists( 'WooCommerce' ) ) {
	wp_die( esc_html__( 'WooCommerce is not active', 'starter-theme-1.x' ) );
}

// Ensure Timber is available
if ( ! class_exists( 'Timber' ) ) {
	wp_die( esc_html__( 'Timber is not active', 'starter-theme-1.x' ) );
}

// Build context for Timber
$context = Timber::context();

// Render the Timber template
Timber::render( 'shop.twig', $context );
exit;

