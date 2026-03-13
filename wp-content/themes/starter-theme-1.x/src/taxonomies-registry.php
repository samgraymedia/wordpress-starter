<?php
/**
 * Timber Taxonomies Registry
 * Auto-discovers and registers taxonomies from PHP files in src/taxonomies/
 * 
 * Taxonomies are hierarchical or flat classification systems for post types.
 * Examples: Categories (hierarchical) for Services, Tags (flat) for Posts
 * 
 * File headers define everything needed for registration:
 *   Taxonomy: slug              (required)
 *   Name: Display Name          (required)
 *   PostTypes: post,page        (required)
 *   Args: {json_config}         (optional, from file return value)
 *   FieldsKey: acf_key          (optional, for ACF field groups)
 */

if ( ! class_exists( 'Timber_Taxonomies' ) ) {
	class Timber_Taxonomies {
		/**
		 * Constructor - hook into WordPress init
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'register_taxonomies' ), 12 );
			add_action( 'init', array( $this, 'register_taxonomy_fields' ), 13 );
		}

		/**
		 * Scan and register taxonomies from src/taxonomies directory
		 */
		public function register_taxonomies(): void {
			$taxonomies = $this->discover_taxonomies();

			foreach ( $taxonomies as $taxonomy ) {
				$this->register_single_taxonomy( $taxonomy );
			}
		}

		/**
		 * Register ACF fields for discovered taxonomies
		 */
		public function register_taxonomy_fields(): void {
			if ( ! function_exists( 'acf_add_local_field_group' ) ) {
				return;
			}

			$taxonomies = $this->discover_taxonomies();

			foreach ( $taxonomies as $taxonomy ) {
				if ( ! empty( $taxonomy['fields'] ) ) {
					acf_add_local_field_group( $taxonomy['fields'] );
				}
			}
		}

		/**
		 * Discover taxonomies from PHP files
		 *
		 * @return array<string, array<string, mixed>> Array of taxonomy configurations
		 */
		private function discover_taxonomies(): array {
			$taxonomies_dir = get_template_directory() . '/src/taxonomies';
			$taxonomies     = array();

			if ( ! is_dir( $taxonomies_dir ) ) {
				return $taxonomies;
			}

			$files = glob( $taxonomies_dir . '/*.php' );
			if ( false === $files ) {
				return $taxonomies;
			}

			foreach ( $files as $file ) {
				// Skip the registry file itself
				if ( basename( $file ) === 'registry.php' ) {
					continue;
				}

				$config = $this->parse_taxonomy_file( $file );
				if ( $config ) {
					$taxonomies[ $config['slug'] ] = $config;
				}
			}

			return $taxonomies;
		}

		/**
		 * Parse a taxonomy PHP file for configuration
		 *
		 * File should use these headers:
		 *   Taxonomy: slug                (required)
		 *   Name: Display Name             (required)
		 *   PostTypes: post,page,service   (required, comma-separated)
		 *   Hierarchical: true|false       (optional)
		 *   Args: {json_config}            (optional, if using custom args)
		 *   FieldsKey: acf_group_key       (optional, if using ACF fields)
		 *
		 * Example file structure:
		 *   <?php
		 *   /**
		 *    * Service Category Taxonomy
		 *    * 
		 *    * Taxonomy: service-category
		 *    * Name: Service Categories
		 *    * PostTypes: service
		 *    * Hierarchical: true
		 *    *\/
		 *   return array(
		 *       'args' => array(
		 *           'public'            => true,
		 *           'show_ui'           => true,
		 *           'show_in_nav_menus' => true,
		 *           'show_admin_column' => true,
		 *           'hierarchical'      => true,
		 *           'rewrite'           => array( 'slug' => 'service-category' ),
		 *       ),
		 *   );
		 *
		 * @param string $file File path to parse.
		 * @return array<string, mixed>|false Taxonomy configuration or false if invalid
		 */
		private function parse_taxonomy_file( string $file ) {
			$headers = get_file_data(
				$file,
				array(
					'slug'       => 'Taxonomy',
					'name'       => 'Name',
					'post_types' => 'PostTypes',
					'args'       => 'Args',
					'fields_key' => 'FieldsKey',
				)
			);

			// Check required headers
			if ( empty( $headers['slug'] ) || empty( $headers['name'] ) || empty( $headers['post_types'] ) ) {
				return false;
			}

			// Parse post types (comma-separated string to array)
			$post_types = array_map( 'trim', explode( ',', $headers['post_types'] ) );

			// Get the configuration from the file
			$config = require $file;

			if ( ! is_array( $config ) || empty( $config['args'] ) ) {
				return false;
			}

			$config['slug']        = $headers['slug'];
			$config['post_types']  = $post_types;
			$config['fields_key']  = $headers['fields_key'] ?? '';

			return $config;
		}

		/**
		 * Register a single taxonomy
		 *
		 * @param array<string, mixed> $taxonomy Taxonomy configuration.
		 */
		private function register_single_taxonomy( array $taxonomy ): void {
			if ( empty( $taxonomy['slug'] ) || empty( $taxonomy['post_types'] ) || empty( $taxonomy['args'] ) ) {
				return;
			}

			register_taxonomy( $taxonomy['slug'], $taxonomy['post_types'], $taxonomy['args'] );
		}
	}

	new Timber_Taxonomies();
}
