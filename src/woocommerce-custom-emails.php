<?php
/**
 * Custom WooCommerce Email Templates Integration
 *
 * Handles Timber/Twig rendering for WooCommerce email templates.
 * This file integrates with WooCommerce to use Twig-based email templates
 * instead of traditional PHP templates.
 *
 * @package Starter_Theme
 * @subpackage WooCommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Early exit if WC_Email is not available
if ( ! class_exists( 'WC_Email' ) ) {
	return;
}

/**
 * Custom Email Classes for Non-Standard Emails
 * (Abandoned Cart, Post-Purchase Follow-up)
 *
 * Note: These classes are only loaded via woocommerce_loaded hook
 * when WC_Email and other WooCommerce classes are available.
 */

/**
 * Abandoned Cart Email Class
 */
class Theme_Email_Abandoned_Cart extends WC_Email {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id             = 'abandoned_cart';
		$this->title          = __( 'Abandoned Cart Recovery', 'woocommerce' );
		$this->description    = __( 'Sent to customers who leave items in their cart without completing purchase', 'woocommerce' );
		$this->subject        = __( 'Your Items Are Waiting', 'woocommerce' );
		$this->heading        = __( 'Your Shopping Cart Is Waiting', 'woocommerce' );
		$this->email_type     = 'html';
		$this->template_html  = 'emails/abandoned-cart.php';
		$this->template_plain = 'emails/abandoned-cart-plain.php';

		// Call parent constructor
		parent::__construct();

		// Register settings hook
		add_action( 'woocommerce_update_options_email_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	/**
	 * Generate the HTML email content
	 *
	 * @return string
	 */
	public function get_content_html() {
		ob_start();

		// Load the PHP wrapper that calls Timber
		wc_get_template(
			'emails/abandoned-cart.php',
			array(
				'email'               => $this,
				'email_heading'       => $this->get_heading(),
				'additional_content'  => $this->get_option( 'additional_content' ),
				'customer_name'       => isset( $this->customer_name ) ? $this->customer_name : '',
				'customer_email'      => isset( $this->customer_email ) ? $this->customer_email : '',
				'cart_items'          => isset( $this->cart_items ) ? $this->cart_items : array(),
				'cart_total'          => isset( $this->cart_total ) ? $this->cart_total : '',
				'cart_url'            => isset( $this->cart_url ) ? $this->cart_url : wc_get_page_permalink( 'cart' ),
				'discount_code'       => isset( $this->discount_code ) ? $this->discount_code : '',
				'discount_percent'    => isset( $this->discount_percent ) ? $this->discount_percent : '',
				'site_name'           => get_bloginfo( 'name' ),
				'site_url'            => site_url(),
			),
			'',
			$this->get_template_path()
		);

		return ob_get_clean();
	}

	/**
	 * Trigger the email
	 *
	 * @param string $customer_name Customer name
	 * @param string $customer_email Customer email
	 * @param array  $cart_items Cart items
	 * @param string $cart_total Cart total
	 * @param string $cart_url Cart URL
	 * @param string $discount_code Discount code (optional)
	 * @param string $discount_percent Discount percentage (optional)
	 */
	public function trigger( $customer_name, $customer_email, $cart_items, $cart_total, $cart_url, $discount_code = '', $discount_percent = '' ) {
		if ( ! $customer_email ) {
			return;
		}

		$this->recipient       = $customer_email;
		$this->customer_name   = $customer_name;
		$this->customer_email  = $customer_email;
		$this->cart_items      = $cart_items;
		$this->cart_total      = $cart_total;
		$this->cart_url        = $cart_url;
		$this->discount_code   = $discount_code;
		$this->discount_percent = $discount_percent;

		if ( $this->is_enabled() && $this->get_recipient() ) {
			$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content_html(), $this->get_headers(), $this->get_attachments() );
		}
	}

	/**
	 * Admin options
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'enabled'             => array(
				'title'   => __( 'Enable/Disable', 'woocommerce' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable Abandoned Cart Recovery Email', 'woocommerce' ),
				'default' => 'yes',
			),
			'subject'             => array(
				'title'       => __( 'Subject', 'woocommerce' ),
				'type'        => 'text',
				'description' => sprintf( __( 'Available placeholders: %s', 'woocommerce' ), '<code>{order_date}</code>, <code>{site_title}</code>' ),
				'placeholder' => $this->subject,
				'default'     => $this->subject,
			),
			'heading'             => array(
				'title'       => __( 'Email Heading', 'woocommerce' ),
				'type'        => 'text',
				'description' => sprintf( __( 'This controls the main heading displayed in the email notification', 'woocommerce' ) ),
				'placeholder' => $this->heading,
				'default'     => $this->heading,
			),
			'additional_content'  => array(
				'title'       => __( 'Additional Content', 'woocommerce' ),
				'description' => __( 'Text to appear below the main email content', 'woocommerce' ),
				'css'         => 'width:100%; height: 180px;',
				'placeholder' => __( 'N/A', 'woocommerce' ),
				'type'        => 'textarea',
			),
		);
	}
}

/**
 * Post-Purchase Follow-up Email Class
 */
class Theme_Email_Post_Purchase_Followup extends WC_Email {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->id             = 'post_purchase_followup';
		$this->title          = __( 'Post-Purchase Follow-up', 'woocommerce' );
		$this->description    = __( 'Sent 7-10 days after order delivery for engagement and reviews', 'woocommerce' );
		$this->subject        = __( 'Thank You for Your Purchase!', 'woocommerce' );
		$this->heading        = __( 'Thank You for Your Purchase!', 'woocommerce' );
		$this->email_type     = 'html';
		$this->template_html  = 'emails/post-purchase-followup.php';
		$this->template_plain = 'emails/post-purchase-followup-plain.php';

