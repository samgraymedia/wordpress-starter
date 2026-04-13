<?php
/**
 * Post Type: Products
 * PostType: product
 * Name: Products
 * FieldsKey: group_products
 */

return array(
	'args'   => array(
		'labels'             => array(
			'name'               => __( 'Products', 'starter-theme-1.x' ),
			'singular_name'      => __( 'Product', 'starter-theme-1.x' ),
			'add_new'            => __( 'Add New', 'starter-theme-1.x' ),
			'add_new_item'       => __( 'Add New Product', 'starter-theme-1.x' ),
			'edit_item'          => __( 'Edit Product', 'starter-theme-1.x' ),
			'new_item'           => __( 'New Product', 'starter-theme-1.x' ),
			'view_item'          => __( 'View Product', 'starter-theme-1.x' ),
			'search_items'       => __( 'Search Products', 'starter-theme-1.x' ),
			'not_found'          => __( 'No products found', 'starter-theme-1.x' ),
			'not_found_in_trash' => __( 'No products found in Trash', 'starter-theme-1.x' ),
			'parent_item_colon'  => '',
			'menu_name'          => __( 'Products', 'starter-theme-1.x' ),
		),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'products' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 6,
		'menu_icon'          => 'dashicons-tag',
		'supports'           => array( 'title', 'thumbnail', 'excerpt', 'custom-fields', 'author' ),
		'show_in_rest'       => false,
	),
	'fields' => array(
		'key'      => 'group_products',
		'title'    => 'Product Fields',
		'fields'   => array(
			array(
				'key'             => 'field_product_description',
				'label'           => 'Product Description',
				'name'            => 'product_description',
				'type'            => 'textarea',
				'instructions'    => 'Detailed product description',
				'required'        => 0,
				'rows'            => 5,
			),
			array(
				'key'             => 'field_product_gallery',
				'label'           => 'Product Gallery',
				'name'            => 'product_gallery',
				'type'            => 'gallery',
				'instructions'    => 'Upload high-resolution product images',
				'required'        => 0,
				'return_format'   => 'id',
				'min'             => 0,
				'max'             => 0,
			),
			array(
				'key'             => 'field_product_materials',
				'label'           => 'Materials & Finishes',
				'name'            => 'product_materials',
				'type'            => 'text',
				'instructions'    => 'Materials and finishes used',
				'required'        => 0,
			),
			array(
				'key'             => 'field_product_dimensions',
				'label'           => 'Dimensions',
				'name'            => 'product_dimensions',
				'type'            => 'textarea',
				'instructions'    => 'Product dimensions and specifications',
				'required'        => 0,
				'rows'            => 3,
			),
			array(
				'key'             => 'field_product_category',
				'label'           => 'Product Category',
				'name'            => 'product_category',
				'type'            => 'text',
				'instructions'    => 'Product line or category',
				'required'        => 0,
			),
			array(
				'key'             => 'field_product_featured',
				'label'           => 'Featured Product',
				'name'            => 'product_featured',
				'type'            => 'true_false',
				'instructions'    => 'Mark this product as featured',
				'required'        => 0,
				'ui'              => 1,
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'product',
				),
			),
		),
	),
);

