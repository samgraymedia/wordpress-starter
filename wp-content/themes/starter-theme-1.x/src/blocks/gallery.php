<?php

/**
 * Register Gallery Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_gallery_block',
    'title' => 'Gallery Block Fields',
    'fields' => array(
        array(
            'key' => 'field_gallery_images',
            'label' => 'Images',
            'name' => 'images',
            'type' => 'repeater',
            'instructions' => 'Add images to the gallery',
            'required' => 1,
            'layout' => 'grid',
            'min' => 1,
            'sub_fields' => array(
                array(
                    'key' => 'field_gallery_image',
                    'label' => 'Image',
                    'name' => 'url',
                    'type' => 'image',
                    'instructions' => 'Upload an image for the gallery',
                    'required' => 1,
                    'return_format' => 'url',
                ),
                array(
                    'key' => 'field_gallery_image_alt',
                    'label' => 'Alt Text',
                    'name' => 'alt',
                    'type' => 'text',
                    'instructions' => 'Alternative text for the image (for accessibility)',
                    'required' => 1,
                    'maxlength' => 150,
                ),
                array(
                    'key' => 'field_gallery_image_caption',
                    'label' => 'Caption',
                    'name' => 'caption',
                    'type' => 'text',
                    'instructions' => 'Optional caption for the image',
                    'required' => 0,
                    'maxlength' => 200,
                ),
            ),
        ),
        array(
            'key' => 'field_gallery_columns',
            'label' => 'Columns',
            'name' => 'columns',
            'type' => 'select',
            'instructions' => 'Choose the number of columns for the gallery grid',
            'required' => 0,
            'choices' => array(
                2 => '2 Columns',
                3 => '3 Columns',
                4 => '4 Columns',
            ),
            'default_value' => 3,
            'allow_null' => 0,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/gallery',
            ),
        ),
    ),
));

endif;
