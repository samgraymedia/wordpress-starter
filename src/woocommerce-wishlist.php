<?php
/**
 * WooCommerce Wishlist Integration
 *
 * Provides complete wishlist functionality:
 * - Add/remove products to wishlist
 * - User wishlist storage (user meta)
 * - AJAX endpoints for frontend
 * - Timber context helpers
 * - Wishlist template rendering
 *
 * @package StarterTheme
 */

/**
 * Initialize Wishlist System
 *
 * Registers AJAX handlers and template hooks
 */
add_action( 'wp_loaded', function() {
	// AJAX endpoints - for logged-in users
	add_action( 'wp_ajax_theme_add_to_wishlist', 'theme_handle_add_to_wishlist' );
	add_action( 'wp_ajax_theme_remove_from_wishlist', 'theme_handle_remove_from_wishlist' );
	add_action( 'wp_ajax_theme_get_wishlist', 'theme_handle_get_wishlist' );
	add_action( 'wp_ajax_theme_get_wishlist_count', 'theme_handle_get_wishlist_count' );

	// AJAX endpoints - for non-logged-in users (session-based)
	add_action( 'wp_ajax_nopriv_theme_add_to_wishlist', 'theme_handle_add_to_wishlist_guest' );
	add_action( 'wp_ajax_nopriv_theme_remove_from_wishlist', 'theme_handle_remove_from_wishlist_guest' );
	add_action( 'wp_ajax_nopriv_theme_get_wishlist', 'theme_handle_get_wishlist_guest' );
	add_action( 'wp_ajax_nopriv_theme_get_wishlist_count', 'theme_handle_get_wishlist_count_guest' );
} );

/**
 * Get user's wishlist items
 *
 * @param integer $user_id User ID (optional, defaults to current user)
 * @return array Array of product IDs in wishlist
 */
function theme_get_wishlist( $user_id = null ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	// For logged-in users, get from user meta
	if ( $user_id ) {
		$wishlist = get_user_meta( $user_id, '_user_wishlist', true );
		return is_array( $wishlist ) ? $wishlist : array();
	}

	// For guests, get from session/cookie
	if ( isset( $_COOKIE['theme_wishlist'] ) ) {
		$wishlist = json_decode( sanitize_text_field( wp_unslash( $_COOKIE['theme_wishlist'] ) ), true );
		return is_array( $wishlist ) ? $wishlist : array();
	}

	return array();
}

/**
 * Add product to wishlist
 *
 * @param integer $product_id Product ID to add
 * @param integer $user_id User ID (optional, defaults to current or guest)
 * @return boolean True if added successfully
 */
function theme_add_to_wishlist( $product_id, $user_id = null ) {
	if ( ! $product_id ) {
		return false;
	}

	$product_id = intval( $product_id );

	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	// For logged-in users
	if ( $user_id ) {
		$wishlist = get_user_meta( $user_id, '_user_wishlist', true );
		$wishlist = is_array( $wishlist ) ? $wishlist : array();

		// Add if not already present
		if ( ! in_array( $product_id, $wishlist, true ) ) {
			$wishlist[] = $product_id;
			update_user_meta( $user_id, '_user_wishlist', $wishlist );
			return true;
		}
		return false;
	}

	// For guests, store in cookie
	$wishlist = theme_get_wishlist();
	if ( ! in_array( $product_id, $wishlist, true ) ) {
		$wishlist[] = $product_id;
		$cookie_value = wp_json_encode( $wishlist );
		setcookie( 'theme_wishlist', $cookie_value, time() + ( 30 * DAY_IN_SECONDS ), '/', '', false, true );
		return true;
	}

	return false;
}

/**
 * Remove product from wishlist
 *
 * @param integer $product_id Product ID to remove
 * @param integer $user_id User ID (optional, defaults to current or guest)
 * @return boolean True if removed successfully
 */
function theme_remove_from_wishlist( $product_id, $user_id = null ) {
	if ( ! $product_id ) {
		return false;
	}

	$product_id = intval( $product_id );

	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	// For logged-in users
	if ( $user_id ) {
		$wishlist = get_user_meta( $user_id, '_user_wishlist', true );
		$wishlist = is_array( $wishlist ) ? $wishlist : array();

		$key = array_search( $product_id, $wishlist, true );
		if ( false !== $key ) {
			unset( $wishlist[ $key ] );
			$wishlist = array_values( $wishlist ); // Reindex
			update_user_meta( $user_id, '_user_wishlist', $wishlist );
			return true;
		}
		return false;
	}

	// For guests, update cookie
	$wishlist = theme_get_wishlist();
	$key = array_search( $product_id, $wishlist, true );
	if ( false !== $key ) {
		unset( $wishlist[ $key ] );
		$wishlist = array_values( $wishlist );
		$cookie_value = wp_json_encode( $wishlist );
		setcookie( 'theme_wishlist', $cookie_value, time() + ( 30 * DAY_IN_SECONDS ), '/', '', false, true );
		return true;
	}

	return false;
}

