<?php

/**
 * Register Banner / Promo Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_banner_block',
    'title' => 'Banner / Promo Block Fields',
    'fields' => array(
        array(
            'key' => 'field_banner_title',
            'label' => 'Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Enter the banner title',
            'required' => 1,
            'maxlength' => 150,
        ),
        array(
            'key' => 'field_banner_subtitle',
            'label' => 'Subtitle',
            'name' => 'subtitle',
            'type' => 'textarea',
            'instructions' => 'Enter promotional text or description',
            'required' => 0,
            'rows' => 3,
        ),
        array(
            'key' => 'field_banner_background_image',
            'label' => 'Background Image',
            'name' => 'background_image',
            'type' => 'image',
            'instructions' => 'Upload a background image for the banner',
            'required' => 0,
            'return_format' => 'array',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/banner',
            ),
        ),
    ),
));

endif;
