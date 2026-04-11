<?php

/**
 * Register CTA Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_cta_block',
    'title' => 'CTA Block Fields',
    'fields' => array(
        // Title
        array(
            'key' => 'field_cta_title',
            'label' => 'Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Main title for the CTA',
            'required' => 1,
        ),
        // Description
        array(
            'key' => 'field_cta_description',
            'label' => 'Description',
            'name' => 'description',
            'type' => 'textarea',
            'instructions' => 'Description text for the CTA',
            'required' => 0,
            'rows' => 3,
        ),
        // Buttons Repeater
        array(
            'key' => 'field_cta_buttons_repeater',
            'label' => 'Buttons',
            'name' => 'buttons',
            'type' => 'repeater',
            'instructions' => 'Add buttons for the CTA',
            'required' => 0,
            'max' => 2,
            'min' => 1,
            'layout' => 'block',
            'button_label' => 'Add Button',
            'sub_fields' => array(
                array(
                    'key' => 'field_cta_button_label',
                    'label' => 'Button Label',
                    'name' => 'button_label',
                    'type' => 'text',
                    'required' => 1,
                ),
                array(
                    'key' => 'field_cta_button_url',
                    'label' => 'Button URL',
                    'name' => 'button_url',
                    'type' => 'url',
                    'required' => 1,
                ),
                array(
                    'key' => 'field_cta_button_style',
                    'label' => 'Button Style',
                    'name' => 'button_style',
                    'type' => 'select',
                    'choices' => array(
                        'primary' => 'Primary',
                        'secondary' => 'Secondary',
                    ),
                    'required' => 1,
                    'default_value' => 'primary',
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/cta',
            ),
        ),
    ),
));

endif;
