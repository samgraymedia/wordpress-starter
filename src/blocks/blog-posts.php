<?php

/**
 * Register Blog Posts Block Fields
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_blog_posts_block',
    'title' => 'Blog Posts Block Fields',
    'fields' => array(
          array(
            'key' => 'field_blog_posts_lead',
            'label' => 'Section Lead',
            'name' => 'lead',
            'type' => 'text',
            'instructions' => 'Optional lead text for the blog posts section',
            'required' => 0,
        ),
        // Section Title
        array(
            'key' => 'field_blog_posts_title',
            'label' => 'Section Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Optional title for the blog posts section',
            'required' => 0,
        ),
        // Blog Posts Selection
        array(
            'key' => 'field_blog_posts_select',
            'label' => 'Select Blog Posts',
            'name' => 'posts',
            'type' => 'post_object',
            'instructions' => 'Select up to 4 blog posts to display',
            'required' => 0,
            'post_type' => array(
                0 => 'blog',
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
                'value' => 'acf/blog-posts',
            ),
        ),
    ),
));

endif;
