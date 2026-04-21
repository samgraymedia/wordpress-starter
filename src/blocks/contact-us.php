<?php

/**
 * Register Contact Us Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_contact_us_block',
    'title' => 'Contact Us Block Fields',
    'fields' => array(
         array(
            'key' => 'field_contact_us_show_map',
            'label' => 'Show Map',
            'name' => 'show_map',
            'type' => 'true_false',
            'instructions' => 'Display a map on this block',
            'required' => 0,
            'ui' => 1,
            'ui_on_text' => 'Yes',
            'ui_off_text' => 'No',
        ),
        array(
            'key' => 'field_contact_us_title',
            'label' => 'Main Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Enter the main title for the contact block',
            'required' => 0,
        ),
        array(
            'key' => 'field_contact_us_right_title',
            'label' => 'Right Title',
            'name' => 'right_title',
            'type' => 'text',
            'instructions' => 'Enter the right-side title or subtitle',
            'required' => 0,
        ),
        array(
            'key' => 'field_contact_us_form',
            'label' => 'Contact Form 7',
            'name' => 'contact_form',
            'type' => 'post_object',
            'instructions' => 'Select a Contact Form 7 form to display',
            'required' => 0,
            'post_type' => array( 'wpcf7_contact_form' ),
            'allow_null' => 1,
            'return_format' => 'id',
        ),
       
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/contact-us',
            ),
        ),
    ),
));

endif;
