<?php

/**
 * Register Accordion Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_accordion_block',
    'title' => 'Accordion Block Fields',
    'fields' => array(
        array(
            'key' => 'field_accordion_title',
            'label' => 'Section Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Optional title for the accordion section',
            'required' => 0,
            'placeholder' => 'Frequently Asked Questions',
            'maxlength' => 200,
        ),
        array(
            'key' => 'field_accordion_items',
            'label' => 'Accordion Items',
            'name' => 'items',
            'type' => 'repeater',
            'instructions' => 'Add accordion items with title and content',
            'required' => 1,
            'min' => 1,
            'button_label' => 'Add Item',
            'sub_fields' => array(
                array(
                    'key' => 'field_accordion_item_title',
                    'label' => 'Title',
                    'name' => 'title',
                    'type' => 'text',
                    'required' => 1,
                    'placeholder' => 'Question or heading',
                    'maxlength' => 200,
                ),
                array(
                    'key' => 'field_accordion_item_content',
                    'label' => 'Content',
                    'name' => 'content',
                    'type' => 'wysiwyg',
                    'required' => 1,
                    'toolbar' => 'basic',
                    'media_upload' => 0,
                    'tabs' => 'visual',
                ),
            ),
            'collapsed' => 'field_accordion_item_title',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/accordion',
            ),
        ),
    ),
));

endif;
