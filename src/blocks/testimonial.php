<?php
/**
 * Testimonials Block Fields
 * 
 * ACF field configuration for the testimonials block
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group( array(
	'key'      => 'group_block_testimonials',
	'title'    => 'Testimonials Block',
	'fields'   => array(
		array(
			'key'           => 'field_block_testimonials_select',
			'label'         => 'Select Testimonials',
			'name'          => 'testimonials',
			'type'          => 'relationship',
			'return_format' => 'id',
			'post_type'     => 'testimonial',
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/testimonial',
			),
		),
	),
	'menu_order'       => 0,
	'position'         => 'normal',
	'style'            => 'default',
	'label_placement'  => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen'   => '',
	'active'           => true,
	'description'      => 'Select testimonials to display in the block',
) );
