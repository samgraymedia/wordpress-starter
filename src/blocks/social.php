<?php

/**
 * Register Social Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_social_block',
	'title' => 'Social Block Fields',
	'fields' => array(
		array(
			'key' => 'field_social_icon',
			'label' => 'Icon',
			'name' => 'icon',
			'type' => 'image',
			'return_format' => 'array',
			'preview_size' => 'thumbnail',
		),
		array(
			'key' => 'field_social_title',
			'label' => 'Title',
			'name' => 'title',
			'type' => 'text',
		),
		array(
			'key' => 'field_social_images',
			'label' => 'Images',
			'name' => 'images',
			'type' => 'repeater',
			'layout' => 'block',
			'button_label' => 'Add Image',
			'sub_fields' => array(
				array(
					'key' => 'field_social_image_item',
					'label' => 'Image',
					'name' => 'image',
					'type' => 'image',
					'return_format' => 'array',
					'preview_size' => 'thumbnail',
				),
			),
		),
		array(
			'key' => 'field_social_lead',
			'label' => 'Lead',
			'name' => 'lead',
			'type' => 'textarea',
			'rows' => 3,
		),
		array(
			'key' => 'field_social_links',
			'label' => 'Social Links',
			'name' => 'social_links',
			'type' => 'repeater',
			'layout' => 'block',
			'button_label' => 'Add Link',
			'sub_fields' => array(
				array(
					'key' => 'field_social_link_name',
					'label' => 'Name',
					'name' => 'name',
					'type' => 'text',
				),
				array(
					'key' => 'field_social_link_url',
					'label' => 'URL',
					'name' => 'url',
					'type' => 'url',
				),
			),
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'block',
				'operator' => '==',
				'value' => 'acf/social',
			),
		),
	),
));

endif;
