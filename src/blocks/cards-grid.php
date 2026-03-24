<?php

/**
 * Register Cards Grid Block Fields
 * Generic reusable card layout for services, features, categories, team, or linked items
 */

if ( function_exists( 'acf_add_local_field_group' ) ) {
	acf_add_local_field_group(
		array(
			'key'                   => 'group_cards_grid_block',
			'title'                 => 'Cards Grid Block',
			'fields'                => array(
				array(
					'key'           => 'field_cards_title',
					'label'         => 'Section Title',
					'name'          => 'title',
					'type'          => 'text',
					'placeholder'   => 'Our Services',
					'instructions'  => 'Main heading for the cards section',
					'required'      => 0,
					'maxlength'     => 200,
				),
				array(
					'key'           => 'field_cards_description',
					'label'         => 'Section Description',
					'name'          => 'description',
					'type'          => 'textarea',
					'instructions'  => 'Optional intro text',
					'required'      => 0,
					'rows'          => 3,
				),
				array(
					'key'           => 'field_cards_items',
					'label'         => 'Cards',
					'name'          => 'cards',
					'type'          => 'repeater',
					'instructions'  => 'Add individual card items',
					'required'      => 1,
					'layout'        => 'table',
					'min'           => 1,
					'max'           => 20,
					'sub_fields'    => array(
						array(
							'key'           => 'field_card_image',
							'label'         => 'Image/Icon',
							'name'          => 'image',
							'type'          => 'image',
							'instructions'  => 'Upload image or icon (square recommended)',
							'required'      => 1,
							'return_format' => 'array',
							'preview_size'  => 'medium',
						),
						array(
							'key'           => 'field_card_image_alt',
							'label'         => 'Image Alt Text',
							'name'          => 'image_alt',
							'type'          => 'text',
							'instructions'  => 'Describe the image for SEO & accessibility',
							'required'      => 1,
							'maxlength'     => 150,
						),
						array(
							'key'           => 'field_card_title',
							'label'         => 'Title',
							'name'          => 'title',
							'type'          => 'text',
							'instructions'  => 'Card heading (e.g., "Web Design")',
							'required'      => 1,
							'maxlength'     => 100,
						),
						array(
							'key'           => 'field_card_description',
							'label'         => 'Description',
							'name'          => 'description',
							'type'          => 'textarea',
							'instructions'  => 'Short description or summary',
							'required'      => 1,
							'rows'          => 3,
							'maxlength'     => 300,
						),
						array(
							'key'           => 'field_card_link',
							'label'         => 'Link',
							'name'          => 'link',
							'type'          => 'link',
							'instructions'  => 'Optional link for the card',
							'required'      => 0,
							'return_format' => 'array',
						),
						array(
							'key'           => 'field_card_highlight',
							'label'         => 'Highlight/Feature',
							'name'          => 'highlight',
							'type'          => 'true_false',
							'instructions'  => 'Highlight this card with special styling',
							'default_value' => 0,
						),
					),
				),
				array(
					'key'           => 'field_cards_columns',
					'label'         => 'Desktop Columns',
					'name'          => 'columns',
					'type'          => 'select',
					'instructions'  => 'Number of columns on desktop',
					'required'      => 0,
					'choices'       => array(
						'2' => '2 Columns',
						'3' => '3 Columns',
						'4' => '4 Columns',
					),
					'allow_null'    => 0,
					'default_value' => '3',
				),
				array(
					'key'           => 'field_cards_style',
					'label'         => 'Card Style',
					'name'          => 'style',
					'type'          => 'select',
					'instructions'  => 'Choose card design',
					'required'      => 0,
					'choices'       => array(
						'standard'   => 'Standard (Image top, text below)',
						'overlay'    => 'Overlay (Image with text overlay)',
						'horizontal' => 'Horizontal (Image left, text right)',
					),
					'allow_null'    => 0,
					'default_value' => 'standard',
				),
				array(
					'key'           => 'field_cards_image_height',
					'label'         => 'Image Height',
					'name'          => 'image_height',
					'type'          => 'select',
					'instructions'  => 'Height of card images',
					'required'      => 0,
					'choices'       => array(
						'200px'  => 'Small (200px)',
						'250px'  => 'Medium (250px)',
						'300px'  => 'Large (300px)',
					),
					'allow_null'    => 0,
					'default_value' => '250px',
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/cards-grid',
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
