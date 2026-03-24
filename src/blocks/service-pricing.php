<?php
/**
 * Block: Service Pricing Grid
 * Description: Display services in a pricing card layout with tiers
 */

return array(
	'key'      => 'block_service_pricing',
	'title'    => 'Service Pricing',
	'fields'   => array(
		array(
			'key'           => 'field_service_pricing_services',
			'label'         => 'Services',
			'name'          => 'services',
			'type'          => 'post_object',
			'instructions'  => 'Select services to display as pricing cards',
			'post_type'     => array( 'services' ),
			'return_format' => 'object',
			'ui'            => 1,
			'allow_null'    => 0,
			'multiple'      => 1,
		),
		array(
			'key'          => 'field_service_pricing_columns',
			'label'        => 'Columns',
			'name'         => 'columns',
			'type'         => 'select',
			'choices'      => array(
				'2' => '2 Columns',
				'3' => '3 Columns',
				'4' => '4 Columns',
			),
			'default_value' => '3',
			'return_format' => 'value',
		),
		array(
			'key'          => 'field_service_pricing_highlight',
			'label'        => 'Highlight Best Option',
			'name'         => 'highlight',
			'type'         => 'true_false',
			'instructions' => 'Scale up and highlight one service as the recommended choice',
			'default_value' => 0,
		),
		array(
			'key'           => 'field_service_pricing_featured',
			'label'         => 'Featured Service',
			'name'          => 'featured_service',
			'type'          => 'post_object',
			'instructions'  => 'Which service should be featured/highlighted?',
			'post_type'     => array( 'services' ),
			'return_format' => 'object',
			'ui'            => 1,
			'allow_null'    => 1,
			'multiple'      => 0,
			'conditional_logic' => array(
				array(
					array(
						'field'    => 'field_service_pricing_highlight',
						'operator' => '==',
						'value'    => 1,
					),
				),
			),
		),
		array(
			'key'          => 'field_service_pricing_currency',
			'label'        => 'Currency Symbol',
			'name'         => 'currency',
			'type'         => 'text',
			'instructions' => 'e.g., "$", "€", "£", "¥"',
			'default_value' => '$',
			'placeholder'  => '$',
		),
		array(
			'key'          => 'field_service_pricing_show_duration',
			'label'        => 'Show Duration',
			'name'         => 'show_duration',
			'type'         => 'true_false',
			'instructions' => 'Display service duration/timeline',
			'default_value' => 1,
		),
		array(
			'key'          => 'field_service_pricing_show_features',
			'label'        => 'Show Features',
			'name'         => 'show_features',
			'type'         => 'true_false',
			'instructions' => 'Display key features/benefits for each service',
			'default_value' => 1,
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/service-pricing',
			),
		),
	),
);
