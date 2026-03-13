<?php

/**
 * Register Hero Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_hero_block',
    'title' => 'Hero Block Fields',
    'fields' => array(
        array(
            'key' => 'field_hero_title',
            'label' => 'Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Enter the hero block title',
            'required' => 1,
            'maxlength' => 200,
        ),
        array(
            'key' => 'field_hero_subtitle',
            'label' => 'Subtitle',
            'name' => 'subtitle',
            'type' => 'textarea',
            'instructions' => 'Enter the hero block subtitle',
            'required' => 0,
            'rows' => 3,
        ),
        array(
            'key' => 'field_hero_background_image',
            'label' => 'Background Image',
            'name' => 'background_image',
            'type' => 'image',
            'instructions' => 'Upload a background image for the hero section',
            'required' => 0,
            'return_format' => 'array',
        ),
        array(
            'key' => 'field_hero_buttons',
            'label' => 'Buttons',
            'name' => 'buttons',
            'type' => 'repeater',
            'instructions' => 'Add up to 2 action buttons (Maximum 2)',
            'required' => 0,
            'layout' => 'table',
            'max' => 2,
            'sub_fields' => array(
                array(
                    'key' => 'field_hero_button_label',
                    'label' => 'Button Label',
                    'name' => 'button_label',
                    'type' => 'text',
                    'instructions' => 'Text to display on the button',
                    'required' => 1,
                    'maxlength' => 100,
                ),
                array(
                    'key' => 'field_hero_button_url',
                    'label' => 'Button URL',
                    'name' => 'button_url',
                    'type' => 'url',
                    'instructions' => 'Link destination for the button',
                    'required' => 1,
                ),
                array(
                    'key' => 'field_hero_button_type',
                    'label' => 'Button Style',
                    'name' => 'button_type',
                    'type' => 'select',
                    'instructions' => 'Choose button style: primary for main action, secondary for alternative action',
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
                'value' => 'acf/hero',
            ),
        ),
    ),
));

endif;
