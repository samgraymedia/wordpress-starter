---
name: wordpress
description: Expert knowledge of this team's WordPress starter theme. Use when building, editing, or debugging anything in the starter-theme-1.x WordPress theme — blocks, templates, SCSS, JavaScript modules, WooCommerce pages, or PHP src files. Covers Timber/Twig templates, ACF Gutenberg blocks, Vite build system, SCSS architecture, and WooCommerce integration patterns.
---

## Overview

This skill teaches Claude how to build WordPress themes using the **WordPress Starter Theme 1.x** methodology—a modern, component-based, highly modular approach to WordPress theme development.

**Repository:** `/Applications/MAMP/htdocs/wordpress-starter-store`  
**Stack:** WordPress 6.0+, Timber 2.x, ACF Pro, Vite, Bootstrap 5.3, PHP 7.4+ with type hints  
**Architecture:** Layered (Presentation → Application → Service → Data)

---

## Core Philosophy

The starter theme follows **4 core principles:**

1. **Auto-Discovery Registries** — No manual registration; systems auto-discover from directories
2. **Centralized Configuration** — All settings in one place (`src/config.php`)
3. **Separation of Concerns** — Logic (PHP), presentation (Twig), styling (SCSS), scripting (ES6) are separate
4. **Component-Based UI** — Reusable Twig components, BEM CSS, modular JavaScript

---

## Project Structure

```
starter-theme-1.x/
├── src/                          # Service layer & configuration
│   ├── config.php                # Central theme config
│   ├── theme-config.php          # Config access helper (typed)
│   ├── post-types-registry.php   # Auto-discover post types
│   ├── taxonomies-registry.php   # Auto-discover taxonomies
│   ├── page-templates-registry.php # Auto-discover page templates
│   ├── timber-acf-wp-blocks.php  # Auto-discover Gutenberg blocks
│   ├── post-types/               # Custom post type definitions (auto-discovered)
│   ├── taxonomies/               # Taxonomy definitions (auto-discovered)
│   ├── page-templates/           # Custom page templates (auto-discovered)
│   ├── blocks/                   # ACF field definitions for blocks
│   ├── woocommerce-integration.php # Optional WooCommerce support
│   └── admin/                    # Admin customizations
│
├── views/                        # Application layer (WordPress routing)
│   ├── index.php                 # Blog entry
│   ├── single.php                # Single post entry
│   ├── page.php                  # Static page entry
│   ├── archive.php               # Archive entry
│   ├── author.php                # Author template
│   ├── search.php                # Search results
│   └── blocks/                   # Block template files
│
├── templates/                    # Presentation layer (Twig templates)
│   ├── base.twig                 # Master layout
│   ├── index.twig                # Blog listing
│   ├── single.twig               # Single post
│   ├── page.twig                 # Static pages
│   ├── components/               # Reusable UI components
│   ├── partial/                  # Partial templates
│   ├── macros.twig               # Template helper macros
│   └── blocks/                   # Block Twig templates
│
├── css/                          # SCSS stylesheets
│   ├── inc/
│   │   ├── base/                 # Variables, reset, typography
│   │   ├── global/               # Layout, general styles
│   │   ├── components/           # Component styles (BEM)
│   │   ├── blocks/               # Block styles
│   │   └── utilities/            # Utility classes
│   └── style.scss                # Main compiled stylesheet
│
├── scripts/                      # JavaScript modules
│   ├── index.js                  # Entry point
│   ├── utils/                    # Utility functions
│   ├── modules/                  # Feature modules
│   └── hamburger.js              # Mobile navigation (IIFE pattern)
│
├── functions.php                 # Theme initialization & hooks
├── package.json                  # Node dependencies
├── vite.config.js                # Vite bundler config
└── .eslintrc.json                # ESLint configuration
```

---

## Architecture Layers

### Layer 1: Data Layer
- WordPress database & core
- Timber data objects (Posts, Terms, Menus)
- ACF field data
- WooCommerce (optional)

