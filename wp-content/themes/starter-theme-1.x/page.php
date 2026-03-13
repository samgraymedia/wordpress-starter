<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * To generate specific templates for your pages you can use:
 * /mytheme/templates/page-mypage.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-mypage.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();

$timber_post     = new Timber\Post();
$context['post'] = $timber_post;

// Check if a custom page template is selected
$page_template = get_page_template_slug( $timber_post->ID );

if ( $page_template ) {
	// Build list of Twig templates to try:
	// 1. Custom template from the page templates registry (if available via filter)
	// 2. Template matching the template slug (e.g., page-full-width.twig for 'page-full-width')
	// 3. Default page slug template (e.g., page-mypage.twig)
	// 4. Default page template
	
	$templates = array();
	
	// Check if the registry provided a custom Twig template path
	$custom_twig = apply_filters( 'timber_page_twig_template', null );
	if ( $custom_twig ) {
		$templates[] = $custom_twig;
	}
	
	// Fallback to template name matching the page template slug
	$templates[] = 'page-' . $page_template . '.twig';
	
	// Then try slug-specific and default templates as usual
	$templates[] = 'page-' . $timber_post->post_name . '.twig';
	$templates[] = 'page.twig';
	
	Timber::render( $templates, $context );
} else {
	// No custom template selected - use default behavior
	Timber::render( array( 'page-' . $timber_post->post_name . '.twig', 'page.twig' ), $context );
}