		// Call parent constructor
		parent::__construct();

		// Register settings hook
		add_action( 'woocommerce_update_options_email_' . $this->id, array( $this, 'process_admin_options' ) );
	}

	/**
	 * Generate the HTML email content
	 *
	 * @return string
	 */
	public function get_content_html() {
		ob_start();

		// Load the PHP wrapper that calls Timber
		wc_get_template(
			'emails/post-purchase-followup.php',
			array(
				'email'          => $this,
				'email_heading'  => $this->get_heading(),
				'additional_content' => $this->get_option( 'additional_content' ),
				'order'          => isset( $this->order ) ? $this->order : null,
				'customer_name'  => isset( $this->customer_name ) ? $this->customer_name : '',
				'order_id'       => isset( $this->order_id ) ? $this->order_id : 0,
				'site_name'      => get_bloginfo( 'name' ),
				'site_url'       => site_url(),
			),
			'',
			$this->get_template_path()
		);

		return ob_get_clean();
	}

	/**
	 * Trigger the email
	 *
	 * @param mixed $order WC_Order object
	 */
	public function trigger( $order ) {
		if ( ! is_a( $order, 'WC_Order' ) ) {
			return;
		}

		$this->order          = $order;
		$this->order_id       = $order->get_id();
		$this->recipient      = $order->get_billing_email();
		$this->customer_name  = $order->get_billing_first_name();

		if ( $this->is_enabled() && $this->get_recipient() ) {
			$this->send( $this->get_recipient(), $this->get_subject(), $this->get_content_html(), $this->get_headers(), $this->get_attachments() );
		}
	}

	/**
	 * Admin options
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'enabled'            => array(
				'title'   => __( 'Enable/Disable', 'woocommerce' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable Post-Purchase Follow-up Email', 'woocommerce' ),
				'default' => 'yes',
			),
			'subject'            => array(
				'title'       => __( 'Subject', 'woocommerce' ),
				'type'        => 'text',
				'description' => sprintf( __( 'Available placeholders: %s', 'woocommerce' ), '<code>{order_date}</code>, <code>{site_title}</code>' ),
				'placeholder' => $this->subject,
				'default'     => $this->subject,
			),
			'heading'            => array(
				'title'       => __( 'Email Heading', 'woocommerce' ),
				'type'        => 'text',
				'description' => sprintf( __( 'This controls the main heading displayed in the email notification', 'woocommerce' ) ),
				'placeholder' => $this->heading,
				'default'     => $this->heading,
			),
			'additional_content' => array(
				'title'       => __( 'Additional Content', 'woocommerce' ),
				'description' => __( 'Text to appear below the main email content', 'woocommerce' ),
				'css'         => 'width:100%; height: 180px;',
				'placeholder' => __( 'N/A', 'woocommerce' ),
				'type'        => 'textarea',
			),
		);
	}
}

/**
 * Register custom email classes
 *
 * @param array $email_classes Registered email classes
 * @return array
 */
function theme_register_custom_emails( $email_classes ) {
	// Only register if WooCommerce is active
	if ( class_exists( 'WC_Email' ) ) {
		$email_classes['abandoned_cart']             = new Theme_Email_Abandoned_Cart();
		$email_classes['post_purchase_followup']     = new Theme_Email_Post_Purchase_Followup();
	}

	return $email_classes;
}
add_filter( 'woocommerce_email_classes', 'theme_register_custom_emails' );

/**
 * Public API: Send abandoned cart email
 *
 * @param string $customer_name Customer name
 * @param string $customer_email Customer email
 * @param array  $cart_items Cart items
 * @param string $cart_total Cart total
 * @param string $cart_url Cart URL
 * @param string $discount_code Discount code (optional)
 * @param string $discount_percent Discount percentage (optional)
 */
function theme_send_abandoned_cart_email( $customer_name, $customer_email, $cart_items, $cart_total, $cart_url, $discount_code = '', $discount_percent = '' ) {
	if ( class_exists( 'Theme_Email_Abandoned_Cart' ) ) {
		$email = new Theme_Email_Abandoned_Cart();
		$email->trigger( $customer_name, $customer_email, $cart_items, $cart_total, $cart_url, $discount_code, $discount_percent );
	}
}

/**
 * Public API: Send post-purchase follow-up email
 *
 * @param WC_Order $order Order object
 */
function theme_send_post_purchase_followup_email( $order ) {
	if ( class_exists( 'Theme_Email_Post_Purchase_Followup' ) ) {
		$email = new Theme_Email_Post_Purchase_Followup();
		$email->trigger( $order );
	}
}
