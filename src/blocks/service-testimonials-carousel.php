<?php
/**
 * Block: Service Testimonials Carousel
 * Description: Display testimonials in a carousel/slider filtered by service
 */

return array(
	'key'      => 'block_service_testimonials_carousel',
	'title'    => 'Service Testimonials Carousel',
	'fields'   => array(
		array(
			'key'           => 'field_testimonials_carousel_service',
			'label'         => 'Filter by Service (Optional)',
			'name'          => 'service',
			'type'          => 'post_object',
			'instructions'  => 'Leave empty to show all testimonials, or select a specific service',
			'post_type'     => array( 'services' ),
			'return_format' => 'object',
			'ui'            => 1,
			'allow_null'    => 1,
			'multiple'      => 0,
		),
		array(
			'key'          => 'field_testimonials_carousel_limit',
			'label'        => 'Number of Testimonials',
			'name'         => 'limit',
			'type'         => 'number',
			'instructions' => 'Leave blank to show all',
			'required'     => 0,
			'min'          => 1,
			'max'          => 20,
			'default_value' => 6,
		),
		array(
			'key'          => 'field_testimonials_carousel_layout',
			'label'        => 'Layout',
			'name'         => 'layout',
			'type'         => 'select',
			'choices'      => array(
				'slider'  => 'Carousel (Auto-scroll)',
				'grid'    => 'Grid',
				'single'  => 'Single (Rotates)',
			),
			'default_value' => 'slider',
			'return_format' => 'value',
		),
		array(
			'key'          => 'field_testimonials_carousel_columns',
			'label'        => 'Columns (Grid layout only)',
			'name'         => 'columns',
			'type'         => 'select',
			'choices'      => array(
				'1' => '1 Column',
				'2' => '2 Columns',
				'3' => '3 Columns',
			),
			'default_value' => '1',
			'return_format' => 'value',
			'conditional_logic' => array(
				array(
					array(
						'field'    => 'field_testimonials_carousel_layout',
						'operator' => '==',
						'value'    => 'grid',
					),
				),
			),
		),
		array(
			'key'          => 'field_testimonials_carousel_show_rating',
			'label'        => 'Show Star Rating',
			'name'         => 'show_rating',
			'type'         => 'true_false',
			'instructions' => 'Display 1-5 star rating',
			'default_value' => 1,
		),
		array(
			'key'          => 'field_testimonials_carousel_show_author',
			'label'        => 'Show Author Name',
			'name'         => 'show_author',
			'type'         => 'true_false',
			'instructions' => 'Display testimonial author name',
			'default_value' => 1,
		),
		array(
			'key'          => 'field_testimonials_carousel_show_location',
			'label'        => 'Show Location',
			'name'         => 'show_location',
			'type'         => 'true_false',
			'instructions' => 'Display author location',
			'default_value' => 0,
		),
		array(
			'key'          => 'field_testimonials_carousel_autoplay',
			'label'        => 'Autoplay',
			'name'         => 'autoplay',
			'type'         => 'true_false',
			'instructions' => 'Auto-advance carousel (slider layout only)',
			'default_value' => 1,
			'conditional_logic' => array(
				array(
					array(
						'field'    => 'field_testimonials_carousel_layout',
						'operator' => '!=',
						'value'    => 'grid',
					),
				),
			),
		),
		array(
			'key'          => 'field_testimonials_carousel_autoplay_delay',
			'label'        => 'Autoplay Delay (seconds)',
			'name'         => 'autoplay_delay',
			'type'         => 'number',
			'instructions' => 'How long to show each testimonial before auto-advancing',
			'required'     => 0,
			'default_value' => 5,
			'min'          => 2,
			'max'          => 30,
			'conditional_logic' => array(
				array(
					array(
						'field'    => 'field_testimonials_carousel_autoplay',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/service-testimonials-carousel',
			),
		),
	),
);
