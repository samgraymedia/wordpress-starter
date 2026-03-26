<?php
/**
 * Cart Page Template
 * 
 * WooCommerce will render this template instead of the plugin's default cart template.
 * Uses Timber to render templates/cart.twig
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

// Build context for Timber
$context = Timber::context();
$context['woocommerce_cart'] = WC()->cart;

// Render the Timber template
Timber::render( 'cart.twig', $context );
exit;
