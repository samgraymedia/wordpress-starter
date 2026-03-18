<?php

/**
 * Register Stats / Metrics Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_stats_block',
    'title' => 'Stats / Metrics Block Fields',
    'fields' => array(
        array(
            'key' => 'field_stats_title',
            'label' => 'Section Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Enter the section title (e.g., "Our Impact")',
            'required' => 0,
            'maxlength' => 200,
        ),
        array(
            'key' => 'field_stats_description',
            'label' => 'Section Description',
            'name' => 'description',
            'type' => 'textarea',
            'instructions' => 'Optional intro text for the stats section',
            'required' => 0,
            'rows' => 3,
        ),
        array(
            'key' => 'field_stats_items',
            'label' => 'Statistics',
            'name' => 'stats',
            'type' => 'repeater',
            'instructions' => 'Add individual statistics or metrics',
            'required' => 1,
            'layout' => 'table',
            'min' => 1,
            'max' => 12,
            'sub_fields' => array(
                array(
                    'key' => 'field_stats_number',
                    'label' => 'Number / Value',
                    'name' => 'number',
                    'type' => 'text',
                    'instructions' => 'Main statistical value (e.g., 10000, 99.9%, $2.5M)',
                    'required' => 1,
                    'maxlength' => 50,
                ),
                array(
                    'key' => 'field_stats_label',
                    'label' => 'Label / Title',
                    'name' => 'label',
                    'type' => 'text',
                    'instructions' => 'What this stat represents (e.g., "Customers Served")',
                    'required' => 1,
                    'maxlength' => 150,
                ),
                array(
                    'key' => 'field_stats_description',
                    'label' => 'Description',
                    'name' => 'description',
                    'type' => 'textarea',
                    'instructions' => 'Optional additional detail or context',
                    'required' => 0,
                    'rows' => 2,
                ),
                array(
                    'key' => 'field_stats_icon',
                    'label' => 'Icon (Font Awesome)',
                    'name' => 'icon',
                    'type' => 'text',
                    'instructions' => 'Font Awesome class (e.g., fa-users, fa-star, fa-rocket). Leave blank for no icon.',
                    'required' => 0,
                    'placeholder' => 'fa-users',
                ),
                array(
                    'key' => 'field_stats_color',
                    'label' => 'Icon Color',
                    'name' => 'color',
                    'type' => 'select',
                    'instructions' => 'Color scheme for this stat',
                    'required' => 0,
                    'choices' => array(
                        'primary' => 'Primary (Blue)',
                        'secondary' => 'Secondary (Gray)',
                        'success' => 'Success (Green)',
                        'warning' => 'Warning (Yellow)',
                        'info' => 'Info (Cyan)',
                        'danger' => 'Danger (Red)',
                    ),
                    'allow_null' => 1,
                    'default_value' => 'primary',
                ),
            ),
        ),
        array(
            'key' => 'field_stats_columns',
            'label' => 'Desktop Columns',
            'name' => 'columns',
            'type' => 'select',
            'instructions' => 'Number of columns on desktop (responsive on smaller screens)',
            'required' => 0,
            'choices' => array(
                '2' => '2 Columns',
                '3' => '3 Columns',
                '4' => '4 Columns',
            ),
            'allow_null' => 0,
            'default_value' => '4',
        ),
        array(
            'key' => 'field_stats_layout',
            'label' => 'Layout Style',
            'name' => 'layout',
            'type' => 'select',
            'instructions' => 'Choose how stats are displayed',
            'required' => 0,
            'choices' => array(
                'card' => 'Cards (With Background)',
                'minimal' => 'Minimal (No Background)',
            ),
            'allow_null' => 0,
            'default_value' => 'card',
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
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
));

endif;
