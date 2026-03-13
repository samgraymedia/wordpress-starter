# Starter Theme

A WordPress starter theme using Timber, Twig, ACF Pro, Bootstrap, and BEM CSS.
Designed for structured, component-based theme development with modern build tools.

[![WordPress 6.0+](https://img.shields.io/badge/WordPress-6.0%2B-green)](https://wordpress.org/)
[![PHP 7.4+](https://img.shields.io/badge/PHP-7.4%2B-blue)](https://www.php.net/)
[![JavaScript ES6+](https://img.shields.io/badge/JavaScript-ES6%2B-yellow)](https://www.javascript.com/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

## Overview

Features:

- 15 pre-built custom Gutenberg blocks (Hero, Banner, Tabs, Blog Posts, Share Buttons, and more)
- Responsive mobile navigation with hamburger menu
- Auto-discovery registries (post types, taxonomies, page templates, blocks)
- Centralized configuration system with type-safe access
- Reusable Twig components and template macros
- BEM CSS for scalable stylesheets with Bootstrap 5.3
- ES6 JavaScript modules with Vite bundler
- Bootstrap 5.3 components (modals, dropdowns, tooltips, etc.)
- ACF Pro integration with block field configuration
- Optional WooCommerce support (works with or without the plugin)
- Comprehensive documentation for developers

## Why This Theme Exists

Most WordPress themes follow a copy-paste architecture: code and configuration scattered across functions.php, duplicated patterns, and little structure for scale.

This theme exists to solve that:

- **Scalability** — Auto-discovery registries eliminate boilerplate. Add a file and it is registered automatically.
- **Maintainability** — Centralized configuration keeps settings in one place. Type-safe helpers prevent errors.
- **Developer Experience** — Comprehensive documentation and modern build tools (Vite, ESLint) get teams productive fast.
- **Best Practices** — Type hints (PHP 7.4+), BEM CSS, component-based templates, ES6 modules, and proper architecture.
- **Professional Foundation** — Not a buzzword-heavy showcase theme, but an internal engineering starter kit.

Use this if you're building custom WordPress sites that need to scale beyond a single developer or project.

## Quick Start

### Prerequisites

**Required:**

- WordPress 6.0+
- PHP 7.4+
- Timber Plugin 2.x
- Advanced Custom Fields Pro
- Node.js 16+ (for build tools)

**Optional:**

- WooCommerce (for e-commerce functionality)

### Installation

```bash
# 1. Navigate to themes directory
cd wp-content/themes/

# 2. Copy theme folder
cp -r starter-theme my-theme

# 3. Install dependencies
cd my-theme
npm install

# 4. Activate required plugins (in WordPress admin)
# - Timber Library
# - Advanced Custom Fields Pro

# 5. (Optional) Activate WooCommerce plugin if you need e-commerce
# - WooCommerce

# 6. Activate theme
# Go to Appearance > Themes, activate "Starter Theme"

# 7. Start development (builds both JS and CSS)
npm run watch

# 8. For production
npm run build
```

## Documentation

Complete documentation:

- [QUICK-REFERENCE.md](QUICK-REFERENCE.md) - Fast lookup
- [DEVELOPER-GUIDE.md](DEVELOPER-GUIDE.md) - Complete development reference
- [BLOCKS-GUIDE.md](BLOCKS-GUIDE.md) - All 15 custom Gutenberg blocks reference
- [JAVASCRIPT-GUIDE.md](JAVASCRIPT-GUIDE.md) - JavaScript module system and Vite (includes hamburger menu)
- [ARCHITECTURE.md](ARCHITECTURE.md) - System design
- [CONFIG-GUIDE.md](CONFIG-GUIDE.md) - Configuration system
- [COMPONENTS-GUIDE.md](COMPONENTS-GUIDE.md) - Twig components
- [BEM-GUIDE.md](BEM-GUIDE.md) - CSS architecture
- [STYLE-SHEET.md](STYLE-SHEET.md) - Design system variables, colors, typography, spacing
- [OPTIONAL-FEATURES.md](OPTIONAL-FEATURES.md) - Optional plugins (WooCommerce, etc.)
- [TODO.md](TODO.md) Our To Do list

Start with [QUICK-REFERENCE.md](QUICK-REFERENCE.md) for a quick overview.

## Project Structure

```
starter-theme/
├── src/                      # PHP Application Code
│   ├── config.php           # Centralized theme configuration
│   ├── theme-config.php     # Configuration accessor (typed)
│   ├── post-types-registry.php    # Auto-discovery: post types
│   ├── taxonomies-registry.php    # Auto-discovery: taxonomies
│   ├── page-templates-registry.php # Auto-discovery: page templates
│   ├── post-types/          # Individual post type configs
│   ├── taxonomies/          # Individual taxonomy configs
│   ├── page-templates/      # Individual page template configs
│   └── timber-acf-wp-blocks.php # Block auto-discovery
│
├── templates/               # Twig Templates
│   ├── base.twig           # Master layout
│   ├── index.twig          # Homepage/blog
│   ├── components/         # Reusable components
│   └── macros.twig         # Template macros
│
├── scss/                    # SCSS Stylesheets (BEM)
│   ├── inc/
│   │   ├── base/           # Variables & foundation
│   │   ├── components/     # BEM components
│   │   ├── global/         # Large sections
│   │   └── utilities/      # Helper classes
│   └── style.scss          # Main import file
│
├── views/                   # PHP Template Files (WordPress template hierarchy)
├── static/                  # Images, fonts, JS
├── functions.php            # Theme initialization
├── package.json             # npm configuration
│
└── Documentation/
    ├── README.md            # This file
    ├── QUICK-REFERENCE.md   # Quick lookup guide
    ├── DEVELOPER-GUIDE.md   # Complete reference
    ├── ARCHITECTURE.md      # System design
    ├── CONFIG-GUIDE.md      # Configuration
    ├── COMPONENTS-GUIDE.md  # Components
    └── BEM-GUIDE.md         # CSS architecture
```

See [DEVELOPER-GUIDE.md](DEVELOPER-GUIDE.md#directory-structure) for detailed structure.

## Technology Stack

### Runtime

- WordPress 6.0+
- Timber 2.x
- Twig 2.x
- ACF Pro
- Bootstrap 5.3.7

### Development

- PHP 7.4+ (with type hints)
- Sass/SCSS
- Node.js

## Key Features

### 1. Centralized Configuration

All theme settings in one place:

```php
// src/config.php
return [
    'post_types' => [...],
    'menus' => [...],
    'image_sizes' => [...],
    'acf' => [...],
];

// Access with type safety
Theme_Config::get_menus();  // array<string, string>
```

### 2. Auto-Discovery System

Create a file and it's auto-registered:

```
src/post-types/my-type.php → Auto-registered post type
src/taxonomies/my-tax.php → Auto-registered taxonomy
src/page-templates/my-template.php → Auto-registered page template
templates/blocks/my-block.twig → Auto-registered block
```

### 3. Reusable Twig Components

Includes several built-in Twig components such as button, card, alert, and heading.

See [COMPONENTS-GUIDE.md](COMPONENTS-GUIDE.md) for details.

### 4. Taxonomies Registry

Auto-discovered taxonomies:

```php
// File: src/taxonomies/my-taxonomy.php
// Taxonomy: my-taxonomy
// Name: My Taxonomy
// PostTypes: post,page

return [ /* ... */ ];
```

### 5. Page Templates Registry

Auto-discovered custom page layouts:

```php
// File: src/page-templates/hero-layout.php
// PageTemplate: hero-layout
// Title: Hero Layout
// TwigTemplate: templates/page-hero-layout.twig
```

Assign from WordPress page editor dropdown.

### 6. BEM CSS Organization

Scalable, maintainable stylesheet:

```scss
.button {
} // Block
.button__text {
} // Element
.button--primary {
} // Modifier
```

### 7. Type-Safe PHP

Full PHP 7.4+ type hints for IDE support and reliability.

## Development

```bash
# Install dependencies
npm install

# Compile SCSS once
npm run sass

# Watch SCSS for changes
npm run watch   # Auto-compiles on file change

# Start developing
code .
```

## Common Tasks

### Add New Post Type

```php
// File: src/post-types/my-type.php
// PostType: My Type
// Name: My Type

return [
    'args' => [
        'label' => 'My Types',
        'public' => true,
    ],
];
```

### Add New Taxonomy

```php
// File: src/taxonomies/my-taxonomy.php
// Taxonomy: my-taxonomy
// Name: My Taxonomy
// PostTypes: post,page

return [
    'args' => [
        'labels' => ['name' => 'My Taxonomies'],
        'public' => true,
    ],
];
```

### Add Custom Page Template

```php
// File: src/page-templates/my-template.php
// PageTemplate: my-template
// Title: My Custom Layout
// PostTypes: page
// TwigTemplate: templates/page-my-template.twig
```

Then create `templates/page-my-template.twig` with your custom layout.

### Add New Component

```twig
{# File: templates/components/my-component.twig #}
{# Props: title, content #}

<div class="my-component">
    <h3>{{ title }}</h3>
    <p>{{ content }}</p>
</div>
```

### Create BEM Component

```scss
// File: css/inc/components/my-component.scss
.my-component {
  &__element {
  }
  &--modifier {
  }
}
```

See [DEVELOPER-GUIDE.md#common-tasks](DEVELOPER-GUIDE.md#common-tasks) for more examples.

## Troubleshooting

For debugging and issues, see [DEVELOPER-GUIDE.md#debugging--troubleshooting](DEVELOPER-GUIDE.md#debugging--troubleshooting).

## External Resources

- [Timber Documentation](https://timber.github.io/docs/)
- [Twig Documentation](https://twig.symfony.com/)
- [ACF Documentation](https://www.advancedcustomfields.com/resources/)
- [WordPress Handbook](https://developer.wordpress.org/plugins/)
- [Bootstrap 5 Docs](https://getbootstrap.com/docs/5.3/)
- [BEM Methodology](https://bem.info/)

## Contributing

See [DEVELOPER-GUIDE.md#contributing-guidelines](DEVELOPER-GUIDE.md#contributing-guidelines) for guidelines.

## Changelog

**1.0.0** (March 2026)

- Type-safe PHP with auto-discovery registries
- BEM CSS architecture
- Comprehensive documentation

## License

MIT License. See LICENSE file for details.

---

**Version:** 1.0.0 | **PHP:** 7.4+ | **WordPress:** 6.0+ | **Last Updated:** March 2026
