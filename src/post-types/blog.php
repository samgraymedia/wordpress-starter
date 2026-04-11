<?php
/**
 * Post Type: Blog
 * PostType: blog
 * Name: Blog
 * FieldsKey: group_featured_blog
 */

return array(
	'args'   => array(
		'labels'             => array(
			'name'               => __( 'Blog Posts', 'starter-theme-1.x' ),
			'singular_name'      => __( 'Blog Post', 'starter-theme-1.x' ),
			'add_new'            => __( 'Add New', 'starter-theme-1.x' ),
			'add_new_item'       => __( 'Add New Blog Post', 'starter-theme-1.x' ),
			'edit_item'          => __( 'Edit Blog Post', 'starter-theme-1.x' ),
			'new_item'           => __( 'New Blog Post', 'starter-theme-1.x' ),
			'view_item'          => __( 'View Blog Post', 'starter-theme-1.x' ),
			'search_items'       => __( 'Search Blog Posts', 'starter-theme-1.x' ),
			'not_found'          => __( 'No blog posts found', 'starter-theme-1.x' ),
			'not_found_in_trash' => __( 'No blog posts found in Trash', 'starter-theme-1.x' ),
			'parent_item_colon'  => '',
			'menu_name'          => __( 'Blog', 'starter-theme-1.x' ),
		),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'blog' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 4,
		'menu_icon'          => 'dashicons-edit',
	'supports'           => array( 'title', 'thumbnail', 'excerpt', 'custom-fields', 'author', 'comments' ),
		'show_in_rest'       => false,
	),
	'fields' => array(
		'key'      => 'group_featured_blog',
		'title'    => 'Featured Blog Post',
		'fields'   => array(
			array(
				'key'             => 'field_blog_wysiwyg',
				'label'           => 'Content',
				'name'            => 'blog_wysiwyg',
				'type'            => 'wysiwyg',
				'instructions'    => 'Main article content with full editor features',
				'required'        => 0,
				'tabs'            => 'all',
				'toolbar'         => 'full',
				'media_upload'    => 1,
				'delay'           => 0,
			),
			array(
				'key'             => 'field_is_featured_blog',
				'label'           => 'Featured Post',
				'name'            => 'is_featured',
				'type'            => 'true_false',
				'instructions'    => 'Tick to make this the featured blog post. Only one can be featured at a time.',
				'default_value'   => 0,
				'ui'              => 1,
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'blog',
				),
			),
		),
	),
);
