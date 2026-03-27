<?php
/**
 * Template Name: My Account
 * Template Post Type: page
 *
 * Custom account page for logged-in users
 * Displays order history, wishlist, and account settings
 *
 * @package StarterTheme
 */

// Redirect non-logged-in users to login page
if ( ! is_user_logged_in() ) {
	wp_redirect( home_url( '/login' ) );
	exit;
}

// Get the Timber context
$context = Timber::context();

// Get current user
$current_user = wp_get_current_user();

$context['customer'] = array(
	'id'           => $current_user->ID,
	'first_name'   => $current_user->first_name,
	'last_name'    => $current_user->last_name,
	'display_name' => $current_user->display_name,
	'email'        => $current_user->user_email,
);

// Get order data if WooCommerce is active
if ( class_exists( 'WooCommerce' ) ) {
	$customer = new WC_Customer( get_current_user_id() );
	$orders = wc_get_orders( array(
		'customer_id' => get_current_user_id(),
		'limit'       => 10,
		'orderby'     => 'date',
		'order'       => 'DESC',
	) );

	$order_data = array();
	foreach ( $orders as $order ) {
		$order_data[] = array(
			'id'         => $order->get_id(),
			'date'       => $order->get_date_created()->date( 'F d, Y' ),
			'status'     => $order->get_status(),
			'total'      => wc_price( $order->get_total(), array( 'echo' => false ) ),
			'item_count' => $order->get_item_count(),
			'url'        => $order->get_view_order_url(),
		);
	}

	$context['orders']      = $order_data;
	$context['order_count'] = count( $orders );
	$context['is_woocommerce_active'] = true;
} else {
	$context['orders']      = array();
	$context['order_count'] = 0;
	$context['is_woocommerce_active'] = false;
}

// Get wishlist data if available
if ( function_exists( 'theme_get_wishlist_count' ) ) {
	$context['wishlist_count'] = theme_get_wishlist_count();
	$context['wishlist_url']   = home_url( '/wishlist' );
}

// Get comparison data if available
if ( function_exists( 'theme_get_comparison' ) ) {
	$comparison = theme_get_comparison();
	$context['comparison_count'] = count( $comparison );
	$context['comparison_url']   = home_url( '/comparison' );
}

// Render the template
Timber::render( 'account.twig', $context );
