<?php

/**
 * Register Hero Block Fields
 */
if( function_exists('acf_add_local_field_group') ):
acf_add_local_field_group(array(
    'key' => 'group_hero_block',
    'title' => 'Hero Block Fields',
    'fields' => array(
        array(
            'key' => 'field_hero_image',
            'label' => 'Hero Image',
            'name' => 'hero_image',
            'type' => 'image',
            'instructions' => 'Upload the hero image',
            'required' => 0,
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
        ),
        array(
            'key' => 'field_hero_title',
            'label' => 'Hero Title',
            'name' => 'hero_title',
            'type' => 'text',
            'instructions' => 'Enter the hero title',
            'required' => 0,
            'allow_null' => 0,
        ),
        array(
            'key' => 'field_hero_lead',
            'label' => 'Hero Lead',
            'name' => 'hero_lead',
            'type' => 'text',
            'instructions' => 'Enter the hero lead text',
            'required' => 0,
            'allow_null' => 0,
        ),
           array(
            'key' => 'field_hero_description',
            'label' => 'Hero Description',
            'name' => 'hero_description',
            'type' => 'textarea',
            'instructions' => 'Enter the hero description text',
            'required' => 0,
            'allow_null' => 0,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/hero',
            ),
        ),
    ),
));

endif; 