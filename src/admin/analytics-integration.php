<?php
/**
 * Analytics Integration
 *
 * Google Analytics and conversion tracking setup
 *
 * @package Starter_Theme
 * @subpackage Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get analytics settings option key
 *
 * @return string
 */
function theme_get_analytics_key() {
	return 'theme_analytics_settings';
}

/**
 * Get analytics configuration
 *
 * @return array
 */
function theme_get_analytics_config() {
	$settings = get_option( theme_get_analytics_key(), array() );

	return array(
		'google_tracking_id'     => isset( $settings['google_tracking_id'] ) ? sanitize_text_field( $settings['google_tracking_id'] ) : '',
		'google_4_measurement_id' => isset( $settings['google_4_measurement_id'] ) ? sanitize_text_field( $settings['google_4_measurement_id'] ) : '',
		'enable_conversion_tracking' => isset( $settings['enable_conversion_tracking'] ) ? rest_sanitize_boolean( $settings['enable_conversion_tracking'] ) : false,
		'enable_event_tracking' => isset( $settings['enable_event_tracking'] ) ? rest_sanitize_boolean( $settings['enable_event_tracking'] ) : false,
		'conversion_currency' => isset( $settings['conversion_currency'] ) ? sanitize_text_field( $settings['conversion_currency'] ) : 'USD',
	);
}

/**
 * Update analytics configuration
 *
 * @param array $settings Settings to update
 * @return bool
 */
function theme_update_analytics_config( $settings ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return false;
	}

	$current = get_option( theme_get_analytics_key(), array() );

	$updated = array_merge( $current, array(
		'google_tracking_id'     => sanitize_text_field( $settings['google_tracking_id'] ?? '' ),
		'google_4_measurement_id' => sanitize_text_field( $settings['google_4_measurement_id'] ?? '' ),
		'enable_conversion_tracking' => rest_sanitize_boolean( $settings['enable_conversion_tracking'] ?? false ),
		'enable_event_tracking' => rest_sanitize_boolean( $settings['enable_event_tracking'] ?? false ),
		'conversion_currency' => sanitize_text_field( $settings['conversion_currency'] ?? 'USD' ),
	) );

	return update_option( theme_get_analytics_key(), $updated );
}

/**
 * Output Google Analytics script
 * Supports both Universal Analytics (GA) and Google Analytics 4 (GA4)
 *
 * @return void
 */
function theme_output_google_analytics() {
	if ( is_admin() ) {
		return;
	}

	$config = theme_get_analytics_config();

	// Google Analytics 4 (Recommended)
	if ( ! empty( $config['google_4_measurement_id'] ) ) {
		?>
		<!-- Google Analytics 4 -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $config['google_4_measurement_id'] ); ?>"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());
			gtag('config', '<?php echo esc_attr( $config['google_4_measurement_id'] ); ?>', {
				page_path: window.location.pathname,
				page_title: document.title,
			});
		</script>
		<?php
	}
	// Universal Analytics (Legacy)
	elseif ( ! empty( $config['google_tracking_id'] ) ) {
		?>
		<!-- Google Analytics (Universal) -->
		<script async src="https://www.google-analytics.com/analytics.js"></script>
		<script>
			window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
			ga('create', '<?php echo esc_attr( $config['google_tracking_id'] ); ?>', 'auto');
			ga('send', 'pageview');
		</script>
		<?php
	}
}
add_action( 'wp_head', 'theme_output_google_analytics', 99 );

/**
 * Track WooCommerce purchase events
 *
 * @param int $order_id Order ID
 * @return void
 */
function theme_track_woocommerce_purchase( $order_id ) {
	if ( ! function_exists( 'wc_get_order' ) ) {
		return;
	}

	$config = theme_get_analytics_config();
	if ( ! $config['enable_conversion_tracking'] && ! $config['enable_event_tracking'] ) {
		return;
	}

	$order = wc_get_order( $order_id );
	if ( ! $order ) {
		return;
	}

	// Store conversion data in transient for output on order confirmation page
	$conversion_data = array(
		'order_id'    => $order_id,
		'order_total' => floatval( $order->get_total() ),
		'currency'    => $order->get_currency(),
		'items'       => array(),
	);

	foreach ( $order->get_items() as $item ) {
		$conversion_data['items'][] = array(
			'name'      => $item->get_name(),
			'sku'       => $item->get_product()->get_sku(),
			'quantity'  => $item->get_quantity(),
			'price'     => floatval( $item->get_total() ),
		);
	}

	set_transient( 'theme_conversion_' . $order_id, $conversion_data, HOUR_IN_SECONDS );
}
add_action( 'woocommerce_thankyou', 'theme_track_woocommerce_purchase', 10, 1 );

