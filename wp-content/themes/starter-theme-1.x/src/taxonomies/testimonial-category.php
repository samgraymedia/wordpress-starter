<?php
/**
 * Testimonial Category Taxonomy
 * 
 * Taxonomy: testimonial-category
 * Name: Testimonial Categories
 * PostTypes: testimonials
 * Hierarchical: false
 * 
 * Flat taxonomy for categorizing and filtering testimonials.
 * Examples: Beginner Success, Student Review, Parent Feedback, Corporate Training
 */

return array(
	'args' => array(
		'labels'                => array(
			'name'                       => _x( 'Testimonial Categories', 'taxonomy general name' ),
			'singular_name'              => _x( 'Testimonial Category', 'taxonomy singular name' ),
			'search_items'               => __( 'Search Testimonial Categories' ),
			'all_items'                  => __( 'All Testimonial Categories' ),
			'popular_items'              => __( 'Popular Testimonial Categories' ),
			'edit_item'                  => __( 'Edit Testimonial Category' ),
			'update_item'                => __( 'Update Testimonial Category' ),
			'add_new_item'               => __( 'Add New Testimonial Category' ),
			'new_item_name'              => __( 'New Testimonial Category Name' ),
			'menu_name'                  => __( 'Testimonial Categories' ),
			'back_to_items'              => __( 'Back to Testimonial Categories' ),
		),
		'description'           => 'Categorize testimonials by type or feedback category',
		'public'                => true,
		'show_ui'               => true,
		'show_in_rest'          => true,
		'show_in_nav_menus'     => false,
		'show_admin_column'     => true,
		'hierarchical'          => false,
		'rewrite'               => array(
			'slug'       => 'testimonial-category',
			'with_front' => true,
		),
		'capabilities'          => array(
			'manage_terms' => 'manage_categories',
			'edit_terms'   => 'edit_posts',
			'delete_terms' => 'manage_categories',
			'assign_terms' => 'edit_posts',
		),
	),
);
