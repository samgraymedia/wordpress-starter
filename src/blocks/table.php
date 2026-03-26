<?php
/**
 * Table Block Fields
 * 
 * Registers ACF fields for the generic data table block
 * Supports specs, pricing, opening hours, comparisons, and more
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group( array(
	'key'      => 'group_table_block',
	'title'    => 'Table Block',
	'fields'   => array(
		array(
			'key'           => 'field_table_title',
			'label'         => 'Table Title',
			'name'          => 'title',
			'type'          => 'text',
			'placeholder'   => 'e.g., "Pricing Plans" or "Opening Hours"',
			'maxlength'     => 200,
			'instructions'  => 'Optional table heading (displays as h3)',
		),
		array(
			'key'           => 'field_table_caption',
			'label'         => 'Table Caption',
			'name'          => 'caption',
			'type'          => 'textarea',
			'placeholder'   => 'e.g., "All prices include tax"',
			'rows'          => 2,
			'maxlength'     => 500,
			'instructions'  => 'Optional descriptive text below title',
		),
		array(
			'key'           => 'field_table_striped',
			'label'         => 'Striped Rows',
			'name'          => 'striped',
			'type'          => 'true_false',
			'message'       => 'Alternate row background colors for readability',
			'ui'            => 1,
			'instructions'  => 'Apply alternating background colors to rows',
		),
		array(
			'key'           => 'field_table_rows',
			'label'         => 'Table Rows',
			'name'          => 'rows',
			'type'          => 'repeater',
			'instructions'  => 'Add table rows (first row can be header)',
			'button_label'  => 'Add Row',
			'min'           => 1,
			'sub_fields'    => array(
				array(
					'key'           => 'field_table_row_is_header',
					'label'         => 'Header Row',
					'name'          => 'is_header',
					'type'          => 'true_false',
					'message'       => 'Mark as header (thead)',
					'ui'            => 1,
					'instructions'  => 'Check if this is a header row',
					'default_value' => 0,
				),
				array(
					'key'           => 'field_table_row_cells',
					'label'         => 'Cells',
					'name'          => 'cells',
					'type'          => 'repeater',
					'instructions'  => 'Add cells for this row',
					'button_label'  => 'Add Cell',
					'min'           => 1,
					'sub_fields'    => array(
						array(
							'key'           => 'field_table_cell_content',
							'label'         => 'Content',
							'name'          => 'content',
							'type'          => 'textarea',
							'placeholder'   => 'Cell content',
							'rows'          => 1,
							'maxlength'     => 500,
							'instructions'  => 'Text, numbers, or markup for this cell',
						),
					),
				),
			),
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/table',
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
	'description'           => 'Generic data table block for specs, pricing, hours, comparisons, fees, policies, and more',
) );
