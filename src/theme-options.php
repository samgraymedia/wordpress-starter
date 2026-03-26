<?php
/**
 * Theme Options - ACF Field Group
 * 
 * Registers custom fields for the Theme Options admin page
 * These settings are available globally across the site
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group( array(
	'key'      => 'group_theme_options',
	'title'    => 'Theme Options',
	'fields'   => array(
		// Contact Information Section
		array(
			'key'           => 'field_contact_section',
			'label'         => 'Contact Information',
			'name'          => 'contact_section',
			'type'          => 'tab',
		),
		array(
			'key'           => 'field_contact_email',
			'label'         => 'Email Address',
			'name'          => 'contact_email',
			'type'          => 'email',
			'required'      => 1,
			'placeholder'   => 'info@example.com',
		),
		array(
			'key'           => 'field_phone_number',
			'label'         => 'Phone Number',
			'name'          => 'phone_number',
			'type'          => 'text',
			'placeholder'   => '+1 (555) 123-4567',
		),
		array(
			'key'           => 'field_address',
			'label'         => 'Address',
			'name'          => 'address',
			'type'          => 'textarea',
			'rows'          => 3,
			'placeholder'   => '123 Main Street, City, State 12345',
		),

		// Social Media Section
		array(
			'key'           => 'field_social_section',
			'label'         => 'Social Media',
			'name'          => 'social_section',
			'type'          => 'tab',
		),
		array(
			'key'           => 'field_facebook_url',
			'label'         => 'Facebook URL',
			'name'          => 'facebook_url',
			'type'          => 'url',
			'placeholder'   => 'https://facebook.com/yourpage',
		),
		array(
			'key'           => 'field_twitter_url',
			'label'         => 'Twitter/X URL',
			'name'          => 'twitter_url',
			'type'          => 'url',
			'placeholder'   => 'https://twitter.com/yourhandle',
		),
		array(
			'key'           => 'field_instagram_url',
			'label'         => 'Instagram URL',
			'name'          => 'instagram_url',
			'type'          => 'url',
			'placeholder'   => 'https://instagram.com/yourhandle',
		),
		array(
			'key'           => 'field_linkedin_url',
			'label'         => 'LinkedIn URL',
			'name'          => 'linkedin_url',
			'type'          => 'url',
			'placeholder'   => 'https://linkedin.com/company/yourcompany',
		),

		// Business Information Section
		array(
			'key'           => 'field_business_section',
			'label'         => 'Business Information',
			'name'          => 'business_section',
			'type'          => 'tab',
		),
		array(
			'key'           => 'field_business_name',
			'label'         => 'Business Name',
			'name'          => 'business_name',
			'type'          => 'text',
			'placeholder'   => 'Your Company Name',
		),
		array(
			'key'           => 'field_business_description',
			'label'         => 'Business Description',
			'name'          => 'business_description',
			'type'          => 'textarea',
			'rows'          => 4,
			'placeholder'   => 'Short description of your business',
		),
		array(
			'key'           => 'field_business_hours',
			'label'         => 'Business Hours',
			'name'          => 'business_hours',
			'type'          => 'textarea',
			'rows'          => 4,
			'placeholder'   => 'Monday - Friday: 9:00 AM - 5:00 PM' . "\n" . 'Saturday: 10:00 AM - 3:00 PM' . "\n" . 'Sunday: Closed',
		),

		// Appearance Settings Section
		array(
			'key'           => 'field_appearance_section',
			'label'         => 'Appearance Settings',
			'name'          => 'appearance_section',
			'type'          => 'tab',
		),
		array(
			'key'           => 'field_enable_dark_mode',
			'label'         => 'Enable Dark Mode',
			'name'          => 'enable_dark_mode',
			'type'          => 'true_false',
			'message'       => 'Allow users to toggle between light and dark theme',
			'default_value' => 1,
			'ui'            => 1,
			'ui_on_text'    => 'Enabled',
			'ui_off_text'   => 'Disabled',
			'instruction'   => 'When enabled, visitors can toggle between light and dark modes. The preference is saved in their browser.',
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'options_page',
				'operator' => '==',
				'value'    => 'acf-options'
			),
		),
	),
	'menu_order'       => 0,
	'position'         => 'normal',
	'style'            => 'default',
	'label_placement'  => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen'   => '',
	'active'           => true,
	'description'      => 'Global theme settings used throughout the site (footer, contact pages, etc.)',
) );
