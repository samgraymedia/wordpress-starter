<?php
/**
 * Block: Projects
 * BlockName: Projects Grid
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group( array(
	'key'      => 'group_block_projects',
	'title'    => 'Projects Block',
	'fields'   => array(
             array(
            'key'             => 'field_block_projects_lead',
            'label'           => 'Block Lead',
            'name'            => 'lead',
            'type'            => 'text',
            'instructions'    => 'Optional lead for the projects block',
            'required'        => 0,
        ),
        array(
            'key'             => 'field_block_projects_title',
            'label'           => 'Block Title',
            'name'            => 'title',
            'type'            => 'text',
            'instructions'    => 'Optional title for the projects block',
            'required'        => 0,
        ),
		array(
			'key'           => 'field_block_projects_select',
			'label'         => 'Select Projects',
			'name'          => 'projects',
			'type'          => 'relationship',
			'instructions'  => 'Select projects to display',
			'required'      => 0,
			'return_format' => 'id',
			'post_type'     => 'project',
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/projects',
			),
		),
	),
) );
