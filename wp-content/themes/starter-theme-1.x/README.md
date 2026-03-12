# Abbie - Eleanor's Driving School WordPress Theme

**A modern, type-safe WordPress theme built with Timber, ACF Pro, and BEM CSS methodology.**

[![WordPress 6.0+](https://img.shields.io/badge/WordPress-6.0%2B-green)](https://wordpress.org/)
[![PHP 7.4+](https://img.shields.io/badge/PHP-7.4%2B-blue)](https://www.php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

## 📋 Overview

This is **Eleanor's Driving School** WordPress theme featuring:

✅ **Modern Architecture** - Registry patterns, centralized configuration, auto-discovery  
✅ **Type-Safe PHP** - Full PHP 7.4+ type hints (19+ methods typed)  
✅ **Reusable Components** - 5 Twig components + 9 template macros  
✅ **BEM CSS** - Scalable, maintainable stylesheet organization  
✅ **ACF Integration** - Advanced field configuration with blocks  
✅ **Developer Friendly** - Comprehensive documentation and guides

## 🚀 Quick Start

### Prerequisites

- WordPress 6.0+
- PHP 7.4+
- Timber Plugin 2.x
- Advanced Custom Fields Pro
- Node.js 16+ (for SCSS compilation)

### Installation

```bash
# 1. Navigate to themes directory
cd wp-content/themes/

# 2. Copy theme folder
cp -r starter-theme-1.x my-theme

# 3. Install dependencies
cd my-theme
npm install

# 4. Activate plugins (in WordPress admin)
# - Timber Library
# - Advanced Custom Fields Pro
# - Contact Form 7

# 5. Activate theme
# Go to Appearance > Themes, activate "Abbie"

# 6. Compile SCSS
npm run sass

# 7. Start developing
npm run watch
```

## 📚 Documentation

Complete documentation is organized into specialized guides:

| Document                                       | Purpose                                    |
| ---------------------------------------------- | ------------------------------------------ |
| **[QUICK-REFERENCE.md](QUICK-REFERENCE.md)**   | Fast lookup for common tasks               |
| **[DEVELOPER-GUIDE.md](DEVELOPER-GUIDE.md)**   | Complete development reference (20+ pages) |
| **[ARCHITECTURE.md](ARCHITECTURE.md)**         | System design and design patterns          |
| **[CONFIG-GUIDE.md](CONFIG-GUIDE.md)**         | Theme configuration system                 |
| **[COMPONENTS-GUIDE.md](COMPONENTS-GUIDE.md)** | Twig components and macros                 |
| **[BEM-GUIDE.md](BEM-GUIDE.md)**               | CSS architecture and utilities             |

**New to the project?** Start with [QUICK-REFERENCE.md](QUICK-REFERENCE.md) then dive into [DEVELOPER-GUIDE.md](DEVELOPER-GUIDE.md).

## 📁 Project Structure

```
starter-theme-1.x/
├── src/                      # PHP Application Code
│   ├── config.php           # Centralized theme configuration
│   ├── theme-config.php     # Configuration accessor (typed)
│   ├── post-types-registry.php  # Auto-discovery registry
│   ├── post-types/          # Individual post type configs
│   └── timber-acf-wp-blocks.php # Block auto-discovery
│
├── templates/               # Twig Templates
│   ├── base.twig           # Master layout
│   ├── index.twig          # Homepage/blog
│   ├── components/         # Reusable components
│   └── macros.twig         # Template macros
│
├── css/                     # SCSS Stylesheets (BEM)
│   ├── inc/
│   │   ├── base/           # Variables & foundation
│   │   ├── components/     # BEM components
│   │   ├── global/         # Large sections
│   │   └── utilities/      # Helper classes
│   └── style.scss          # Main import file
│
├── views/                   # WordPress Template Hierarchy
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

## 🛠 Technology Stack

### Core Framework

- **WordPress** 6.0+ - CMS foundation
- **Timber** 2.x - PHP-to-Twig bridge
- **Twig** 2.x - Template engine
- **ACF Pro** - Field management
- **Bootstrap** 5.3.7 - CSS framework

### Development

- **PHP** 7.4+ - Backend language (with type hints)
- **SCSS/Sass** - CSS preprocessing
- **Node.js** - Build tools

### Plugins

- Timber Library
- Advanced Custom Fields Pro
- Contact Form 7
- CF7 Database Addon

## 🎯 Key Features

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

Create a file, it's auto-registered:

```
src/post-types/my-type.php → Auto-registered post type
templates/blocks/my-block.twig → Auto-registered block
```

### 3. Reusable Components

5 built-in Twig components:

```twig
{% include "components/button.twig" %}
{% include "components/card.twig" %}
{% include "components/alert.twig" %}
{% include "components/heading.twig" %}
{% include "components/testimonial-card.twig" %}
```

### 4. Template Macros

9 reusable template functions:

```twig
{% import "macros.twig" as m %}
{{ m.badge("New", "primary") }}
{{ m.price(99.99, "USD") }}
{{ m.rating(4, 5) }}
```

### 5. BEM CSS Organization

Scalable, maintainable stylesheet:

```scss
.button {
} // Block
.button__text {
} // Element
.button--primary {
} // Modifier
```

### 6. Type-Safe PHP

Full type hints for IDE support and reliability:

```php
public function get_posts( int $count, string $type ): array {
    // Type hints on 19+ methods
}
```

## 💻 Development Commands

```bash
# Watch SCSS for changes (use in one terminal)
npm run watch

# Compile SCSS once
npm run sass

# Start development
code .  # Open in editor
# Visit http://localhost:8888/wordpress-starter/
```

## 🔧 Common Tasks

### Add New Post Type

```php
// File: src/post-types/my-type.php
<?php
// PostType: My Type
// Name: My Type

return [
    'args' => [
        'label' => 'My Types',
        'public' => true,
    ],
];
```

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

See [DEVELOPER-GUIDE.md](DEVELOPER-GUIDE.md#common-tasks) for more examples.

## 📖 Documentation Highlights

### QUICK-REFERENCE.md

- File locations
- Quick commands
- Common tasks
- CSS utilities
- Debugging tips

### DEVELOPER-GUIDE.md

- Project overview
- Technology stack
- File organization
- Development workflow
- 100+ code examples
- Debugging guide
- Security best practices
- Performance optimization

### ARCHITECTURE.md

- Layered architecture
- Design patterns (6 major patterns)
- Data flow diagrams
- Dependency management
- Extensibility guide
- Security architecture
- Scaling strategies

### CONFIG-GUIDE.md

- Configuration system
- All available settings
- How to add settings
- Post type configuration
- ACF setup
- Image size management

### COMPONENTS-GUIDE.md

- Component documentation
- Component usage examples
- Macro reference
- Best practices
- Creating new components

### BEM-GUIDE.md

- BEM methodology
- Naming conventions
- Component examples
- Utilities reference
- Best practices
- File structure

## 🔐 Security

This theme follows WordPress security best practices:

✅ Input sanitization  
✅ Output escaping  
✅ Nonce verification  
✅ Capability checking  
✅ SQL protection

See [DEVELOPER-GUIDE.md](DEVELOPER-GUIDE.md#security-best-practices) for security guidelines.

## 🚀 Performance

Built with performance in mind:

✅ Minified CSS output  
✅ Optimized SCSS imports  
✅ Image size optimization  
✅ Efficient database queries  
✅ Template caching support

See [DEVELOPER-GUIDE.md](DEVELOPER-GUIDE.md#performance-optimization) for optimization guide.

## 🐛 Troubleshooting

### Styles not updating?

```bash
# Hard refresh browser
Cmd+Shift+R (Mac) or Ctrl+Shift+R (Windows)

# Ensure watch is running
npm run watch

# Check for SCSS errors
npm run sass
```

### Post type not showing?

- Check file exists: `src/post-types/my-type.php`
- Check file header format
- Clear WordPress cache
- Refresh browser

### Component not rendering?

- Check file path in `{% include %}`
- Check prop names
- Use `{{ dump() }}` to debug
- Check template syntax

See [DEVELOPER-GUIDE.md](DEVELOPER-GUIDE.md#debugging--troubleshooting) for more troubleshooting.

## 📚 External Resources

- **[Timber Documentation](https://timber.github.io/docs/)**
- **[Twig Documentation](https://twig.symfony.com/)**
- **[ACF Documentation](https://www.advancedcustomfields.com/resources/)**
- **[WordPress Plugin Handbook](https://developer.wordpress.org/plugins/)**
- **[Bootstrap 5 Docs](https://getbootstrap.com/docs/5.0/)**
- **[BEM Methodology](https://bem.info/)**

## 👥 Contributing

This is a professional theme for Eleanor's Driving School. Follow these guidelines:

1. **Code Style** - Follow PSR-2 standards with WordPress conventions
2. **Type Hints** - Add type hints to all new methods (PHP 7.4+)
3. **Documentation** - Update relevant .md files
4. **Testing** - Test responsive design and browser compatibility
5. **Commit Messages** - Use clear, descriptive messages

See [DEVELOPER-GUIDE.md](DEVELOPER-GUIDE.md#contributing-guidelines) for full guidelines.

## 📝 Version History

**Version 1.0.0** (March 2026)

- Initial theme release
- Full type hints implementation
- BEM CSS architecture
- Comprehensive documentation
- Registry-based auto-discovery
- Reusable components system

## 📄 License

This theme is based on the Timber Starter Theme. See LICENSE file for details.

## 🤝 Support

For questions or issues:

1. **Check documentation** - Start with [QUICK-REFERENCE.md](QUICK-REFERENCE.md)
2. **Search codebase** - Use IDE search for similar implementations
3. **Debug output** - Use `{{ dump() }}` in Twig or `error_log()` in PHP
4. **Consult team** - Ask team members for guidance

---

**Theme Name:** Abbie  
**Client:** Eleanor's Driving School  
**Version:** 1.0.0  
**PHP:** 7.4+  
**WordPress:** 6.0+  
**Last Updated:** March 2026
