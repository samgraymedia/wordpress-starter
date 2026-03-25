<?php
/**
 * Map / Locations Block
 * 
 * Displays multiple locations with address, contact info, hours, map, and image
 */

if ( ! function_exists( 'acf_register_block_type' ) ) {
	return;
}

acf_register_block_type( array(
	'name'            => 'map',
	'title'           => 'Map / Locations',
	'description'     => 'Display multiple locations with maps, contact info, and images',
	'render_template' => 'views/blocks/map.twig',
	'category'        => 'widgets',
	'icon'            => 'location-alt',
	'keywords'        => array( 'map', 'locations', 'contact', 'address' ),
	'supports'        => array(
		'align'  => false,
		'anchor' => true,
	),
) );

if ( function_exists( 'acf_add_local_field_group' ) ) {
	acf_add_local_field_group( array(
		'key'                   => 'group_map_locations',
		'title'                 => 'Map / Locations',
		'fields'                => array(
			array(
				'key'               => 'field_map_title',
				'label'             => 'Title',
				'name'              => 'title',
				'type'              => 'text',
				'required'          => 0,
				'placeholder'       => 'e.g., Our Locations',
				'maxlength'         => 100,
				'wrapper'           => array( 'width' => '100' ),
			),
			array(
				'key'               => 'field_map_locations',
				'label'             => 'Locations',
				'name'              => 'locations',
				'type'              => 'repeater',
				'required'          => 1,
				'min'               => 1,
				'max'               => 0,
				'layout'            => 'block',
				'button_label'      => 'Add Location',
				'wrapper'           => array( 'width' => '100' ),
				'sub_fields'        => array(
					array(
						'key'               => 'field_map_loc_address',
						'label'             => 'Address',
						'name'              => 'address',
						'type'              => 'textarea',
						'required'          => 1,
						'rows'              => 2,
						'new_lines'         => 'br',
						'placeholder'       => '123 Main Street, City, State 12345',
						'wrapper'           => array( 'width' => '50' ),
					),
					array(
						'key'               => 'field_map_loc_phone',
						'label'             => 'Phone',
						'name'              => 'phone',
						'type'              => 'text',
						'required'          => 0,
						'placeholder'       => '(555) 123-4567',
						'wrapper'           => array( 'width' => '50' ),
					),
					array(
						'key'               => 'field_map_loc_hours',
						'label'             => 'Hours',
						'name'              => 'hours',
						'type'              => 'textarea',
						'required'          => 0,
						'rows'              => 3,
						'new_lines'         => 'br',
						'placeholder'       => 'Mon-Fri: 9am - 5pm\nSat: 10am - 3pm\nSun: Closed',
						'wrapper'           => array( 'width' => '100' ),
					),
					array(
						'key'               => 'field_map_loc_map_coords',
						'label'             => 'Map Coordinates (Latitude, Longitude)',
						'name'              => 'map_coords',
						'type'              => 'text',
						'required'          => 0,
						'placeholder'       => '40.7128, -74.0060',
						'instructions'      => 'Optional: Enter latitude and longitude separated by comma. If provided, a map pin will display.',
						'wrapper'           => array( 'width' => '100' ),
					),
					array(
						'key'               => 'field_map_loc_image',
						'label'             => 'Location Image',
						'name'              => 'image',
						'type'              => 'image',
						'required'          => 0,
						'return_format'     => 'array',
						'preview_size'      => 'thumbnail',
						'library'           => 'all',
						'wrapper'           => array( 'width' => '50' ),
					),
					array(
						'key'               => 'field_map_loc_link',
						'label'             => 'External Link',
						'name'              => 'link',
						'type'              => 'url',
						'required'          => 0,
						'placeholder'       => 'https://google.com/maps/...',
						'instructions'      => 'Optional: Link to Google Maps or other map service',
						'wrapper'           => array( 'width' => '50' ),
					),
				),
			),
			array(
				'key'               => 'field_map_layout',
				'label'             => 'Layout Style',
				'name'              => 'layout',
				'type'              => 'button_group',
				'choices'           => array(
					'grid'   => 'Grid',
					'list'   => 'List',
					'map'    => 'Map View',
				),
				'default_value'     => 'grid',
				'wrapper'           => array( 'width' => '50' ),
			),
			array(
				'key'               => 'field_map_columns',
				'label'             => 'Grid Columns',
				'name'              => 'columns',
				'type'              => 'button_group',
				'choices'           => array(
					'2' => '2 Columns',
					'3' => '3 Columns',
				),
				'default_value'     => '2',
				'conditional_logic' => array(
					array(
						array(
							'field'    => 'field_map_layout',
							'operator' => '==',
							'value'    => 'grid',
						),
					),
				),
				'wrapper'           => array( 'width' => '50' ),
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'block',
					'operator' => '==',
					'value'    => 'acf/map',
				),
			),
		),
	) );
}
