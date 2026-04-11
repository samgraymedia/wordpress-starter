<?php

/**
 * Register FAQ Posts Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_faq_posts_block',
    'title' => 'FAQ Posts Block Fields',
    'fields' => array(
          array(
            'key' => 'field_faq_posts_lead',
            'label' => 'Section Lead',
            'name' => 'lead',
            'type' => 'text',
            'instructions' => 'Optional lead text for the faq posts section',
            'required' => 0,
        ),
        // Section Title
        array(
            'key' => 'field_faq_posts_title',
            'label' => 'Section Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Optional title for the faq posts section',
            'required' => 0,
        ),
        // FAQ Posts Selection
        array(
            'key' => 'field_faq_posts_select',
            'label' => 'Select FAQ Posts',
            'name' => 'posts',
            'type' => 'post_object',
            'instructions' => 'Select FAQ posts to display (unlimited)',
            'required' => 0,
            'post_type' => array(
                0 => 'faq',
            ),
            'taxonomy' => array(),
            'allow_null' => 0,
            'multiple' => 1,
            'return_format' => 'object',
            'ui' => 1,
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/faq',
            ),
        ),
    ),
));

endif;
