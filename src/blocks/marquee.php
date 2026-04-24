<?php

/**
 * Register Marquee Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_marquee_block',
    'title' => 'Marquee Block Fields',
    'fields' => array(
        array(
            'key' => 'field_marquee_images',
            'label' => 'Images',
            'name' => 'images',
            'type' => 'repeater',
            'instructions' => 'Add images to display in the marquee',
            'required' => 1,
            'min' => 1,
            'max' => 0,
            'layout' => 'block',
            'button_label' => 'Add Image',
            'sub_fields' => array(
                array(
                    'key' => 'field_marquee_image',
                    'label' => 'Image',
                    'name' => 'image',
                    'type' => 'image',
                    'required' => 1,
                    'return_format' => 'array',
                    'preview_size' => 'thumbnail',
                    'library' => 'all',
                ),
                array(
                    'key' => 'field_marquee_image_alt',
                    'label' => 'Alt Text',
                    'name' => 'alt',
                    'type' => 'text',
                    'instructions' => 'Helpful for accessibility and SEO',
                    'required' => 0,
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/marquee',
            ),
        ),
    ),
));

endif;
