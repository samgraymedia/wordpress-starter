<?php
/**
 * Post Type: Testimonial
 * PostType: testimonial
 * Name: Testimonial
 * FieldsKey: group_featured_testimonial
 */

return array(
	'args'   => array(
		'labels'             => array(
			'name'               => __( 'Testimonial Posts', 'starter-theme-1.x' ),
			'singular_name'      => __( 'Testimonial Post', 'starter-theme-1.x' ),
			'add_new'            => __( 'Add New', 'starter-theme-1.x' ),
			'add_new_item'       => __( 'Add New Testimonial Post', 'starter-theme-1.x' ),
			'edit_item'          => __( 'Edit Testimonial Post', 'starter-theme-1.x' ),
			'new_item'           => __( 'New Testimonial Post', 'starter-theme-1.x' ),
			'view_item'          => __( 'View Testimonial Post', 'starter-theme-1.x' ),
			'search_items'       => __( 'Search Testimonial Posts', 'starter-theme-1.x' ),
			'not_found'          => __( 'No testimonial posts found', 'starter-theme-1.x' ),
			'not_found_in_trash' => __( 'No testimonial posts found in Trash', 'starter-theme-1.x' ),
			'parent_item_colon'  => '',
			'menu_name'          => __( 'Testimonial', 'starter-theme-1.x' ),
		),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'testimonial' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 4,
		'menu_icon'          => 'dashicons-edit',
	'supports'           => array( 'title', 'thumbnail', 'excerpt', 'custom-fields', 'author', 'comments' ),
		'show_in_rest'       => false,
	),
	'fields' => array(
		'key'      => 'group_featured_testimonial',
		'title'    => 'Featured Testimonial Post',
		'fields'   => array(
			array(
				'key'             => 'field_testimonial_wysiwyg',
				'label'           => 'Content',
				'name'            => 'testimonial_wysiwyg',
				'type'            => 'wysiwyg',
				'instructions'    => 'Main article content with full editor features',
				'required'        => 0,
				'tabs'            => 'all',
				'toolbar'         => 'full',
				'media_upload'    => 1,
				'delay'           => 0,
			),
			array(
				'key'             => 'field_job_title',
				'label'           => 'Job Title',
				'name'            => 'job_title',
				'type'            => 'text',
				'instructions'    => 'Enter the job title of the person giving the testimonial.',
			),
					array(
				'key'             => 'field_job_organization',
				'label'           => 'Organization',
				'name'            => 'job_organization',
				'type'            => 'text',
				'instructions'    => 'Enter the organization of the person giving the testimonial.',
			),
		
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'testimonial',
				),
			),
		),
	),
);
