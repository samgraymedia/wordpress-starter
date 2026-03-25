<?php

/**
 * Register Related Content / Latest Content Block Fields
 * Query-based automatic content block for displaying posts dynamically
 */

if ( function_exists( 'acf_add_local_field_group' ) ) {
	acf_add_local_field_group(
		array(
			'key'                   => 'group_related_content_block',
			'title'                 => 'Related Content / Latest Content Block',
			'fields'                => array(
				array(
					'key'           => 'field_related_title',
					'label'         => 'Section Title',
					'name'          => 'title',
					'type'          => 'text',
					'placeholder'   => 'Related Articles',
					'instructions'  => 'Optional section heading',
					'required'      => 0,
					'maxlength'     => 200,
				),
				array(
					'key'           => 'field_related_description',
					'label'         => 'Section Description',
					'name'          => 'description',
					'type'          => 'textarea',
					'instructions'  => 'Optional intro text',
					'required'      => 0,
					'rows'          => 3,
				),
				array(
					'key'           => 'field_related_post_type',
					'label'         => 'Content Type',
					'name'          => 'post_type',
					'type'          => 'select',
					'instructions'  => 'Which post type to query',
					'required'      => 1,
					'choices'       => array(
						'post'    => 'Posts (Blog)',
						'page'    => 'Pages',
						'product' => 'Products (WooCommerce)',
					),
					'default_value' => 'post',
				),
				array(
					'key'           => 'field_related_taxonomy',
					'label'         => 'Filter by Category/Taxonomy',
					'name'          => 'taxonomy',
					'type'          => 'select',
					'instructions'  => 'Optional taxonomy to filter by (category, tag, etc.)',
					'required'      => 0,
					'allow_null'    => 1,
					'choices'       => array(),
					// Dynamic choices set via PHP
				),
				array(
					'key'           => 'field_related_term',
					'label'         => 'Filter by Specific Term',
					'name'          => 'term',
					'type'          => 'select',
					'instructions'  => 'Optional specific category/tag to filter by',
					'required'      => 0,
					'allow_null'    => 1,
					'choices'       => array(),
					// Dynamic choices set via PHP
				),
				array(
					'key'           => 'field_related_items',
					'label'         => 'Number of Items',
					'name'          => 'items_per_page',
					'type'          => 'number',
					'instructions'  => 'How many posts to display',
					'min'           => 1,
					'max'           => 20,
					'default_value' => 6,
					'required'      => 1,
				),
				array(
					'key'           => 'field_related_order_by',
					'label'         => 'Order By',
					'name'          => 'order_by',
					'type'          => 'select',
					'instructions'  => 'How to sort the posts',
					'required'      => 1,
					'choices'       => array(
						'date_desc'  => 'Latest First',
						'date_asc'   => 'Oldest First',
						'modified'   => 'Recently Updated',
						'title'      => 'Title (A-Z)',
						'random'     => 'Random',
						'meta_value' => 'Featured Posts First',
					),
					'default_value' => 'date_desc',
				),
				array(
					'key'           => 'field_related_columns',
					'label'         => 'Columns',
					'name'          => 'columns',
					'type'          => 'select',
					'instructions'  => 'Number of columns on desktop',
					'required'      => 1,
					'choices'       => array(
						'2' => '2 Columns',
						'3' => '3 Columns',
						'4' => '4 Columns',
					),
					'default_value' => '3',
				),
				array(
					'key'           => 'field_related_show_excerpt',
					'label'         => 'Show Excerpt',
					'name'          => 'show_excerpt',
					'type'          => 'true_false',
					'instructions'  => 'Display post excerpt or summary',
					'default_value' => 1,
				),
				array(
					'key'           => 'field_related_show_image',
					'label'         => 'Show Featured Image',
					'name'          => 'show_image',
					'type'          => 'true_false',
					'instructions'  => 'Display featured image thumbnail',
					'default_value' => 1,
				),
				array(
					'key'           => 'field_related_show_date',
					'label'         => 'Show Publish Date',
					'name'          => 'show_date',
					'type'          => 'true_false',
					'instructions'  => 'Display post date and author',
					'default_value' => 1,
				),
				array(
					'key'           => 'field_related_exclude_current',
					'label'         => 'Exclude Current Post',
					'name'          => 'exclude_current',
					'type'          => 'true_false',
					'instructions'  => 'Do not include the current post in results',
					'default_value' => 1,
				),
				array(
					'key'           => 'field_related_link_target',
					'label'         => 'Link Behavior',
					'name'          => 'link_target',
					'type'          => 'select',
					'instructions'  => 'How to open links',
					'required'      => 1,
					'choices'       => array(
						'_self'  => 'Same Window',
						'_blank' => 'New Window',
					),
					'default_value' => '_self',
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/related-content',
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
			'description'           => '',
		)
	);
}

/**
 * Populate taxonomy choices based on selected post type
 * This is a helper for the ACF field if dynamic taxonomy selection is needed
 */
function get_post_type_taxonomies( $post_type = 'post' ) {
	$taxonomies = get_object_taxonomies( $post_type, 'objects' );
	$choices    = array();

	foreach ( $taxonomies as $taxonomy ) {
		if ( $taxonomy->publicly_queryable ) {
			$choices[ $taxonomy->name ] = $taxonomy->label;
		}
	}

	return $choices;
}
