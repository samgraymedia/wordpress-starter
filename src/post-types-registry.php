<?php
/**
 * Timber Post Type Registry
 * Auto-discovers and registers post types from PHP files in src/post-types/
 */

if ( ! class_exists( 'Timber_Post_Types' ) ) {
	class Timber_Post_Types {
		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'register_post_types' ), 9 );
			add_action( 'init', array( $this, 'register_post_type_fields' ), 10 );
		}

		/**
		 * Scan and register post types from src/post-types directory
		 */
		public function register_post_types(): void {
			$post_types = $this->discover_post_types();

			foreach ( $post_types as $post_type ) {
				$this->register_single_post_type( $post_type );
			}
		}

		/**
		 * Register ACF fields for discovered post types
		 */
		public function register_post_type_fields(): void {
			if ( ! function_exists( 'acf_add_local_field_group' ) ) {
				return;
			}

			$post_types = $this->discover_post_types();

			foreach ( $post_types as $post_type ) {
				if ( ! empty( $post_type['fields'] ) ) {
					acf_add_local_field_group( $post_type['fields'] );
				}
			}
		}

		/**
		 * Discover post types from PHP files
		 *
		 * @return array<string, array<string, mixed>> Array of post type configurations
		 */
		private function discover_post_types(): array {
			$post_types_dir = get_template_directory() . '/src/post-types';
			$post_types     = array();

			if ( ! is_dir( $post_types_dir ) ) {
				return $post_types;
			}

			$files = glob( $post_types_dir . '/*.php' );
			if ( false === $files ) {
				return $post_types;
			}

			foreach ( $files as $file ) {
				// Skip the registry file itself
				if ( basename( $file ) === 'registry.php' ) {
					continue;
				}

				$config = $this->parse_post_type_file( $file );
				if ( $config ) {
					$post_types[ $config['slug'] ] = $config;
				}
			}

			return $post_types;
		}

		/**
		 * Parse a post type PHP file for configuration
		 *
		 * @param string $file File path to parse.
		 * @return array<string, mixed>|false Post type configuration or false if invalid
		 */
		private function parse_post_type_file( string $file ) {
			$headers = get_file_data(
				$file,
				array(
					'slug'       => 'PostType',
					'name'       => 'Name',
					'args'       => 'Args',
					'fields_key' => 'FieldsKey',
				)
			);

			// Check required headers
			if ( empty( $headers['slug'] ) || empty( $headers['name'] ) ) {
				return false;
			}

			// Get the actual configuration from the file
			$config = require $file;

			if ( ! is_array( $config ) || empty( $config['args'] ) ) {
				return false;
			}

			$config['slug'] = $headers['slug'];
			return $config;
		}

		/**
		 * Register a single post type
		 *
		 * @param array<string, mixed> $post_type Post type configuration.
		 */
		private function register_single_post_type( array $post_type ): void {
			if ( empty( $post_type['slug'] ) || empty( $post_type['args'] ) ) {
				return;
			}

			register_post_type( $post_type['slug'], $post_type['args'] );
		}
	}

	new Timber_Post_Types();
}
