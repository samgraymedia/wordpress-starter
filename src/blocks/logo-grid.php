<?php

/**
 * Register Logo Grid Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_logo_grid_block',
    'title' => 'Logo Grid Block Fields',
    'fields' => array(
        array(
            'key' => 'field_logo_grid_title',
            'label' => 'Section Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Enter the section title (optional)',
            'required' => 0,
            'maxlength' => 200,
        ),
        array(
            'key' => 'field_logo_grid_description',
            'label' => 'Description',
            'name' => 'description',
            'type' => 'textarea',
            'instructions' => 'Enter a description or subtitle',
            'required' => 0,
            'rows' => 3,
        ),
        array(
            'key' => 'field_logo_grid_logos',
            'label' => 'Logos',
            'name' => 'logos',
            'type' => 'repeater',
            'instructions' => 'Add logos for partners, clients, or brands',
            'required' => 1,
            'layout' => 'grid',
            'min' => 1,
            'sub_fields' => array(
                array(
                    'key' => 'field_logo_grid_image',
                    'label' => 'Logo Image',
                    'name' => 'image',
                    'type' => 'image',
                    'instructions' => 'Upload a logo image (SVG or PNG recommended)',
                    'required' => 1,
                    'return_format' => 'url',
                ),
                array(
                    'key' => 'field_logo_grid_alt_text',
                    'label' => 'Alt Text',
                    'name' => 'alt_text',
                    'type' => 'text',
                    'instructions' => 'Alternative text for the logo',
                    'required' => 1,
                    'maxlength' => 150,
                ),
                array(
                    'key' => 'field_logo_grid_link',
                    'label' => 'Link (Optional)',
                    'name' => 'link',
                    'type' => 'url',
                    'instructions' => 'If provided, the logo will be a clickable link',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_logo_grid_link_target',
                    'label' => 'Open Link in New Tab',
                    'name' => 'link_target',
                    'type' => 'true_false',
                    'instructions' => 'If checked, link will open in a new tab',
                    'required' => 0,
                    'ui' => 1,
                ),
            ),
        ),
        array(
            'key' => 'field_logo_grid_columns',
            'label' => 'Columns',
            'name' => 'columns',
            'type' => 'select',
            'instructions' => 'Choose the number of columns for the grid',
            'required' => 0,
            'choices' => array(
                2 => '2 Columns',
                3 => '3 Columns',
                4 => '4 Columns',
                5 => '5 Columns',
                6 => '6 Columns',
            ),
            'default_value' => 4,
            'allow_null' => 0,
        ),
        array(
            'key' => 'field_logo_grid_align',
            'label' => 'Logo Alignment',
            'name' => 'align',
            'type' => 'select',
            'instructions' => 'How to align logos within their containers',
            'required' => 0,
            'choices' => array(
                'center' => 'Center',
                'left' => 'Left',
                'right' => 'Right',
            ),
            'default_value' => 'center',
            'allow_null' => 0,
        ),
        array(
            'key' => 'field_logo_grid_greyscale',
            'label' => 'Greyscale Style',
            'name' => 'greyscale',
            'type' => 'true_false',
            'instructions' => 'If checked, logos will display in greyscale with color on hover',
            'required' => 0,
            'ui' => 1,
        ),
        array(
            'key' => 'field_logo_grid_spacing',
            'label' => 'Logo Spacing',
            'name' => 'spacing',
            'type' => 'select',
            'instructions' => 'Control the spacing between logos',
            'required' => 0,
            'choices' => array(
                'tight' => 'Tight (Small gaps)',
                'normal' => 'Normal (Medium gaps)',
                'loose' => 'Loose (Large gaps)',
            ),
            'default_value' => 'normal',
            'allow_null' => 0,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/logo-grid',
            ),
        ),
    ),
));

endif;