### Layer 2: Service Layer (`src/`)
- Configuration system (`config.php`, `theme-config.php`)
- Auto-discovery registries
- Post type definitions
- Taxonomy definitions
- Page template definitions
- Block field definitions
- Admin customizations
- WooCommerce integrations

### Layer 3: Application Layer (`views/`)
- WordPress template hierarchy entry points
- Route to Timber/Twig
- Query building
- Data preparation

### Layer 4: Presentation Layer (`templates/`)
- Twig templates (master, pages, components)
- Reusable components
- Template macros
- Block templates

---

## Auto-Discovery Registries

### 1. Post Types Registry (`src/post-types-registry.php`)
**Auto-discovers files from `src/post-types/`**

**How it works:**
- Scans `src/post-types/` directory at init hook (priority 9-10)
- Each PHP file returns array with `'args'` and `'fields'` keys
- Registers post type with WordPress
- Auto-attaches ACF field group from `'fields'` key

**Creating a post type (`src/post-types/my-post.php`):**
```php
<?php
/**
 * My Custom Post Type
 * 
 * Slug: my-post
 * Menu Position: 5
 */

return array(
    'args' => array(
        'label'             => 'My Posts',
        'public'            => true,
        'has_archive'       => true,
        'hierarchical'      => false,
        'supports'          => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_position'     => 5,
        'menu_icon'         => 'dashicons-documents',
        'taxonomies'        => array('category', 'post_tag'),
        'rewrite'           => array('slug' => 'my-posts'),
    ),
    'fields' => array(
        'key'       => 'group_my_post',
        'title'     => 'My Post Fields',
        'fields'    => array(
            array(
                'key'     => 'field_my_post_subtitle',
                'label'   => 'Subtitle',
                'name'    => 'subtitle',
                'type'    => 'text',
            ),
        ),
        'location'  => array(
            array(
                array(
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'my-post',
                ),
            ),
        ),
    ),
);
```

**Built-in post types:**
- `blog` — Standard WordPress posts
- `service` — Services with pricing & testimonials
- `testimonial` — Customer testimonials with ratings

### 2. Taxonomies Registry (`src/taxonomies-registry.php`)
**Auto-discovers files from `src/taxonomies/`**

**Creating a taxonomy (`src/taxonomies/my-category.php`):**
```php
<?php
return array(
    'args' => array(
        'label'             => 'My Categories',
        'hierarchical'      => true,
        'rewrite'           => array('slug' => 'my-category'),
    ),
    'post_types' => array('my-post'),
);
```

**Built-in taxonomies:**
- `service-category` — Categories for services
- `service-tag` — Tags for services
- `testimonial-category` — Categories for testimonials

### 3. Page Templates Registry (`src/page-templates-registry.php`)
**Auto-discovers files from `src/page-templates/`**

**Creating a page template (`src/page-templates/my-layout.php`):**
```php
<?php
/**
 * My Custom Layout
 * 
 * PageTemplate: my-layout              // Unique ID (required)
 * Title: My Custom Layout              // Display name (required)
 * Description: Custom full-width layout
 * PostTypes: page,post,service         // Comma-separated (default: page)
 * TwigTemplate: templates/my-layout.twig
 */
```

Then create the Twig template: `templates/my-layout.twig`

### 4. Blocks Registry (`src/timber-acf-wp-blocks.php`)
**Auto-discovers block templates from `views/blocks/`**

**How it works:**
- Scans `views/blocks/` for `.twig` files
- Reads comment header for block metadata
- Creates corresponding ACF field group if `src/blocks/` file exists
- Registers as Gutenberg block automatically

**Creating a block:**

1. **Twig template (`views/blocks/my-block.twig`):**
```twig
{#
    Title: My Block
    Description: My custom block
    Category: common
    Icon: dashicons-star-filled
    Keywords: custom block
    Mode: auto
    PostTypes: page post
    SupportsMode: true
    SupportsMultiple: true
#}

<div class="my-block">
    {# Block content here #}
</div>
```