/**
 * Output conversion tracking code
 * Automatically injected on confirmation pages
 *
 * @return void
 */
function theme_output_conversion_tracking() {
	if ( ! function_exists( 'wc_get_order' ) || ! is_order_received_page() ) {
		return;
	}

	$config = theme_get_analytics_config();
	if ( ! $config['enable_conversion_tracking'] ) {
		return;
	}

	// Get order ID from URL
	global $wp_query;
	$order_id = isset( $wp_query->query_vars['order-received'] ) ? absint( $wp_query->query_vars['order-received'] ) : 0;

	if ( ! $order_id ) {
		return;
	}

	$conversion_data = get_transient( 'theme_conversion_' . $order_id );
	if ( ! $conversion_data ) {
		return;
	}

	// Google Analytics 4 conversion
	if ( ! empty( $config['google_4_measurement_id'] ) ) {
		?>
		<script>
		gtag('event', 'purchase', {
			'transaction_id': '<?php echo esc_attr( $conversion_data['order_id'] ); ?>',
			'affiliation': '<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>',
			'value': <?php echo floatval( $conversion_data['order_total'] ); ?>,
			'currency': '<?php echo esc_attr( $conversion_data['currency'] ); ?>',
			'items': [
				<?php
				foreach ( $conversion_data['items'] as $item ) {
					?>
					{
						'item_id': '<?php echo esc_attr( $item['sku'] ); ?>',
						'item_name': '<?php echo esc_attr( $item['name'] ); ?>',
						'quantity': <?php echo intval( $item['quantity'] ); ?>,
						'price': <?php echo floatval( $item['price'] ); ?>,
					},
					<?php
				}
				?>
			]
		});
		</script>
		<?php
	}

	// Delete transient after using
	delete_transient( 'theme_conversion_' . $order_id );
}
add_action( 'wp_head', 'theme_output_conversion_tracking', 100 );

/**
 * Track product view events
 *
 * @return void
 */
function theme_track_product_view_event() {
	if ( ! is_singular( 'product' ) ) {
		return;
	}

	$config = theme_get_analytics_config();
	if ( ! $config['enable_event_tracking'] || empty( $config['google_4_measurement_id'] ) ) {
		return;
	}

	$product = wc_get_product( get_the_ID() );
	if ( ! $product ) {
		return;
	}

	?>
	<script>
	gtag('event', 'view_item', {
		'items': [{
			'item_id': '<?php echo esc_attr( $product->get_sku() ); ?>',
			'item_name': '<?php echo esc_attr( $product->get_title() ); ?>',
			'item_category': '<?php echo esc_attr( implode( ',', wp_get_post_terms( $product->get_id(), 'product_cat', array( 'fields' => 'names' ) ) ) ); ?>',
			'price': <?php echo floatval( $product->get_price() ); ?>,
			'currency': '<?php echo esc_attr( get_woocommerce_currency() ); ?>'
		}]
	});
	</script>
	<?php
}
add_action( 'wp_footer', 'theme_track_product_view_event', 99 );

/**
 * AJAX endpoint to track ad-hoc events
 *
 * @return void
 */
function theme_track_custom_event() {
	check_ajax_referer( 'theme-analytics-nonce' );

	$event_name = sanitize_text_field( $_POST['event_name'] ?? '' );
	$event_data = isset( $_POST['event_data'] ) ? wp_kses_post_deep( wp_unslash( $_POST['event_data'] ) ) : array();

	if ( empty( $event_name ) ) {
		wp_send_json_error( array( 'message' => __( 'Event name required', 'starter-theme-1.x' ) ) );
	}

	// Log event for later analysis
	$events_log = get_option( 'theme_custom_events_log', array() );
	$events_log[] = array(
		'event_name' => $event_name,
		'event_data' => $event_data,
		'timestamp'  => current_time( 'mysql', true ),
		'user_id'    => get_current_user_id(),
	);

	// Keep only last 1000 events
	if ( count( $events_log ) > 1000 ) {
		$events_log = array_slice( $events_log, -1000 );
	}

	update_option( 'theme_custom_events_log', $events_log );

	wp_send_json_success( array( 'message' => __( 'Event tracked', 'starter-theme-1.x' ) ) );
}
add_action( 'wp_ajax_nopriv_theme_track_custom_event', 'theme_track_custom_event' );
add_action( 'wp_ajax_theme_track_custom_event', 'theme_track_custom_event' );

/**
 * Register analytics settings
 *
 * @return void
 */
