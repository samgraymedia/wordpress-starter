<?php

/**
 * Register Banner Block Fields
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group( array(
	'key'      => 'group_banner_block',
	'title'    => 'Banner Block Fields',
	'fields'   => array(
		array(
			'key'             => 'field_banner_no_image_layout',
			'label'           => 'Text Only Layout',
			'name'            => 'no_image_layout',
			'type'            => 'true_false',
			'instructions'    => 'Hide image and display text only',
			'required'        => 0,
			'default_value'   => 0,
			'ui'              => 1,
		),
		array(
			'key'             => 'field_banner_image',
			'label'           => 'Banner Image',
			'name'            => 'banner_image',
			'type'            => 'image',
			'instructions'    => 'Upload the banner image',
			'required'        => 0,
			'return_format'   => 'id',
			'conditional_logic' => array(
				array(
					array(
						'field'    => 'field_banner_no_image_layout',
						'operator' => '==',
						'value'    => 0,
					),
				),
			),
		),
		array(
			'key'             => 'field_banner_title',
			'label'           => 'Banner Title',
			'name'            => 'banner_title',
			'type'            => 'text',
			'instructions'    => 'Enter the banner title',
			'required'        => 0,
		),
		array(
			'key'             => 'field_banner_button_url',
			'label'           => 'Button URL',
			'name'            => 'button_url',
			'type'            => 'url',
			'instructions'    => 'Link for the button',
			'required'        => 0,
			'conditional_logic' => array(
				array(
					array(
						'field'    => 'field_banner_no_image_layout',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
		),
		array(
			'key'             => 'field_banner_button_text',
			'label'           => 'Button Text',
			'name'            => 'button_text',
			'type'            => 'text',
			'instructions'    => 'Text for the button',
			'required'        => 0,
			'conditional_logic' => array(
				array(
					array(
						'field'    => 'field_banner_no_image_layout',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
		),
		array(
			'key'             => 'field_banner_button_style',
			'label'           => 'Button Style',
			'name'            => 'button_style',
			'type'            => 'select',
			'instructions'    => 'Choose button style',
			'required'        => 0,
			'choices'         => array(
				'primary'   => 'Primary',
				'secondary' => 'Secondary',
				'tertiary'  => 'Tertiary',
			),
			'default_value'   => 'primary',
			'conditional_logic' => array(
				array(
					array(
						'field'    => 'field_banner_no_image_layout',
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
				'value'    => 'acf/banner',
			),
		),
	),
) ); 