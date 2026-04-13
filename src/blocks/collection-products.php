<?php
/**
 * Collection Products Block Fields
 * 
 * ACF field configuration for the collection products block
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group( array(
	'key'      => 'group_block_collection',
	'title'    => 'Collections Block',
	'fields'   => array(
		array(
			'key'           => 'field_block_collection_select',
			'label'         => 'Select Collections',
			'name'          => 'collection',
			'type'          => 'relationship',
			'return_format' => 'id',
			'post_type'     => 'collection',
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/collection-products',
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
	'description'      => 'Configure the collection products grid display',
) );