function theme_register_analytics_settings() {
	register_setting( 'theme_analytics_group', theme_get_analytics_key(), array(
		'type'              => 'object',
		'sanitize_callback' => function( $input ) {
			if ( ! is_array( $input ) ) {
				$input = array();
			}
			return array(
				'google_tracking_id'     => sanitize_text_field( $input['google_tracking_id'] ?? '' ),
				'google_4_measurement_id' => sanitize_text_field( $input['google_4_measurement_id'] ?? '' ),
				'enable_conversion_tracking' => rest_sanitize_boolean( $input['enable_conversion_tracking'] ?? false ),
				'enable_event_tracking' => rest_sanitize_boolean( $input['enable_event_tracking'] ?? false ),
				'conversion_currency' => sanitize_text_field( $input['conversion_currency'] ?? 'USD' ),
			);
		},
	) );
}
add_action( 'admin_init', 'theme_register_analytics_settings' );

/**
 * Add analytics settings page
 *
 * @return void
 */
function theme_add_analytics_settings_page() {
	add_submenu_page(
		'theme-dashboard',
		__( 'Analytics Settings', 'starter-theme-1.x' ),
		__( 'Analytics', 'starter-theme-1.x' ),
		'manage_options',
		'theme-analytics',
		'theme_render_analytics_settings_page'
	);
}
add_action( 'admin_menu', 'theme_add_analytics_settings_page', 15 );

/**
 * Render analytics settings page
 *
 * @return void
 */
function theme_render_analytics_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Unauthorized', 'starter-theme-1.x' ) );
	}

	$config = theme_get_analytics_config();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Analytics Settings', 'starter-theme-1.x' ); ?></h1>

		<form method="post" action="options.php">
			<?php settings_fields( 'theme_analytics_group' ); ?>

			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="google_4_measurement_id"><?php esc_html_e( 'Google Analytics 4 Measurement ID', 'starter-theme-1.x' ); ?></label>
					</th>
					<td>
						<input 
							type="text" 
							id="google_4_measurement_id" 
							name="<?php echo esc_attr( theme_get_analytics_key() ); ?>[google_4_measurement_id]"
							value="<?php echo esc_attr( $config['google_4_measurement_id'] ); ?>"
							placeholder="G-XXXXXXXXXX"
							class="regular-text"
						>
						<p class="description"><?php esc_html_e( 'Find in Google Analytics > Admin > Data Streams > Measurement ID', 'starter-theme-1.x' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="google_tracking_id"><?php esc_html_e( 'Google Analytics (Universal) Tracking ID', 'starter-theme-1.x' ); ?></label>
					</th>
					<td>
						<input 
							type="text" 
							id="google_tracking_id" 
							name="<?php echo esc_attr( theme_get_analytics_key() ); ?>[google_tracking_id]"
							value="<?php echo esc_attr( $config['google_tracking_id'] ); ?>"
							placeholder="UA-XXXXXXXXX-X"
							class="regular-text"
						>
						<p class="description"><?php esc_html_e( 'Legacy Google Analytics (deprecated but still supported)', 'starter-theme-1.x' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="enable_conversion_tracking"><?php esc_html_e( 'Enable Conversion Tracking', 'starter-theme-1.x' ); ?></label>
					</th>
					<td>
						<input 
							type="checkbox" 
							id="enable_conversion_tracking" 
							name="<?php echo esc_attr( theme_get_analytics_key() ); ?>[enable_conversion_tracking]"
							value="1"
							<?php checked( $config['enable_conversion_tracking'] ); ?>
						>
						<p class="description"><?php esc_html_e( 'Track WooCommerce purchases as conversions', 'starter-theme-1.x' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="enable_event_tracking"><?php esc_html_e( 'Enable Event Tracking', 'starter-theme-1.x' ); ?></label>
					</th>
					<td>
						<input 
							type="checkbox" 
							id="enable_event_tracking" 
							name="<?php echo esc_attr( theme_get_analytics_key() ); ?>[enable_event_tracking]"
							value="1"
							<?php checked( $config['enable_event_tracking'] ); ?>
						>
						<p class="description"><?php esc_html_e( 'Track product views and other custom events', 'starter-theme-1.x' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="conversion_currency"><?php esc_html_e( 'Conversion Currency', 'starter-theme-1.x' ); ?></label>
					</th>
					<td>
						<input 
							type="text" 
							id="conversion_currency" 
							name="<?php echo esc_attr( theme_get_analytics_key() ); ?>[conversion_currency]"
							value="<?php echo esc_attr( $config['conversion_currency'] ); ?>"
							placeholder="USD"
							class="regular-text"
									maxlength="3"
						>
						<p class="description"><?php esc_html_e( '3-letter currency code (USD, EUR, GBP, etc.)', 'starter-theme-1.x' ); ?></p>
					</td>
				</tr>
			</table>

			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
