<?php
/**
 * Block: Service Showcase Grid
 * Description: Display services in a flexible grid layout
 */

return array(
	'key'      => 'block_service_showcase',
	'title'    => 'Service Showcase',
	'fields'   => array(
		array(
			'key'          => 'field_service_showcase_layout',
			'label'        => 'Layout',
			'name'         => 'layout',
			'type'         => 'select',
			'choices'      => array(
				'grid-2'  => '2 Columns',
				'grid-3'  => '3 Columns',
				'grid-4'  => '4 Columns',
				'list'    => 'List',
			),
			'default_value' => 'grid-3',
			'return_format' => 'value',
		),
		array(
			'key'           => 'field_service_showcase_services',
			'label'         => 'Services to Display',
			'name'          => 'services',
			'type'          => 'post_object',
			'instructions'  => 'Leave empty to show all services, or select specific ones',
			'post_type'     => array( 'services' ),
			'return_format' => 'object',
			'ui'            => 1,
			'allow_null'    => 1,
			'multiple'      => 1,
		),
		array(
			'key'          => 'field_service_showcase_orderby',
			'label'        => 'Order By',
			'name'         => 'orderby',
			'type'         => 'select',
			'choices'      => array(
				'date'   => 'Date',
				'title'  => 'Title',
				'menu_order' => 'Menu Order',
			),
			'default_value' => 'menu_order',
			'return_format' => 'value',
		),
		array(
			'key'          => 'field_service_showcase_order',
			'label'        => 'Order',
			'name'         => 'order',
			'type'         => 'select',
			'choices'      => array(
				'ASC'  => 'Ascending',
				'DESC' => 'Descending',
			),
			'default_value' => 'ASC',
			'return_format' => 'value',
		),
		array(
			'key'          => 'field_service_showcase_show_price',
			'label'        => 'Show Price',
			'name'         => 'show_price',
			'type'         => 'true_false',
			'instructions' => 'Display service price if available',
			'default_value' => 1,
		),
		array(
			'key'          => 'field_service_showcase_show_features',
			'label'        => 'Show Features',
			'name'         => 'show_features',
			'type'         => 'true_false',
			'instructions' => 'Display key features/benefits',
			'default_value' => 1,
		),
		array(
			'key'          => 'field_service_showcase_show_cta',
			'label'        => 'Show CTA Button',
			'name'         => 'show_cta',
			'type'         => 'true_false',
			'instructions' => 'Display call-to-action button',
			'default_value' => 1,
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/service-showcase',
			),
		),
	),
);
