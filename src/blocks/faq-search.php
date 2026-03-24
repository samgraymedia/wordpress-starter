<?php

/**
 * Register FAQ Search / Filtered Block Fields
 * Advanced accordion with search, category filtering, and SEO schema markup
 */

if ( function_exists( 'acf_add_local_field_group' ) ) {
	acf_add_local_field_group(
		array(
			'key'                   => 'group_faq_search_block',
			'title'                 => 'FAQ Search / Filtered Block',
			'fields'                => array(
				array(
					'key'           => 'field_faq_title',
					'label'         => 'Section Title',
					'name'          => 'title',
					'type'          => 'text',
					'placeholder'   => 'Frequently Asked Questions',
					'instructions'  => 'Main heading for the FAQ section',
					'required'      => 0,
					'maxlength'     => 200,
				),
				array(
					'key'           => 'field_faq_description',
					'label'         => 'Section Description',
					'name'          => 'description',
					'type'          => 'textarea',
					'instructions'  => 'Optional intro text',
					'required'      => 0,
					'rows'          => 3,
				),
				array(
					'key'           => 'field_faq_enable_search',
					'label'         => 'Enable Search',
					'name'          => 'enable_search',
					'type'          => 'true_false',
					'instructions'  => 'Allow users to search FAQs by keyword',
					'default_value' => 1,
				),
				array(
					'key'           => 'field_faq_enable_categories',
					'label'         => 'Enable Category Filter',
					'name'          => 'enable_categories',
					'type'          => 'true_false',
					'instructions'  => 'Allow users to filter by category',
					'default_value' => 1,
				),
				array(
					'key'           => 'field_faq_expand_first',
					'label'         => 'Expand First Item',
					'name'          => 'expand_first',
					'type'          => 'true_false',
					'instructions'  => 'Expand the first FAQ item by default',
					'default_value' => 1,
				),
				array(
					'key'           => 'field_faq_items',
					'label'         => 'FAQ Items',
					'name'          => 'items',
					'type'          => 'repeater',
					'instructions'  => 'Add individual FAQ questions and answers',
					'required'      => 1,
					'layout'        => 'table',
					'min'           => 1,
					'max'           => 100,
					'sub_fields'    => array(
						array(
							'key'           => 'field_faq_question',
							'label'         => 'Question',
							'name'          => 'question',
							'type'          => 'text',
							'instructions'  => 'FAQ question (used for search)',
							'required'      => 1,
							'maxlength'     => 300,
						),
						array(
							'key'           => 'field_faq_answer',
							'label'         => 'Answer',
							'name'          => 'answer',
							'type'          => 'wysiwyg',
							'instructions'  => 'FAQ answer (supports rich text)',
							'required'      => 1,
							'tabs'          => 'all',
							'toolbar'       => 'full',
							'media_upload'  => 1,
						),
						array(
							'key'           => 'field_faq_category',
							'label'         => 'Category',
							'name'          => 'category',
							'type'          => 'select',
							'instructions'  => 'Assign category for filtering',
							'required'      => 0,
							'allow_null'    => 1,
							'choices'       => array(),
							// Dynamic choices can be added via PHP
						),
					),
				),
				array(
					'key'           => 'field_faq_categories_list',
					'label'         => 'Available Categories',
					'name'          => 'categories',
					'type'          => 'text',
					'instructions'  => 'Comma-separated list of categories (e.g., "Billing,Technical,Account")',
					'required'      => 0,
					'placeholder'   => 'Billing, Technical, Account, Pricing',
				),
				array(
					'key'           => 'field_faq_schema_enabled',
					'label'         => 'Enable Schema Markup',
					'name'          => 'schema_enabled',
					'type'          => 'true_false',
					'instructions'  => 'Output FAQPage schema for SEO',
					'default_value' => 1,
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/faq-search',
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