2. **ACF fields (`src/blocks/my-block.php`)** (optional):
```php
<?php
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_my_block',
    'title' => 'My Block Fields',
    'fields' => array(
        array(
            'key' => 'field_my_block_title',
            'label' => 'Title',
            'name' => 'title',
            'type' => 'text',
        ),
    ),
    'location' => array(
        array(
            array(
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/my-block',
            ),
        ),
    ),
));

endif;
```

3. **Styles (`css/inc/blocks/my-block.scss`):**
```scss
/**
 * My Block Styles
 */
.my-block {
    // Block styling
}
```

---

## Centralized Configuration

**Location:** `src/config.php`

All theme settings are defined here and accessed via `Theme_Config` helper class:

```php
// Get entire config
$config = Theme_Config::get();

// Get specific sections
$post_types = Theme_Config::get_post_types();
$taxonomies = Theme_Config::get_taxonomies();
$menus = Theme_Config::get_menus();
$image_sizes = Theme_Config::get_image_sizes();
$blocks = Theme_Config::get_blocks();
$theme_support = Theme_Config::get_theme_support();

// Get specific items
$service_post_type = Theme_Config::get_post_type('service');
$primary_menu = Theme_Config::get_menu('primary');
$thumbnail_size = Theme_Config::get_image_size('thumbnail');
```

---

## Twig Templating

### Master Template (`templates/base.twig`)
```twig
<!DOCTYPE html>
<html {{ site.language_attributes }}>
<head>
    {{ site.head }}
</head>
<body>
    <header>
        {# Header content #}
    </header>
    
    <main id="content">
        {% block content %}{% endblock %}
    </main>
    
    <footer>
        {# Footer content #}
    </footer>
    
    {{ wp_footer }}
</body>
</html>
```

### Component Pattern
```twig
{# templates/components/post-card.twig #}
<article class="post-card">
    <img src="{{ post.thumbnail.src }}" alt="{{ post.title }}">
    <h3>{{ post.title }}</h3>
    <p>{{ post.excerpt }}</p>
    <a href="{{ post.link }}" class="btn">Read More</a>
</article>
```

**Using components:**
```twig
{% include 'components/post-card.twig' with { post: post } %}
```

### Template Naming Convention
- **Single post:** `templates/single-{{ post_type }}.twig`
- **Archive:** `templates/archive-{{ post_type }}.twig`
- **Page:** `templates/page.twig`
- **Index:** `templates/index.twig`
- **Block:** `views/blocks/{{ block_name }}.twig`

### Common Twig Snippets
```twig
{# Get ACF field #}
{{ post.get_field('field_name') }}

{# Loop posts #}
{% for post in posts %}
    <h2>{{ post.title }}</h2>
{% endfor %}

{# Conditional display #}
{% if post.get_field('active') %}
    Active
{% endif %}

{# Debug object #}
{{ dump(post) }}

{# Format date #}
{{ post.post_date|date('F j, Y') }}
```

---

## CSS Architecture (BEM)

**Methodology:** Block Element Modifier (BEM)

**Structure:** `css/inc/` contains:
- `base/` — Reset, typography, variables
- `global/` — Layout, shared styles
- `components/` — Component styles
- `blocks/` — Gutenberg block styles
- `utilities/` — Utility classes

**BEM Pattern:**
```scss
.component {                      // Block
    background: white;
    
    &__header {                   // Element
        padding: 1rem;
        
        &--featured {             // Modifier
            background: blue;
        }
    }
    
    &__item {
        margin: 0.5rem;
    }
}
```

**Usage in Twig:**
```twig
<div class="component">
    <div class="component__header component__header--featured">
        <h2>Featured Header</h2>
    </div>
    <div class="component__item">
        Item content
    </div>
</div>
```

---

## JavaScript Modules

**Pattern:** IIFE (Immediately Invoked Function Expression)

All modules auto-execute when imported—no manual function calls needed.

**Example module (`scripts/modules/toggle.js`):**
```javascript
// Automatically executed when imported
(function() {
    const toggles = document.querySelectorAll('.js-toggle');
    
    toggles.forEach(toggle => {
        toggle.addEventListener('click', (e) => {
            e.target.classList.toggle('active');
        }, { passive: true });
    });
    
    console.log('[Toggle Module] Initialized');
})();
```

