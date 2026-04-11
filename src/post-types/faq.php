<?php
/**
 * Post Type: FAQ
 * PostType: faq
 * Name: FAQ
 * FieldsKey: group_featured_faq
 */

return array(
	'args'   => array(
		'labels'             => array(
			'name'               => __( 'FAQ Posts', 'starter-theme-1.x' ),
			'singular_name'      => __( 'FAQ Post', 'starter-theme-1.x' ),
			'add_new'            => __( 'Add New', 'starter-theme-1.x' ),
			'add_new_item'       => __( 'Add New FAQ Post', 'starter-theme-1.x' ),
			'edit_item'          => __( 'Edit FAQ Post', 'starter-theme-1.x' ),
			'new_item'           => __( 'New FAQ Post', 'starter-theme-1.x' ),
			'view_item'          => __( 'View FAQ Post', 'starter-theme-1.x' ),
			'search_items'       => __( 'Search FAQ Posts', 'starter-theme-1.x' ),
			'not_found'          => __( 'No faq posts found', 'starter-theme-1.x' ),
			'not_found_in_trash' => __( 'No faq posts found in Trash', 'starter-theme-1.x' ),
			'parent_item_colon'  => '',
			'menu_name'          => __( 'FAQ', 'starter-theme-1.x' ),
		),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'faq' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 4,
		'menu_icon'          => 'dashicons-edit',
		'supports'           => array( 'title', 'thumbnail', 'excerpt', 'custom-fields', 'author', 'comments' ),
		'show_in_rest'       => false,
	),
	'fields' => array(
		'key'      => 'group_featured_faq',
		'title'    => 'Featured FAQ Post',
		'fields'   => array(
			array(
				'key'             => 'field_faq_answer',
				'label'           => 'Answer',
				'name'            => 'faq_answer',
				'type'            => 'textarea',
				'instructions'    => 'Enter the answer for the FAQ.',
			),
		
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'faq',
				),
			),
		),
	),
);
