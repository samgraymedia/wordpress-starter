<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * Methods for TimberHelper can be found in the /functions sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();

// Add 404 page options to context
if ( function_exists( 'get_field' ) ) {
	$context['error_404_heading'] = get_field( '404_heading', 'option' ) ?: 'Page Not Found';
	$context['error_404_message'] = get_field( '404_message', 'option' ) ?: 'Sorry, the page you are looking for does not exist or has been moved.';
	$context['error_404_cta_text'] = get_field( '404_cta_text', 'option' ) ?: 'Return to Home';
	$context['error_404_cta_url'] = get_field( '404_cta_url', 'option' ) ?: '';
	$context['error_404_show_search'] = get_field( '404_show_search', 'option' );
	$context['error_404_show_suggestions'] = get_field( '404_show_suggestions', 'option' );
}

// Check if WooCommerce is active
$context['is_woocommerce_active'] = class_exists( 'WooCommerce' );

Timber::render( '404.twig', $context );
