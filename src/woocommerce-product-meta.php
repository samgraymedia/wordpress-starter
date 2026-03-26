<?php
/**
 * Product Meta Fields - ACF Integration
 * Registers custom product meta fields using Advanced Custom Fields
 * 
 * Features:
 * - Product Highlights (repeater)
 * - Recommended With (relationship)
 * - Product Specifications (repeater)
 * - Brand/Manufacturer
 * - Product Details (flexible content)
 * 
 * @package StarterTheme
 */

if ( function_exists( 'acf_add_local_field_group' ) ) {

	acf_add_local_field_group( array(
		'key'                   => 'group_product_meta',
		'title'                 => __( 'Product Meta', 'starter-theme-1.x' ),
		'fields'                => array(
			/**
			 * Product Highlights - Repeater
			 * Display key selling points and features
			 */
			array(
				'key'               => 'field_product_highlights',
				'label'             => __( 'Product Highlights', 'starter-theme-1.x' ),
				'name'              => 'product_highlights',
				'type'              => 'repeater',
				'instructions'      => __( 'Add key features and selling points for this product', 'starter-theme-1.x' ),
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '100',
					'class' => '',
					'id'    => '',
				),
				'collapsed'         => 'field_highlight_text',
				'min'               => 0,
				'max'               => 5,
				'layout'            => 'table',
				'button_label'      => __( 'Add Highlight', 'starter-theme-1.x' ),
				'sub_fields'        => array(
					array(
						'key'           => 'field_highlight_icon',
						'label'         => __( 'Icon', 'starter-theme-1.x' ),
						'name'          => 'icon',
						'type'          => 'select',
						'choices'       => array(
							'icon-check'      => '✓ Check',
							'icon-star'       => '★ Star',
							'icon-heart'      => '♥ Heart',
							'icon-shield'     => '🛡 Shield',
							'icon-zap'        => '⚡ Zap',
							'icon-award'      => '🏆 Award',
							'icon-truck'      => '🚚 Truck',
							'icon-lock'       => '🔒 Lock',
							'icon-thumbs-up'  => '👍 Thumbs Up',
							'icon-smile'      => '😊 Smile',
						),
						'default_value' => 'icon-check',
						'allow_null'    => 0,
						'multiple'      => 0,
						'ui'            => 1,
						'return_format' => 'value',
						'ajax'          => 0,
					),
					array(
						'key'           => 'field_highlight_text',
						'label'         => __( 'Highlight Text', 'starter-theme-1.x' ),
						'name'          => 'text',
						'type'          => 'text',
						'instructions'  => __( 'E.g., "Free Shipping," "30-Day Returns," "Premium Quality"', 'starter-theme-1.x' ),
						'required'      => 1,
						'maxlength'     => 50,
					),
				),
			),

			/**
			 * Product Specifications - Repeater
			 * Display technical specs and product details
			 */
			array(
				'key'               => 'field_product_specs',
				'label'             => __( 'Product Specifications', 'starter-theme-1.x' ),
				'name'              => 'product_specifications',
				'type'              => 'repeater',
				'instructions'      => __( 'Add technical specifications and product details', 'starter-theme-1.x' ),
				'required'          => 0,
				'conditional_logic' => 0,
				'wrapper'           => array(
					'width' => '100',
					'class' => '',
					'id'    => '',
				),
				'collapsed'         => 'field_spec_label',
				'min'               => 0,
				'max'               => 10,
				'layout'            => 'table',
				'button_label'      => __( 'Add Specification', 'starter-theme-1.x' ),
				'sub_fields'        => array(
					array(
						'key'       => 'field_spec_label',
						'label'     => __( 'Specification Label', 'starter-theme-1.x' ),
						'name'      => 'label',
						'type'      => 'text',
						'required'  => 1,
						'maxlength' => 50,
					),
					array(
						'key'       => 'field_spec_value',
						'label'     => __( 'Specification Value', 'starter-theme-1.x' ),
						'name'      => 'value',
						'type'      => 'text',
						'required'  => 1,
						'maxlength' => 100,
					),
				),
			),

			/**
			 * Brand / Manufacturer
			 * Single text field for brand information
			 */
			array(
				'key'               => 'field_product_brand',
				'label'             => __( 'Brand / Manufacturer', 'starter-theme-1.x' ),
				'name'              => 'product_brand',
				'type'              => 'text',
				'instructions'      => __( 'Enter the manufacturer or brand name', 'starter-theme-1.x' ),
				'required'          => 0,
				'maxlength'         => 100,
			),

			/**
			 * Recommended With - Relationship
			 * Link to related/recommended products
			 */
			array(
				'key'               => 'field_recommended_products',
				'label'             => __( 'Recommended Products', 'starter-theme-1.x' ),
				'name'              => 'recommended_products',
				'type'              => 'relationship',
				'instructions'      => __( 'Select products to recommend to customers viewing this product', 'starter-theme-1.x' ),
				'required'          => 0,
				'post_type'         => array( 'product' ),
				'taxonomy'          => array(),
				'filters'           => array( 'search', 'post_type', 'taxonomy' ),
				'elements'          => '',
				'min'               => 0,
				'max'               => 5,
				'return_format'     => 'object',
			),

			/**
			 * Product Materials (optional)
			 * Text field for material composition
			 */
			array(
				'key'               => 'field_product_materials',
				'label'             => __( 'Materials', 'starter-theme-1.x' ),
				'name'              => 'product_materials',
				'type'              => 'text',
				'instructions'      => __( 'E.g., "100% Cotton," "Stainless Steel," "Leather & Fabric"', 'starter-theme-1.x' ),
				'required'          => 0,
				'maxlength'         => 200,
			),

			/**
			 * Warranty Information (optional)
			 * Text field for warranty details
			 */
			array(
				'key'               => 'field_product_warranty',
				'label'             => __( 'Warranty Information', 'starter-theme-1.x' ),
				'name'              => 'product_warranty',
				'type'              => 'textarea',
				'instructions'      => __( 'Describe warranty coverage and duration', 'starter-theme-1.x' ),
				'required'          => 0,
				'rows'              => 3,
				'maxlength'         => 500,
			),

			/**
			 * Care Instructions (optional)
			 * Text field for product care
			 */
			array(
				'key'               => 'field_product_care',
				'label'             => __( 'Care Instructions', 'starter-theme-1.x' ),
				'name'              => 'product_care_instructions',
				'type'              => 'textarea',
				'instructions'      => __( 'Provide care, cleaning, or maintenance instructions', 'starter-theme-1.x' ),
				'required'          => 0,
				'rows'              => 3,
				'maxlength'         => 500,
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'product',
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
		'description'           => __( 'Custom meta fields for WooCommerce products', 'starter-theme-1.x' ),
	) );

}

/**
 * Display Product Highlights in Single Product Page
 * Hook into woocommerce_after_single_product_summary
 */
add_action( 'woocommerce_after_single_product_summary', function() {
	global $product;

	if ( ! $product || ! function_exists( 'get_field' ) ) {
		return;
	}

	$highlights = get_field( 'product_highlights', $product->get_id() );

	if ( ! empty( $highlights ) ) {
		?>
		<div class="product-highlights" style="padding: 30px 0; border-top: 1px solid #e0e0e0; margin-top: 30px;">
			<h3 style="font-size: 18px; font-weight: bold; margin-bottom: 20px;"><?php _e( 'Why Choose This Product?', 'starter-theme-1.x' ); ?></h3>
			<ul class="product-highlights__list" style="list-style: none; padding: 0; margin: 0; display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
				<?php foreach ( $highlights as $highlight ) : ?>
					<li class="product-highlights__item" style="display: flex; gap: 10px; align-items: center;">
						<span class="product-highlights__icon" style="font-size: 20px; color: #007bff;"><?php echo isset( $highlight['icon'] ) ? esc_html( $highlight['icon'] ) : '✓'; ?></span>
						<span class="product-highlights__text" style="font-size: 14px;"><?php echo esc_html( $highlight['text'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
	}
}, 15 );

/**
 * Display Product Specifications
 * Hook into woocommerce_after_single_product_summary
 */
add_action( 'woocommerce_after_single_product_summary', function() {
	global $product;

	if ( ! $product || ! function_exists( 'get_field' ) ) {
		return;
	}

	$specifications = get_field( 'product_specifications', $product->get_id() );

	if ( ! empty( $specifications ) ) {
		?>
		<div class="product-specifications" style="padding: 30px 0;">
			<h3 style="font-size: 18px; font-weight: bold; margin-bottom: 20px;"><?php _e( 'Specifications', 'starter-theme-1.x' ); ?></h3>
			<table class="product-specifications__table" style="width: 100%; border-collapse: collapse;">
				<tbody>
					<?php foreach ( $specifications as $spec ) : ?>
						<tr style="border-bottom: 1px solid #e0e0e0;">
							<td style="padding: 12px; font-weight: 600; width: 30%; background-color: #f9f9f9;"><?php echo esc_html( $spec['label'] ); ?></td>
							<td style="padding: 12px;"><?php echo esc_html( $spec['value'] ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php
	}
}, 20 );
