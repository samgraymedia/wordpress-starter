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
		array(
			'key'           => 'field_footer_image',
			'label'         => 'Footer Image',
			'name'          => 'footer_image',
			'type'          => 'image',
			'return_format' => 'url',
			'preview_size'  => 'medium',
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

		// 404 Error Page Section
		array(
			'key'           => 'field_404_section',
			'label'         => '404 Error Page',
			'name'          => '404_section',
			'type'          => 'tab',
		),
		array(
			'key'           => 'field_404_heading',
			'label'         => '404 Heading',
			'name'          => '404_heading',
			'type'          => 'text',
			'placeholder'   => 'Page Not Found',
			'default_value' => 'Page Not Found',
			'instruction'   => 'Main heading displayed on the 404 error page',
		),
		array(
			'key'           => 'field_404_message',
			'label'         => '404 Message',
			'name'          => '404_message',
			'type'          => 'textarea',
			'rows'          => 3,
			'placeholder'   => 'Sorry, the page you are looking for does not exist or has been moved.',
			'default_value' => 'Sorry, the page you are looking for does not exist or has been moved.',
			'instruction'   => 'Detailed message to display on the 404 page',
		),
		array(
			'key'           => 'field_404_cta_text',
			'label'         => 'CTA Button Text',
			'name'          => '404_cta_text',
			'type'          => 'text',
			'placeholder'   => 'Return to Home',
			'default_value' => 'Return to Home',
			'instruction'   => 'Text for the call-to-action button',
		),
		array(
			'key'           => 'field_404_cta_url',
			'label'         => 'CTA Button URL',
			'name'          => '404_cta_url',
			'type'          => 'url',
			'placeholder'   => 'https://example.com',
			'instruction'   => 'Where the button should link to (defaults to homepage)',
		),
		array(
			'key'           => 'field_404_show_search',
			'label'         => 'Show Search Form',
			'name'          => '404_show_search',
			'type'          => 'true_false',
			'message'       => 'Display search form on 404 page',
			'default_value' => 1,
			'ui'            => 1,
			'ui_on_text'    => 'Show',
			'ui_off_text'   => 'Hide',
		),
		array(
			'key'           => 'field_404_show_suggestions',
			'label'         => 'Show Suggested Links',
			'name'          => '404_show_suggestions',
			'type'          => 'true_false',
			'message'       => 'Display helpful links (Home, Shop, Blog, etc.)',
			'default_value' => 1,
			'ui'            => 1,
			'ui_on_text'    => 'Show',
			'ui_off_text'   => 'Hide',
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
