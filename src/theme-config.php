<?php
/**
 * Theme Config Helper
 *
 * Provides type-safe access to theme configuration settings
 */

class Theme_Config {
	/**
	 * Configuration array
	 *
	 * @var array<string, mixed>
	 */
	private static array $config = array();

	/**
	 * Initialize config from file
	 *
	 * @return void
	 */
	public static function initialize(): void {
		if ( empty( self::$config ) ) {
			self::$config = require_once get_template_directory() . '/src/config.php';
		}
	}

	/**
	 * Get entire config array or specific section
	 *
	 * @param string|null $section Optional section key.
	 * @return array<string, mixed>
	 */
	public static function get( ?string $section = null ) {
		self::initialize();

		if ( null === $section ) {
			return self::$config;
		}

		return self::$config[ $section ] ?? array();
	}

	/**
	 * Get theme metadata
	 *
	 * @return array<string, string>
	 */
	public static function get_theme_meta(): array {
		return self::get( 'theme' );
	}

	/**
	 * Get post types configuration
	 *
	 * @return array<string, array<string, mixed>>
	 */
	public static function get_post_types(): array {
		return self::get( 'post_types' );
	}

	/**
	 * Get specific post type config
	 *
	 * @param string $post_type Post type key.
	 * @return array<string, mixed>|null
	 */
	public static function get_post_type( string $post_type ): ?array {
		$post_types = self::get_post_types();
		return $post_types[ $post_type ] ?? null;
	}

	/**
	 * Get menu locations
	 *
	 * @return array<string, string>
	 */
	public static function get_menus(): array {
		return self::get( 'menus' );
	}

	/**
	 * Get image sizes configuration
	 *
	 * @return array<string, array<int, int|bool>>
	 */
	public static function get_image_sizes(): array {
		return self::get( 'image_sizes' );
	}

	/**
	 * Get theme support configuration
	 *
	 * @return array<string, mixed>
	 */
	public static function get_theme_support(): array {
		return self::get( 'theme_support' );
	}

	/**
	 * Get sidebar/widget areas
	 *
	 * @return array<string, array<string, string>>
	 */
	public static function get_sidebars(): array {
		return self::get( 'sidebars' );
	}

	/**
	 * Get ACF configuration
	 *
	 * @return array<string, mixed>
	 */
	public static function get_acf(): array {
		return self::get( 'acf' );
	}

	/**
	 * Get Timber configuration
	 *
	 * @return array<string, mixed>
	 */
	public static function get_timber(): array {
		return self::get( 'timber' );
	}

	/**
	 * Get assets configuration
	 *
	 * @return array<string, string>
	 */
	public static function get_assets(): array {
		return self::get( 'assets' );
	}

	/**
	 * Check if a config key exists
	 *
	 * @param string $key Config key.
	 * @return bool
	 */
	public static function has( string $key ): bool {
		self::initialize();
		return isset( self::$config[ $key ] );
	}

	/**
	 * Get text domain for translations
	 *
	 * @return string
	 */
	public static function get_text_domain(): string {
		$theme_meta = self::get_theme_meta();
		return $theme_meta['textdomain'] ?? 'starter-theme-1.x';
	}
}

// Initialize on load
Theme_Config::initialize();
