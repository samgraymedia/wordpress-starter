<?php
/**
 * WooCommerce Product Comparison
 *
 * Provides complete product comparison functionality:
 * - Add/remove products to comparison (max 5 items)
 * - Session/cookie storage for guests, user meta for logged-in
 * - Detailed comparison table with specs
 * - AJAX endpoints
 * - Dedicated comparison page
 *
 * @package StarterTheme
 */

/**
 * Initialize Comparison System
 */
add_action( 'wp_loaded', function() {
	// AJAX endpoints - logged-in users
	add_action( 'wp_ajax_theme_add_to_comparison', 'theme_handle_add_to_comparison' );
	add_action( 'wp_ajax_theme_remove_from_comparison', 'theme_handle_remove_from_comparison' );
	add_action( 'wp_ajax_theme_get_comparison', 'theme_handle_get_comparison' );
	add_action( 'wp_ajax_theme_clear_comparison', 'theme_handle_clear_comparison' );

	// AJAX endpoints - guest users
	add_action( 'wp_ajax_nopriv_theme_add_to_comparison', 'theme_handle_add_to_comparison_guest' );
	add_action( 'wp_ajax_nopriv_theme_remove_from_comparison', 'theme_handle_remove_from_comparison_guest' );
	add_action( 'wp_ajax_nopriv_theme_get_comparison', 'theme_handle_get_comparison_guest' );
	add_action( 'wp_ajax_nopriv_theme_clear_comparison', 'theme_handle_clear_comparison_guest' );
} );

/**
 * Get user's comparison list
 *
 * @param integer $user_id User ID (optional)
 * @return array Array of product IDs
 */
function theme_get_comparison( $user_id = null ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	if ( $user_id ) {
		$comparison = get_user_meta( $user_id, '_user_comparison', true );
		return is_array( $comparison ) ? array_slice( $comparison, 0, 5 ) : array();
	}

	if ( isset( $_COOKIE['theme_comparison'] ) ) {
		$comparison = json_decode( sanitize_text_field( wp_unslash( $_COOKIE['theme_comparison'] ) ), true );
		return is_array( $comparison ) ? array_slice( $comparison, 0, 5 ) : array();
	}

	return array();
}

/**
 * Add product to comparison (max 5 items)
 *
 * @param integer $product_id Product ID
 * @param integer $user_id User ID (optional)
 * @return boolean True if added
 */
function theme_add_to_comparison( $product_id, $user_id = null ) {
	if ( ! $product_id ) {
		return false;
	}

	$product_id = intval( $product_id );

	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	$comparison = theme_get_comparison( $user_id );
	if ( count( $comparison ) >= 5 ) {
		return false;
	}

	if ( $user_id ) {
		if ( ! in_array( $product_id, $comparison, true ) ) {
			$comparison[] = $product_id;
			update_user_meta( $user_id, '_user_comparison', $comparison );
			return true;
		}
		return false;
	}

	if ( ! in_array( $product_id, $comparison, true ) ) {
		$comparison[] = $product_id;
		$cookie_value = wp_json_encode( $comparison );
		setcookie( 'theme_comparison', $cookie_value, time() + ( 30 * DAY_IN_SECONDS ), '/', '', false, true );
		return true;
	}

	return false;
}

/**
 * Remove product from comparison
 *
 * @param integer $product_id Product ID
 * @param integer $user_id User ID (optional)
 * @return boolean True if removed
 */
function theme_remove_from_comparison( $product_id, $user_id = null ) {
	if ( ! $product_id ) {
		return false;
	}

	$product_id = intval( $product_id );

	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	if ( $user_id ) {
		$comparison = theme_get_comparison( $user_id );
		$key = array_search( $product_id, $comparison, true );
		if ( false !== $key ) {
			unset( $comparison[ $key ] );
			$comparison = array_values( $comparison );
			update_user_meta( $user_id, '_user_comparison', $comparison );
			return true;
		}
		return false;
	}

	$comparison = theme_get_comparison();
	$key = array_search( $product_id, $comparison, true );
	if ( false !== $key ) {
		unset( $comparison[ $key ] );
		$comparison = array_values( $comparison );
		$cookie_value = wp_json_encode( $comparison );
		setcookie( 'theme_comparison', $cookie_value, time() + ( 30 * DAY_IN_SECONDS ), '/', '', false, true );
		return true;
	}

	return false;
}

/**
 * Check if product is in comparison
 *
 * @param integer $product_id Product ID
 * @param integer $user_id User ID (optional)
 * @return boolean True if in comparison
 */
function theme_is_in_comparison( $product_id, $user_id = null ) {
	$comparison = theme_get_comparison( $user_id );
	return in_array( intval( $product_id ), $comparison, true );
}

