# Theme Configuration Guide

This theme uses a centralized configuration system to manage all theme settings in one place.

## File Structure

```
src/
├── config.php                    - Main configuration array with all theme settings
├── theme-config.php              - Theme_Config helper class for accessing settings
├── post-types-registry.php
├── taxonomies-registry.php
├── page-templates-registry.php
├── timber-acf-wp-blocks.php
├── post-types/                   - Custom post type definitions (auto-discovered)
├── taxonomies/                   - Custom taxonomy definitions (auto-discovered)
└── page-templates/               - Custom page template definitions (auto-discovered)
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

**Quick Start Example:**

```php
// Access common settings
$theme_name = Theme_Config::get_text_domain();  // 'starter-theme'
$menus = Theme_Config::get_menus();             // All menu locations
$sizes = Theme_Config::get_image_sizes();       // All image size configs
```

Configuration is loaded once and cached in memory by the `Theme_Config` class for efficiency.

### Available Helper Methods

```php
// Theme metadata
Theme_Config::get_theme_meta();      // Returns: ['name' => '...', 'textdomain' => '...', 'description' => '...', 'version' => '...']

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

**Best Practice:** Always use `Theme_Config` helper methods instead of accessing the raw config array directly. This maintains type safety and allows the class to handle caching and fallbacks.

## Adding to Configuration

### Add a New Menu Location

Edit `src/config.php`:

```php
'menus' => array(
    'main'   => __( 'Main Navigation', 'starter-theme' ),
    'footer' => __( 'Footer Navigation', 'starter-theme' ),
    'mobile' => __( 'Mobile Menu', 'starter-theme' ),  // New
),
```

### Add a New Image Size

Edit `src/config.php`:

```php
'image_sizes' => [
    'hero' => [
        'width'  => 1200,
        'height' => 300,
        'crop'   => true
    ],
    'card' => [
        'width'  => 400,
        'height' => 300,
        'crop'   => true
    ],
],
```

### Add a New Sidebar

Edit `src/config.php`:

```php
'sidebars' => array(
    'primary'  => array( /* ... */ ),
    'footer'   => array(                    // New
        'name'          => __( 'Footer Widgets', 'starter-theme' ),
        'description'   => __( 'Widgets in footer', 'starter-theme' ),
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

Defines custom post type configuration used by the post type registry

### Taxonomies

Auto-discovered from `src/taxonomies/` directory (see Taxonomies Registry section below)

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

## Registry System

Beyond the main config file, this theme uses **auto-discovery registries** for scalability. See [ARCHITECTURE.md](ARCHITECTURE.md) for a full explanation of the registry pattern used by the theme.

### Post Types Registry

**Location:** `src/post-types-registry.php`
**Auto-discovers:** `src/post-types/*.php`

Each post type file contains its configuration array returned directly. Place file in `src/post-types/` and it auto-registers.

### Taxonomies Registry

**Location:** `src/taxonomies-registry.php`
**Auto-discovers:** `src/taxonomies/*.php`

Each taxonomy file defines its structure via file headers (Taxonomy:, Name:, PostTypes:, etc.) and returns args array.

### Page Templates Registry

**Location:** `src/page-templates-registry.php`
**Auto-discovers:** `src/page-templates/*.php`

Define custom page templates with file headers that map to Twig template files.

## Benefits

✅ **Single Source of Truth** - All settings in one file
✅ **Type-Safe Access** - Helper methods with type hints
✅ **Easy Customization** - Change settings without touching functions.php
✅ **Maintainable** - Clear organization of all theme constants
✅ **Extensible** - Easy to add new sections
✅ **Auto-Discovery** - Add files to directories and they register automatically
