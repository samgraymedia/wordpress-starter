<?php
/**
 * Block: Image + Text Items
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group( array(
	'key'      => 'group_block_image_text_items',
	'title'    => 'Image + Text Items Block',
	'fields'   => array(
		array(
			'key'             => 'field_image_text_items',
			'label'           => 'Items',
			'name'            => 'items',
			'type'            => 'repeater',
			'instructions'    => 'Add items with image and text',
			'required'        => 0,
			'sub_fields'      => array(
				array(
					'key'             => 'field_item_lead',
					'label'           => 'Lead',
					'name'            => 'lead',
					'type'            => 'text',
					'instructions'    => 'Lead text',
					'required'        => 0,
				),
				array(
					'key'             => 'field_item_text',
					'label'           => 'Text',
					'name'            => 'text',
					'type'            => 'textarea',
					'instructions'    => 'Item text content',
					'required'        => 0,
					'rows'            => 4,
				),
				array(
					'key'             => 'field_item_image',
					'label'           => 'Image',
					'name'            => 'image',
					'type'            => 'image',
					'instructions'    => 'Item image',
					'required'        => 0,
					'return_format'   => 'id',
				),
			),
			'min'             => 0,
			'max'             => 0,
			'layout'          => 'table',
			'button_label'    => 'Add Item',
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/image-text-items',
			),
		),
	),
) );