/**
 * Check if product is in wishlist
 *
 * @param integer $product_id Product ID to check
 * @param integer $user_id User ID (optional, defaults to current or guest)
 * @return boolean True if product is in wishlist
 */
function theme_is_in_wishlist( $product_id, $user_id = null ) {
	$wishlist = theme_get_wishlist( $user_id );
	return in_array( intval( $product_id ), $wishlist, true );
}

/**
 * Get wishlist count
 *
 * @param integer $user_id User ID (optional)
 * @return integer Number of items in wishlist
 */
function theme_get_wishlist_count( $user_id = null ) {
	$wishlist = theme_get_wishlist( $user_id );
	return count( $wishlist );
}

/**
 * Clear entire wishlist
 *
 * @param integer $user_id User ID (optional)
 * @return boolean True if cleared
 */
function theme_clear_wishlist( $user_id = null ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	if ( $user_id ) {
		delete_user_meta( $user_id, '_user_wishlist' );
		return true;
	}

	setcookie( 'theme_wishlist', '', time() - 3600, '/' );
	return true;
}

/**
 * Get wishlist page URL
 *
 * Constructs the URL for the wishlist page
 *
 * @return string Wishlist page URL
 */
function theme_get_wishlist_page_url() {
	$wishlist_slug = apply_filters( 'theme_wishlist_slug', 'wishlist' );
	return home_url( '/' . $wishlist_slug . '/' );
}

/**
 * Get wishlist products for display
 *
 * Returns array of WooCommerce product objects with additional metadata
 *
 * @param integer $user_id User ID (optional)
 * @return array Array of product objects with wishlist data
 */
function theme_get_wishlist_products( $user_id = null ) {
	$product_ids = theme_get_wishlist( $user_id );

	if ( empty( $product_ids ) ) {
		return array();
	}

	$products = array();
	foreach ( $product_ids as $product_id ) {
		$product = wc_get_product( $product_id );
		if ( $product ) {
			$products[] = array(
				'product'     => $product,
				'product_id'  => $product_id,
				'title'       => $product->get_name(),
				'price'       => $product->get_price(),
				'image'       => $product->get_image(),
				'permalink'   => $product->get_permalink(),
				'in_stock'    => $product->is_in_stock(),
				'on_sale'     => $product->is_on_sale(),
				'sale_price'  => $product->get_sale_price(),
			);
		}
	}

	return $products;
}

/**
 * AJAX: Add product to wishlist (logged-in users)
 */
function theme_handle_add_to_wishlist() {
	check_ajax_referer( 'theme_wishlist_nonce', 'nonce' );

	$product_id = intval( sanitize_text_field( isset( $_POST['product_id'] ) ? $_POST['product_id'] : 0 ) );

	if ( ! $product_id ) {
		wp_send_json_error( array( 'message' => __( 'Invalid product ID', 'starter-theme-1.x' ) ) );
	}

	$result = theme_add_to_wishlist( $product_id );

	if ( $result ) {
		wp_send_json_success( array(
			'message' => __( 'Added to wishlist', 'starter-theme-1.x' ),
			'count'   => theme_get_wishlist_count(),
			'url'     => theme_get_wishlist_page_url(),
		) );
	} else {
		wp_send_json_error( array( 'message' => __( 'Already in wishlist', 'starter-theme-1.x' ) ) );
	}
}

/**
 * AJAX: Add product to wishlist (guest users)
 */
function theme_handle_add_to_wishlist_guest() {
	check_ajax_referer( 'theme_wishlist_nonce', 'nonce' );

	$product_id = intval( sanitize_text_field( isset( $_POST['product_id'] ) ? $_POST['product_id'] : 0 ) );

	if ( ! $product_id ) {
		wp_send_json_error( array( 'message' => __( 'Invalid product ID', 'starter-theme-1.x' ) ) );
	}

	$result = theme_add_to_wishlist( $product_id, 0 );

	if ( $result ) {
		wp_send_json_success( array(
			'message' => __( 'Added to wishlist', 'starter-theme-1.x' ),
			'count'   => theme_get_wishlist_count(),
			'url'     => theme_get_wishlist_page_url(),
		) );
	} else {
		wp_send_json_error( array( 'message' => __( 'Already in wishlist', 'starter-theme-1.x' ) ) );
	}
}

/**
 * AJAX: Remove product from wishlist (logged-in users)
 */
