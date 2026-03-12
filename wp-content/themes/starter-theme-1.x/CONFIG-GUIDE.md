# Theme Configuration Guide

This theme uses a centralized configuration system to manage all theme settings in one place.

## File Structure

```
src/
├── config.php          - Main configuration array with all theme settings
├── theme-config.php    - Theme_Config helper class for accessing settings
├── post-types-registry.php
├── timber-acf-wp-blocks.php
└── blocks/
```

## Using Theme Configuration

### Basic Access

```php
// Get entire config
$config = Theme_Config::get();

// Get specific section
$post_types = Theme_Config::get( 'post_types' );

// Get specific value
$text_domain = Theme_Config::get_text_domain();
```

### Available Helper Methods

```php
// Theme metadata
Theme_Config::get_theme_meta();      // Returns: ['name', 'textdomain', 'description', 'version']

// Post types
Theme_Config::get_post_types();      // Returns all post types config
Theme_Config::get_post_type( 'services' );  // Returns specific post type config

// Menus
Theme_Config::get_menus();           // Returns menu locations

// Images
Theme_Config::get_image_sizes();     // Returns image size configuration

// Theme support
Theme_Config::get_theme_support();   // Returns theme support config

// Sidebars
Theme_Config::get_sidebars();        // Returns widget area definitions

// ACF
Theme_Config::get_acf();             // Returns ACF settings

// Timber
Theme_Config::get_timber();          // Returns Timber configuration

// Assets
Theme_Config::get_assets();          // Returns JS/CSS handles

// Check if key exists
Theme_Config::has( 'menus' );        // Returns boolean
```

## Adding to Configuration

### Add a New Menu Location

Edit `src/config.php`:

```php
'menus' => array(
    'main'   => __( 'Main Navigation', 'starter-theme-1.x' ),
    'footer' => __( 'Footer Navigation', 'starter-theme-1.x' ),
    'mobile' => __( 'Mobile Menu', 'starter-theme-1.x' ),  // New
),
```

### Add a New Image Size

Edit `src/config.php`:

```php
'image_sizes' => array(
    'thumbnail' => array( 150, 150, true ),
    'hero'      => array( 1200, 300, true ),
    'card'      => array( 400, 300, true ),  // New
),
```

### Add a New Sidebar

Edit `src/config.php`:

```php
'sidebars' => array(
    'primary'  => array( /* ... */ ),
    'footer'   => array(                    // New
        'name'          => __( 'Footer Widgets', 'starter-theme-1.x' ),
        'description'   => __( 'Widgets in footer', 'starter-theme-1.x' ),
        // ... rest of args
    ),
),
```

## Configuration Sections

### Theme

- `name` - Theme display name
- `textdomain` - Translation text domain
- `description` - Theme description
- `version` - Current version

### Post Types

Define custom post type slugs and metadata

### Taxonomies

(Currently empty) Add custom taxonomies here

### Menus

WordPress menu location definitions for navigation

### Image Sizes

Registered image dimensions for responsive images

### Theme Support

WordPress theme support features (post thumbnails, HTML5, etc.)

### Sidebars

Widget area definitions

### ACF

Advanced Custom Fields settings

### Timber

Twig template configuration

### Assets

JavaScript and CSS handle names

### Uploads

Allowed file types for media library

### Debug

Development/debugging settings

## Benefits

✅ **Single Source of Truth** - All settings in one file
✅ **Type-Safe Access** - Helper methods with type hints
✅ **Easy Customization** - Change settings without touching functions.php
✅ **Maintainable** - Clear organization of all theme constants
✅ **Extensible** - Easy to add new sections
