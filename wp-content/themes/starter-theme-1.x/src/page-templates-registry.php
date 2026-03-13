<?php
/**
 * Timber Page Templates Registry
 * Auto-discovers and registers page templates from PHP files in src/page-templates/
 * 
 * Usage:
 * - Create files in src/page-templates/ with metadata headers
 * - File headers define template metadata and Twig template path
 * - Templates are registered and available in WordPress page settings
 */

if ( ! class_exists( 'Timber_Page_Templates' ) ) {
	class Timber_Page_Templates {
		/**
		 * Constructor - hook into WordPress init
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'register_page_templates' ), 11 );
			add_filter( 'theme_page_templates', array( $this, 'add_page_templates_to_dropdown' ), 10, 4 );
			add_filter( 'template_include', array( $this, 'maybe_redirect_to_page_template' ), 999 );
		}

		/**
		 * Discover and register page templates from src/page-templates directory
		 */
		public function register_page_templates(): void {
			$page_templates = $this->discover_page_templates();
			// Templates are stored and available via the filter hook
		}

		/**
		 * Add discovered page templates to the WordPress page template dropdown
		 *
		 * @param array<string, string> $post_templates Existing templates.
		 * @param WP_Theme             $wp_theme       Theme object.
		 * @param WP_Post|null         $post           Post object if in editor.
		 * @param string                $post_type      Post type being edited.
		 * @return array<string, string> Templates with our custom templates added.
		 */
		public function add_page_templates_to_dropdown( 
			array $post_templates, 
			$wp_theme = null, 
			$post = null, 
			string $post_type = 'page' 
		): array {
			$page_templates = $this->discover_page_templates();

			foreach ( $page_templates as $template ) {
				// Only add if it applies to this post type
				if ( ! in_array( $post_type, $template['post_types'], true ) ) {
					continue;
				}

				// Use the template slug as the array key (WordPress convention)
				$template_key                      = $template['template_key'];
				$post_templates[ $template_key ] = $template['title'];
			}

			return $post_templates;
		}

		/**
		 * Redirect to custom template if one is selected
		 * This hooks into the template hierarchy to render the correct Twig template
		 *
		 * @param string $template Current template file path.
		 * @return string Template file path.
		 */
		public function maybe_redirect_to_page_template( string $template ): string {
			global $post;

			if ( ! $post ) {
				return $template;
			}

			// Check if this post has a custom page template selected
			$page_template = get_page_template_slug( $post->ID );
			if ( ! $page_template ) {
				return $template;
			}

			$page_templates = $this->discover_page_templates();

			// Try to find the matching template
			foreach ( $page_templates as $pt ) {
				if ( $pt['template_key'] === $page_template ) {
					// Pass the Twig template path to page.php via filter
					// page.php will use this Twig template instead of the default
					add_filter( 'timber_page_twig_template', function( $twig_template ) use ( $pt ) {
						return $pt['twig_template'];
					}, 10, 1 );

					return $template; // Return the standard page.php
				}
			}

			return $template;
		}

		/**
		 * Discover page templates from PHP files
		 *
		 * @return array<int, array<string, mixed>> Array of page template configurations
		 */
		private function discover_page_templates(): array {
			$templates_dir  = get_template_directory() . '/src/page-templates';
			$page_templates = array();

			if ( ! is_dir( $templates_dir ) ) {
				return $page_templates;
			}

			$files = glob( $templates_dir . '/*.php' );
			if ( false === $files ) {
				return $page_templates;
			}

			foreach ( $files as $file ) {
				// Skip the registry file itself
				if ( basename( $file ) === 'registry.php' ) {
					continue;
				}

				$config = $this->parse_template_file( $file );
				if ( $config ) {
					$page_templates[] = $config;
				}
			}

			return $page_templates;
		}

		/**
		 * Parse a page template PHP file for configuration
		 *
		 * File should use these headers:
		 *   PageTemplate: template-slug        (required, unique)
		 *   Title: Display Name                (required, shown in WordPress)
		 *   Description: Template details      (optional)
		 *   PostTypes: page,post,service       (optional, defaults to 'page')
		 *   TwigTemplate: path/to/file.twig   (required, relative to theme root)
		 *
		 * Example:
		 *   <?php
		 *   /**
		 *    * PageTemplate: testimonials-hero
		 *    * Title: Testimonials with Hero
		 *    * Description: Full-width layout with testimonials showcase
		 *    * PostTypes: page,testimonials
		 *    * TwigTemplate: templates/page-testimonials-hero.twig
		 *    *\/
		 *
		 * @param string $file File path to parse.
		 * @return array<string, mixed>|false Template configuration or false if invalid
		 */
		private function parse_template_file( string $file ) {
			$headers = get_file_data(
				$file,
				array(
					'template_key'  => 'PageTemplate',
					'title'         => 'Title',
					'description'   => 'Description',
					'post_types'    => 'PostTypes',
					'twig_template' => 'TwigTemplate',
				)
			);

			// Check required headers
			if ( empty( $headers['template_key'] ) || empty( $headers['title'] ) || empty( $headers['twig_template'] ) ) {
				return false;
			}

			// Default to 'page' if no post types specified
			$post_types = ! empty( $headers['post_types'] ) 
				? array_map( 'trim', explode( ',', $headers['post_types'] ) )
				: array( 'page' );

			return array(
				'template_key'  => $headers['template_key'],
				'title'         => $headers['title'],
				'description'   => $headers['description'] ?? '',
				'post_types'    => $post_types,
				'twig_template' => $headers['twig_template'],
				'file'          => $file,
			);
		}
	}

	new Timber_Page_Templates();
}
