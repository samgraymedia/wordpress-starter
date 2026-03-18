<?php

/**
 * Register Newsletter Signup Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_newsletter_block',
    'title' => 'Newsletter Signup Block Fields',
    'fields' => array(
        array(
            'key' => 'field_newsletter_title',
            'label' => 'Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Main headline for the newsletter signup section',
            'required' => 0,
            'placeholder' => 'Subscribe to Our Newsletter',
            'maxlength' => 200,
        ),
        array(
            'key' => 'field_newsletter_description',
            'label' => 'Description',
            'name' => 'description',
            'type' => 'textarea',
            'instructions' => 'Subtitle or description text encouraging signup',
            'required' => 0,
            'placeholder' => 'Get the latest updates delivered to your inbox',
            'rows' => 3,
        ),
        array(
            'key' => 'field_newsletter_form_id',
            'label' => 'Contact Form 7 Form ID',
            'name' => 'form_id',
            'type' => 'text',
            'instructions' => 'Enter the Contact Form 7 form ID (e.g., 5b3e36a). Find this in the Contact Form 7 settings page.',
            'required' => 1,
            'placeholder' => '5b3e36a',
            'maxlength' => 100,
        ),
        array(
            'key' => 'field_newsletter_background',
            'label' => 'Background Color',
            'name' => 'background_color',
            'type' => 'color_picker',
            'instructions' => 'Optional background color for the newsletter section',
            'required' => 0,
            'default_value' => '#f8f9fa',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/newsletter',
            ),
        ),
    ),
));

endif;