/**
 * Get comparison count
 *
 * @param integer $user_id User ID (optional)
 * @return integer Count of items
 */
function theme_get_comparison_count( $user_id = null ) {
	$comparison = theme_get_comparison( $user_id );
	return count( $comparison );
}

/**
 * Clear comparison list
 *
 * @param integer $user_id User ID (optional)
 * @return boolean True if cleared
 */
function theme_clear_comparison( $user_id = null ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	if ( $user_id ) {
		delete_user_meta( $user_id, '_user_comparison' );
		return true;
	}

	setcookie( 'theme_comparison', '', time() - 3600, '/' );
	return true;
}

/**
 * Get comparison products with full details
 *
 * @param integer $user_id User ID (optional)
 * @return array Array of product data
 */
function theme_get_comparison_products( $user_id = null ) {
	$product_ids = theme_get_comparison( $user_id );

	if ( empty( $product_ids ) ) {
		return array();
	}

	$products = array();
	foreach ( $product_ids as $product_id ) {
		$product = wc_get_product( $product_id );
		if ( $product ) {
			$specs = array();
			if ( function_exists( 'get_field' ) ) {
				$spec_repeater = get_field( 'product_specifications', $product_id );
				if ( is_array( $spec_repeater ) ) {
					foreach ( $spec_repeater as $spec ) {
						if ( isset( $spec['name'] ) && isset( $spec['value'] ) ) {
							$specs[ $spec['name'] ] = $spec['value'];
						}
					}
				}
			}

			$average_rating = $product->get_average_rating();
			$review_count = wc_get_product_review_count( $product_id );

			$products[] = array(
				'product'        => $product,
				'product_id'     => $product_id,
				'title'          => $product->get_name(),
				'price'          => $product->get_price(),
				'regular_price'  => $product->get_regular_price(),
				'sale_price'     => $product->get_sale_price(),
				'image'          => $product->get_image(),
				'permalink'      => $product->get_permalink(),
				'in_stock'       => $product->is_in_stock(),
				'on_sale'        => $product->is_on_sale(),
				'description'    => wp_trim_words( $product->get_short_description(), 20 ),
				'rating'         => $average_rating,
				'review_count'   => $review_count,
				'sku'            => $product->get_sku(),
				'weight'         => $product->get_weight(),
				'brand'          => get_field( 'brand', $product_id ) ?: 'N/A',
				'material'       => get_field( 'material', $product_id ) ?: 'N/A',
				'warranty'       => get_field( 'warranty', $product_id ) ?: 'N/A',
				'specifications' => $specs,
			);
		}
	}

	return $products;
}

/**
 * Get all unique specification keys
 *
 * @param array $product_ids Product IDs
 * @return array Unique spec keys
 */
function theme_get_comparison_spec_keys( $product_ids ) {
	$all_keys = array();

	foreach ( $product_ids as $product_id ) {
		if ( function_exists( 'get_field' ) ) {
			$specs = get_field( 'product_specifications', $product_id );
			if ( is_array( $specs ) ) {
				foreach ( $specs as $spec ) {
					if ( isset( $spec['name'] ) ) {
						$all_keys[] = $spec['name'];
					}
				}
			}
		}
	}

	return array_unique( $all_keys );
}

/**
 * AJAX: Add to comparison (logged-in)
 */
function theme_handle_add_to_comparison() {
	check_ajax_referer( 'theme_comparison_nonce', 'nonce' );

	$product_id = intval( sanitize_text_field( isset( $_POST['product_id'] ) ? $_POST['product_id'] : 0 ) );

	if ( ! $product_id ) {
		wp_send_json_error( array( 'message' => __( 'Invalid product', 'starter-theme-1.x' ) ) );
	}

	if ( ! theme_add_to_comparison( $product_id ) ) {
		wp_send_json_error( array( 'message' => __( 'Comparison full or already added', 'starter-theme-1.x' ) ) );
	}

	wp_send_json_success( array(
		'message' => __( 'Added to comparison', 'starter-theme-1.x' ),
		'count'   => theme_get_comparison_count(),
	) );
}

/**
 * AJAX: Add to comparison (guest)
 */
function theme_handle_add_to_comparison_guest() {
	check_ajax_referer( 'theme_comparison_nonce', 'nonce' );

	$product_id = intval( sanitize_text_field( isset( $_POST['product_id'] ) ? $_POST['product_id'] : 0 ) );

	if ( ! $product_id ) {
		wp_send_json_error( array( 'message' => __( 'Invalid product', 'starter-theme-1.x' ) ) );
	}

	if ( ! theme_add_to_comparison( $product_id, 0 ) ) {
		wp_send_json_error( array( 'message' => __( 'Comparison full or already added', 'starter-theme-1.x' ) ) );
	}

	wp_send_json_success( array(
		'message' => __( 'Added to comparison', 'starter-theme-1.x' ),
		'count'   => theme_get_comparison_count(),
	) );
}

