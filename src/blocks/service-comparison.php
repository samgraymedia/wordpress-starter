<?php
/**
 * Block: Service Comparison
 * Description: Compare multiple services side-by-side with feature comparison
 */

return array(
	'key'      => 'block_service_comparison',
	'title'    => 'Service Comparison',
	'fields'   => array(
		array(
			'key'           => 'field_service_comparison_services',
			'label'         => 'Services to Compare',
			'name'          => 'services',
			'type'          => 'post_object',
			'instructions'  => 'Select 2-4 services to compare',
			'post_type'     => array( 'services' ),
			'return_format' => 'object',
			'ui'            => 1,
			'allow_null'    => 0,
			'multiple'      => 1,
		),
		array(
			'key'          => 'field_service_comparison_features',
			'label'        => 'Comparison Features',
			'name'         => 'features',
			'type'         => 'repeater',
			'instructions' => 'Add features/aspects to compare across services',
			'required'     => 0,
			'sub_fields'   => array(
				array(
					'key'      => 'field_comparison_feature_name',
					'label'    => 'Feature Name',
					'name'     => 'feature_name',
					'type'     => 'text',
					'required' => 1,
				),
			),
			'button_label' => 'Add Comparison Feature',
		),
		array(
			'key'          => 'field_service_comparison_layout',
			'label'        => 'Layout',
			'name'         => 'layout',
			'type'         => 'select',
			'choices'      => array(
				'table'     => 'Table (Desktop)',
				'cards'     => 'Cards (Mobile-friendly)',
				'accordion' => 'Accordion',
			),
			'default_value' => 'table',
			'return_format' => 'value',
		),
		array(
			'key'          => 'field_service_comparison_highlight',
			'label'        => 'Highlight Best Choice',
			'name'         => 'highlight',
			'type'         => 'true_false',
			'instructions' => 'Add a "Best Choice" or "Popular" badge to one service',
			'default_value' => 0,
		),
		array(
			'key'           => 'field_service_comparison_best_choice',
			'label'         => 'Best Choice Service',
			'name'          => 'best_choice',
			'type'          => 'post_object',
			'instructions'  => 'Which service should be highlighted as best choice?',
			'post_type'     => array( 'services' ),
			'return_format' => 'object',
			'ui'            => 1,
			'allow_null'    => 1,
			'multiple'      => 0,
			'conditional_logic' => array(
				array(
					array(
						'field'    => 'field_service_comparison_highlight',
						'operator' => '==',
						'value'    => 1,
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
				'value'    => 'acf/service-comparison',
			),
		),
	),
);
