<?php
/**
 * Admin New Order Email
 *
 * This template loads the Twig template for admin new order notifications.
 * WooCommerce passes variables: $order, $email_heading, $additional_content, $sent_to_admin, $plain_text, $email
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

// Prepare context for Twig template
$context = array(
	'order'               => $order,
	'email_heading'       => $email_heading,
	'additional_content'  => $additional_content,
	'site'                => Timber::get_context()['site'] ?? [],
	'admin_url'           => admin_url(),
	'site_url'            => site_url(),
	'bloginfo'            => function( $show = '' ) {
		return get_bloginfo( $show );
	},
);

// Add Timber context filters
$context = apply_filters( 'woocommerce_email_template_context', $context, $email );

// Render the Twig template
echo Timber::compile(
	'email/admin-new-order.twig',
	$context
);
