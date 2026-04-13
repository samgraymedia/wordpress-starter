<?php
/**
 * Banner CTA Block Fields
 * 
 * ACF field configuration for the banner CTA block with lead, title, buttons, and media
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group( array(
	'key'      => 'group_block_banner_cta',
	'title'    => 'Banner CTA Block',
	'fields'   => array(
		array(
			'key'           => 'field_block_banner_cta_lead',
			'label'         => 'Lead Text',
			'name'          => 'lead',
			'type'          => 'text',
			'instructions'  => 'Small text that appears above the title',
			'required'      => 0,
		),
		array(
			'key'           => 'field_block_banner_cta_title',
			'label'         => 'Title',
			'name'          => 'title',
			'type'          => 'textarea',
			'instructions'  => 'Main heading for the banner',
			'required'      => 1,
			'rows'          => 3,
		),
		array(
			'key'           => 'field_block_banner_cta_buttons',
			'label'         => 'Buttons',
			'name'          => 'buttons',
			'type'          => 'repeater',
			'instructions'  => 'Add up to 2 call-to-action buttons',
			'required'      => 0,
			'max'           => 2,
			'layout'        => 'table',
			'sub_fields'    => array(
				array(
					'key'           => 'field_block_banner_cta_button_text',
					'label'         => 'Button Text',
					'name'          => 'text',
					'type'          => 'text',
					'required'      => 1,
				),
				array(
					'key'           => 'field_block_banner_cta_button_url',
					'label'         => 'Button URL',
					'name'          => 'url',
					'type'          => 'url',
					'required'      => 1,
				),
				array(
					'key'           => 'field_block_banner_cta_button_style',
					'label'         => 'Button Style',
					'name'          => 'style',
					'type'          => 'select',
					'choices'       => array(
						'primary'   => 'Primary',
						'secondary' => 'Secondary',
						'outline'   => 'Outline',
					),
					'default_value' => 'primary',
					'required'      => 0,
				),
			),
		),
		array(
			'key'           => 'field_block_banner_cta_media',
			'label'         => 'Background Image or SVG',
			'name'          => 'media',
			'type'          => 'image',
			'return_format' => 'array',
			'instructions'  => 'Upload an image (PNG, JPG, WebP) or SVG file for the banner background',
			'required'      => 0,
			'preview_size'  => 'medium',
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/banner-cta',
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
	'description'      => 'Display a banner with lead, title, CTA buttons, and background image/SVG',
) );
