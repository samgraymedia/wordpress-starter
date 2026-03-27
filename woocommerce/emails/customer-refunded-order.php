<?php
/**
 * Customer Refunded Order Email
 *
 * This template loads the Twig template for refund notifications.
 * WooCommerce passes variables: $order, $refund, $partial_refund, $email_heading, $additional_content, $sent_to_admin, $plain_text, $email, $blogname
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
	'refund'              => isset( $refund ) ? $refund : null,
	'partial_refund'      => isset( $partial_refund ) ? $partial_refund : false,
	'email_heading'       => $email_heading,
	'additional_content'  => $additional_content,
	'site'                => Timber::get_context()['site'] ?? [],
	'site_url'            => site_url(),
	'wc_get_page_permalink' => function( $page ) {
		return wc_get_page_permalink( $page );
	},
	'bloginfo'            => function( $show = '' ) {
		return get_bloginfo( $show );
	},
);

// Add Timber context filters
$context = apply_filters( 'woocommerce_email_template_context', $context, $email );

// Render the Twig template
echo Timber::compile(
	'email/customer-refunded-order.twig',
	$context
);
