<?php
/**
 * WooCommerce Integration - Direct Timber Rendering
 * 
 * Intercepts WooCommerce pages and renders Timber templates directly,
 * bypassing WooCommerce's default template system entirely.
 */

add_action( 'template_redirect', function() {
	if ( ! class_exists( 'WooCommerce' ) || ! class_exists( 'Timber' ) ) {
		return;
	}

	// Check if this is a page with basket/cart slug or WooCommerce cart
	global $post;
	$is_cart_page = is_cart() || ( isset( $post ) && $post->post_name === 'basket' );

	if ( $is_cart_page ) {
		$context = Timber::context();
		$context['woocommerce_cart'] = WC()->cart;
		
		// Calculate cart totals for display
		$cart = WC()->cart;
		$subtotal = (float) $cart->get_cart_contents_total();
		$shipping = (float) $cart->get_shipping_total();
		$tax = (float) $cart->get_total_tax();
		$total = $subtotal + $shipping + $tax;
		
		$context['cart_totals'] = array(
			'subtotal' => $subtotal,
			'shipping' => $shipping,
			'tax'      => $tax,
			'total'    => $total
		);
		
		Timber::render( 'cart.twig', $context );
		exit;
	}

	// Checkout page  
	if ( is_checkout() && ! is_order_received_page() ) {
		$context = Timber::context();
		$context['checkout'] = WC()->checkout();
		
		// Calculate cart totals for display
		$cart = WC()->cart;
		$subtotal = (float) $cart->get_cart_contents_total();
		$shipping = (float) $cart->get_shipping_total();
		$tax = (float) $cart->get_total_tax();
		$total = $subtotal + $shipping + $tax;
		
		$context['cart_totals'] = array(
			'subtotal' => $subtotal,
			'shipping' => $shipping,
			'tax'      => $tax,
			'total'    => $total
		);
		
		Timber::render( 'checkout.twig', $context );
		exit;
	}

	// Order received/thank you page
	if ( is_order_received_page() ) {
		$context = Timber::context();
		$order_id = sanitize_text_field( isset( $_GET['order'] ) ? $_GET['order'] : 0 );
		$order = wc_get_order( $order_id );
		
		if ( $order ) {
			$context['order'] = $order;
			$context['order_items'] = $order->get_items();
			$context['order_total'] = $order->get_total();
			$context['order_subtotal'] = $order->get_subtotal();
			$context['order_shipping'] = $order->get_shipping_total();
			$context['order_tax'] = $order->get_tax_total();
		}
		
		Timber::render( 'order-received.twig', $context );
		exit;
	}

	// My Account / Order History
	if ( is_account_page() ) {
		$context = Timber::context();
		$current_user = wp_get_current_user();
		
		if ( $current_user->ID ) {
			// Get customer orders
			$orders = wc_get_customer_orders( array(
				'customer_id' => $current_user->ID,
				'return' => 'objects',
				'limit' => -1,
			) );
			
			$context['customer'] = $current_user;
			$context['customer_orders'] = $orders;
			$context['order_count'] = count( $orders );
		}
		
		Timber::render( 'account.twig', $context );
		exit;
	}

	// Single product
	if ( is_product() ) {
		$context = Timber::context();
		// Get product using WooCommerce's proper API
		$product = wc_get_product( get_the_ID() );
		if ( $product ) {
			$context['product'] = $product;
		}
		Timber::render( 'single-product.twig', $context );
		exit;
	}

	// Shop/archives
	if ( is_product_category() || is_product_tag() || is_shop() ) {
		$context = Timber::context();
		
		// Query products directly using WooCommerce args
		// Do NOT use the global $wp_query, as it contains the shop page itself, not products
		$paged = get_query_var('paged') ? get_query_var('paged') : 1;
		$posts_per_page = get_option('posts_per_page');
		
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => $posts_per_page,
			'paged' => $paged,
			'orderby' => get_query_var('orderby') ? get_query_var('orderby') : 'date',
			'order' => 'DESC',
			'tax_query' => array(),
		);
		
		// Add category filtering
		if ( is_product_category() ) {
			$cat_id = get_queried_object_id();
			if ( $cat_id ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'product_cat',
					'field' => 'term_id',
					'terms' => $cat_id,
				);
			}
		}
		
		// Add tag filtering
		if ( is_product_tag() ) {
			$tag_id = get_queried_object_id();
			if ( $tag_id ) {
				$args['tax_query'][] = array(
					'taxonomy' => 'product_tag',
					'field' => 'term_id',
					'terms' => $tag_id,
				);
			}
		}
		
		$product_query = new WP_Query( $args );
		
		$products = [];
		if ( $product_query->have_posts() ) {
			while ( $product_query->have_posts() ) {
				$product_query->the_post();
				$product = wc_get_product( get_the_ID() );
				if ( $product ) {
					$products[] = $product;
				}
			}
			wp_reset_postdata();
		}
		
		$context['posts'] = $products;
		$context['wp_query'] = $product_query; // For pagination
		
		Timber::render( 'shop.twig', $context );
		exit;
	}
}, 1 );