/**
 * AJAX: Remove from comparison (logged-in)
 */
function theme_handle_remove_from_comparison() {
	check_ajax_referer( 'theme_comparison_nonce', 'nonce' );

	$product_id = intval( sanitize_text_field( isset( $_POST['product_id'] ) ? $_POST['product_id'] : 0 ) );

	if ( ! $product_id || ! theme_remove_from_comparison( $product_id ) ) {
		wp_send_json_error( array( 'message' => __( 'Error removing product', 'starter-theme-1.x' ) ) );
	}

	wp_send_json_success( array(
		'message' => __( 'Removed from comparison', 'starter-theme-1.x' ),
		'count'   => theme_get_comparison_count(),
	) );
}

/**
 * AJAX: Remove from comparison (guest)
 */
function theme_handle_remove_from_comparison_guest() {
	check_ajax_referer( 'theme_comparison_nonce', 'nonce' );

	$product_id = intval( sanitize_text_field( isset( $_POST['product_id'] ) ? $_POST['product_id'] : 0 ) );

	if ( ! $product_id || ! theme_remove_from_comparison( $product_id, 0 ) ) {
		wp_send_json_error( array( 'message' => __( 'Error removing product', 'starter-theme-1.x' ) ) );
	}

	wp_send_json_success( array(
		'message' => __( 'Removed from comparison', 'starter-theme-1.x' ),
		'count'   => theme_get_comparison_count(),
	) );
}

/**
 * AJAX: Get comparison (logged-in)
 */
function theme_handle_get_comparison() {
	check_ajax_referer( 'theme_comparison_nonce', 'nonce' );

	$products = theme_get_comparison_products();

	wp_send_json_success( array(
		'products' => $products,
		'count'    => count( $products ),
	) );
}

/**
 * AJAX: Get comparison (guest)
 */
function theme_handle_get_comparison_guest() {
	check_ajax_referer( 'theme_comparison_nonce', 'nonce' );

	$products = theme_get_comparison_products( 0 );

	wp_send_json_success( array(
		'products' => $products,
		'count'    => count( $products ),
	) );
}

/**
 * AJAX: Clear comparison (logged-in)
 */
function theme_handle_clear_comparison() {
	check_ajax_referer( 'theme_comparison_nonce', 'nonce' );
	theme_clear_comparison();
	wp_send_json_success( array( 'message' => __( 'Comparison cleared', 'starter-theme-1.x' ) ) );
}

/**
 * AJAX: Clear comparison (guest)
 */
function theme_handle_clear_comparison_guest() {
	check_ajax_referer( 'theme_comparison_nonce', 'nonce' );
	theme_clear_comparison( 0 );
	wp_send_json_success( array( 'message' => __( 'Comparison cleared', 'starter-theme-1.x' ) ) );
}

/**
 * Add comparison context to Timber
 */
add_filter( 'timber/context', function( $context ) {
	$context['comparison'] = array(
		'nonce'   => wp_create_nonce( 'theme_comparison_nonce' ),
		'count'   => theme_get_comparison_count(),
		'items'   => theme_get_comparison_products(),
		'is_user' => is_user_logged_in(),
	);
	return $context;
} );

/**
 * Enqueue comparison JavaScript
 */
add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_script(
		'theme-comparison',
		get_theme_file_uri( 'scripts/comparison.js' ),
		array( 'jquery' ),
		filemtime( get_theme_file_path( 'scripts/comparison.js' ) ),
		true
	);

	wp_localize_script( 'theme-comparison', 'themeComparison', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'theme_comparison_nonce' ),
	) );
} );

/**
 * Comparison page template
 */
add_action( 'template_redirect', function() {
	if ( ! class_exists( 'Timber' ) ) {
		return;
	}

	global $post;

	$comparison_slug = apply_filters( 'theme_comparison_slug', 'compare' );
	$is_comparison_page = ( isset( $post ) && $post->post_name === $comparison_slug ) || 
	                      ( isset( $_SERVER['REQUEST_URI'] ) && false !== strpos( $_SERVER['REQUEST_URI'], '/' . $comparison_slug ) );

	if ( $is_comparison_page ) {
		$context = Timber::context();
		$context['comparison_products'] = theme_get_comparison_products();
		$context['comparison_count'] = theme_get_comparison_count();
		$context['is_empty'] = empty( $context['comparison_products'] );
		$context['spec_keys'] = theme_get_comparison_spec_keys( array_column( $context['comparison_products'], 'product_id' ) );

		Timber::render( 'comparison.twig', $context );
		exit;
	}
}, 20 );
