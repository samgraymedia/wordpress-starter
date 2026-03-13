<?php
/**
 * Testimonial / Reviews Block - ACF Field Registration
 * Block itself is auto-discovered from views/blocks/testimonial.twig
 */

if ( function_exists( 'acf_add_local_field_group' ) ) {
	acf_add_local_field_group(
		array(
			'key'                   => 'group_testimonial_block',
			'title'                 => 'Testimonial Block',
			'fields'                => array(
				array(
					'key'           => 'field_testimonial_title',
					'label'         => 'Section Title',
					'name'          => 'title',
					'type'          => 'text',
					'placeholder'   => 'Customer Testimonials',
				),
				array(
					'key'           => 'field_testimonial_description',
					'label'         => 'Section Description',
					'name'          => 'description',
					'type'          => 'textarea',
					'rows'          => 3,
				),
				array(
					'key'           => 'field_testimonial_selection',
					'label'         => 'Select Testimonials',
					'name'          => 'testimonials',
					'type'          => 'post_object',
					'post_type'     => 'testimonials',
					'allow_null'    => 1,
					'multiple'      => 1,
					'return_format' => 'object',
					'ui'            => 1,
				),
				array(
					'key'           => 'field_testimonial_layout',
					'label'         => 'Layout',
					'name'          => 'layout',
					'type'          => 'select',
					'choices'       => array(
						'grid'      => 'Grid',
						'carousel'  => 'Carousel',
						'list'      => 'List',
					),
					'default_value' => 'grid',
				),
				array(
					'key'           => 'field_testimonial_columns',
					'label'         => 'Columns (Grid Only)',
					'name'          => 'columns',
					'type'          => 'select',
					'choices'       => array(
						'2' => '2 Columns',
						'3' => '3 Columns',
					),
					'default_value' => '3',
				),
				array(
					'key'           => 'field_testimonial_show_rating',
					'label'         => 'Show Star Ratings',
					'name'          => 'show_rating',
					'type'          => 'true_false',
					'default_value' => 1,
				),
				array(
					'key'           => 'field_testimonial_show_author',
					'label'         => 'Show Author Info',
					'name'          => 'show_author',
					'type'          => 'true_false',
					'default_value' => 1,
				),
				array(
					'key'           => 'field_testimonial_show_image',
					'label'         => 'Show Author Image',
					'name'          => 'show_image',
					'type'          => 'true_false',
					'default_value' => 1,
				),
				array(
					'key'           => 'field_testimonial_autoplay',
					'label'         => 'Autoplay (Carousel Only)',
					'name'          => 'autoplay',
					'type'          => 'true_false',
					'default_value' => 1,
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/testimonial',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => array(),
			'active'                => true,
			'description'           => '',
		)
	);
}
