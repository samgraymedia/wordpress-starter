<?php

/**
 * Register Announcement Bar Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_announcement_block',
    'title' => 'Announcement Bar Block Fields',
    'fields' => array(
        array(
            'key' => 'field_announcement_message',
            'label' => 'Announcement Message',
            'name' => 'message',
            'type' => 'wysiwyg',
            'instructions' => 'The main announcement message',
            'required' => 1,
            'toolbar' => 'basic',
            'media_upload' => 0,
            'tabs' => 'visual',
        ),
        array(
            'key' => 'field_announcement_button_text',
            'label' => 'Button Text',
            'name' => 'button_text',
            'type' => 'text',
            'instructions' => 'Optional call-to-action button text',
            'required' => 0,
            'placeholder' => 'Learn More',
            'maxlength' => 100,
        ),
        array(
            'key' => 'field_announcement_button_url',
            'label' => 'Button URL',
            'name' => 'button_url',
            'type' => 'url',
            'instructions' => 'Link for the button',
            'required' => 0,
            'conditional_logic' => array(
                array(
                    array(
                        'field' => 'field_announcement_button_text',
                        'operator' => '!=',
                        'value' => '',
                    ),
                ),
            ),
        ),
        array(
            'key' => 'field_announcement_icon',
            'label' => 'Icon',
            'name' => 'icon',
            'type' => 'select',
            'instructions' => 'Optional icon to display before the message',
            'required' => 0,
            'choices' => array(
                '' => 'None',
                'alert' => 'Alert',
                'info' => 'Info',
                'check' => 'Checkmark',
                'star' => 'Star',
            ),
            'default_value' => '',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/announcement',
            ),
        ),
    ),
));

endif;
