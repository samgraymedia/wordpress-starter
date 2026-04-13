<?php
/**
 * Post Type: Testimonials
 * PostType: testimonial
 * Name: Testimonials
 * FieldsKey: group_testimonials
 */

return array(
	'args' => array(
		'label'               => 'Testimonials',
		'description'         => 'Client testimonials and reviews',
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'query_var'           => true,
		'rewrite'             => array(
			'slug' => 'testimonial',
		),
		'capability_type'     => 'post',
		'has_archive'         => true,
		'hierarchical'        => false,
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-format-quote',
		'supports'            => array(
			'title',
			
			'thumbnail',
			'excerpt',
		),
	),
	'fields' => array(
		'key'                   => 'group_testimonials',
		'title'                 => 'Testimonial Fields',
		'fields'                => array(
			array(
				'key'           => 'field_testimonial_image',
				'label'         => "Person's Image",
				'name'          => 'person_image',
				'type'          => 'image',
				'return_format' => 'array',
				'preview_size'  => 'medium',
			),
			array(
				'key'           => 'field_testimonial_quote',
				'label'         => 'Testimonial',
				'name'          => 'testimonial',
				'type'          => 'textarea',
				'rows'          => 4,
				'placeholder'   => 'Enter the testimonial or review...',
			),
			array(
				'key'           => 'field_testimonial_name',
				'label'         => 'Name',
				'name'          => 'name',
				'type'          => 'text',
				'placeholder'   => 'Full name',
			),
			array(
				'key'           => 'field_testimonial_job_title',
				'label'         => 'Job Title',
				'name'          => 'job_title',
				'type'          => 'text',
				'placeholder'   => 'e.g. Marketing Manager',
			),
			array(
				'key'           => 'field_testimonial_organization',
				'label'         => 'Organization',
				'name'          => 'organization',
				'type'          => 'text',
				'placeholder'   => 'Company or organization name',
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'testimonial',
				),
			),
		),
		'menu_order'            => 0,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => '',
		'active'                => true,
		'description'           => 'Fields for displaying testimonials with person information',
	),
);
