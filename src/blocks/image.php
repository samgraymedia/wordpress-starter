<?php
/**
 * Image Block
 *
 * ACF field group registration for the Image block.
 *
 * @package StarterTheme
 */

if ( function_exists( 'acf_add_local_field_group' ) ) {
	acf_add_local_field_group(
		array(
			'key'                   => 'group_image_block',
			'title'                 => 'Image Block',
			'fields'                => array(
				array(
					'key'           => 'field_image_block_image',
					'label'         => 'Image',
					'name'          => 'image',
					'type'          => 'image',
					'instructions'  => 'Upload or select an image',
					'required'      => 0,
					'conditional_logic' => false,
					'wrapper'       => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'return_format' => 'array',
					'preview_size'  => 'medium',
					'library'       => 'all',
					'min_width'     => '',
					'min_height'    => '',
					'min_size'      => '',
					'max_width'     => '',
					'max_height'    => '',
					'max_size'      => '',
					'mime_types'    => '',
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/image',
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
			'description'           => '',
		)
	);
}