/**
 * WooCommerce Setup
 */
add_action( 'after_setup_theme', function() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	// Remove default WooCommerce styles
	add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
	
	// Add theme support
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	
	// Register image sizes
	add_image_size( 'woocommerce_thumbnail', 300, 300, true );
	add_image_size( 'woocommerce_single', 600, 600, true );
} );

/**
 * Add WooCommerce to Timber context
 */
add_filter( 'timber/context', function( $context ) {
	if ( ! function_exists( 'WC' ) ) {
		return $context;
	}

	try {
		$context['woocommerce'] = array(
			'cart_url'        => wc_get_cart_url(),
			'checkout_url'    => wc_get_checkout_url(),
			'cart_count'      => WC()->cart ? WC()->cart->get_cart_contents_count() : 0,
			'cart_total'      => WC()->cart ? WC()->cart->get_cart_total() : '',
		);
	} catch ( \Exception $e ) {
		// Silent fail
	}

	return $context;
} );

/**
 * Remove WooCommerce styles
 */
add_action( 'wp_enqueue_scripts', function() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	wp_dequeue_style( 'woocommerce-general' );
	wp_dequeue_style( 'woocommerce-layout' );
	wp_dequeue_style( 'woocommerce-smallscreen' );
	wp_dequeue_style( 'woocommerce_frontend_styles' );
	wp_dequeue_style( 'woocommerce-blockstyles' );
	wp_dequeue_style( 'wc-block-style' );
	wp_dequeue_style( 'wc-blocks-style' );
}, 999 );

/**
 * Remove WooCommerce wrappers
 */
add_action( 'init', function() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
	remove_action( 'wp_footer', 'WC_Structured_Data::output_structured_data', 10 );
}, 20 );

/**
 * Add WooCommerce Twig filters and functions - register early
 */
add_action( 'init', function() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	// Use the timber/twig filter to add functions and filters
	add_filter( 'timber/twig', function( $twig ) {
		// Add price filter
		$twig->addFilter( new Twig\TwigFilter( 'wc_price', function( $price ) {
			return wc_price( $price );
		} ) );

		// Add commonly used WooCommerce functions
		$twig->addFunction( new Twig\TwigFunction( 'wc_get_cart_remove_url', function( $cart_item_key ) {
			return wc_get_cart_remove_url( $cart_item_key );
		} ) );

		$twig->addFunction( new Twig\TwigFunction( 'wc_get_checkout_url', function() {
			return wc_get_checkout_url();
		} ) );

		$twig->addFunction( new Twig\TwigFunction( 'wc_get_cart_url', function() {
			return wc_get_cart_url();
		} ) );

		$twig->addFunction( new Twig\TwigFunction( 'wc_placeholder_img_src', function() {
			return function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src() : '';
		} ) );

		$twig->addFunction( new Twig\TwigFunction( 'wc_get_page_permalink', function( $page = '' ) {
			return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( $page ) : '';
		} ) );

		$twig->addFunction( new Twig\TwigFunction( 'wc_get_product', function( $product_id ) {
			return wc_get_product( $product_id );
		} ) );

		$twig->addFunction( new Twig\TwigFunction( 'wp_nonce_field', function( $action = -1, $name = '_wpnonce', $referer = true, $echo = true ) {
			return wp_nonce_field( $action, $name, $referer, $echo );
		} ) );

		return $twig;
	}, 10 );
}, 5 );