function theme_handle_remove_from_wishlist() {
	check_ajax_referer( 'theme_wishlist_nonce', 'nonce' );

	$product_id = intval( sanitize_text_field( isset( $_POST['product_id'] ) ? $_POST['product_id'] : 0 ) );

	if ( ! $product_id ) {
		wp_send_json_error( array( 'message' => __( 'Invalid product ID', 'starter-theme-1.x' ) ) );
	}

	$result = theme_remove_from_wishlist( $product_id );

	if ( $result ) {
		wp_send_json_success( array(
			'message' => __( 'Removed from wishlist', 'starter-theme-1.x' ),
			'count'   => theme_get_wishlist_count(),
		) );
	} else {
		wp_send_json_error( array( 'message' => __( 'Not in wishlist', 'starter-theme-1.x' ) ) );
	}
}

/**
 * AJAX: Remove product from wishlist (guest users)
 */
function theme_handle_remove_from_wishlist_guest() {
	check_ajax_referer( 'theme_wishlist_nonce', 'nonce' );

	$product_id = intval( sanitize_text_field( isset( $_POST['product_id'] ) ? $_POST['product_id'] : 0 ) );

	if ( ! $product_id ) {
		wp_send_json_error( array( 'message' => __( 'Invalid product ID', 'starter-theme-1.x' ) ) );
	}

	$result = theme_remove_from_wishlist( $product_id, 0 );

	if ( $result ) {
		wp_send_json_success( array(
			'message' => __( 'Removed from wishlist', 'starter-theme-1.x' ),
			'count'   => theme_get_wishlist_count(),
		) );
	} else {
		wp_send_json_error( array( 'message' => __( 'Not in wishlist', 'starter-theme-1.x' ) ) );
	}
}

/**
 * AJAX: Get full wishlist (logged-in users)
 */
function theme_handle_get_wishlist() {
	check_ajax_referer( 'theme_wishlist_nonce', 'nonce' );

	$products = theme_get_wishlist_products();

	wp_send_json_success( array(
		'products' => $products,
		'count'    => count( $products ),
	) );
}

/**
 * AJAX: Get full wishlist (guest users)
 */
function theme_handle_get_wishlist_guest() {
	check_ajax_referer( 'theme_wishlist_nonce', 'nonce' );

	$products = theme_get_wishlist_products( 0 );

	wp_send_json_success( array(
		'products' => $products,
		'count'    => count( $products ),
	) );
}

/**
 * AJAX: Get wishlist count (logged-in users)
 */
function theme_handle_get_wishlist_count() {
	check_ajax_referer( 'theme_wishlist_nonce', 'nonce' );

	wp_send_json_success( array(
		'count' => theme_get_wishlist_count(),
	) );
}

/**
 * AJAX: Get wishlist count (guest users)
 */
function theme_handle_get_wishlist_count_guest() {
	check_ajax_referer( 'theme_wishlist_nonce', 'nonce' );

	wp_send_json_success( array(
		'count' => theme_get_wishlist_count( 0 ),
	) );
}

/**
 * Filter: Add wishlist nonce and context to Timber
 *
 * Exposes wishlist functions and nonce to templates
 */
add_filter( 'timber/context', function( $context ) {
	$context['wishlist'] = array(
		'nonce'   => wp_create_nonce( 'theme_wishlist_nonce' ),
		'count'   => theme_get_wishlist_count(),
		'items'   => theme_get_wishlist_products(),
		'is_user' => is_user_logged_in(),
	);

	return $context;
} );

/**
 * Enqueue wishlist JavaScript
 */
add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_script(
		'theme-wishlist',
		get_theme_file_uri( 'scripts/wishlist.js' ),
		array( 'jquery' ),
		filemtime( get_theme_file_path( 'scripts/wishlist.js' ) ),
		true
	);

	wp_localize_script( 'theme-wishlist', 'themeWishlist', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'theme_wishlist_nonce' ),
	) );
} );

/**
 * Register wishlist template
 *
 * Intercepts wishlist page rendering at /wishlist/ URL
 */
add_action( 'template_redirect', function() {
	if ( ! class_exists( 'Timber' ) ) {
		return;
	}

	global $post;

	// Check if this is the wishlist page (via slug or post name)
	$wishlist_slug = apply_filters( 'theme_wishlist_slug', 'wishlist' );
	$is_wishlist_page = ( isset( $post ) && $post->post_name === $wishlist_slug ) || 
	                     ( isset( $_SERVER['REQUEST_URI'] ) && false !== strpos( $_SERVER['REQUEST_URI'], '/' . $wishlist_slug ) );

	if ( $is_wishlist_page ) {
		$context = Timber::context();
		$context['wishlist_products'] = theme_get_wishlist_products();
		$context['wishlist_count'] = theme_get_wishlist_count();
		$context['is_empty'] = empty( $context['wishlist_products'] );

		Timber::render( 'wishlist.twig', $context );
		exit;
	}
}, 20 );
