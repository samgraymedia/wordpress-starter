<?php
/**
 * Post Type: Services
 * PostType: services
 * Name: Service
 */

return array(
	'args' => array(
		'labels'             => array(
			'name'               => __( 'Services', 'starter-theme-1.x' ),
			'singular_name'      => __( 'Service', 'starter-theme-1.x' ),
			'add_new'            => __( 'Add New', 'starter-theme-1.x' ),
			'add_new_item'       => __( 'Add New Service', 'starter-theme-1.x' ),
			'edit_item'          => __( 'Edit Service', 'starter-theme-1.x' ),
			'new_item'           => __( 'New Service', 'starter-theme-1.x' ),
			'view_item'          => __( 'View Service', 'starter-theme-1.x' ),
			'search_items'       => __( 'Search Services', 'starter-theme-1.x' ),
			'not_found'          => __( 'No services found', 'starter-theme-1.x' ),
			'not_found_in_trash' => __( 'No services found in Trash', 'starter-theme-1.x' ),
			'parent_item_colon'  => '',
			'menu_name'          => __( 'Services', 'starter-theme-1.x' ),
		),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'services' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-clipboard',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'show_in_rest'       => false,
	),
);
