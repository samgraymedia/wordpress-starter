<?php

/**
 * Register Contact Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_contact_block',
    'title' => 'Contact Block Fields',
    'fields' => array(
        array(
            'key' => 'field_contact_block_title',
            'label' => 'Section Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Optional title for the contact section',
            'required' => 0,
            'placeholder' => 'Contact Us',
            'maxlength' => 200,
        ),
        array(
            'key' => 'field_contact_block_form_id',
            'label' => 'Contact Form 7 Form ID',
            'name' => 'form_id',
            'type' => 'text',
            'instructions' => 'Enter the Contact Form 7 form ID to display a contact form. Contact information is pulled automatically from Theme Options.',
            'required' => 0,
            'placeholder' => '5b3e36a',
            'maxlength' => 100,
        ),
        array(
            'key' => 'field_contact_block_note',
            'label' => 'Contact Information Source',
            'name' => 'note',
            'type' => 'message',
            'message' => 'Contact information (email, phone, address) and social media links are automatically pulled from <strong>Appearance → Theme Options</strong>. Update those settings to change the displayed information.',
            'new_lines' => 'wpautop',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/contact',
            ),
        ),
    ),
));

endif;
