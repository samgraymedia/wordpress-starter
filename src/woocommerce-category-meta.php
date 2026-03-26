<?php
/**
 * WooCommerce Product Category ACF Fields
 *
 * Registers advanced custom fields for product categories including:
 * - Category banner images
 * - Category description overrides
 * - Category layout options
 *
 * @package Starter_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register product category fields via ACF
 * Called only when ACF Pro is active and WooCommerce is enabled
 */
function register_product_category_fields() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key'      => 'group_product_category_meta',
		'title'    => 'Product Category Settings',
		'fields'   => array(
			array(
				'key'               => 'field_category_banner',
				'label'             => 'Category Banner Image',
				'name'              => 'category_banner_image',
				'type'              => 'image',
				'return_format'     => 'array',
				'preview_size'      => 'medium',
				'library'           => 'all',
				'instructions'      => 'Upload a custom hero banner image for this category. If not set, the default shop hero will be used.',
			),
			array(
				'key'               => 'field_category_description_override',
				'label'             => 'Category Description Override',
				'name'              => 'category_description_override',
				'type'              => 'textarea',
				'rows'              => 4,
				'instructions'      => 'Optional: Override the default category description shown in the hero section.',
				'maxlength'         => 500,
			),
			array(
				'key'               => 'field_category_layout',
				'label'             => 'Category Layout Style',
				'name'              => 'category_layout_style',
				'type'              => 'select',
				'choices'           => array(
					'grid'     => 'Grid Layout (3/2/1 columns)',
					'list'     => 'List Layout (full width)',
					'featured' => 'Featured + Grid (featured products first)',
				),
				'default_value'     => 'grid',
				'instructions'      => 'Choose how products should be displayed in this category.',
			),
			array(
				'key'               => 'field_featured_products',
				'label'             => 'Featured Products',
				'name'              => 'featured_products',
				'type'              => 'relationship',
				'post_type'         => array(
					'product',
				),
				'taxonomy'          => '',
				'filters'           => array(
					'search',
				),
				'return_format'     => 'object',
				'min'               => 0,
				'max'               => 3,
				'instructions'      => 'Select up to 3 products to feature at the top of this category (only used if "Featured + Grid" layout is selected).',
			),
			array(
				'key'               => 'field_show_subcats',
				'label'             => 'Show Subcategories',
				'name'              => 'show_subcategories',
				'type'              => 'true_false',
				'ui'                => 1,
				'default_value'     => 1,
				'instructions'      => 'Display subcategories at the top of the product list.',
			),
			array(
				'key'               => 'field_products_per_page',
				'label'             => 'Products Per Page',
				'name'              => 'products_per_page',
				'type'              => 'number',
				'default_value'     => 12,
				'min'               => 6,
				'max'               => 48,
				'step'              => 6,
				'instructions'      => 'Number of products to display per page in this category.',
			),
			array(
				'key'               => 'field_category_icon',
				'label'             => 'Category Icon/SVG',
				'name'              => 'category_icon',
				'type'              => 'file',
				'return_format'     => 'url',
				'library'           => 'all',
				'instructions'      => 'Optional: Upload an SVG icon for this category (used in breadcrumbs or category listings).',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'taxonomy',
					'operator' => '==',
					'value'    => 'product_cat',
				),
			),
		),
		'menu_order'            => 0,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => '',
		'active'                => true,
		'description'           => 'Customize product category appearance and behavior',
	) );
}

// Register fields when ACF is loaded
add_action( 'acf/include_fields', 'register_product_category_fields' );

/**
 * Handle category layout customization
 * Applies layout-specific CSS classes to the category archive page
 */
function apply_product_category_layout_styles() {
	if ( ! function_exists( 'is_product_cat' ) || ! is_product_cat() ) {
		return;
	}

	$category = get_queried_object();
	if ( ! $category ) {
		return;
	}

	$layout = get_field( 'category_layout_style', 'product_cat_' . $category->term_id );

	if ( 'list' === $layout ) {
		add_filter( 'body_class', function( $classes ) {
			$classes[] = 'category-layout-list';
			return $classes;
		} );
	} elseif ( 'featured' === $layout ) {
		add_filter( 'body_class', function( $classes ) {
			$classes[] = 'category-layout-featured';
			return $classes;
		} );
	} else {
		add_filter( 'body_class', function( $classes ) {
			$classes[] = 'category-layout-grid';
			return $classes;
		} );
	}
}

add_action( 'wp', 'apply_product_category_layout_styles' );

/**
 * Modify products per page based on category settings
 */
function apply_category_products_per_page( $products_per_page ) {
	if ( ! function_exists( 'is_product_cat' ) || ! is_product_cat() ) {
		return $products_per_page;
	}

	$category = get_queried_object();
	if ( ! $category ) {
		return $products_per_page;
	}

	$custom_count = get_field( 'products_per_page', 'product_cat_' . $category->term_id );
	if ( $custom_count ) {
		return intval( $custom_count );
	}

	return $products_per_page;
}

add_filter( 'loop_shop_per_page', 'apply_category_products_per_page' );
