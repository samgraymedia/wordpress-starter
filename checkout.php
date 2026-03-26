<?php
/**
 * Checkout Page Template
 * 
 * WooCommerce will render this template instead of the plugin's default checkout template.
 * Uses Timber to render templates/checkout.twig
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
$context['checkout'] = WC()->checkout();

// Render the Timber template
Timber::render( 'checkout.twig', $context );
exit;