/**
 * AJAX: Update single cart item quantity
 */
add_action( 'wp_ajax_wc_update_cart_quantity', function() {
	check_ajax_referer( 'wc_nonce', 'nonce' );

	$item_key = isset( $_POST['item_key'] ) ? sanitize_text_field( $_POST['item_key'] ) : '';
	$quantity = isset( $_POST['quantity'] ) ? intval( $_POST['quantity'] ) : 0;

	if ( ! $item_key ) {
		wp_send_json_error( array( 'message' => 'Invalid item key' ) );
		return;
	}

	// Update cart
	try {
		WC()->cart->set_quantity( $item_key, $quantity );
		WC()->cart->calculate_totals();

		wp_send_json_success( array(
			'cart_total'     => WC()->cart->get_cart_total(),
			'cart_subtotal'  => WC()->cart->get_subtotal(),
			'item_subtotal'  => WC()->cart->get_cart()->{ $item_key }->get_subtotal(),
		) );
	} catch ( \Exception $e ) {
		wp_send_json_error( array( 'message' => $e->getMessage() ) );
	}
} );

add_action( 'wp_ajax_nopriv_wc_update_cart_quantity', function() {
	check_ajax_referer( 'wc_nonce', 'nonce' );

	$item_key = isset( $_POST['item_key'] ) ? sanitize_text_field( $_POST['item_key'] ) : '';
	$quantity = isset( $_POST['quantity'] ) ? intval( $_POST['quantity'] ) : 0;

	if ( ! $item_key ) {
		wp_send_json_error( array( 'message' => 'Invalid item key' ) );
		return;
	}

	// Update cart
	try {
		WC()->cart->set_quantity( $item_key, $quantity );
		WC()->cart->calculate_totals();

		wp_send_json_success( array(
			'cart_total'    => WC()->cart->get_cart_total(),
			'cart_subtotal' => WC()->cart->get_subtotal(),
		) );
	} catch ( \Exception $e ) {
		wp_send_json_error( array( 'message' => $e->getMessage() ) );
	}
} );

/**
 * AJAX: Update multiple cart items
 */
add_action( 'wp_ajax_wc_update_cart_items', function() {
	check_ajax_referer( 'wc_nonce', 'nonce' );

	$quantities_json = isset( $_POST['quantities'] ) ? sanitize_text_field( $_POST['quantities'] ) : '{}';
	$quantities = json_decode( $quantities_json, true );

	if ( ! is_array( $quantities ) || empty( $quantities ) ) {
		wp_send_json_error( array( 'message' => 'Invalid quantities data' ) );
		return;
	}

	try {
		// Update all quantities
		foreach ( $quantities as $item_key => $quantity ) {
			WC()->cart->set_quantity( sanitize_text_field( $item_key ), intval( $quantity ) );
		}

		WC()->cart->calculate_totals();

		wp_send_json_success( array(
			'cart_total'    => WC()->cart->get_cart_total(),
			'cart_subtotal' => WC()->cart->get_subtotal(),
			'cart_count'    => WC()->cart->get_cart_contents_count(),
		) );
	} catch ( \Exception $e ) {
		wp_send_json_error( array( 'message' => $e->getMessage() ) );
	}
} );

add_action( 'wp_ajax_nopriv_wc_update_cart_items', function() {
	check_ajax_referer( 'wc_nonce', 'nonce' );

	$quantities_json = isset( $_POST['quantities'] ) ? sanitize_text_field( $_POST['quantities'] ) : '{}';
	$quantities = json_decode( $quantities_json, true );

	if ( ! is_array( $quantities ) || empty( $quantities ) ) {
		wp_send_json_error( array( 'message' => 'Invalid quantities data' ) );
		return;
	}

	try {
		// Update all quantities
		foreach ( $quantities as $item_key => $quantity ) {
			WC()->cart->set_quantity( sanitize_text_field( $item_key ), intval( $quantity ) );
		}

		WC()->cart->calculate_totals();

		wp_send_json_success( array(
			'cart_total'    => WC()->cart->get_cart_total(),
			'cart_subtotal' => WC()->cart->get_subtotal(),
			'cart_count'    => WC()->cart->get_cart_contents_count(),
		) );
	} catch ( \Exception $e ) {
		wp_send_json_error( array( 'message' => $e->getMessage() ) );
	}
} );

