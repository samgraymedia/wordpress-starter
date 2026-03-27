<?php
/**
 * Bulk Product Editing
 *
 * Enable bulk editing of WooCommerce products in admin
 *
 * @package Starter_Theme
 * @subpackage Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add bulk edit action for products
 *
 * @param array $bulk_actions Current bulk actions
 * @return array
 */
function theme_add_product_bulk_actions( $bulk_actions ) {
	if ( ! function_exists( 'wc_get_product' ) ) {
		return $bulk_actions;
	}

	$bulk_actions['theme_bulk_edit_products'] = __( 'Bulk Edit Products', 'starter-theme-1.x' );
	return $bulk_actions;
}
add_filter( 'bulk_actions-edit-product', 'theme_add_product_bulk_actions' );

/**
 * Handle bulk product edit action
 *
 * @return void
 */
function theme_handle_product_bulk_edit() {
	// Only run on products page
	global $pagenow;
	if ( 'edit.php' !== $pagenow || 'product' !== ( $_GET['post_type'] ?? '' ) ) {
		return;
	}

	// Check for bulk edit action
	if ( empty( $_REQUEST['action'] ) || 'theme_bulk_edit_products' !== $_REQUEST['action'] ) {
		return;
	}

	check_admin_referer( 'bulk-posts' );

	// Get selected product IDs
	$post_ids = isset( $_POST['post'] ) ? array_map( 'absint', $_POST['post'] ) : array();
	if ( empty( $post_ids ) ) {
		return;
	}

	// Get product category and tag from request
	$category = isset( $_POST['bulk_category'] ) ? absint( $_POST['bulk_category'] ) : 0;
	$tags = isset( $_POST['bulk_tags'] ) ? explode( ',', sanitize_text_field( $_POST['bulk_tags'] ) ) : array();
	$price_change = isset( $_POST['bulk_price_change'] ) ? floatval( $_POST['bulk_price_change'] ) : 0;
	$action_type = sanitize_text_field( $_POST['bulk_action_type'] ?? 'none' );

	// Apply changes to each product
	foreach ( $post_ids as $product_id ) {
		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			continue;
		}

		// Apply category
		if ( $category > 0 ) {
			wp_set_post_terms( $product_id, array( $category ), 'product_cat' );
		}

		// Apply tags
		if ( ! empty( $tags ) ) {
			wp_set_post_terms( $product_id, $tags, 'product_tag' );
		}

		// Adjust price
		if ( $price_change !== 0 ) {
			if ( 'percentage' === $action_type ) {
				// Percentage change
				$current_price = floatval( $product->get_price() );
				$new_price = $current_price + ( $current_price * ( $price_change / 100 ) );
			} else {
				// Fixed amount change
				$current_price = floatval( $product->get_price() );
				$new_price = $current_price + $price_change;
			}

			$product->set_price( $new_price );
			$product->save();
		}
	}

	// Redirect back with success message
	wp_safe_remote_post( admin_url( 'edit.php?post_type=product&bulk_message=1' ), array(
		'blocking'  => false,
		'sslverify' => apply_filters( 'https_local_ssl_verify', false ),
	) );
}
add_action( 'admin_init', 'theme_handle_product_bulk_edit' );

/**
 * Add bulk edit form to product list
 *
 * @return void
 */
function theme_add_product_bulk_edit_form() {
	global $pagenow;
	if ( 'edit.php' !== $pagenow || 'product' !== ( $_GET['post_type'] ?? '' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_products' ) ) {
		return;
	}

	// Get all product categories and tags for dropdown
	$categories = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
	) );

	$tags = get_terms( array(
		'taxonomy'   => 'product_tag',
		'hide_empty' => false,
	) );
	?>
	<style>
		.theme-bulk-edit-section {
			display: none;
			background: #f5f5f5;
			padding: 15px;
			margin: 15px 0;
			border-left: 4px solid #0073aa;
		}

		.theme-bulk-edit-section.show {
			display: block;
		}

		.theme-bulk-edit-row {
			margin-bottom: 15px;
		}

		.theme-bulk-edit-row label {
			display: inline-block;
			width: 150px;
			font-weight: bold;
		}

		.theme-bulk-edit-row input,
		.theme-bulk-edit-row select {
			min-width: 200px;
		}
	</style>

	<script>
		jQuery(document).ready(function($) {
			$('select[name="action"]').on('change', function() {
				var $form = $(this).closest('form');
				var action = $(this).val();

				if (action === 'theme_bulk_edit_products') {
					$form.find('.theme-bulk-edit-section').addClass('show');
				} else {
					$form.find('.theme-bulk-edit-section').removeClass('show');
				}
			});
		});
	</script>

	<div class="theme-bulk-edit-section">
		<h3><?php esc_html_e( 'Bulk Product Edit Options', 'starter-theme-1.x' ); ?></h3>

		<div class="theme-bulk-edit-row">
			<label for="bulk_category"><?php esc_html_e( 'Category:', 'starter-theme-1.x' ); ?></label>
			<select id="bulk_category" name="bulk_category">
				<option value="0"><?php esc_html_e( '— No Change —', 'starter-theme-1.x' ); ?></option>
				<?php foreach ( $categories as $category ) : ?>
					<option value="<?php echo esc_attr( $category->term_id ); ?>">
						<?php echo esc_html( $category->name ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>

		<div class="theme-bulk-edit-row">
			<label for="bulk_tags"><?php esc_html_e( 'Tags (comma-separated):', 'starter-theme-1.x' ); ?></label>
			<textarea id="bulk_tags" name="bulk_tags" placeholder="tag1, tag2, tag3"></textarea>
		</div>

		<div class="theme-bulk-edit-row">
			<label for="bulk_action_type"><?php esc_html_e( 'Price Adjustment Type:', 'starter-theme-1.x' ); ?></label>
			<select id="bulk_action_type" name="bulk_action_type">
				<option value="none"><?php esc_html_e( 'No Change', 'starter-theme-1.x' ); ?></option>
				<option value="fixed"><?php esc_html_e( 'Fixed Amount', 'starter-theme-1.x' ); ?></option>
				<option value="percentage"><?php esc_html_e( 'Percentage', 'starter-theme-1.x' ); ?></option>
			</select>
		</div>

		<div class="theme-bulk-edit-row">
			<label for="bulk_price_change"><?php esc_html_e( 'Price Change:', 'starter-theme-1.x' ); ?></label>
			<input 
				type="number" 
				id="bulk_price_change" 
				name="bulk_price_change" 
				step="0.01"
				placeholder="0.00"
			>
			<span class="description"><?php esc_html_e( '(positive for increase, negative for decrease)', 'starter-theme-1.x' ); ?></span>
		</div>
	</div>
	<?php
}
add_action( 'manage_posts_extra_tablenav', 'theme_add_product_bulk_edit_form' );

