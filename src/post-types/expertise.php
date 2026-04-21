<?php
/**
 * Post Type: Expertise
 * PostType: expertise
 * Name: Expertise
 * FieldsKey: group_expertise
 */

return array(
	'args'   => array(
		'labels'             => array(
			'name'               => __( 'Expertise', 'starter-theme-1.x' ),
			'singular_name'      => __( 'Expertise Area', 'starter-theme-1.x' ),
			'add_new'            => __( 'Add New', 'starter-theme-1.x' ),
			'add_new_item'       => __( 'Add New Expertise Area', 'starter-theme-1.x' ),
			'edit_item'          => __( 'Edit Expertise Area', 'starter-theme-1.x' ),
			'new_item'           => __( 'New Expertise Area', 'starter-theme-1.x' ),
			'view_item'          => __( 'View Expertise Area', 'starter-theme-1.x' ),
			'search_items'       => __( 'Search Expertise Areas', 'starter-theme-1.x' ),
			'not_found'          => __( 'No expertise areas found', 'starter-theme-1.x' ),
			'not_found_in_trash' => __( 'No expertise areas found in Trash', 'starter-theme-1.x' ),
			'parent_item_colon'  => '',
			'menu_name'          => __( 'Expertise', 'starter-theme-1.x' ),
		),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'expertise' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-star-filled',
		'supports'           => array( 'title', 'thumbnail', 'excerpt', 'custom-fields' ),
		'show_in_rest'       => false,
	),
	'fields' => array(
		'key'      => 'group_expertise',
		'title'    => 'Expertise Details',
		'fields'   => array(
			array(
				'key'             => 'field_expertise_description',
				'label'           => 'Description',
				'name'            => 'description',
				'type'            => 'wysiwyg',
				'instructions'    => 'Detailed description of this expertise area',
				'required'        => 0,
				'tabs'            => 'all',
				'toolbar'         => 'full',
				'media_upload'    => 1,
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'expertise',
				),
			),
		),
	),
);
