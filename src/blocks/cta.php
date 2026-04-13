<?php

/**
 * Register CTA Block
 */
register_block_type(__DIR__ . '/cta');

/**
 * Register CTA Block Fields
 */
if( function_exists('acf_add_local_field_group') ):
acf_add_local_field_group(array(
    'key' => 'group_cta_block',
    'title' => 'CTA Block Fields',
    'fields' => array(
        array(
            'key' => 'field_cta_lead',
            'label' => 'CTA Lead',
            'name' => 'cta_lead',
            'type' => 'text',
            'instructions' => 'Enter the CTA lead text',
            'required' => 0,
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
        ),
        array(
            'key' => 'field_cta_title',
            'label' => 'CTA Title',
            'name' => 'cta_title',
            'type' => 'text',
            'instructions' => 'Enter the CTA title',
            'required' => 0,
            'allow_null' => 0,
        ),
        array(
            'key' => 'field_cta_button_text',
            'label' => 'CTA Button Text',
            'name' => 'cta_button_text',
            'type' => 'text',
            'instructions' => 'Enter the CTA button text',
            'required' => 0,
            'allow_null' => 0,
        ),
           array(
            'key' => 'field_cta_button_url',
            'label' => 'CTA Button URL',
            'name' => 'cta_button_url',
            'type' => 'url',
            'instructions' => 'Enter the CTA button URL',
            'required' => 0,
            'allow_null' => 0,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/cta',
            ),
        ),
    ),
));

endif; 