**Entry point (`scripts/index.js`):**
```javascript
// Import modules (they auto-execute)
import './modules/toggle';
import './modules/carousel';
import './utils/helpers';

console.log('[Scripts] All modules loaded');
```

### Scroll Animation Pattern

The starter includes a **collection products scroll animation** (`scripts/collection-products.js`):

**How it works:**
1. Detects `.js-scroll-translate` elements
2. Auto-detects scrollable container (#content, window, body, or parent with overflow)
3. Calculates scroll percentage
4. For each item: `moveX = scrollPercent * itemIndex * 100`
5. Applies: `transform: translate(moveX%, 50%)`

**Template integration:**
```twig
<div class="collection-product-item">
    First item (no animation)
</div>

<div class="collection-product-item js-scroll-translate" 
     data-index="{{ loop.index }}" 
     style="transform: translate(0%, 50%);">
    Item {{ loop.index }} (animated)
</div>
```

---

## Build Tools

### Vite Configuration
**File:** `vite.config.js`

- Fast HMR (Hot Module Replacement) dev server
- SCSS preprocessing
- Asset bundling & minification
- Output to `dist/` directory

### npm Scripts
```bash
npm run dev          # Start Vite dev server
npm run watch        # Watch mode (dev + SCSS watch)
npm run watch:css    # SCSS watch only
npm run build        # Production build
npm run lint         # Run ESLint
```

### Asset Enqueuing
In `functions.php`, assets are enqueued via `theme_enqueue_assets` hook:

```php
add_action('theme_enqueue_assets', function() {
    // Enqueue CSS
    wp_enqueue_style(
        'theme-style',
        get_template_directory_uri() . '/dist/css/style.css',
        array(),
        filemtime(get_template_directory() . '/dist/css/style.css')
    );
    
    // Enqueue JS
    wp_enqueue_script(
        'theme-script',
        get_template_directory_uri() . '/dist/js/index.js',
        array(),
        filemtime(get_template_directory() . '/dist/js/index.js'),
        true
    );
});
```

---

## Custom Post Types

### 1. Service Post Type
**Slug:** `service`  
**Display name:** Services  
**Supports:** title, editor, thumbnail, excerpt  
**Taxonomies:** service-category, service-tag

**ACF Fields:**
- Price
- Duration
- Features (repeater)
- Icon
- CTA text & link
- Related testimonials (relationship)

**Templates:**
- `templates/archive-services.twig` — Service listing
- `templates/single-services.twig` — Service details

**Blocks:**
- Service Showcase (grid/list layout)
- Service Comparison (table/cards/accordion)
- Service Pricing (pricing tiers)
- Service Testimonials (carousel/grid)

### 2. Testimonial Post Type
**Slug:** `testimonial`  
**Display name:** Testimonials  
**Menu icon:** dashicons-format-quote

**ACF Fields:**
- Rating (1-5 stars)
- Testimonial text
- Author name & role
- Category (testimonial-category taxonomy)

**Seeded data:** 4 sample testimonials from `insert-testimonials.sql`

### 3. Blog Post Type (Default)
Standard WordPress posts with title, editor, thumbnail, excerpt, categories, tags.

---

## WooCommerce Integration

**File:** `src/woocommerce-integration.php`

Automatically activates when WooCommerce plugin is active.

**Features:**
- Custom shop page template
- Custom product template
- Product archive template
- Image size registration
- Price formatting
- Cart/checkout support

**Templates:**
- `templates/shop.twig` — Shop page
- `templates/single-product.twig` — Product details
- `templates/archive-product.twig` — Product archive

**Reference:** WOOCOMMERCE-GUIDE.md in theme documentation

---

## Development Workflow

### Setup
```bash
1. Clone/download theme
2. npm install
3. Activate required plugins (Timber, ACF Pro)
4. Activate theme in WordPress admin
5. npm run watch
```

### Development
```bash
npm run watch        # Start dev server + watch SCSS
npm run dev          # Dev server only
npm run watch:css    # SCSS watcher only
```

Edit files in:
- `src/` — Service layer
- `views/` — Application layer
- `templates/` — Presentation layer
- `scripts/` — JavaScript
- `css/` — SCSS stylesheets

### Production
```bash
npm run build        # Compile for production
```

Files compiled to `dist/` directory ready for deployment.

### Debugging
- Check `wp-content/debug.log` for errors
- Use `{{ dump(post) }}` in Twig to inspect objects
- Browser DevTools for frontend debugging
- Enable WordPress debug mode in `wp-config.php`

---

## Common Development Tasks

### Adding a New Post Type
1. Create file in `src/post-types/my-type.php`
2. Return array with `'args'` and `'fields'` keys
3. Create `views/single-my-type.php`
4. Create `templates/single-my-type.twig`
5. Auto-discovered and registered on next page load

### Adding a New Taxonomy
1. Create file in `src/taxonomies/my-tax.php`
2. Return array with taxonomy config
3. Auto-discovered and registered

### Adding a New Gutenberg Block
1. Create Twig template in `views/blocks/my-block.twig` with headers
2. Create ACF fields (optional) in `src/blocks/my-block.php`
3. Create styles in `css/inc/blocks/my-block.scss`
4. Auto-discovered and registered

### Adding a New Page Template
1. Create file in `src/page-templates/my-template.php` with headers
2. Create Twig template at `templates/my-template.twig`
3. Auto-discovered and available in WordPress page editor

### Creating a Reusable Component
1. Create Twig template in `templates/components/my-component.twig`
2. Include in other templates: `{% include 'components/my-component.twig' %}`
3. Pass data via `with` clause: `{% include 'components/my-component.twig' with { data: value } %}`

---

## Best Practices

### PHP
- Always use `Theme_Config::get()` for configuration access
- Type hint all function parameters (PHP 7.4+)
- Use file headers for metadata (post types, taxonomies, blocks, templates)
- Follow WordPress naming conventions

### Twig
- Use components for reusable UI
- Keep templates focused on presentation only
- Use Timber data objects (post, term, menu, site)
- Avoid business logic in templates

### CSS
- Follow BEM naming convention
- Organize styles in `css/inc/` subdirectories
- Use SCSS variables for colors, spacing, typography
- Mobile-first responsive design

### JavaScript
- Use IIFE pattern for modules
- Implement modules in `scripts/modules/`
- Use `data-*` attributes for JS hooks (`.js-*` class pattern)
- Leverage passive event listeners for performance

### Workflow
- Run `npm run watch` during development
- Test on mobile devices
- Use WordPress debug mode
- Check `wp-content/debug.log` for errors
- Build before deploying: `npm run build`

---

## Key Hooks & Filters

### Theme Hooks
- `theme_enqueue_assets` — Register CSS/JS
- `theme_page_templates` — Add custom page templates

### WordPress Hooks
- `init` (priority 9-10) — Post types registry
- `init` (priority 11) — Page templates registry
- `init` (priority 12-13) — Taxonomies registry
- `acf/init` — Blocks registry
- `timber/context` — Pass data to Twig

### Timber Hooks
- `timber_page_twig_template` — Override page template
- `timber_context` — Add data to template context

---

## Useful Timber/Twig Methods

```twig
{{ post.title }}                    # Post title
{{ post.content }}                  # Post content
{{ post.get_field('field') }}       # ACF field
{{ post.link }}                     # Post URL
{{ post.excerpt }}                  # Post excerpt
{{ post.thumbnail.src }}            # Featured image URL
{{ post.meta('meta_key') }}         # Post meta
{{ loop.index }}                    # Loop counter
{{ site.name }}                     # Site name
{{ menu('primary').items }}         # Menu items
```

---

## File Header Format Reference

### Post Type Header
```php
/**
 * Post Type Name
 * 
 * Slug: post-type-slug
 * Menu Position: 5
 */
```

### Taxonomy Header
```php
/**
 * Taxonomy Name
 * 
 * Taxonomy: tax-slug
 * Post Types: post,page
 */
```

### Page Template Header
```php
/**
 * Template Name
 * 
 * PageTemplate: template-id
 * Title: Display Name
 * Description: Brief description
 * PostTypes: page,post
 * TwigTemplate: templates/template-name.twig
 */
```

### Block Header (Twig)
```twig
{#
    Title: Block Name
    Description: What block does
    Category: common
    Icon: dashicons-icon
    Keywords: keyword1 keyword2
    Mode: auto
    PostTypes: page post
    SupportsMode: true
    SupportsMultiple: true
#}
```

---

## Testing & Verification

Before considering work complete, verify:

- ✅ Theme activates without errors
- ✅ Auto-discovery registries load (post types, taxonomies, templates, blocks)
- ✅ ACF field groups are attached correctly
- ✅ No console errors in browser DevTools
- ✅ Check `wp-content/debug.log` for PHP warnings
- ✅ Test on mobile devices
- ✅ Run `npm run build` successfully
- ✅ Assets load correctly (CSS, JS)

---

## Repository Memory Files

Full documentation of this starter theme is maintained in:

- `/memories/repo/codebase-overview.md` — Complete architecture & structure
- `/memories/repo/block-creation-pattern.md` — Tested block creation pattern
- `/memories/repo/template-patterns.md` — Template & post type patterns
- `/memories/repo/page-templates-registry.md` — Page templates system
- `/memories/repo/scroll-container-architecture.md` — Scroll animation system
- `/memories/repo/cpt-taxonomy-patterns-investigation.md` — Custom post type & taxonomy patterns

---

## When to Use This Skill

Use this WordPress skill when:

- Building or extending WordPress themes using the starter theme
- Creating new post types, taxonomies, templates, or blocks
- Adding features to the theme
- Debugging theme issues
- Implementing WooCommerce integration
- Creating custom Gutenberg blocks
- Working with Timber/Twig templating
- Configuring theme settings
- Optimizing theme performance

---

## What This Skill Covers

✅ Auto-discovery registry systems  
✅ Centralized configuration  
✅ Post type, taxonomy, page template creation  
✅ Gutenberg block development  
✅ Twig templating & components  
✅ BEM CSS architecture  
✅ IIFE JavaScript modules  
✅ Vite build tools  
✅ WooCommerce integration  
✅ ACF field management  
✅ Best practices & development workflow  

---

## What This Skill Does NOT Cover

❌ WordPress core administration  
❌ Plugin development (beyond integration)  
❌ Hosting, server configuration  
❌ Database optimization  
❌ SEO best practices (though schema is included)  
❌ Performance optimization beyond assets  

---

## Quick Reference: File Locations

| Component | Location | Pattern |
|-----------|----------|---------|
| Configuration | `src/config.php` | Central settings |
| Post Types | `src/post-types/*.php` | Auto-discovered |
| Taxonomies | `src/taxonomies/*.php` | Auto-discovered |
| Page Templates | `src/page-templates/*.php` | Auto-discovered |
| Block Fields | `src/blocks/*.php` | Optional, ACF config |
| Block Templates | `views/blocks/*.twig` | Auto-discovered |
| Twig Templates | `templates/*.twig` | Presentation layer |
| Components | `templates/components/*.twig` | Reusable UI |
| Styles | `css/inc/**/*.scss` | BEM organized 
| Scripts | `scripts/**/*.js` | IIFE modules |
| Entry Points | `views/*.php` | WordPress routing |

---

## Integration with Development Process

This skill implements the team's **5-Phase SOP** for all WordPress theme work:

1. **DISCOVERY** — Understand requirements, review existing patterns in starter theme
2. **VERIFICATION** — Check that WordPress hooks, Timber methods, ACF APIs are available
3. **IMPLEMENTATION** — Build using starter theme patterns, auto-discovery, centralized config
4. **VALIDATION** — Test registries auto-discover, assets load, no console errors
5. **COMMUNICATION** — Report what was built with verification evidence

This ensures theme work is consistent, modular, maintainable, and follows the established architecture.

