<?php

/**
 * Register Text Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_text_block',
    'title' => 'Text Block Fields',
    'fields' => array(
        array(
            'key' => 'field_text_content',
            'label' => 'Content',
            'name' => 'content',
            'type' => 'wysiwyg',
            'instructions' => 'Enter the text content',
            'required' => 0,
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/text',
            ),
        ),
    ),
));

endif; 