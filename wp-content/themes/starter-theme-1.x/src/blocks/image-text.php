<?php

/**
 * Register Image + Text Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_image_text_block',
    'title' => 'Image + Text Block Fields',
    'fields' => array(
        array(
            'key' => 'field_image_text_image',
            'label' => 'Image',
            'name' => 'image',
            'type' => 'image',
            'instructions' => 'Upload an image to display',
            'required' => 1,
            'return_format' => 'array',
        ),
        array(
            'key' => 'field_image_text_layout',
            'label' => 'Layout',
            'name' => 'layout',
            'type' => 'select',
            'instructions' => 'Choose which side the image appears on',
            'required' => 0,
            'choices' => array(
                'left' => 'Image on Left',
                'right' => 'Image on Right',
            ),
            'default_value' => 'left',
            'allow_null' => 0,
        ),
        array(
            'key' => 'field_image_text_content_items',
            'label' => 'Content Items',
            'name' => 'content_items',
            'type' => 'repeater',
            'instructions' => 'Add titles and subtexts to display alongside the image',
            'required' => 0,
            'layout' => 'table',
            'sub_fields' => array(
                array(
                    'key' => 'field_image_text_item_title',
                    'label' => 'Title',
                    'name' => 'title',
                    'type' => 'text',
                    'instructions' => 'Enter the title for this content item',
                    'required' => 1,
                    'maxlength' => 150,
                ),
                array(
                    'key' => 'field_image_text_item_subtext',
                    'label' => 'Subtext',
                    'name' => 'subtext',
                    'type' => 'textarea',
                    'instructions' => 'Enter descriptive text for this content item',
                    'required' => 0,
                    'rows' => 3,
                ),
            ),
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/image-text',
            ),
        ),
    ),
));

endif;
