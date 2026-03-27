<?php
/**
 * Template Name: Product Comparison
 * Template Post Type: page
 *
 * This is the main template for the product comparison page.
 * It loads the comparison.twig template from the templates directory.
 *
 * @package StarterTheme
 */

// Ensure WooCommerce and Timber are active
if ( ! class_exists( 'WooCommerce' ) ) {
	wp_die( esc_html__( 'WooCommerce is not active', 'starter-theme' ) );
}

if ( ! class_exists( 'Timber' ) ) {
	wp_die( esc_html__( 'Timber plugin is not active', 'starter-theme' ) );
}

// Get the Timber context
global $wp_query;
$context = Timber::context();

// Get comparison products using the theme comparison function
$comparison_ids = theme_get_comparison();
$comparison_products = array();

if ( ! empty( $comparison_ids ) ) {
	foreach ( $comparison_ids as $product_id ) {
		$product = wc_get_product( $product_id );
		if ( $product ) {
			$comparison_products[] = array(
				'product_id' => $product->get_id(),
				'title' => $product->get_name(),
				'price' => wc_price( $product->get_price(), array( 'echo' => false ) ),
				'regular_price' => wc_price( $product->get_regular_price(), array( 'echo' => false ) ),
				'on_sale' => $product->is_on_sale(),
				'in_stock' => $product->is_in_stock(),
				'image' => $product->get_image( 'medium' ),
				'sku' => $product->get_sku(),
				'permalink' => $product->get_permalink(),
				'rating' => round( $product->get_average_rating() ),
				'review_count' => $product->get_review_count(),
				'brand' => $product->get_attribute( 'pa_brand' ) ?: __( 'Not specified', 'starter-theme' ),
				'material' => $product->get_attribute( 'pa_material' ) ?: __( 'Not specified', 'starter-theme' ),
				'warranty' => $product->get_attribute( 'pa_warranty' ) ?: __( 'Not specified', 'starter-theme' ),
				'description' => wp_trim_words( $product->get_description(), 20 ),
				'specifications' => apply_filters( 'theme_product_comparison_specs', array(), $product ),
			);
		}
	}
}

// Get unique specification keys
$spec_keys = array();
foreach ( $comparison_products as $product ) {
	$spec_keys = array_unique( array_merge( $spec_keys, array_keys( $product['specifications'] ) ) );
}

$context['comparison_products'] = $comparison_products;
$context['comparison_count'] = count( $comparison_products );
$context['is_empty'] = empty( $comparison_products );
$context['spec_keys'] = $spec_keys;

// Render the template
Timber::render( 'comparison.twig', $context );
