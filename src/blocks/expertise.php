<?php
/**
 * Register Expertise Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_expertise_block',
    'title' => 'Expertise Block Fields',
    'fields' => array(
        array(
            'key' => 'field_expertise_block_title',
            'label' => 'Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Block title',
            'required' => 0,
        ),
        array(
            'key' => 'field_expertise_block_posts',
            'label' => 'Expertise',
            'name' => 'expertise',
            'type' => 'relationship',
            'instructions' => 'Select expertise areas to display',
            'required' => 0,
            'post_type' => array( 'expertise' ),
            'taxonomy' => array(),
            'filters' => array( 'search', 'post_type', 'taxonomy' ),
            'elements' => '',
            'min' => 0,
            'max' => 0,
            'return_format' => 'object',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/expertise',
            ),
        ),
    ),
));

endif;
