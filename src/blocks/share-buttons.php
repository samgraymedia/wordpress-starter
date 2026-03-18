<?php

/**
 * Share Buttons Block
 * ACF Field Configuration
 */

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_share_buttons_block',
    'title' => 'Share Buttons Block Fields',
    'fields' => array(
        array(
            'key' => 'field_share_buttons_title',
            'label' => 'Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Optional title to display above the buttons',
            'required' => 0,
            'default_value' => 'Share this post',
            'placeholder' => 'Share this post',
        ),
        array(
            'key' => 'field_share_buttons_alignment',
            'label' => 'Alignment',
            'name' => 'alignment',
            'type' => 'button_group',
            'instructions' => 'Choose how to align the buttons',
            'required' => 0,
            'default_value' => 'center',
            'choices' => array(
                'left' => 'Left',
                'center' => 'Center',
                'right' => 'Right',
            ),
        ),
        array(
            'key' => 'field_share_buttons_platforms',
            'label' => 'Social Platforms',
            'name' => 'platforms',
            'type' => 'checkbox',
            'instructions' => 'Select which social platforms to display',
            'required' => 0,
            'choices' => array(
                'facebook' => 'Facebook',
                'twitter' => 'Twitter (X)',
                'linkedin' => 'LinkedIn',
                'pinterest' => 'Pinterest',
                'email' => 'Email',
                'whatsapp' => 'WhatsApp',
                'reddit' => 'Reddit',
                'telegram' => 'Telegram',
            ),
            'default_value' => array('facebook', 'twitter', 'linkedin', 'email'),
            'layout' => 'vertical',
            'toggle' => 0,
            'return_format' => 'array',
        ),
        array(
            'key' => 'field_share_buttons_style',
            'label' => 'Button Style',
            'name' => 'style',
            'type' => 'button_group',
            'instructions' => 'Choose the button display style',
            'required' => 0,
            'default_value' => 'icons',
            'choices' => array(
                'icons' => 'Icons Only',
                'labels' => 'Icons + Labels',
            ),
        ),
        array(
            'key' => 'field_share_buttons_size',
            'label' => 'Button Size',
            'name' => 'size',
            'type' => 'button_group',
            'instructions' => 'Choose the button size',
            'required' => 0,
            'default_value' => 'medium',
            'choices' => array(
                'small' => 'Small',
                'medium' => 'Medium',
                'large' => 'Large',
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/share-buttons',
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
    'show_in_rest' => 0,
));

endif;
?>
