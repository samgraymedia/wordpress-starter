<?php

/**
 * Blog Posts Block
 * ACF Field Configuration
 */

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_blog_posts_block',
    'title' => 'Blog Posts Block Fields',
    'fields' => array(
        array(
            'key' => 'field_blog_posts_title',
            'label' => 'Block Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Optional title to display above the blog posts',
            'required' => 0,
            'placeholder' => 'Latest Blog Posts',
        ),
        array(
            'key' => 'field_blog_posts_select',
            'label' => 'Select Blog Posts',
            'name' => 'blog_posts',
            'type' => 'relationship',
            'instructions' => 'Choose which blog posts to display',
            'required' => 0,
            'post_type' => array( 'blog' ),
            'taxonomy' => array(),
            'filters' => array( 'search', 'post_type', 'taxonomy' ),
            'elements' => '',
            'min' => 1,
            'max' => 12,
            'return_format' => 'object',
        ),
        array(
            'key' => 'field_blog_posts_show_excerpt',
            'label' => 'Show Excerpt',
            'name' => 'show_excerpt',
            'type' => 'true_false',
            'instructions' => 'Display post excerpt below title',
            'default_value' => 1,
            'ui' => 1,
        ),
        array(
            'key' => 'field_blog_posts_show_date',
            'label' => 'Show Date',
            'name' => 'show_date',
            'type' => 'true_false',
            'instructions' => 'Display post published date',
            'default_value' => 1,
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
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => true,
    'description' => '',
    'show_in_rest' => 0,
));

endif;
?>
