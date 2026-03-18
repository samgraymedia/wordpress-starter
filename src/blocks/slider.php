<?php

/**
 * Register Slider / Carousel Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_slider_block',
    'title' => 'Slider / Carousel Block Fields',
    'fields' => array(
        array(
            'key' => 'field_slider_slides',
            'label' => 'Slides',
            'name' => 'slides',
            'type' => 'repeater',
            'instructions' => 'Add slides to the carousel',
            'required' => 1,
            'layout' => 'table',
            'min' => 1,
            'sub_fields' => array(
                array(
                    'key' => 'field_slider_slide_image',
                    'label' => 'Background Image',
                    'name' => 'image',
                    'type' => 'image',
                    'instructions' => 'Upload a background image for this slide',
                    'required' => 1,
                    'return_format' => 'array',
                ),
                array(
                    'key' => 'field_slider_slide_title',
                    'label' => 'Title',
                    'name' => 'title',
                    'type' => 'text',
                    'instructions' => 'Slide title (optional)',
                    'required' => 0,
                    'maxlength' => 150,
                ),
                array(
                    'key' => 'field_slider_slide_description',
                    'label' => 'Description',
                    'name' => 'description',
                    'type' => 'textarea',
                    'instructions' => 'Slide description or subtitle (optional)',
                    'required' => 0,
                    'rows' => 3,
                ),
                array(
                    'key' => 'field_slider_slide_button_url',
                    'label' => 'Button URL',
                    'name' => 'button_url',
                    'type' => 'url',
                    'instructions' => 'Link for the CTA button (optional)',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_slider_slide_button_text',
                    'label' => 'Button Text',
                    'name' => 'button_text',
                    'type' => 'text',
                    'instructions' => 'Button label (defaults to "Learn More")',
                    'required' => 0,
                    'maxlength' => 50,
                ),
                array(
                    'key' => 'field_slider_slide_button_style',
                    'label' => 'Button Style',
                    'name' => 'button_style',
                    'type' => 'select',
                    'instructions' => 'Choose button style',
                    'required' => 0,
                    'choices' => array(
                        'primary' => 'Primary',
                        'secondary' => 'Secondary',
                    ),
                    'default_value' => 'primary',
                    'allow_null' => 0,
                ),
                array(
                    'key' => 'field_slider_slide_overlay',
                    'label' => 'Dark Overlay',
                    'name' => 'overlay',
                    'type' => 'true_false',
                    'instructions' => 'Add a dark overlay over the image',
                    'required' => 0,
                    'default_value' => 1,
                ),
            ),
        ),
        array(
            'key' => 'field_slider_autoplay',
            'label' => 'Autoplay',
            'name' => 'autoplay',
            'type' => 'true_false',
            'instructions' => 'Automatically advance slides',
            'required' => 0,
            'default_value' => 1,
        ),
        array(
            'key' => 'field_slider_interval',
            'label' => 'Autoplay Interval (ms)',
            'name' => 'interval',
            'type' => 'number',
            'instructions' => 'Time in milliseconds between slides (default: 5000)',
            'required' => 0,
            'default_value' => 5000,
            'min' => 1000,
            'step' => 500,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/slider',
            ),
        ),
    ),
));

endif;
