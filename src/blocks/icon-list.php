<?php
/**
 * Icon List / Features List Block - ACF Field Registration
 * Block itself is auto-discovered from views/blocks/icon-list.twig
 */

if ( function_exists( 'acf_add_local_field_group' ) ) {
	acf_add_local_field_group(
		array(
			'key'                   => 'group_icon_list_block',
			'title'                 => 'Icon List Block',
			'fields'                => array(
				array(
					'key'           => 'field_icon_list_title',
					'label'         => 'Section Title',
					'name'          => 'title',
					'type'          => 'text',
					'placeholder'   => 'Key Features',
				),
				array(
					'key'           => 'field_icon_list_description',
					'label'         => 'Section Description',
					'name'          => 'description',
					'type'          => 'textarea',
					'rows'          => 3,
				),
				array(
					'key'           => 'field_icon_list_items',
					'label'         => 'Features / Items',
					'name'          => 'items',
					'type'          => 'repeater',
					'layout'        => 'block',
					'button_label'  => 'Add Item',
					'sub_fields'    => array(
						array(
							'key'           => 'field_icon_list_item_icon',
							'label'         => 'Icon (SVG or icon class)',
							'name'          => 'icon',
							'type'          => 'textarea',
							'rows'          => 4,
							'placeholder'   => '<svg>...</svg> or fa-icon-name',
							'instructions'  => 'Paste SVG code or enter Font Awesome class (e.g., fa-star)',
						),
						array(
							'key'           => 'field_icon_list_item_label',
							'label'         => 'Label',
							'name'          => 'label',
							'type'          => 'text',
							'required'      => 1,
							'placeholder'   => 'Feature title',
						),
						array(
							'key'           => 'field_icon_list_item_description',
							'label'         => 'Description',
							'name'          => 'description',
							'type'          => 'textarea',
							'rows'          => 2,
							'placeholder'   => 'Brief description of this feature',
						),
					),
					'min'           => 1,
					'max'           => 12,
				),
				array(
					'key'           => 'field_icon_list_layout',
					'label'         => 'Layout Style',
					'name'          => 'layout',
					'type'          => 'select',
					'choices'       => array(
						'vertical'   => 'Vertical (stacked)',
						'horizontal' => 'Horizontal (left-to-right)',
						'columns'    => 'Grid (columns)',
					),
					'default_value' => 'vertical',
				),
				array(
					'key'           => 'field_icon_list_columns',
					'label'         => 'Columns (Grid Layout Only)',
					'name'          => 'columns',
					'type'          => 'select',
					'choices'       => array(
						'2' => '2 Columns',
						'3' => '3 Columns',
						'4' => '4 Columns',
					),
					'default_value' => '3',
				),
				array(
					'key'           => 'field_icon_list_icon_size',
					'label'         => 'Icon Size',
					'name'          => 'icon_size',
					'type'          => 'select',
					'choices'       => array(
						'small'  => 'Small (40px)',
						'medium' => 'Medium (56px)',
						'large'  => 'Large (72px)',
					),
					'default_value' => 'medium',
				),
				array(
					'key'           => 'field_icon_list_icon_color',
					'label'         => 'Icon Color',
					'name'          => 'icon_color',
					'type'          => 'select',
					'choices'       => array(
						'primary'   => 'Primary',
						'secondary' => 'Secondary',
						'success'   => 'Success',
						'warning'   => 'Warning',
						'info'      => 'Info',
						'danger'    => 'Danger',
					),
					'default_value' => 'primary',
				),
				array(
					'key'           => 'field_icon_list_text_align',
					'label'         => 'Text Alignment',
					'name'          => 'text_align',
					'type'          => 'select',
					'choices'       => array(
						'left'   => 'Left',
						'center' => 'Center',
					),
					'default_value' => 'left',
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/icon-list',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => array(),
			'active'                => true,
			'description'           => '',
		)
	);
}
