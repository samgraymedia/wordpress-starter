<?php
/**
 * Customer New Account Email
 *
 * This template loads the Twig template for new customer account creation notifications.
 * WooCommerce passes variables: $user_login, $user_email, $blogname, $additional_content, $email
 *
 * @package WooCommerce/Templates/Emails
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Timber' ) ) {
	return;  // Fallback to default if Timber not available
}

use Timber\Timber;

// Get customer info
$customer_user = get_user_by( 'login', isset( $user_login ) ? $user_login : '' );
$customer_name = $customer_user ? $customer_user->first_name ?: $customer_user->display_name : __( 'Valued Customer' );
$customer_email = isset( $user_email ) ? $user_email : ( $customer_user ? $customer_user->user_email : '' );

// Prepare context for Twig template
$context = array(
	'customer_name'      => $customer_name,
	'customer_email'     => $customer_email,
	'email_heading'      => __( 'Welcome to ' ) . get_bloginfo( 'name' ),
	'additional_content' => isset( $additional_content ) ? $additional_content : '',
	'site'               => Timber::get_context()['site'] ?? [],
	'site_name'          => get_bloginfo( 'name' ),
	'site_url'           => site_url(),
	'bloginfo'           => function( $show = '' ) {
		return get_bloginfo( $show );
	},
);

// Add Timber context filters
$context = apply_filters( 'woocommerce_email_template_context', $context, isset( $email ) ? $email : null );

// Render the Twig template
echo Timber::compile(
	'email/customer-new-account.twig',
	$context
);
