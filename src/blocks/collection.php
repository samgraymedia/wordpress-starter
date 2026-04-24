<?php
/**
 * Collection Block
 *
 * ACF field group registration for the Collection block.
 * Repeater of items with image, hover image, title, and year.
 *
 * @package StarterTheme
 */

if ( function_exists( 'acf_add_local_field_group' ) ) {
	acf_add_local_field_group(
		array(
			'key'                   => 'group_collection_block',
			'title'                 => 'Collection Block',
			'fields'                => array(
				array(
					'key'           => 'field_collection_items',
					'label'         => 'Collection Items',
					'name'          => 'collection_items',
					'type'          => 'repeater',
					'instructions'  => 'Add items to your collection',
					'required'      => 0,
					'conditional_logic' => false,
					'wrapper'       => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'collapsed'     => 'field_collection_item_title',
					'min'           => 0,
					'max'           => 0,
					'layout'        => 'table',
					'button_label'  => 'Add Item',
					'sub_fields'    => array(
						array(
							'key'           => 'field_collection_image',
							'label'         => 'Image',
							'name'          => 'image',
							'type'          => 'image',
							'instructions'  => 'Main product/collection image',
							'required'      => 0,
							'conditional_logic' => false,
							'wrapper'       => array(
								'width' => '25',
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
						array(
							'key'           => 'field_collection_hover_image',
							'label'         => 'Hover Image',
							'name'          => 'hover_image',
							'type'          => 'image',
							'instructions'  => 'Image shown on hover',
							'required'      => 0,
							'conditional_logic' => false,
							'wrapper'       => array(
								'width' => '25',
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
						array(
							'key'           => 'field_collection_title',
							'label'         => 'Title',
							'name'          => 'title',
							'type'          => 'text',
							'instructions'  => 'Item title or name',
							'required'      => 0,
							'conditional_logic' => false,
							'wrapper'       => array(
								'width' => '25',
								'class' => '',
								'id'    => '',
							),
							'placeholder'   => '',
							'prepend'       => '',
							'append'        => '',
							'maxlength'     => '',
						),
						array(
							'key'           => 'field_collection_year',
							'label'         => 'Year',
							'name'          => 'year',
							'type'          => 'text',
							'instructions'  => 'Year or date associated with item',
							'required'      => 0,
							'conditional_logic' => false,
							'wrapper'       => array(
								'width' => '25',
								'class' => '',
								'id'    => '',
							),
							'placeholder'   => '',
							'prepend'       => '',
							'append'        => '',
							'maxlength'     => '',
						),
					),
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/collection',
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
