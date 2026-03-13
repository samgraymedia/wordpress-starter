<?php
/**
 * Search results page
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

$templates = array( 'search.twig', 'archive.twig', 'index.twig' );

$context = Timber::context();
$search_query = get_search_query();

$context['title'] = 'Search results for ' . $search_query;

// Determine post types to search
if ( isset( $_GET['post_type'] ) && 'product' === $_GET['post_type'] && function_exists( 'WC' ) ) {
	// Product search
	$args = array(
		's'              => $search_query,
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => 12,
		'paged'          => max( 1, get_query_var( 'paged' ) ),
		'orderby'        => 'relevance',
		'order'          => 'DESC',
	);
} else {
	// General post search
	$args = array(
		's'              => $search_query,
		'post_type'      => array( 'post', 'page', 'product' ),
		'post_status'    => 'publish',
		'posts_per_page' => 10,
		'paged'          => max( 1, get_query_var( 'paged' ) ),
	);
}

$context['posts'] = Timber::get_posts( $args );

Timber::render( $templates, $context );
