<?php
/**
 * Image Pair Block Fields
 * 
 * ACF field configuration for the image pair block
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group( array(
	'key'      => 'group_block_image_pair',
	'title'    => 'Image Pair Block',
	'fields'   => array(
		array(
			'key'           => 'field_block_image_pair_1',
			'label'         => 'Image 1',
			'name'          => 'image_1',
			'type'          => 'image',
			'return_format' => 'array',
		),
		array(
			'key'           => 'field_block_image_pair_2',
			'label'         => 'Image 2',
			'name'          => 'image_2',
			'type'          => 'image',
			'return_format' => 'array',
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/image-pair',
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
	'description'      => 'Display up to two images',
) );
