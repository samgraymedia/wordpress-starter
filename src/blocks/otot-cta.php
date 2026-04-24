<?php
/**
 * OTOT CTA Block
 *
 * ACF field group registration for the OTOT CTA block.
 * Repeater of max 2 items with title, text, button URL, and image.
 *
 * @package StarterTheme
 */

if ( function_exists( 'acf_add_local_field_group' ) ) {
	acf_add_local_field_group(
		array(
			'key'                   => 'group_otot_cta_block',
			'title'                 => 'OTOT CTA Block',
			'fields'                => array(
				array(
					'key'           => 'field_otot_cta_items',
					'label'         => 'CTA Items',
					'name'          => 'cta_items',
					'type'          => 'repeater',
					'instructions'  => 'Add up to 2 CTA items',
					'required'      => 0,
					'conditional_logic' => false,
					'wrapper'       => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'collapsed'     => 'field_otot_cta_title',
					'min'           => 0,
					'max'           => 2,
					'layout'        => 'table',
					'button_label'  => 'Add Item',
					'sub_fields'    => array(
						array(
							'key'           => 'field_otot_cta_title',
							'label'         => 'Title',
							'name'          => 'title',
							'type'          => 'text',
							'instructions'  => 'Item title',
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
							'key'           => 'field_otot_cta_text',
							'label'         => 'Text',
							'name'          => 'text',
							'type'          => 'textarea',
							'instructions'  => 'Description or call-to-action text',
							'required'      => 0,
							'conditional_logic' => false,
							'wrapper'       => array(
								'width' => '25',
								'class' => '',
								'id'    => '',
							),
							'placeholder'   => '',
							'maxlength'     => '',
							'rows'          => 4,
						),
						array(
							'key'           => 'field_otot_cta_button_url',
							'label'         => 'Button URL',
							'name'          => 'button_url',
							'type'          => 'url',
							'instructions'  => 'Link for the CTA button',
							'required'      => 0,
							'conditional_logic' => false,
							'wrapper'       => array(
								'width' => '25',
								'class' => '',
								'id'    => '',
							),
							'placeholder'   => '',
						),
						array(
							'key'           => 'field_otot_cta_image',
							'label'         => 'Image',
							'name'          => 'image',
							'type'          => 'image',
							'instructions'  => 'CTA item image',
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
					),
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/otot-cta',
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
