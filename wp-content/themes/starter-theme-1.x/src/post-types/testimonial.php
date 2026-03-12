<?php
/**
 * Post Type: Testimonials
 * PostType: testimonials
 * Name: Testimonial
 * FieldsKey: group_testimonials
 */

return array(
	'args'   => array(
		'labels'             => array(
			'name'               => __( 'Testimonials', 'starter-theme-1.x' ),
			'singular_name'      => __( 'Testimonial', 'starter-theme-1.x' ),
			'add_new'            => __( 'Add New', 'starter-theme-1.x' ),
			'add_new_item'       => __( 'Add New Testimonial', 'starter-theme-1.x' ),
			'edit_item'          => __( 'Edit Testimonial', 'starter-theme-1.x' ),
			'new_item'           => __( 'New Testimonial', 'starter-theme-1.x' ),
			'view_item'          => __( 'View Testimonial', 'starter-theme-1.x' ),
			'search_items'       => __( 'Search Testimonials', 'starter-theme-1.x' ),
			'not_found'          => __( 'No testimonials found', 'starter-theme-1.x' ),
			'not_found_in_trash' => __( 'No testimonials found in Trash', 'starter-theme-1.x' ),
			'parent_item_colon'  => '',
			'menu_name'          => __( 'Testimonials', 'starter-theme-1.x' ),
		),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'testimonials' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 6,
		'menu_icon'          => 'dashicons-format-quote',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'show_in_rest'       => false,
	),
	'fields' => array(
		'key'      => 'group_testimonials',
		'title'    => 'Testimonial Details',
		'fields'   => array(
			array(
				'key'          => 'field_testimonial_rating',
				'label'        => 'Rating',
				'name'         => 'testimonial_rating',
				'type'         => 'range',
				'instructions' => 'Select the rating (1-5 stars)',
				'required'     => 1,
				'min'          => 1,
				'max'          => 5,
				'step'         => 1,
				'default_value' => 5,
			),
			array(
				'key'          => 'field_testimonial_text',
				'label'        => 'Testimonial',
				'name'         => 'testimonial_text',
				'type'         => 'textarea',
				'instructions' => 'Enter the testimonial text',
				'required'     => 1,
				'rows'         => 4,
			),
			array(
				'key'          => 'field_testimonial_location',
				'label'        => 'Location',
				'name'         => 'testimonial_location',
				'type'         => 'text',
				'instructions' => 'Enter the location (e.g., "New York, NY" or "London, UK")',
				'required'     => 1,
			),
			array(
				'key'           => 'field_related_service',
				'label'         => 'Related Service',
				'name'          => 'related_service',
				'type'          => 'post_object',
				'instructions'  => 'Select the related service this testimonial is for.',
				'required'      => 0,
				'post_type'     => array( 'services' ),
				'return_format' => 'object',
				'ui'            => 1,
				'allow_null'    => 1,
				'multiple'      => 0,
			),
		),
		'location'            => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'testimonials',
				),
			),
		),
		'menu_order'          => 0,
		'position'            => 'normal',
		'style'               => 'default',
		'label_placement'     => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'      => '',
	),
);