/**
 * Add quick edit support for products
 *
 * @return void
 */
function theme_add_quick_edit_product() {
	if ( ! current_user_can( 'edit_products' ) ) {
		return;
	}

	$categories = get_terms( array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
	) );
	?>
	<fieldset class="inline-edit-col-right">
		<div class="inline-edit-col">
			<h4><?php esc_html_e( 'Product Fields', 'starter-theme-1.x' ); ?></h4>

			<div class="inline-edit-group">
				<label for="inline_product_category"><?php esc_html_e( 'Category:', 'starter-theme-1.x' ); ?></label>
				<select id="inline_product_category" name="product_category" class="inline-edit-categories">
					<option value=""><?php esc_html_e( '— Select Category —', 'starter-theme-1.x' ); ?></option>
					<?php foreach ( $categories as $category ) : ?>
						<option value="<?php echo esc_attr( $category->term_id ); ?>">
							<?php echo esc_html( $category->name ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="inline-edit-group">
				<label for="inline_product_price"><?php esc_html_e( 'Price:', 'starter-theme-1.x' ); ?></label>
				<input 
					type="number" 
					id="inline_product_price" 
					name="product_price" 
					step="0.01"
					class="inline-edit-price"
				>
			</div>

			<div class="inline-edit-group">
				<label for="inline_product_status"><?php esc_html_e( 'Status:', 'starter-theme-1.x' ); ?></label>
				<select id="inline_product_status" name="product_status">
					<option value=""><?php esc_html_e( '— No Change —', 'starter-theme-1.x' ); ?></option>
					<option value="publish"><?php esc_html_e( 'Published', 'starter-theme-1.x' ); ?></option>
					<option value="draft"><?php esc_html_e( 'Draft', 'starter-theme-1.x' ); ?></option>
					<option value="pending"><?php esc_html_e( 'Pending', 'starter-theme-1.x' ); ?></option>
				</select>
			</div>
		</div>
	</fieldset>
	<?php
}
add_action( 'quick_edit_custom_box', 'theme_add_quick_edit_product', 10, 2 );

/**
 * Save quick edit changes via AJAX
 *
 * @return void
 */
function theme_save_quick_edit_product() {
	check_ajax_referer( 'inline-nonce' );

	if ( ! current_user_can( 'edit_products' ) ) {
		wp_send_json_error( array( 'message' => __( 'Insufficient permissions', 'starter-theme-1.x' ) ) );
	}

	$product_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
	if ( ! $product_id ) {
		wp_send_json_error( array( 'message' => __( 'Invalid product ID', 'starter-theme-1.x' ) ) );
	}

	$product = wc_get_product( $product_id );
	if ( ! $product ) {
		wp_send_json_error( array( 'message' => __( 'Product not found', 'starter-theme-1.x' ) ) );
	}

	// Update category
	if ( isset( $_POST['product_category'] ) && ! empty( $_POST['product_category'] ) ) {
		wp_set_post_terms( $product_id, absint( $_POST['product_category'] ), 'product_cat' );
	}

	// Update price
	if ( isset( $_POST['product_price'] ) && ! empty( $_POST['product_price'] ) ) {
		$product->set_price( floatval( $_POST['product_price'] ) );
	}

	// Update status
	if ( isset( $_POST['product_status'] ) && ! empty( $_POST['product_status'] ) ) {
		$post_status = sanitize_text_field( $_POST['product_status'] );
		$product->set_status( $post_status === 'publish' ? 'publish' : 'draft' );
	}

	$product->save();

	wp_send_json_success( array( 'message' => __( 'Product updated', 'starter-theme-1.x' ) ) );
}
add_action( 'wp_ajax_theme_save_quick_edit_product', 'theme_save_quick_edit_product' );
