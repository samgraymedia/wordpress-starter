<?php
/**
 * Form Embed Block
 * 
 * Allows editors to embed Contact Form 7 forms with custom messaging
 */

if ( ! function_exists( 'acf_register_block_type' ) ) {
	return;
}

acf_register_block_type( array(
	'name'            => 'form-embed',
	'title'           => 'Form Embed',
	'description'     => 'Embed Contact Form 7 with title, intro text, and success message',
	'render_template' => 'views/blocks/form-embed.twig',
	'category'        => 'widgets',
	'icon'            => 'email',
	'keywords'        => array( 'form', 'contact', 'embed', 'cf7' ),
	'supports'        => array(
		'align'  => false,
		'anchor' => true,
	),
) );

if ( function_exists( 'acf_add_local_field_group' ) ) {
	acf_add_local_field_group( array(
		'key'                   => 'group_form_embed',
		'title'                 => 'Form Embed',
		'fields'                => array(
			array(
				'key'               => 'field_form_embed_title',
				'label'             => 'Title',
				'name'              => 'title',
				'type'              => 'text',
				'required'          => 0,
				'placeholder'       => 'e.g., Get In Touch',
				'maxlength'         => 100,
				'wrapper'           => array( 'width' => '50' ),
			),
			array(
				'key'               => 'field_form_embed_intro',
				'label'             => 'Intro Text',
				'name'              => 'intro_text',
				'type'              => 'textarea',
				'required'          => 0,
				'placeholder'       => 'Optional description or instructions',
				'rows'              => 3,
				'new_lines'         => 'wpautop',
				'wrapper'           => array( 'width' => '50' ),
			),
			array(
				'key'               => 'field_form_embed_shortcode',
				'label'             => 'Contact Form 7 Shortcode',
				'name'              => 'form_shortcode',
				'type'              => 'textarea',
				'required'          => 1,
				'placeholder'       => '[contact-form-7 id="123" title="Contact form 1"]',
				'rows'              => 2,
				'instructions'      => 'Paste the Contact Form 7 shortcode. Copy from your form in the CF7 plugin.',
				'wrapper'           => array( 'width' => '100' ),
			),
			array(
				'key'               => 'field_form_embed_success',
				'label'             => 'Success Message',
				'name'              => 'success_message',
				'type'              => 'textarea',
				'required'          => 0,
				'placeholder'       => 'Thank you for reaching out. We will get back to you soon.',
				'rows'              => 3,
				'new_lines'         => 'wpautop',
				'instructions'      => 'Optional custom success message (displays in addition to CF7 message)',
				'wrapper'           => array( 'width' => '100' ),
			),
			array(
				'key'               => 'field_form_embed_layout',
				'label'             => 'Layout Style',
				'name'              => 'layout',
				'type'              => 'button_group',
				'choices'           => array(
					'default'  => 'Default',
					'minimal'  => 'Minimal',
					'boxed'    => 'Boxed',
				),
				'default_value'     => 'default',
				'wrapper'           => array( 'width' => '50' ),
			),
			array(
				'key'               => 'field_form_embed_alignment',
				'label'             => 'Alignment',
				'name'              => 'alignment',
				'type'              => 'button_group',
				'choices'           => array(
					'left'   => 'Left',
					'center' => 'Center',
					'right'  => 'Right',
				),
				'default_value'     => 'left',
				'wrapper'           => array( 'width' => '50' ),
			),
			array(
				'key'               => 'field_form_embed_width',
				'label'             => 'Form Width',
				'name'              => 'max_width',
				'type'              => 'select',
				'choices'           => array(
					'full'    => 'Full Width',
					'lg'      => 'Large (90%)',
					'md'      => 'Medium (75%)',
					'sm'      => 'Small (50%)',
				),
				'default_value'     => 'full',
				'wrapper'           => array( 'width' => '100' ),
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'block',
					'operator' => '==',
					'value'    => 'acf/form-embed',
				),
			),
		),
	) );
}
