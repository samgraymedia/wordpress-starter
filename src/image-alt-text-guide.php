<?php

/**
 * Image Alt Text Enforcement - ACF Field Template
 * 
 * Use this template when creating ACF image fields in blocks or post types.
 * Ensures all images have descriptive alt text for SEO and accessibility.
 * 
 * EXAMPLE USAGE in a block (e.g., src/blocks/hero.php):
 * 
 *     array(
 *         'key' => 'field_hero_image',
 *         'label' => 'Hero Image',
 *         'name' => 'hero_image',
 *         'type' => 'image',
 *         'return_format' => 'array',
 *         'preview_size' => 'medium',
 *         'required' => 1,
 *         'instructions' => 'Upload a high-quality hero image (minimum 1920x1080px)',
 *     ),
 *     array(
 *         'key' => 'field_hero_image_alt',
 *         'label' => 'Image Alt Text',
 *         'name' => 'hero_image_alt',
 *         'type' => 'text',
 *         'required' => 1,
 *         'instructions' => 'Describe the image for SEO and accessibility (e.g., "Team collaborating in modern office")',
 *         'placeholder' => 'Describe what you see in the image...',
 *         'maxlength' => 150,
 *     ),
 * 
 * TWIG USAGE:
 *     <img 
 *         src="{{ image.url }}" 
 *         alt="{{ image_alt|default(image.alt) }}" 
 *         loading="lazy"
 *         decoding="async"
 *     />
 * 
 * ALT TEXT BEST PRACTICES:
 * 
 * ✅ DO:
 *     - Be descriptive: "Woman running on beach at sunset"
 *     - Include relevant keywords naturally: "blue running shoes for marathons"
 *     - Keep it concise: 100-150 characters max
 *     - Describe the subject and context
 *     - Use plain language, avoid keyword stuffing
 * 
 * ❌ DON'T:
 *     - Leave it blank or use "image"
 *     - Stuff with keywords: "best blue running shoes online store"
 *     - Repeat the page title
 *     - Use "image of", "picture of" (screen readers add this)
 *     - Make them too long (>150 characters)
 * 
 * EXAMPLES BY TYPE:
 * 
 * Hero Image:
 *     "Team members collaborating at conference table"
 *
 * Product Image:
 *     "Blue ergonomic office chair with adjustable lumbar support"
 * 
 * Logo/Icon:
 *     "TechCorp company logo"
 * 
 * Blog/Article:
 *     "Chicago skyline at night with reflection in water"
 * 
 * Team Member:
 *     "Portrait of Sarah Johnson, CEO and Founder"
 */

if ( ! function_exists( 'validate_image_alt_text' ) ) {
	/**
	 * Validate image alt text is not empty
	 * Hook this to 'save_post_*' or use in ACF validation
	 */
	function validate_image_alt_text( $valid, $value, $field, $input ) {
		// For image fields with required alt text
		if ( $field['type'] === 'image' && $field['required'] ) {
			// Check if image field name suggests alt text requirement
			if ( strpos( $field['name'], 'image' ) !== false ) {
				if ( ! $value ) {
					$valid = __( 'Please upload an image and fill in the alt text field.', 'starter-theme-1.x' );
				}
			}
		}

		return $valid;
	}
	// Hook to ACF validation: add_filter( 'acf/validate_attachment_alt', 'validate_image_alt_text', 10, 4 );
}

/**
 * DOCUMENT STRUCTURE - Add to COMPONENTS-GUIDE.md
 * 
 * ## Image Fields & Alt Text
 * 
 * All image fields must include an accompanying alt text field. The alt text is critical for:
 * 
 * - **SEO**: Search engines index alt text for image search
 * - **Accessibility**: Screen readers describe images for visually impaired users
 * - **UX**: Displays if image fails to load
 * - **Social**: Used when sharing content on social media
 * 
 * ### Field Structure
 * 
 * Every image in ACF blocks should follow this pattern:
 * 
 * ```php
 * // Main image field (required)
 * array(
 *     'key' => 'field_myblock_image',
 *     'label' => 'Image',
 *     'name' => 'image',
 *     'type' => 'image',
 *     'return_format' => 'array',
 *     'preview_size' => 'medium',
 *     'required' => 1,
 * ),
 * // Alt text field (required)
 * array(
 *     'key' => 'field_myblock_image_alt',
 *     'label' => 'Alt Text',
 *     'name' => 'image_alt',
 *     'type' => 'text',
 *     'required' => 1,
 *     'maxlength' => 150,
 *     'instructions' => 'Describe the image for SEO & accessibility',
 * ),
 * ```
 * 
 * ### Twig Template Usage
 * 
 * ```twig
 * <img 
 *     src="{{ fields.image.url }}" 
 *     alt="{{ fields.image_alt }}" 
 *     loading="lazy"
 *     decoding="async"
 * />
 * ```
 * 
 * ### WordPressNative Alt Text
 * 
 * If using WordPress's native image insertion:
 * 
 * ```twig
 * {% if post.thumbnail %}
 *     <img 
 *         src="{{ post.thumbnail.src }}" 
 *         alt="{{ post.thumbnail.alt|default(post.title) }}"
 *         loading="lazy"
 *     />
 * {% endif %}
 * ```
 * 
 * ### Testing Alt Text
 * 
 * 1. Right-click image → Inspect Element
 * 2. Check `alt` attribute is populated
 * 3. Use Axe DevTools to verify accessibility
 * 4. Test with [WAVE](https://wave.webaim.org/) accessibility checker
 */
