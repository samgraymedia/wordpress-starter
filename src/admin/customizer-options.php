<?php
/**
 * Theme Customizer Options Setup
 *
 * Configures WordPress theme customization panels and controls
 * for site-wide styling and layout options.
 *
 * @package Starter_Theme
 * @subpackage Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register customizer panels, sections, and controls
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance
 * @return void
 */
function theme_customize_register( $wp_customize ) {
	// ===== BRANDING PANEL =====
	$wp_customize->add_panel( 'theme_branding', array(
		'title'       => __( 'Theme Branding', 'starter-theme-1.x' ),
		'description' => __( 'Customize site logo, colors, and brand settings', 'starter-theme-1.x' ),
		'priority'    => 30,
	) );

	// Section: Logo & Site Identity
	$wp_customize->add_section( 'theme_logo_section', array(
		'title'       => __( 'Logo & Branding', 'starter-theme-1.x' ),
		'panel'       => 'theme_branding',
		'priority'    => 10,
	) );

	// Logo
	$wp_customize->add_setting( 'theme_logo_image', array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'transport'         => 'refresh',
		'sanitize_callback' => 'absint',
	) );

	$wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, 'theme_logo_image_control', array(
		'label'       => __( 'Logo', 'starter-theme-1.x' ),
		'section'     => 'theme_logo_section',
		'settings'    => 'theme_logo_image',
		'flex_width'  => true,
		'flex_height' => true,
		'width'       => 300,
		'height'      => 100,
	) ) );

	// Logo Width
	$wp_customize->add_setting( 'theme_logo_width', array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'absint',
		'default'           => 200,
	) );

	$wp_customize->add_control( 'theme_logo_width_control', array(
		'label'       => __( 'Logo Width (px)', 'starter-theme-1.x' ),
		'section'     => 'theme_logo_section',
		'settings'    => 'theme_logo_width',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 50,
			'max'  => 500,
			'step' => 10,
		),
	) );

	// Tagline Display
	$wp_customize->add_setting( 'theme_show_tagline', array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'transport'         => 'refresh',
		'sanitize_callback' => 'rest_sanitize_boolean',
		'default'           => true,
	) );

	$wp_customize->add_control( 'theme_show_tagline_control', array(
		'label'    => __( 'Display Site Tagline', 'starter-theme-1.x' ),
		'section'  => 'theme_logo_section',
		'settings' => 'theme_show_tagline',
		'type'     => 'checkbox',
	) );

	// ===== COLORS PANEL =====
	$wp_customize->add_panel( 'theme_colors', array(
		'title'       => __( 'Colors', 'starter-theme-1.x' ),
		'description' => __( 'Customize color scheme for the entire site', 'starter-theme-1.x' ),
		'priority'    => 35,
	) );

	// Section: Primary Color
	$wp_customize->add_section( 'theme_primary_color_section', array(
		'title'       => __( 'Primary Colors', 'starter-theme-1.x' ),
		'panel'       => 'theme_colors',
		'priority'    => 10,
	) );

	// Primary Color
	$wp_customize->add_setting( 'theme_primary_color', array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
		'default'           => '#0066cc',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_primary_color_control', array(
		'label'    => __( 'Primary Color', 'starter-theme-1.x' ),
		'section'  => 'theme_primary_color_section',
		'settings' => 'theme_primary_color',
	) ) );

	// Secondary Color
	$wp_customize->add_setting( 'theme_secondary_color', array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'sanitize_hex_color',
		'default'           => '#ff6600',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_secondary_color_control', array(
		'label'    => __( 'Secondary Color', 'starter-theme-1.x' ),
		'section'  => 'theme_primary_color_section',
		'settings' => 'theme_secondary_color',
	) ) );

	// ===== LAYOUT PANEL =====
	$wp_customize->add_panel( 'theme_layout', array(
		'title'       => __( 'Layout & Spacing', 'starter-theme-1.x' ),
		'description' => __( 'Configure site layout and spacing', 'starter-theme-1.x' ),
		'priority'    => 40,
	) );

	// Section: Header Layout
	$wp_customize->add_section( 'theme_header_layout_section', array(
		'title'       => __( 'Header Layout', 'starter-theme-1.x' ),
		'panel'       => 'theme_layout',
		'priority'    => 10,
	) );

	// Header Style
	$wp_customize->add_setting( 'theme_header_style', array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'transport'         => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => 'standard',
	) );

	$wp_customize->add_control( 'theme_header_style_control', array(
		'label'    => __( 'Header Style', 'starter-theme-1.x' ),
		'section'  => 'theme_header_layout_section',
		'settings' => 'theme_header_style',
		'type'     => 'radio',
		'choices'  => array(
			'standard' => __( 'Standard (Logo + Menu)', 'starter-theme-1.x' ),
			'minimal'  => __( 'Minimal (Menu Only)', 'starter-theme-1.x' ),
			'centered' => __( 'Centered (Logo Centered, Menu Below)', 'starter-theme-1.x' ),
		),
	) );

	// Sticky Header
	$wp_customize->add_setting( 'theme_sticky_header', array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'transport'         => 'refresh',
		'sanitize_callback' => 'rest_sanitize_boolean',
		'default'           => true,
	) );

	$wp_customize->add_control( 'theme_sticky_header_control', array(
		'label'    => __( 'Enable Sticky Header', 'starter-theme-1.x' ),
		'section'  => 'theme_header_layout_section',
		'settings' => 'theme_sticky_header',
		'type'     => 'checkbox',
	) );

	// ===== FEATURES PANEL =====
	$wp_customize->add_panel( 'theme_features', array(
		'title'       => __( 'Features', 'starter-theme-1.x' ),
		'description' => __( 'Enable/disable theme feature modules', 'starter-theme-1.x' ),
		'priority'    => 45,
	) );

	// Section: Feature Toggles
	$wp_customize->add_section( 'theme_feature_toggles_section', array(
		'title'       => __( 'Feature Toggles', 'starter-theme-1.x' ),
		'panel'       => 'theme_features',
		'priority'    => 10,
	) );

	// Dark Mode Support
	$wp_customize->add_setting( 'theme_enable_dark_mode', array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'transport'         => 'refresh',
		'sanitize_callback' => 'rest_sanitize_boolean',
		'default'           => true,
	) );

	$wp_customize->add_control( 'theme_enable_dark_mode_control', array(
		'label'    => __( 'Enable Dark Mode', 'starter-theme-1.x' ),
		'section'  => 'theme_feature_toggles_section',
		'settings' => 'theme_enable_dark_mode',
		'type'     => 'checkbox',
	) );

	// Breadcrumbs
	$wp_customize->add_setting( 'theme_enable_breadcrumbs', array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'transport'         => 'refresh',
		'sanitize_callback' => 'rest_sanitize_boolean',
		'default'           => true,
	) );

	$wp_customize->add_control( 'theme_enable_breadcrumbs_control', array(
		'label'    => __( 'Enable Breadcrumbs', 'starter-theme-1.x' ),
		'section'  => 'theme_feature_toggles_section',
		'settings' => 'theme_enable_breadcrumbs',
		'type'     => 'checkbox',
	) );

	// Blog/Post Features
	$wp_customize->add_setting( 'theme_enable_post_meta', array(
		'type'              => 'option',
		'capability'        => 'edit_theme_options',
		'transport'         => 'refresh',
		'sanitize_callback' => 'rest_sanitize_boolean',
		'default'           => true,
	) );

	$wp_customize->add_control( 'theme_enable_post_meta_control', array(
		'label'    => __( 'Show Post Meta (Author, Date, Category)', 'starter-theme-1.x' ),
		'section'  => 'theme_feature_toggles_section',
		'settings' => 'theme_enable_post_meta',
		'type'     => 'checkbox',
	) );

	// WooCommerce Product Wishlist
	if ( function_exists( 'wc_get_product' ) ) {
		$wp_customize->add_setting( 'theme_enable_wishlist', array(
			'type'              => 'option',
			'capability'        => 'edit_theme_options',
			'transport'         => 'refresh',
			'sanitize_callback' => 'rest_sanitize_boolean',
			'default'           => false,
		) );

		$wp_customize->add_control( 'theme_enable_wishlist_control', array(
			'label'    => __( 'Enable Product Wishlist (requires plugin)', 'starter-theme-1.x' ),
			'section'  => 'theme_feature_toggles_section',
			'settings' => 'theme_enable_wishlist',
			'type'     => 'checkbox',
		) );
	}
}
add_action( 'customize_register', 'theme_customize_register' );

/**
 * Get customizer setting value with fallback
 *
 * @param string $setting Setting name
 * @param mixed  $default Default value if not set
 * @return mixed Setting value
 */
function theme_get_customizer_setting( $setting, $default = '' ) {
	$value = get_option( $setting );
	return ! empty( $value ) ? $value : $default;
}

/**
 * Check if feature is enabled via customizer
 *
 * @param string $feature Feature name (without 'theme_enable_' prefix)
 * @return bool
 */
function theme_is_feature_enabled( $feature ) {
	$setting = "theme_enable_{$feature}";
	return rest_sanitize_boolean( get_option( $setting, true ) );
}
