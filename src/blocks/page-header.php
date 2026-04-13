<?php
/**
 * Block: Page Header
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group( array(
	'key'      => 'group_block_page_header',
	'title'    => 'Page Header Block',
	'fields'   => array(
		array(
			'key'             => 'field_page_header_lead',
			'label'           => 'Lead Text',
			'name'            => 'lead',
			'type'            => 'text',
			'instructions'    => 'Subheading or lead text',
			'required'        => 0,
		),
		array(
			'key'             => 'field_page_header_title',
			'label'           => 'Title',
			'name'            => 'title',
			'type'            => 'text',
			'instructions'    => 'Main headline',
			'required'        => 0,
		),
		array(
			'key'             => 'field_page_header_media_type',
			'label'           => 'Media Type',
			'name'            => 'media_type',
			'type'            => 'select',
			'instructions'    => 'Choose between image or video',
			'required'        => 0,
			'choices'         => array(
				'image' => 'Image',
				'video' => 'Video',
			),
			'default_value'   => 'image',
			'allow_null'      => false,
		),
		array(
			'key'             => 'field_page_header_image',
			'label'           => 'Image',
			'name'            => 'image',
			'type'            => 'image',
			'instructions'    => 'Upload an image',
			'required'        => 0,
			'return_format'   => 'id',
			'conditional_logic' => array(
				array(
					array(
						'field'    => 'field_page_header_media_type',
						'operator' => '==',
						'value'    => 'image',
					),
				),
			),
		),
		array(
			'key'             => 'field_page_header_video',
			'label'           => 'Video URL',
			'name'            => 'video',
			'type'            => 'url',
			'instructions'    => 'Embed video URL (YouTube, Vimeo, etc)',
			'required'        => 0,
			'conditional_logic' => array(
				array(
					array(
						'field'    => 'field_page_header_media_type',
						'operator' => '==',
						'value'    => 'video',
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
				'value'    => 'acf/page-header',
			),
		),
	),
) );
