<?php

/**
 * Register Call To Action Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_cta_block',
    'title' => 'Call To Action Block Fields',
    'fields' => array(
        array(
            'key' => 'field_cta_title',
            'label' => 'Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Enter the CTA title',
            'required' => 1,
            'maxlength' => 200,
        ),
        array(
            'key' => 'field_cta_subtitle',
            'label' => 'Subtitle',
            'name' => 'subtitle',
            'type' => 'textarea',
            'instructions' => 'Enter the CTA subtitle or description',
            'required' => 0,
            'rows' => 3,
        ),
        array(
            'key' => 'field_cta_buttons',
            'label' => 'Buttons',
            'name' => 'buttons',
            'type' => 'repeater',
            'instructions' => 'Add up to 2 action buttons (Maximum 2)',
            'required' => 1,
            'layout' => 'table',
            'max' => 2,
            'sub_fields' => array(
                array(
                    'key' => 'field_cta_button_label',
                    'label' => 'Button Label',
                    'name' => 'button_label',
                    'type' => 'text',
                    'instructions' => 'Text to display on the button',
                    'required' => 1,
                    'maxlength' => 100,
                ),
                array(
                    'key' => 'field_cta_button_url',
                    'label' => 'Button URL',
                    'name' => 'button_url',
                    'type' => 'url',
                    'instructions' => 'Link destination for the button',
                    'required' => 1,
                ),
                array(
                    'key' => 'field_cta_button_type',
                    'label' => 'Button Style',
                    'name' => 'button_type',
                    'type' => 'select',
                    'instructions' => 'Choose button style: primary for main action, secondary for alternative',
                    'required' => 0,
                    'choices' => array(
                        'primary' => 'Primary (Main Action)',
                        'secondary' => 'Secondary (Alternative Action)',
                    ),
                    'default_value' => 'primary',
                    'allow_null' => 0,
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
