<?php
/**
 * Section Intro Block Fields
 * 
 * Registers ACF fields for the intro section block
 * Used for page section headers with eyebrow, heading, description, and CTA
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group( array(
	'key'      => 'group_intro_block',
	'title'    => 'Section Intro Block',
	'fields'   => array(
		array(
			'key'           => 'field_intro_eyebrow',
			'label'         => 'Eyebrow Text',
			'name'          => 'eyebrow',
			'type'          => 'text',
			'placeholder'   => 'e.g., "Getting Started" or "Features"',
			'maxlength'     => 100,
			'instructions'  => 'Small text above the heading (optional)',
		),
		array(
			'key'           => 'field_intro_heading',
			'label'         => 'Heading',
			'name'          => 'heading',
			'type'          => 'text',
			'placeholder'   => 'Enter section heading',
			'maxlength'     => 200,
			'required'      => 1,
			'instructions'  => 'Main section headline',
		),
		array(
			'key'           => 'field_intro_description',
			'label'         => 'Description',
			'name'          => 'description',
			'type'          => 'textarea',
			'placeholder'   => 'Enter optional section description',
			'rows'          => 3,
			'maxlength'     => 500,
			'instructions'  => 'Short intro text (optional, up to 500 characters)',
		),
		array(
			'key'           => 'field_intro_alignment',
			'label'         => 'Text Alignment',
			'name'          => 'alignment',
			'type'          => 'button_group',
			'choices'       => array(
				'left'   => 'Left',
				'center' => 'Center',
				'right'  => 'Right',
			),
			'default_value' => 'left',
			'instructions'  => 'Choose alignment for text and button',
		),
		array(
			'key'           => 'field_intro_max_width',
			'label'         => 'Max Width',
			'name'          => 'max_width',
			'type'          => 'select',
			'choices'       => array(
				'full'  => 'Full Width',
				'800'   => 'Narrow (800px)',
				'1000'  => 'Medium (1000px)',
				'1200'  => 'Wide (1200px)',
			),
			'default_value' => 'full',
			'instructions'  => 'Control how wide the intro content spans',
		),
		array(
			'key'           => 'field_intro_cta_button',
			'label'         => 'Call-to-Action Button',
			'name'          => 'cta_button',
			'type'          => 'link',
			'return_format' => 'array',
			'instructions'  => 'Optional button link with text',
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/intro',
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
	'description'           => 'Reusable intro section block for page headers with eyebrow, heading, description, and optional CTA',
) );
