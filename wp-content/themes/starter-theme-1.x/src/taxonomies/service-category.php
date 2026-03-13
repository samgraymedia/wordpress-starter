<?php
/**
 * Service Category Taxonomy
 * 
 * Taxonomy: service-category
 * Name: Service Categories
 * PostTypes: service
 * Hierarchical: true
 * FieldsKey: group_service_category_fields
 * 
 * Hierarchical taxonomy for categorizing driving services.
 * Examples: Beginner, Intermediate, Advanced, Specialized Training
 */

return array(
	'args' => array(
		'labels'                => array(
			'name'                       => _x( 'Service Categories', 'taxonomy general name' ),
			'singular_name'              => _x( 'Service Category', 'taxonomy singular name' ),
			'search_items'               => __( 'Search Service Categories' ),
			'all_items'                  => __( 'All Service Categories' ),
			'parent_item'                => __( 'Parent Service Category' ),
			'parent_item_colon'          => __( 'Parent Service Category:' ),
			'edit_item'                  => __( 'Edit Service Category' ),
			'update_item'                => __( 'Update Service Category' ),
			'add_new_item'               => __( 'Add New Service Category' ),
			'new_item_name'              => __( 'New Service Category Name' ),
			'menu_name'                  => __( 'Service Categories' ),
			'back_to_items'              => __( 'Back to Service Categories' ),
		),
		'description'           => 'Organize and categorize driving services',
		'public'                => true,
		'show_ui'               => true,
		'show_in_rest'          => true,
		'show_in_nav_menus'     => true,
		'show_admin_column'     => true,
		'hierarchical'          => true,
		'rewrite'               => array(
			'slug'       => 'service-category',
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
