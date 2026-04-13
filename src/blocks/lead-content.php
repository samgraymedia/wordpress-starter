<?php
/**
 * Block: Lead + Content
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group( array(
	'key'      => 'group_block_lead_content',
	'title'    => 'Lead + Content Block',
	'fields'   => array(
		array(
			'key'             => 'field_lead_content_lead',
			'label'           => 'Lead',
			'name'            => 'lead',
			'type'            => 'text',
			'instructions'    => 'Subheading or lead text',
			'required'        => 0,
		),
		array(
			'key'             => 'field_lead_content_text',
			'label'           => 'Content',
			'name'            => 'content',
			'type'            => 'textarea',
			'instructions'    => 'Main content text',
			'required'        => 0,
			'rows'            => 6,
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/lead-content',
			),
		),
	),
) );
