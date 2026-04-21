<?php

/**
 * Register Features Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_features_block',
    'title' => 'Features Block Fields',
    'fields' => array(
        array(
            'key' => 'field_features_title',
            'label' => 'Main Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Enter the main title for the features block',
            'required' => 0,
        ),
        array(
            'key' => 'field_features_image',
            'label' => 'Image',
            'name' => 'image',
            'type' => 'image',
            'required' => 0,
            'return_format' => 'array',
        ),
        array(
            'key' => 'field_features_description',
            'label' => 'Description',
            'name' => 'description',
            'type' => 'wysiwyg',
            'required' => 0,
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
        ),
        array(
            'key' => 'field_features_items',
            'label' => 'Feature Items',
            'name' => 'items',
            'type' => 'repeater',
            'instructions' => 'Add feature items',
            'required' => 0,
            'layout' => 'table',
            'button_label' => 'Add Feature Item',
            'sub_fields' => array(
                array(
                    'key' => 'field_features_item_title',
                    'label' => 'Title',
                    'name' => 'item_title',
                    'type' => 'text',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_features_item_text',
                    'label' => 'Text',
                    'name' => 'item_text',
                    'type' => 'textarea',
                    'required' => 0,
                ),
            ),
        ),
        array(
            'key' => 'field_features_buttons',
            'label' => 'Buttons',
            'name' => 'buttons',
            'type' => 'repeater',
            'instructions' => 'Add buttons',
            'required' => 0,
            'layout' => 'table',
            'button_label' => 'Add Button',
            'sub_fields' => array(
                array(
                    'key' => 'field_features_button_text',
                    'label' => 'Button Text',
                    'name' => 'button_text',
                    'type' => 'text',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_features_button_url',
                    'label' => 'Button URL',
                    'name' => 'button_url',
                    'type' => 'url',
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
                'value' => 'acf/features',
            ),
        ),
    ),
));

endif;
