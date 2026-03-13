<?php
/**
 * Service Tag Taxonomy
 * 
 * Taxonomy: service-tag
 * Name: Service Tags
 * PostTypes: service
 * Hierarchical: false
 * 
 * Flat taxonomy for tagging services with keywords.
 * Examples: Manual, Automatic, Defensive Driving, Eco-Driving, City Streets
 */

return array(
	'args' => array(
		'labels'                => array(
			'name'                       => _x( 'Service Tags', 'taxonomy general name' ),
			'singular_name'              => _x( 'Service Tag', 'taxonomy singular name' ),
			'search_items'               => __( 'Search Service Tags' ),
			'all_items'                  => __( 'All Service Tags' ),
			'popular_items'              => __( 'Popular Service Tags' ),
			'edit_item'                  => __( 'Edit Service Tag' ),
			'update_item'                => __( 'Update Service Tag' ),
			'add_new_item'               => __( 'Add New Service Tag' ),
			'new_item_name'              => __( 'New Service Tag Name' ),
			'menu_name'                  => __( 'Service Tags' ),
			'back_to_items'              => __( 'Back to Service Tags' ),
		),
		'description'           => 'Tag services with keywords for filtering',
		'public'                => true,
		'show_ui'               => true,
		'show_in_rest'          => true,
		'show_in_nav_menus'     => true,
		'show_admin_column'     => true,
		'hierarchical'          => false,
		'rewrite'               => array(
			'slug'       => 'service-tag',
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
