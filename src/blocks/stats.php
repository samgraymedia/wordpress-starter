<?php
/**
 * Register Stats Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_stats_block',
    'title' => 'Stats Block Fields',
    'fields' => array(
        array(
            'key' => 'field_stats_title',
            'label' => 'Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Block title',
            'required' => 0,
        ),
        array(
            'key' => 'field_stats_items',
            'label' => 'Stats',
            'name' => 'stats',
            'type' => 'repeater',
            'instructions' => 'Add statistics',
            'required' => 0,
            'sub_fields' => array(
                array(
                    'key' => 'field_stats_item_number',
                    'label' => 'Number',
                    'name' => 'number',
                    'type' => 'text',
                    'instructions' => 'Stat number (e.g., 100, 2.5M, 98%)',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_stats_item_title',
                    'label' => 'Title',
                    'name' => 'title',
                    'type' => 'text',
                    'instructions' => 'Stat title',
                    'required' => 0,
                ),
            ),
            'min' => 0,
            'max' => 0,
            'layout' => 'table',
            'button_label' => 'Add Stat',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/stats',
            ),
        ),
    ),
));

endif;