/**
 * AJAX: Checkout Login (for existing customers)
 */
add_action( 'wp_ajax_nopriv_checkout_login', function() {
	check_ajax_referer( 'wc_checkout_nonce', '_wpnonce' );

	$username = isset( $_POST['username'] ) ? sanitize_text_field( $_POST['username'] ) : '';
	$password = isset( $_POST['password'] ) ? sanitize_text_field( $_POST['password'] ) : '';

	if ( ! $username || ! $password ) {
		wp_send_json_error( array( 'message' => 'Username and password are required' ) );
		return;
	}

	$user = wp_authenticate( $username, $password );

	if ( is_wp_error( $user ) ) {
		wp_send_json_error( array( 'message' => $user->get_error_message() ) );
		return;
	}

	wp_set_current_user( $user->ID );
	wp_set_auth_cookie( $user->ID );
	do_action( 'wp_login', $username, $user );

	wp_send_json_success( array( 'message' => 'Login successful' ) );
} );

/**
 * AJAX: Update shipping method
 */
add_action( 'wp_ajax_update_shipping_method', function() {
	check_ajax_referer( 'wc_checkout_nonce', '_wpnonce' );

	$method = isset( $_POST['method'] ) ? sanitize_text_field( $_POST['method'] ) : '';

	if ( ! $method ) {
		wp_send_json_error( array( 'message' => 'Shipping method is required' ) );
		return;
	}

	try {
		WC()->session->set( 'chosen_shipping_methods', array( $method ) );
		WC()->cart->calculate_totals();

		wp_send_json_success( array(
			'shipping_total' => WC()->cart->get_shipping_total(),
			'cart_total'     => WC()->cart->get_total(),
		) );
	} catch ( \Exception $e ) {
		wp_send_json_error( array( 'message' => $e->getMessage() ) );
	}
} );

add_action( 'wp_ajax_nopriv_update_shipping_method', function() {
	check_ajax_referer( 'wc_checkout_nonce', '_wpnonce' );

	$method = isset( $_POST['method'] ) ? sanitize_text_field( $_POST['method'] ) : '';

	if ( ! $method ) {
		wp_send_json_error( array( 'message' => 'Shipping method is required' ) );
		return;
	}

	try {
		WC()->session->set( 'chosen_shipping_methods', array( $method ) );
		WC()->cart->calculate_totals();

		wp_send_json_success( array(
			'shipping_total' => WC()->cart->get_shipping_total(),
			'cart_total'     => WC()->cart->get_total(),
		) );
	} catch ( \Exception $e ) {
		wp_send_json_error( array( 'message' => $e->getMessage() ) );
	}
} );

/**
 * AJAX: Process checkout
 */
