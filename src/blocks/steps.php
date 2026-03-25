<?php

/**
 * Register Steps / Process Timeline Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_steps_block',
    'title' => 'Steps / Process Timeline Block Fields',
    'fields' => array(
        array(
            'key' => 'field_steps_title',
            'label' => 'Section Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Optional title for the steps section',
            'required' => 0,
            'placeholder' => 'How It Works',
            'maxlength' => 200,
        ),
        array(
            'key' => 'field_steps_description',
            'label' => 'Section Description',
            'name' => 'description',
            'type' => 'textarea',
            'instructions' => 'Optional introductory text',
            'required' => 0,
            'placeholder' => 'Describe the process...',
            'rows' => 3,
        ),
        array(
            'key' => 'field_steps_layout',
            'label' => 'Layout Direction',
            'name' => 'layout',
            'type' => 'button_group',
            'instructions' => 'Choose vertical or horizontal layout',
            'required' => 1,
            'choices' => array(
                'vertical' => 'Vertical',
                'horizontal' => 'Horizontal',
            ),
            'default_value' => 'vertical',
        ),
        array(
            'key' => 'field_steps_items',
            'label' => 'Steps',
            'name' => 'items',
            'type' => 'repeater',
            'instructions' => 'Add process steps with icon, heading, and content',
            'required' => 1,
            'min' => 2,
            'button_label' => 'Add Step',
            'sub_fields' => array(
                array(
                    'key' => 'field_steps_item_icon',
                    'label' => 'Icon / Image',
                    'name' => 'icon',
                    'type' => 'image',
                    'instructions' => 'Upload an icon or image (recommended: 80x80px square)',
                    'required' => 0,
                    'return_format' => 'id',
                    'preview_size' => 'medium',
                    'library' => 'all',
                ),
                array(
                    'key' => 'field_steps_item_heading',
                    'label' => 'Step Heading',
                    'name' => 'heading',
                    'type' => 'text',
                    'instructions' => 'Title for this step',
                    'required' => 1,
                    'placeholder' => 'Step Title',
                    'maxlength' => 100,
                ),
                array(
                    'key' => 'field_steps_item_content',
                    'label' => 'Step Content',
                    'name' => 'content',
                    'type' => 'wysiwyg',
                    'instructions' => 'Description of this step',
                    'required' => 1,
                    'toolbar' => 'basic',
                    'media_upload' => 0,
                    'tabs' => 'visual',
                ),
            ),
            'collapsed' => 'field_steps_item_heading',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/steps',
            ),
        ),
    ),
));

endif;
