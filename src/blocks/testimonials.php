<?php

/**
 * Register Testimonial Posts Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_testimonial_posts_block',
    'title' => 'Testimonial Posts Block Fields',
    'fields' => array(
          array(
            'key' => 'field_testimonial_posts_lead',
            'label' => 'Section Lead',
            'name' => 'lead',
            'type' => 'text',
            'instructions' => 'Optional lead text for the testimonial posts section',
            'required' => 0,
        ),
        // Section Title
        array(
            'key' => 'field_testimonial_posts_title',
            'label' => 'Section Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Optional title for the testimonial posts section',
            'required' => 0,
        ),
        // Testimonial Posts Selection
        array(
            'key' => 'field_testimonial_posts_select',
            'label' => 'Select Testimonial Posts',
            'name' => 'posts',
            'type' => 'post_object',
            'instructions' => 'Select up to 3 testimonial posts to display',
            'required' => 0,
            'post_type' => array(
                0 => 'testimonial',
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
                'value' => 'acf/testimonial',
            ),
        ),
    ),
));

endif;