add_action( 'wp_ajax_process_checkout', function() {
	check_ajax_referer( 'wc_checkout_nonce', '_wpnonce' );

	if ( ! isset( $_POST['billing_first_name'] ) || ! $_POST['billing_first_name'] ) {
		wp_send_json_error( array( 'message' => 'First name is required' ) );
		return;
	}

	if ( ! isset( $_POST['billing_email'] ) || ! is_email( $_POST['billing_email'] ) ) {
		wp_send_json_error( array( 'message' => 'Valid email is required' ) );
		return;
	}

	$create_account = isset( $_POST['create_account'] ) && $_POST['create_account'] === 'on';
	$password = isset( $_POST['account_password'] ) ? sanitize_text_field( $_POST['account_password'] ) : '';

	if ( $create_account && strlen( $password ) < 8 ) {
		wp_send_json_error( array( 'message' => 'Password must be at least 8 characters' ) );
		return;
	}

	$billing_data = array(
		'first_name' => sanitize_text_field( $_POST['billing_first_name'] ?? '' ),
		'last_name'  => sanitize_text_field( $_POST['billing_last_name'] ?? '' ),
		'company'    => sanitize_text_field( $_POST['billing_company'] ?? '' ),
		'email'      => sanitize_email( $_POST['billing_email'] ?? '' ),
		'phone'      => sanitize_text_field( $_POST['billing_phone'] ?? '' ),
		'address_1'  => sanitize_text_field( $_POST['billing_address_1'] ?? '' ),
		'address_2'  => sanitize_text_field( $_POST['billing_address_2'] ?? '' ),
		'city'       => sanitize_text_field( $_POST['billing_city'] ?? '' ),
		'state'      => sanitize_text_field( $_POST['billing_state'] ?? '' ),
		'postcode'   => sanitize_text_field( $_POST['billing_postcode'] ?? '' ),
		'country'    => sanitize_text_field( $_POST['billing_country'] ?? '' ),
	);

	if ( $create_account ) {
		$email = $billing_data['email'];
		if ( email_exists( $email ) ) {
			wp_send_json_error( array( 'message' => 'Email already exists' ) );
			return;
		}

		$user_id = wp_create_user( $email, $password, $email );
		if ( is_wp_error( $user_id ) ) {
			wp_send_json_error( array( 'message' => $user_id->get_error_message() ) );
			return;
		}

		wp_set_current_user( $user_id );
		wp_set_auth_cookie( $user_id );
	}

	foreach ( $billing_data as $key => $value ) {
		update_user_meta( get_current_user_id(), "billing_$key", $value );
	}

	try {
		WC()->checkout()->process_checkout();
	} catch ( \Exception $e ) {
		wp_send_json_error( array( 'message' => $e->getMessage() ) );
		return;
	}
} );

add_action( 'wp_ajax_nopriv_process_checkout', function() {
	check_ajax_referer( 'wc_checkout_nonce', '_wpnonce' );

	if ( ! isset( $_POST['billing_first_name'] ) || ! $_POST['billing_first_name'] ) {
		wp_send_json_error( array( 'message' => 'First name is required' ) );
		return;
	}

	if ( ! isset( $_POST['billing_email'] ) || ! is_email( $_POST['billing_email'] ) ) {
		wp_send_json_error( array( 'message' => 'Valid email is required' ) );
		return;
	}

	$create_account = isset( $_POST['create_account'] ) && $_POST['create_account'] === 'on';
	$password = isset( $_POST['account_password'] ) ? sanitize_text_field( $_POST['account_password'] ) : '';

	if ( $create_account && strlen( $password ) < 8 ) {
		wp_send_json_error( array( 'message' => 'Password must be at least 8 characters' ) );
		return;
	}

	$billing_data = array(
		'first_name' => sanitize_text_field( $_POST['billing_first_name'] ?? '' ),
		'last_name'  => sanitize_text_field( $_POST['billing_last_name'] ?? '' ),
		'company'    => sanitize_text_field( $_POST['billing_company'] ?? '' ),
		'email'      => sanitize_email( $_POST['billing_email'] ?? '' ),
		'phone'      => sanitize_text_field( $_POST['billing_phone'] ?? '' ),
		'address_1'  => sanitize_text_field( $_POST['billing_address_1'] ?? '' ),
		'address_2'  => sanitize_text_field( $_POST['billing_address_2'] ?? '' ),
		'city'       => sanitize_text_field( $_POST['billing_city'] ?? '' ),
		'state'      => sanitize_text_field( $_POST['billing_state'] ?? '' ),
		'postcode'   => sanitize_text_field( $_POST['billing_postcode'] ?? '' ),
		'country'    => sanitize_text_field( $_POST['billing_country'] ?? '' ),
	);

	if ( $create_account ) {
		$email = $billing_data['email'];
		if ( email_exists( $email ) ) {
			wp_send_json_error( array( 'message' => 'Email already exists' ) );
			return;
		}

		$user_id = wp_create_user( $email, $password, $email );
		if ( is_wp_error( $user_id ) ) {
			wp_send_json_error( array( 'message' => $user_id->get_error_message() ) );
			return;
		}

		wp_set_current_user( $user_id );
		wp_set_auth_cookie( $user_id );
	}

	foreach ( $billing_data as $key => $value ) {
		update_user_meta( get_current_user_id(), "billing_$key", $value );
	}

	try {
		WC()->checkout()->process_checkout();
	} catch ( \Exception $e ) {
		wp_send_json_error( array( 'message' => $e->getMessage() ) );
		return;
	}
} );

