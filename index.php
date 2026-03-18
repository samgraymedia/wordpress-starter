<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

$context          = Timber::context();
$context['posts'] = new Timber\PostQuery();
$context['foo']   = 'bar';
$templates        = array( 'index.twig' );

if ( is_home() ) {
	array_unshift( $templates, 'front-page.twig', 'home.twig' );
}

// WooCommerce template detection
if ( function_exists( 'is_shop' ) ) {
	if ( is_product() ) {
		// Single product page
		$context['product'] = new Timber\Post();
		$templates = array( 'single-product.twig', 'single.twig', 'index.twig' );
	} elseif ( is_shop() || is_product_taxonomy() ) {
		// Shop page or product category/tag archive
		// Get WooCommerce products using the global query
		$context['posts'] = new Timber\PostQuery();
		$context['shop_description'] = woocommerce_taxonomy_archive_description();
		$context['shop_title'] = __( 'Shop', 'starter-theme-1.x' );
		$templates = array( 'shop.twig', 'archive.twig', 'index.twig' );
	}
}

Timber::render( $templates, $context );
