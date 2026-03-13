# Developer Guide

**Sarter Theme** – Complete Developer Documentation

Welcome! This guide covers everything you need to know to develop, maintain, and extend this WordPress theme built with Timber, ACF, and modern PHP practices.

## 📚 Documentation Overview

This developer guide is organized into several specialized documents:

| Document                | Purpose                                         |
| ----------------------- | ----------------------------------------------- |
| **README.md**           | Quick start and installation instructions       |
| **DEVELOPER-GUIDE.md**  | This file — comprehensive development reference |
| **CONFIG-GUIDE.md**     | Theme configuration system and settings         |
| **COMPONENTS-GUIDE.md** | Twig components and macro usage                 |
| **BEM-GUIDE.md**        | CSS architecture and utilities                  |
| **ARCHITECTURE.md**     | System design and code organization             |

**Start here:** New to the project? Begin with [Project Overview](#project-overview) below.

---

## Table of Contents

1. [Project Overview](#project-overview)
2. [Quick Start](#quick-start)
3. [Technology Stack](#technology-stack)
4. [Directory Structure](#directory-structure)
5. [Development Workflow](#development-workflow)
6. [Key Concepts](#key-concepts)
7. [Common Tasks](#common-tasks)
8. [PHP Code Examples](#php-code-examples)
9. [Template Examples](#template-examples)
10. [CSS/SCSS Examples](#cssscss-examples)
11. [Debugging & Troubleshooting](#debugging--troubleshooting)
12. [Performance Optimization](#performance-optimization)
13. [Security Best Practices](#security-best-practices)
14. [Contributing Guidelines](#contributing-guidelines)

---

## Project Overview

### About This Theme

**Theme Name:** Starter Theme  
**Client:** N/A  
**Built With:** WordPress + Timber + ACF Pro + Bootstrap 5  
**PHP Version:** 7.4+ (uses typed properties and union types)  
**Last Updated:** March 2026

### Key Features

✅ **Modular Architecture** - Registry-based post types and blocks for scalability  
✅ **Centralized Configuration** - All settings in `src/config.php` with typed access  
✅ **Reusable Components** - Twig components and macros for DRY templates  
✅ **BEM CSS Methodology** - Organized, scalable stylesheet organization  
✅ **Type-Safe PHP** - Full type hints on 20+ methods (PHP 7.4+ standards)  
✅ **ACF Integration** - Advanced field configuration with block support

### Project Goals

- Maintain clean, documented code
- Provide excellent developer experience
- Ensure scalability for future features
- Balance customization with maintainability

---

## Quick Start

### Prerequisites

**Required:**

- WordPress 6.0+
- PHP 7.4+
- Timber Plugin 2.0+
- Advanced Custom Fields Pro
- Node.js 16+ (for SCSS compilation)
- MAMP or equivalent local development environment

**Optional:**

- WooCommerce plugin (for e-commerce functionality)

### Installation Steps

1. **Clone/Download the theme**

   ```bash
   cd wp-content/themes/
   # Copy the starter-theme-1.x folder
   ```

2. **Install dependencies**

   ```bash
   cd starter-theme-1.x
   npm install
   ```

3. **Activate plugins** (in WordPress admin)

   - Timber Library
   - Advanced Custom Fields Pro
   - Contact Form 7
   - CF7 Database Addon

4. **Activate the theme**

   - Go to Appearance > Themes
   - Activate "Starter Theme" theme

5. **Compile SCSS**

   ```bash
   npm run sass    # One-time compilation
   npm run watch   # Watch for changes
   ```

6. **Start developing!**
   ```bash
   # In your IDE/editor, open the theme folder
   # Available at: http://localhost:8888/wordpress-starter/
   ```

### Verify Installation

To confirm everything is working:

```bash
# Check PHP version
php -v  # Should be 7.4 or higher

# Test SCSS compilation
npm run sass
# Should generate style.css without errors

# Check theme activation
# Visit WordPress admin - you should see "Starter Theme" as active theme
```

---

## Technology Stack

### Core Framework

| Technology    | Version | Purpose                   |
| ------------- | ------- | ------------------------- |
| **WordPress** | 6.0+    | CMS Foundation            |
| **Timber**    | 2.x     | PHP-to-Twig bridge        |
| **Twig**      | 2.x     | Template engine           |
| **ACF Pro**   | Latest  | Advanced field management |
| **Bootstrap** | 5.3.7   | CSS framework             |
| **Sass/SCSS** | Latest  | CSS preprocessing         |
| **PHP**       | 7.4+    | Backend language          |

### Key Plugins

```php
// Automatically loaded in functions.php
- Timber Library         // Template engine
- ACF Pro              // Field management
- Contact Form 7       // Form handling
- CF7 Database Addon   // Form submission storage
```

### NPM Scripts

```json
{
  "sass": "sass css/sass:css --no-source-map",
  "watch": "sass --watch css/sass:css --no-source-map"
}
```

### File Organization Pattern

```
Filesystem
    ↓
functions.php (initialization)
    ↓
src/config.php (settings)
src/theme-config.php (typed access)
    ↓
src/post-types-registry.php (auto-load post types)
src/taxonomies-registry.php (auto-load taxonomies)
src/page-templates-registry.php (auto-load page templates)
src/timber-acf-wp-blocks.php (auto-load blocks)
    ↓
templates/ (Twig rendering)
css/ (styling)
```

---

## Directory Structure

### Root Theme Folder

```
starter-theme-1.x/
│
├── functions.php           # Theme initialization & hooks
├── style.scss              # Main SCSS import file
├── style.css               # Compiled CSS output
│
├── src/                    # PHP source code
│   ├── config.php          # Centralized theme configuration
│   ├── theme-config.php    # Configuration accessor class
│   ├── post-types-registry.php   # Post type auto-discovery
│   ├── taxonomies-registry.php   # Taxonomy auto-discovery
│   ├── page-templates-registry.php # Page template auto-discovery
│   ├── timber-acf-wp-blocks.php  # Block auto-discovery
│   ├── post-types/         # Individual post type configs
│   │   ├── post.php        # Blog post configuration
│   │   ├── service.php     # Services post type
│   │   └── testimonial.php # Testimonials post type
│   ├── taxonomies/         # Individual taxonomy configs
│   │   ├── service-category.php
│   │   ├── service-tag.php
│   │   └── testimonial-category.php
│   ├── page-templates/     # Individual page template configs
│   │   └── example-full-width.php
│   └── blocks/             # Custom block definitions
│
├── templates/              # Twig template files
│   ├── base.twig           # Master layout template
│   ├── index.twig          # Homepage/blog listing
│   ├── components/         # Reusable components
│   │   ├── button.twig
│   │   ├── post-card.twig
│   │   ├── testimonial-card.twig
│   │   ├── heading.twig
│   │   └── alert.twig
│   ├── macros.twig         # Twig macros (functions)
│   └── views/              # Additional templates
│
├── css/                    # SCSS & CSS files
│   ├── inc/
│   │   ├── base/           # Foundation styles
│   │   │   ├── vars.scss   # Variables & functions
│   │   │   ├── reset.scss  # HTML resets
│   │   │   ├── layout.scss # Page layout
│   │   │   ├── font.scss   # Typography
│   │   │   └── btns.scss   # Button component (BEM)
│   │   │
│   │   ├── components/     # Reusable components (BEM)
│   │   │   ├── card.scss
│   │   │   ├── alert.scss
│   │   │   └── badge.scss
│   │   │
│   │   ├── global/         # Large sections
│   │   │   ├── nav.scss    # Navigation
│   │   │   └── footer.scss # Footer
│   │   │
│   │   ├── blocks/         # Gutenberg blocks
│   │   │   └── text.scss
│   │   │
│   │   └── utilities/      # Helper classes
│   │       └── helpers.scss
│   │
│   └── sass/               # Legacy sass folder (for watch tasks)
│
├── static/                 # Static assets
│   ├── js/                 # JavaScript files
│   ├── images/             # Image assets
│   └── fonts/              # Custom fonts
│
├── views/                  # PHP template hierarchy files
│   ├── index.php
│   ├── single.php
│   ├── page.php
│   ├── archive.php
│   ├── 404.php
│   └── ...
│
├── node_modules/           # npm dependencies (gitignored)
├── package.json            # npm configuration
├── package-lock.json       # Lock file
│
├── README.md               # Installation & quickstart
├── DEVELOPER-GUIDE.md      # This file
├── CONFIG-GUIDE.md         # Configuration reference
├── COMPONENTS-GUIDE.md     # Twig components reference
├── BEM-GUIDE.md            # CSS architecture guide
└── ARCHITECTURE.md         # System design document
```

### Key Directories Explained

#### `src/` - PHP Application Code

**Purpose:** Core WordPress customizations and theme logic

- **config.php** - Single source of truth for all theme settings (post types, menus, image sizes, ACF, etc.)
- **theme-config.php** - Static helper class providing typed access to configuration
- **post-types-registry.php** - Auto-discovers and registers post type files in `src/post-types/`
- **timber-acf-wp-blocks.php** - Auto-discovers and registers Gutenberg blocks in `views/blocks/`
- **post-types/** - Each post type in its own file with configuration array
- **blocks/** - Custom block type definitions

#### `templates/` - Twig Templates

**Purpose:** Template rendering using Timber + Twig engine

- **base.twig** - Master layout template (header, footer, sidebar wrapper)
- **index.twig** - Homepage and blog listing template
- **components/** - Reusable UI components with consistent interfaces
- **macros.twig** - Twig macros (inline functions) for common patterns
- **views/** - Other page-specific templates

#### `css/` - Stylesheet Organization

**Purpose:** SCSS files organized by SMACSS + BEM layers

- **base/** - Variables, resets, and foundational styles
- **components/** - Reusable component styles (BEM blocks)
- **global/** - Large layout sections (nav, footer)
- **blocks/** - Gutenberg block-specific styling
- **utilities/** - Single-purpose helper classes

#### `views/` - PHP Template Hierarchy

**Purpose:** WordPress template hierarchy entry points

These files determine which Twig template to render based on WordPress context.

---

## Development Workflow

### Daily Development Tasks

#### 1. Start Development Session

```bash
# Navigate to theme directory
cd wp-content/themes/starter-theme-1.x

# Watch SCSS for changes (in one terminal)
npm run watch

# Open code editor
code .

# Visit local site in browser
# http://localhost:8888/wordpress-starter/
```

#### 2. Making Changes

**For PHP Changes:**

- Edit files in `src/` or view files in `views/`
- Refresh browser to see changes
- Check WordPress admin for any errors

**For Template Changes:**

- Edit files in `templates/`
- No compilation needed (Twig renders on the fly)
- Refresh browser to see changes

**For Style Changes:**

- Edit files in `css/inc/`
- SCSS auto-compiles via `npm run watch`
- Refresh browser (or use browser live reload)

**For Configuration Changes:**

- Edit `src/config.php`
- Update Theme_Config getter methods if needed in `src/theme-config.php`
- Clear any caches
- Verify in WordPress admin

#### 3. Testing Changes

```bash
# Test theme in development environment
# 1. Check WordPress admin for errors
# 2. Inspect frontend in browser
# 3. Test responsive design
# 4. Check console for JavaScript errors

# Compile final SCSS for production
npm run sass

# Verify style.css was generated without errors
```

#### 4. Committing Changes

```bash
# Review what changed
git status

# Stage changes
git add src/ templates/ css/ functions.php

# Commit with clear message
git commit -m "Add testimonial carousel component"

# Push to repository
git push origin feature-branch
```

### Adding New Features

#### Adding a New Post Type

1. **Create configuration file**

   ```bash
   # File: src/post-types/my-post-type.php
   ```

2. **Define post type configuration**

   ```php
   <?php
   // PostType: My Custom Type
   // Name: My Type

   return [
       'args' => [
           'label' => 'My Type',
           'public' => true,
           'supports' => ['title', 'editor', 'thumbnail'],
           // ... more args
       ],
       'fields' => [
           // ACF field groups if needed
       ]
   ];
   ```

3. **Auto-registered** via `post-types-registry.php` on next page load
4. **See:** CONFIG-GUIDE.md for detailed post type setup

#### Adding a New Component

1. **Create Twig component file**

   ```bash
   # File: templates/components/my-component.twig
   ```

2. **Define component template**

   ```twig
   {# Component: My Component
      Props: title (string), items (array), variant (string)
      Usage: {% include "components/my-component.twig" with { title: "Title", items: items } %}
   #}

   <div class="my-component my-component--{{ variant }}">
       <h3>{{ title }}</h3>
       {# component content #}
   </div>
   ```

3. **Create component styles**

   ```bash
   # File: css/inc/components/my-component.scss
   ```

4. **Import in style.scss**

   ```scss
   @import "./css/inc/components/my-component";
   ```

5. **Use in templates**
   ```twig
   {% include "components/my-component.twig" with { title: "Hello", items: my_items } %}
   ```

**See:** COMPONENTS-GUIDE.md and BEM-GUIDE.md for detailed examples

#### Adding a New Gutenberg Block

1. **Create block template**

   ```bash
   # File: templates/blocks/my-block.twig
   ```

2. **Add block metadata header**

   ```twig
   {# Block: My Block
      Title: My Block Title
      Description: A custom block
      Category: common
      Icon: star
   #}

   <div class="block-my-block">
       {# block content #}
   </div>
   ```

3. **Auto-registered** via `timber-acf-wp-blocks.php`

4. **See:** CONFIG-GUIDE.md block section for details

---

## Key Concepts

### 1. Theme Configuration System

**Problem:** Settings hardcoded throughout codebase  
**Solution:** Centralized config with typed access

```php
// Get configuration (lazy-loaded on first use)
$config = Theme_Config::get();
$post_types = Theme_Config::get_post_types();
$text_domain = Theme_Config::get_text_domain();

// All values typed and validated
// Returns null if key doesn't exist (safe)
$menu = Theme_Config::get( 'menus', 'primary' );
```

**Files:**

- `src/config.php` - Configuration arrays
- `src/theme-config.php` - Static accessor class
- See CONFIG-GUIDE.md for all settings

### 2. Post Type Registry System

**Problem:** Post types scattered across codebase  
**Solution:** Auto-discovery system with modular files

```php
// src/post-types-registry.php
// Automatically:
// 1. Scans src/post-types/ for PHP files
// 2. Reads file headers for metadata
// 3. Registers post type and ACF fields

// Usage: Just create src/post-types/my-type.php
// Automatically registered on init hook
```

**Files:**

- `src/post-types/` - Individual post type configs
- `src/post-types-registry.php` - Auto-discovery logic

### 3. Block Registration System

**Problem:** Blocks scattered across codebase  
**Solution:** Auto-discovery system with file headers

```php
// src/timber-acf-wp-blocks.php
// Automatically:
// 1. Scans templates/blocks/ for .twig files
// 2. Reads block metadata from file headers
// 3. Registers with ACF
// 4. Renders with Timber

// Usage: Just create templates/blocks/my-block.twig
// Automatically registered on acf/init hook
```

**Files:**

- `templates/blocks/` - Block Twig templates
- `src/timber-acf-wp-blocks.php` - Auto-discovery logic

### 4. Taxonomy Registry System

**Problem:** Taxonomies scattered across codebase  
**Solution:** Auto-discovery system with modular files

```php
// src/taxonomies-registry.php
// Automatically:
// 1. Scans src/taxonomies/ for PHP files
// 2. Reads file headers for metadata (Taxonomy:, Name:, PostTypes:)
// 3. Registers taxonomy and ACF fields

// Usage: Just create src/taxonomies/my-tax.php
// Automatically registered on init hook (priority 12)
```

**Files:**

- `src/taxonomies/` - Individual taxonomy configs
- `src/taxonomies-registry.php` - Auto-discovery logic

### 5. Page Templates Registry System

**Problem:** Custom page templates scattered across codebase  
**Solution:** Auto-discovery system with modular files

```php
// src/page-templates-registry.php
// Automatically:
// 1. Scans src/page-templates/ for PHP files
// 2. Reads file headers for metadata (PageTemplate:, Title:, TwigTemplate:)
// 3. Adds to WordPress page template dropdown
// 4. Routes pages to custom Twig templates

// Usage: Just create src/page-templates/my-template.php
// Automatically registered on init hook (priority 11)
```

**Files:**

- `src/page-templates/` - Individual page template configs
- `src/page-templates-registry.php` - Auto-discovery logic
- `templates/page-*.twig` - Page template Twig files

### 6. BEM CSS Architecture

**Block-Element-Modifier** naming convention for CSS:

```scss
// Block (standalone component)
.card { }

// Elements (parts of block)
.card__image { }
.card__title { }

// Modifiers (variations)
.card--featured { }
.card--minimal { }

// Usage in HTML
<div class="card card--featured">
    <img class="card__image" />
    <h3 class="card__title">Title</h3>
</div>
```

**Benefits:**

- Predictable naming
- Low specificity
- No nesting complexity
- Easy to maintain

**See:** BEM-GUIDE.md for complete reference

### 7. Type Hints & PHP Standards

This theme uses **PHP 7.4+ features:**

```php
// Typed properties (PHP 7.4)
private string $name = '';
public array<string, mixed> $config = [];

// Union types (PHP 8.0)
public function get_value(): string|int|null { }

// Named arguments
Theme_Config::get( section: 'post_types', key: 'services' );

// Match expressions (PHP 8.0)
$size = match( $variant ) {
    'lg' => 'large',
    'sm' => 'small',
    default => 'medium'
};
```

**Why?** Better code reliability, IDE autocomplete, self-documenting code

---

## Common Tasks

### Task 1: Update Theme Settings

```php
// File: src/config.php
return [
    'theme' => [
        'name' => 'Starter Theme',
        'text_domain' => 'Starter Theme ',
        'description' => 'Starter Theme ',
        'version' => '1.0.0',
    ],
    'post_types' => [
        'services' => [
            'args' => [
                'label' => 'Services',
                'menu_position' => 5,
                // ... more args
            ],
        ],
    ],
    // ... other sections
];

// Access in code
$theme_name = Theme_Config::get_theme_meta()['name'];
$services_config = Theme_Config::get_post_types()['services'];
```

### Task 2: Add Custom Image Size

```php
// File: src/config.php in image_sizes section
'image_sizes' => [
    'thumbnail' => [
        'width' => 200,
        'height' => 200,
        'crop' => true,
    ],
    'hero' => [
        'width' => 1200,
        'height' => 400,
        'crop' => true,
    ],
    // Add new size
    'card-featured' => [
        'width' => 400,
        'height' => 300,
        'crop' => true,
    ],
];

// Registered automatically in functions.php
// Usage in template
{{ post.thumbnail( 'card-featured' ) }}
```

### Task 3: Register New Menu Location

```php
// File: src/config.php in menus section
'menus' => [
    'primary' => 'Main Navigation',
    'footer' => 'Footer Links',
    'mobile' => 'Mobile Menu',  // Add new
],

// Register automatically in functions.php
// Usage in template
<nav class="main-nav">
    {% include 'navigation.twig' with { menu: 'primary' } %}
</nav>
```

### Task 4: Create Custom Taxonomy

```php
// File: src/taxonomies/service-difficulty.php
<?php
/**
 * Service Difficulty Taxonomy
 *
 * Taxonomy: service-difficulty
 * Name: Service Difficulty Levels
 * PostTypes: service
 * Hierarchical: true
 */

return array(
    'args' => array(
        'labels' => array(
            'name'          => _x( 'Difficulty Levels', 'taxonomy general name' ),
            'singular_name' => _x( 'Difficulty Level', 'taxonomy singular name' ),
            'add_new_item'  => __( 'Add New Difficulty Level' ),
        ),
        'description'       => 'Categorize services by difficulty level',
        'public'            => true,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'hierarchical'      => true,
        'rewrite'           => array( 'slug' => 'service-difficulty' ),
    ),
);
```

**Result:** Automatically registered on `init` hook (priority 12)

**Usage:** Assign taxonomy terms in WordPress admin

### Task 5: Create Custom Page Template

```php
// File: src/page-templates/testimonials-showcase.php
<?php
/**
 * Testimonials Showcase Page Template
 *
 * PageTemplate: testimonials-showcase
 * Title: Testimonials Showcase
 * Description: Full-width page featuring all testimonials
 * PostTypes: page
 * TwigTemplate: templates/page-testimonials-showcase.twig
 */
```

Then create the Twig template:

```twig
{# File: templates/page-testimonials-showcase.twig #}
{% extends "base.twig" %}

{% block content %}
    <section class="testimonials-showcase">
        <h1>{{ post.title }}</h1>

        <div class="testimonials-grid">
            {% set testimonials = get_posts({
                post_type: 'testimonials',
                posts_per_page: -1
            }) %}

            {% for testimonial in testimonials %}
                {% include 'components/testimonial-card.twig' with { post: testimonial } %}
            {% endfor %}
        </div>
    </section>
{% endblock %}
```

**Result:** Automatically registered on `init` hook (priority 11)

**Usage:** Go to Pages in WordPress admin → Template dropdown → select "Testimonials Showcase"

### Task 6: Create Reusable Component

```twig
{# File: templates/components/hero.twig #}
{# Props: title, subtitle, image, cta_text, cta_url #}

<section class="hero" style="background-image: url({{ image }})">
    <div class="hero__content">
        <h1 class="hero__title">{{ title }}</h1>
        {% if subtitle %}
            <p class="hero__subtitle">{{ subtitle }}</p>
        {% endif %}
        {% if cta_text %}
            {% include "components/button.twig" with {
                text: cta_text,
                url: cta_url
            } %}
        {% endif %}
    </div>
</section>
```

**Usage:**

```twig
{% include "components/hero.twig" with {
    title: "Welcome",
    subtitle: "Learn to drive",
    image: home_hero.hero_image.src,
    cta_text: "Book Now",
    cta_url: booking_url
} %}
```

### Task 7: Add Utility Classes

```scss
// File: css/inc/utilities/helpers.scss
// Add to spacing section

.gap-2 {
  gap: 0.5rem;
}
.gap-3 {
  gap: 0.75rem;
}
.gap-4 {
  gap: 1rem;
}

// Add to typography section
.text-balance {
  text-wrap: balance;
}
.truncate-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
```

**Usage:**

```html
<div class="d-flex gap-4">
  <div>Item 1</div>
  <div>Item 2</div>
</div>

<p class="text-balance">
  Long text will balance on multiple lines
</p>
```

### Task 8: Debug Template Variable

```twig
{# In template file #}

{# Print all available variables #}
<pre>{{ dump() }}</pre>

{# Print specific variable #}
<pre>{{ dump( post ) }}</pre>

{# Conditional debug #}
{% if is_preview %}
    <div style="border: 2px red solid;">
        <p>DEBUG: {{ post.title }}</p>
        {{ dump( post ) }}
    </div>
{% endif %}
```

---

## PHP Code Examples

### Example 1: Custom Post Type Setup

```php
// File: src/post-types/case-study.php
<?php
/**
 * PostType: Case Study
 * Name: Case Studies
 */

return [
    'args' => [
        'label' => 'Case Studies',
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'menu_position' => 7,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'case-studies'],
        'show_in_rest' => true,
    ],
];
```

### Example 2: Access Configuration with Type Safety

```php
// File: functions.php or custom template file

// Get entire config
$config = Theme_Config::get();

// Get specific section with type hints
$post_types = Theme_Config::get_post_types(); // array<string, mixed>
$menus = Theme_Config::get_menus();           // array<string, string>

// Get specific value safely
$text_domain = Theme_Config::get_text_domain(); // string|null

// Use in WordPress function
add_action( 'init', function() {
    $theme_name = Theme_Config::get_theme_meta()['name'];
    add_theme_support( 'title-tag' );
});
```

### Example 3: Custom Hook Integration

```php
// File: functions.php or in a class

// Hook into initialization
add_action( 'after_setup_theme', function() {
    // Use configuration system
    $menus = Theme_Config::get_menus();

    register_nav_menus( $menus );
});

// Hook into post save
add_action( 'acf/save_post', function() {
    $post_types = Theme_Config::get_post_types();

    // Custom logic based on config
    foreach ( $post_types as $type => $config ) {
        if ( isset( $config['custom_action'] ) ) {
            // Do something
        }
    }
});
```

### Example 4: Creating a Template Helper Function

```php
// File: functions.php

/**
 * Get featured image with fallback
 *
 * @param WP_Post $post      Post object
 * @param string  $size      Image size slug
 * @param bool    $force_src Return src string instead of object
 *
 * @return string|object|null Image src or object
 */
function get_featured_image( $post, $size = 'medium', $force_src = false ) {
    if ( ! $post ) {
        return null;
    }

    $image = $post->thumbnail( $size );

    if ( ! $image ) {
        // Return placeholder if no image
        return apply_filters( 'placeholder_image_url', admin_url( 'images/media-placeholder.png' ) );
    }

    return $force_src ? $image->src() : $image;
}

// Usage in template
{{ get_featured_image( post, 'card-featured' ) }}
```

---

## Template Examples

### Example 1: Homepage with Components

```twig
{# File: templates/index.twig #}
{% extends "base.twig" %}

{% block content %}
    <main class="main-content">

        {# Hero section #}
        {% include "components/hero.twig" with {
            title: "Welcome to " ~ site.name,
            subtitle: "Learn to drive with confidence",
            image: site.featured_image.src,
            cta_text: "Book a Lesson",
            cta_url: "/lessons"
        } %}

        {# Latest posts section #}
        <section class="posts-section mt-6">
            {% include "components/heading.twig" with {
                level: 2,
                text: "Latest Posts",
                accent: true
            } %}

            <div class="posts-grid">
                {% for post in posts %}
                    {% include "components/post-card.twig" with {
                        post: post,
                        show_excerpt: true,
                        show_meta: true
                    } %}
                {% endfor %}
            </div>
        </section>

        {# Testimonials section #}
        <section class="testimonials-section mt-6 bg-light">
            {% include "components/heading.twig" with {
                level: 2,
                text: "What Our Students Say",
                accent: false
            } %}

            <div class="testimonials-grid">
                {% for testimonial in testimonials %}
                    {% include "components/testimonial-card.twig" with {
                        post: testimonial,
                        show_rating: true
                    } %}
                {% endfor %}
            </div>
        </section>

    </main>
{% endblock %}
```

### Example 2: Single Post with ACF Data

```twig
{# File: templates/single.twig #}
{% extends "base.twig" %}

{% block content %}
    <article class="post-single">

        {# Featured image #}
        {% if post.thumbnail %}
            <figure class="post-single__featured">
                <img src="{{ post.thumbnail( 'hero' ).src }}" alt="{{ post.title }}" />
            </figure>
        {% endif %}

        {# Post header #}
        <header class="post-single__header">
            <h1 class="post-single__title">{{ post.title }}</h1>

            {# Post metadata #}
            <div class="post-single__meta">
                {% include "macros.twig" as macros %}
                {{ macros.meta_list([
                    "By " ~ post.author.name,
                    post.post_date|date( 'F j, Y' ),
                    "Read time: " ~ macros.read_time( post.content, 200 )
                ]) }}
            </div>
        </header>

        {# Post content #}
        <div class="post-single__content">
            {{ post.content }}
        </div>

        {# ACF fields (custom example) #}
        {% if post.get_field( 'service_duration' ) %}
            <aside class="post-single__sidebar">
                <h3>Lesson Details</h3>
                <p>
                    <strong>Duration:</strong>
                    {{ post.get_field( 'service_duration' ) }} minutes
                </p>
                <p>
                    <strong>Price:</strong>
                    {{ post.get_field( 'service_price' ) }}
                </p>
            </aside>
        {% endif %}

        {# Related posts #}
        {% if related_posts %}
            <section class="post-single__related mt-6">
                <h2>Related Posts</h2>
                <div class="related-posts">
                    {% for post in related_posts %}
                        {% include "components/post-card.twig" with {
                            post: post,
                            show_excerpt: false
                        } %}
                    {% endfor %}
                </div>
            </section>
        {% endif %}

    </article>
{% endblock %}
```

### Example 3: Archive with Filtering

```twig
{# File: templates/archive.twig #}
{% extends "base.twig" %}

{% block content %}
    <section class="archive">

        <h1 class="archive__title">{{ title }}</h1>

        {# Sidebar with filters #}
        <aside class="archive__sidebar">
            <h3>Filter Results</h3>

            {# Category filter #}
            <div class="filter-group">
                <h4>Categories</h4>
                <ul class="filter-list">
                    {% for term in categories %}
                        <li>
                            <a href="{{ term.link }}"
                               class="filter-list__link {% if term.current %}active{% endif %}">
                                {{ term.name }}
                            </a>
                        </li>
                    {% endfor %}
                </ul>
            </div>
        </aside>

        {# Main content #}
        <main class="archive__content">

            {% if posts %}
                <div class="posts-list">
                    {% for post in posts %}
                        {% include "components/post-card.twig" with {
                            post: post,
                            show_excerpt: true,
                            show_meta: true
                        } %}
                    {% endfor %}
                </div>

                {# Pagination #}
                <nav class="pagination" role="navigation">
                    {% if pagination.prev.link %}
                        <a href="{{ pagination.prev.link }}" class="btn btn--outline">
                            ← Previous Page
                        </a>
                    {% endif %}

                    <span class="pagination__info">
                        Page {{ pagination.current }} of {{ pagination.total }}
                    </span>

                    {% if pagination.next.link %}
                        <a href="{{ pagination.next.link }}" class="btn btn--outline">
                            Next Page →
                        </a>
                    {% endif %}
                </nav>
            {% else %}
                {% include "components/alert.twig" with {
                    message: "No posts found.",
                    type: "info",
                    dismissible: false
                } %}
            {% endif %}

        </main>

    </section>
{% endblock %}
```

---

## CSS/SCSS Examples

### Example 1: Creating a BEM Component

```scss
// File: css/inc/components/service-card.scss
/**
 * Service Card Component - BEM Methodology
 *
 * Block: .service-card
 * Elements: .service-card__icon, .service-card__title, .service-card__description
 * Modifiers: .service-card--featured, .service-card--highlighted
 */

.service-card {
  padding: 2rem;
  background: $white;
  border-radius: 8px;
  transition: all 0.3s ease;

  // Elements
  &__icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    display: inline-block;
  }

  &__title {
    font-size: $font-xl;
    font-weight: $font-bold;
    margin: 0 0 0.5rem 0;
    color: $black;
  }

  &__description {
    font-size: $font-base;
    color: #666;
    margin: 0;
    line-height: 1.6;
  }

  // Hover effect
  &:hover {
    box-shadow: 0 4px 12px rgba($black, 0.1);
    transform: translateY(-4px);
  }

  // Modifier: featured
  &--featured {
    border: 2px solid $primary;
    background: linear-gradient(135deg, #f8f9fa 0%, $white 100%);

    .service-card__title {
      color: $primary;
    }
  }

  // Modifier: highlighted
  &--highlighted {
    background: linear-gradient(
      135deg,
      $primary 0%,
      lighten($primary, 10%) 100%
    );
    color: $white;

    .service-card__title,
    .service-card__description {
      color: $white;
    }
  }

  // Responsive
  @media (max-width: $breakpoint-md) {
    padding: 1.5rem;

    &__icon {
      font-size: 2.5rem;
    }

    &__title {
      font-size: $font-lg;
    }
  }
}
```

**HTML Usage:**

```html
<!-- Default card -->
<div class="service-card">
  <div class="service-card__icon">🏎️</div>
  <h3 class="service-card__title">Beginner Lessons</h3>
  <p class="service-card__description">Perfect for new drivers</p>
</div>

<!-- Featured variant -->
<div class="service-card service-card--featured">
  <div class="service-card__icon">⭐</div>
  <h3 class="service-card__title">Premium Package</h3>
  <p class="service-card__description">Comprehensive driving education</p>
</div>
```

### Example 2: Responsive Grid Layout

```scss
// File: css/inc/global/main.scss (if needed)

.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 2rem;
  margin: 2rem 0;

  // Responsive adjustments
  @media (max-width: $breakpoint-lg) {
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1.5rem;
  }

  @media (max-width: $breakpoint-md) {
    grid-template-columns: 1fr;
    gap: 1rem;
  }
}
```

### Example 3: Custom Variables in SCSS

```scss
// File: css/inc/base/vars.scss

// Colors
$primary: #0066cc;
$secondary: #ff6b35;
$success: #28a745;
$warning: #ffc107;
$error: #dc3545;

// Neutral colors
$black: #1a1a1a;
$white: #ffffff;
$gray-light: #f5f5f5;
$gray-medium: #999;
$gray-dark: #333;

// Typography
$font-light: 300;
$font-normal: 400;
$font-semibold: 600;
$font-bold: 700;

$font-xs: 0.75rem;
$font-sm: 0.875rem;
$font-base: 1rem;
$font-lg: 1.125rem;
$font-xl: 1.25rem;
$font-2xl: 1.5rem;
$font-3xl: 1.875rem;
$font-4xl: 2.25rem;

// Spacing scale
$spacing-1: 0.25rem;
$spacing-2: 0.5rem;
$spacing-3: 0.75rem;
$spacing-4: 1rem;
$spacing-5: 1.5rem;
$spacing-6: 2rem;

// Breakpoints
$breakpoint-xs: 0;
$breakpoint-sm: 576px;
$breakpoint-md: 768px;
$breakpoint-lg: 992px;
$breakpoint-xl: 1200px;

// Transitions
$transition-fast: 0.2s ease;
$transition-normal: 0.3s ease;
$transition-slow: 0.5s ease;

// Shadows
$shadow-sm: 0 1px 3px rgba($black, 0.12);
$shadow-md: 0 4px 12px rgba($black, 0.15);
$shadow-lg: 0 8px 24px rgba($black, 0.18);
```

---

## Debugging & Troubleshooting

### Issue 1: Styles Not Updating

**Symptoms:** SCSS changes don't appear in browser

**Solutions:**

```bash
# 1. Ensure watch task is running
npm run watch

# 2. Recompile manually
npm run sass

# 3. Check for SCSS syntax errors
# Look at terminal output for compilation errors

# 4. Clear browser cache
# Hard refresh: Cmd+Shift+R (Mac) or Ctrl+Shift+R (Windows)

# 5. Check CSS file was generated
ls -la style.css  # Should show recent modification time
```

### Issue 2: Post Type Not Showing

**Symptoms:** New post type doesn't appear in WordPress admin

**Solutions:**

```php
// 1. Verify file exists
// File should be: src/post-types/my-type.php

// 2. Check file header format (must match exactly)
<?php
// PostType: My Type Name
// Name: Display Name

return [ /* config */ ];

// 3. Reload WordPress
// Clear all caches and refresh browser

// 4. Check post-types-registry.php is being called
// In functions.php, verify:
add_action( 'init', [ 'Timber_Post_Types', 'register_post_types' ] );

// 5. Debug: Check what post types are registered
// In template: {{ dump( wp ) }}
// Look for post_types in output
```

### Issue 3: ACF Fields Not Loading

**Symptoms:** ACF fields not visible in post editor or template

**Solutions:**

```php
// 1. Verify ACF Pro is activated
// WordPress admin > Plugins (should show "Activated")

// 2. Check field group is assigned to post type
// ACF admin > Field Groups > Edit Group
// Location rules should include post type

// 3. Verify field key in template matches
// Template: {{ post.get_field( 'field_key' ) }}
// ACF: Field key shown in field details

// 4. Debug: Print all fields
// Template: {{ dump( post.get_fields() ) }}

// 5. Check file permissions
chmod -R 755 wp-content/themes/starter-theme-1.x/
```

### Issue 4: Template Variables Not Available

**Symptoms:** Template shows empty or undefined variable

**Solutions:**

```twig
{# 1. Debug: Print all available context #}
<pre>{{ dump() }}</pre>

{# 2. Check specific variable #}
<pre>{{ dump( post ) }}</pre>

{# 3. Verify data is being passed from PHP #}
{# In view file: #}
$context = Timber::context();
$context['my_var'] = my_function();
Timber::render( 'template.twig', $context );

{# 4. Check type of variable #}
{% if post %}
    {% if post is iterable %}
        {# Treat as array/collection #}
    {% else %}
        {# Treat as single object #}
    {% endif %}
{% endif %}
```

### Issue 5: SCSS Compilation Error

**Symptoms:** `npm run watch` or `npm run sass` throws error

**Solutions:**

```bash
# 1. Check syntax error (usually shows line number)
# Review the line and fix syntax

# 2. Verify variable exists
# Common: Using $var that's not defined in vars.scss
# Solution: Add variable to css/inc/base/vars.scss

# 3. Check import path
# Common: @import "./css/...missing-file.scss"
# Solution: Verify file exists

# 4. Clear node_modules and reinstall
npm install

# 5. Update Sass
npm update sass
```

### General Debugging Tips

```php
// In PHP - add temporary debug output
error_log( print_r( $variable, true ) );
// Check /wp-content/debug.log

// Enable debug mode
// wp-config.php:
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

```twig
{# In Twig templates #}
{# Print single variable #}
<pre>{{ variable }}</pre>

{# Print all context #}
<pre>{{ dump() }}</pre>

{# Conditional debug (only for admins) #}
{% if current_user.can('manage_options') %}
    <div style="background: yellow; padding: 1rem;">
        DEBUG: {{ dump(post) }}
    </div>
{% endif %}
```

---

## Performance Optimization

### 1. Image Optimization

```php
// Use appropriate image sizes
{{ post.thumbnail( 'card' ).src }}  // Use defined sizes, not full image

// Lazy load images
<img src="{{ image.src }}" loading="lazy" alt="{{ image.alt }}" />

// Use responsive images
<img
    srcset="{{ image.src( 'small' ) }} 400w,
            {{ image.src( 'medium' ) }} 800w,
            {{ image.src( 'large' ) }} 1200w"
    alt="{{ image.alt }}"
/>
```

### 2. Caching

```php
// Cache expensive queries
$services = wp_cache_get( 'services_list' );
if ( ! $services ) {
    $services = new WP_Query( [
        'post_type' => 'services',
        'posts_per_page' => -1,
    ] );
    wp_cache_set( 'services_list', $services, '', 3600 );
}
```

### 3. Minify CSS/JS

```bash
# Already minified by Sass
npm run sass  # Produces minified CSS

# For JavaScript, use build tools
# Or add to functions.php:
wp_enqueue_script( 'script', get_stylesheet_directory_uri() . '/js/script.min.js' );
```

### 4. Database Optimization

```php
// Only load necessary post data
$posts = new WP_Query( [
    'post_type' => 'post',
    'posts_per_page' => 10,
    'fields' => 'ids',  // Only get IDs, build objects later
] );
```

---

## Security Best Practices

### 1. Sanitize User Input

```php
// Always sanitize
$input = sanitize_text_field( $_POST['input'] );
$email = sanitize_email( $_POST['email'] );

// Validate
if ( ! is_email( $email ) ) {
    wp_die( 'Invalid email' );
}
```

### 2. Escape Output

```php
// In PHP
echo esc_html( $post->title );
echo esc_url( $link );
echo esc_attr( $attribute );

// In Twig (automatic)
{{ post.title }}  {# Already escaped #}
{{ post.content }}  {# Use raw only when necessary #}
{{ post.content | raw }}  {# Explicitly not escaped #}
```

### 3. Use Nonces for Forms

```php
// Generate nonce
wp_nonce_field( 'save_post', 'post_nonce' );

// Verify nonce
if ( ! isset( $_POST['post_nonce'] )
     || ! wp_verify_nonce( $_POST['post_nonce'], 'save_post' ) ) {
    wp_die( 'Security check failed' );
}
```

### 4. Check User Capabilities

```php
// Check before showing content
if ( current_user_can( 'edit_post', $post_id ) ) {
    // Show edit link
}

// In template
{% if current_user.can( 'manage_options' ) %}
    {# Admin only content #}
{% endif %}
```

### 5. Keep WordPress Updated

```bash
# Regularly update WordPress and plugins
# In WordPress admin or:
wp core update
wp plugin update --all
```

---

## Contributing Guidelines

### Code Style

**PHP:**

```php
// Use PSR-2 style with WordPress conventions
class My_Class {
    public function my_method(): string {
        return 'value';
    }
}

// Type hints required
public function get_posts( int $count, string $type ): array {
    // ...
}
```

**SCSS:**

```scss
// Use BEM naming convention
.component {
  &__element {
  }
  &--modifier {
  }
}

// 2-space indentation
$color: #000;
```

**Twig:**

```twig
{# Use consistent spacing and indentation #}
{% include "components/button.twig" with {
    text: button_text,
    url: button_url
} %}
```

### Commit Messages

```bash
# Be descriptive and use imperative mood
git commit -m "Add testimonial carousel component"
git commit -m "Fix navigation menu mobile display"
git commit -m "Refactor post type registry system"

# NOT:
git commit -m "stuff"
git commit -m "fixed things"
```

### Pull Request Process

1. Create feature branch: `git checkout -b feature/description`
2. Make changes following code style
3. Test thoroughly (responsive, browsers, etc.)
4. Commit with clear messages
5. Push: `git push origin feature/description`
6. Create pull request with description

### Documentation Updates

- Update relevant .md files when adding features
- Keep examples current
- Document configuration changes
- Update troubleshooting section if adding known issues

---

## Additional Resources

### External Links

- **Timber Documentation:** https://timber.github.io/docs/
- **Twig Documentation:** https://twig.symfony.com/
- **ACF Documentation:** https://www.advancedcustomfields.com/resources/
- **WordPress Plugin Handbook:** https://developer.wordpress.org/plugins/
- **Bootstrap 5:** https://getbootstrap.com/docs/5.0/
- **BEM Methodology:** https://bem.info/

### Local Documentation Files

- [CONFIG-GUIDE.md](CONFIG-GUIDE.md) - Theme settings and configuration
- [COMPONENTS-GUIDE.md](COMPONENTS-GUIDE.md) - Twig components and macros
- [BEM-GUIDE.md](BEM-GUIDE.md) - CSS architecture and utilities
- [README.md](README.md) - Installation and quick start
- [ARCHITECTURE.md](ARCHITECTURE.md) - System design overview

### Useful Commands

```bash
# SCSS compilation
npm run sass    # One-time
npm run watch   # Watch for changes

# WordPress CLI (if installed)
wp theme info
wp post list
wp option get siteurl

# Git commands
git log --oneline      # View recent commits
git diff               # See uncommitted changes
git status             # View file changes
```

---

## Support & Questions

For questions about this theme:

1. **Check documentation** - Start with relevant .md file
2. **Search codebase** - Use IDE search to find similar code
3. **Debug output** - Use `{{ dump() }}` in Twig or `error_log()` in PHP
4. **Check comments** - Code comments explain design decisions
5. **Ask team** - Consult with other developers on the project

---

## Quick Reference

### File Locations

| Purpose              | Location                         |
| -------------------- | -------------------------------- |
| Theme configuration  | `src/config.php`                 |
| Configuration access | `src/theme-config.php`           |
| Post type setup      | `src/post-types/*.php`           |
| Block setup          | `templates/blocks/*.twig`        |
| Twig components      | `templates/components/*.twig`    |
| SCSS components      | `css/inc/components/*.scss`      |
| SCSS variables       | `css/inc/base/vars.scss`         |
| Utilities            | `css/inc/utilities/helpers.scss` |
| Main stylesheet      | `style.scss`                     |

### Common Functions

```php
// Configuration
Theme_Config::get( $section, $key );
Theme_Config::get_post_types();
Theme_Config::get_menus();

// WordPress
get_field( 'field_key' );           // ACF
get_posts( $args );
wp_get_archives( $args );
```

### Common Twig Tags

```twig
{% include "components/button.twig" %}  {# Include component #}
{% for item in items %}...{% endfor %}  {# Loop #}
{% if condition %}...{% endif %}        {# Conditional #}
{{ post.title }}                        {# Output variable #}
{{ post.get_field( 'key' ) }}          {# Get ACF field #}
```

---

**Document Version:** 1.0  
**Last Updated:** March 2026  
**Theme Version:** 1.0.0  
**PHP Version:** 7.4+  
**WordPress Version:** 6.0+
