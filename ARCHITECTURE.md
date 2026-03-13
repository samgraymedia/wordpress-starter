# System Architecture

**Starter** – Technical Architecture & Design Patterns

This document explains the overall system design, design patterns, and architectural decisions made in this WordPress theme.

## Table of Contents

1. [Architecture Overview](#architecture-overview)
2. [Layered Architecture](#layered-architecture)
3. [Design Patterns](#design-patterns)
4. [Data Flow](#data-flow)
5. [File Organization Philosophy](#file-organization-philosophy)
6. [Dependency Management](#dependency-management)
7. [Extensibility](#extensibility)
8. [Performance Considerations](#performance-considerations)
9. [Security Architecture](#security-architecture)
10. [Scaling Strategies](#scaling-strategies)

---

## Architecture Overview

This theme follows a **layered architecture** pattern with clear separation of concerns:

```
┌─────────────────────────────────────────────────┐
│            Presentation Layer                   │
│      (Twig Templates, HTML, Styling)            │
│  base.twig, index.twig, components/, macros,    │
│  SCSS/BEM components, utilities                 │
└─────────────────────────────────────────────────┘
                      ↓
┌─────────────────────────────────────────────────┐
│            Application Layer                    │
│      (Business Logic & Controllers)             │
│  views/*.php, functions.php, WordPress hooks   │
└─────────────────────────────────────────────────┘
                      ↓
┌─────────────────────────────────────────────────┐
│            Service Layer                        │
│        (Registries & Managers)                  │
│  post-types-registry.php, timber-acf-wp-blocks │
│  theme-config.php, post type configs            │
└─────────────────────────────────────────────────┘
                      ↓
┌─────────────────────────────────────────────────┐
│            Platform/Data Layer                  │
│    (WordPress Core, Database, APIs)             │
│  WordPress, Timber, ACF, MySQL                  │
└─────────────────────────────────────────────────┘
```

### Design Principles

1. **Single Responsibility** - Each component has one job
2. **DRY (Don't Repeat Yourself)** - Configuration centralized, templates reused
3. **Modularity** - Independent components that can be maintained separately
4. **Type Safety** - PHP 7.4+ type hints throughout
5. **Convention over Configuration** - Auto-discovery patterns reduce boilerplate
6. **Separation of Concerns** - Logic, presentation, and styling separated

---

## Layered Architecture

### 1. Presentation Layer (UI)

**Location:** `templates/`, `views/*.php`, and `scss/`

**Responsibility:** Render user interface and apply visual styling

**Components:**

- **Twig Templates** - Markup and template logic
- **Components** - Reusable UI pieces
- **Macros** - Reusable template functions
- **SCSS/CSS** - Styling organized by BEM methodology

**Key Files:**

- `templates/base.twig` - Master layout
- `templates/index.twig` - Content templates
- `templates/components/` - UI components
- `templates/macros.twig` - Template functions
- `scss/` - SCSS organized by BEM (base, components, global, utilities)

**Technology:** Timber + Twig 2.x + Bootstrap 5 + SCSS

**Note:** The `views/*.php` files act as WordPress entry points that prepare context, while `templates/*.twig` files handle the actual markup rendering.

**Example:**

```twig
{# Presentation layer: render data #}
{% include "components/post-card.twig" with {
    post: post,
    show_excerpt: true
} %}
```

### 2. Application Layer (Routing & Logic)

**Location:** `views/` and `functions.php`

**Responsibility:** Map URLs to templates and handle request logic

**Components:**

- **Template Hierarchy Files** - Map URLs to templates (`views/index.php`, `views/single.php`, `views/page.php`, etc.)
- **WordPress Hooks** - Modify behavior at key points
- **Context Building** - Prepare data for templates

**Key Files:**

- `views/index.php` - Blog listing entry point
- `views/single.php` - Single post entry point
- `views/page.php` - Static pages entry point
- `functions.php` - Theme initialization and hooks

**Technology:** PHP + WordPress hooks + Timber context

**Example:**

```php
// Application layer: build context and render
$context = Timber::context();
$context['posts'] = new Timber\PostQuery();
Timber::render( 'index.twig', $context );
```

### 3. Service Layer (Registration & Management)

**Location:** `src/`

**Responsibility:** Register and manage components

**Components:**

- **Configuration** - Centralized settings
- **Registry Classes** - Auto-discover and register components
- **Post Type Definitions** - Configuration-driven post types
- **Block Definitions** - ACF block configurations

**Key Files:**

- `src/config.php` - Configuration arrays
- `src/theme-config.php` - Configuration access (typed)
- `src/post-types-registry.php` - Post type auto-discovery
- `src/timber-acf-wp-blocks.php` - Block auto-discovery
- `src/post-types/` - Individual post type configs

**Technology:** PHP 7.4+ with type hints

**Example:**

```php
// Service layer: access configuration
$menus = Theme_Config::get_menus();
$post_types = Theme_Config::get_post_types();
```

### 4. Platform/Data Layer (Persistence)

**Location:** WordPress core + plugins

**Responsibility:** Store and retrieve data

**Components:**

- **WordPress Database** - Posts, users, meta data (MySQL)
- **Timber** - PHP-to-Twig data bridge
- **ACF** - Field values storage and access
- **Plugins** - Additional data sources (e.g., WooCommerce when active)

**Technology:** MySQL + WordPress API + Timber data layer

**Example:**

```php
// Data layer: WordPress handles persistence
$post = Timber::get_post( $post_id );
$field_value = get_field( 'field_key', $post_id );
```

---

**Note on Architecture:** Bootstrap is used at the presentation layer for styling utility classes. The theme provides BEM component styling that extends and complements Bootstrap's utility approach.

---

## Design Patterns

### Pattern 1: Registry Pattern (Post Types & Blocks)

**Problem:** Post types and blocks scattered across files  
**Solution:** Auto-discovery registry

**Implementation:**

```
src/post-types/ → post-types-registry.php → WordPress API
   ├── post.php       }
   ├── service.php    } Auto-discovered
   └── testimonial.php}
```

**How It Works:**

1. Developer creates `src/post-types/my-type.php`
2. File header contains metadata:
   ```php
   <?php
   // PostType: My Type
   // Name: Display Name
   ```
3. Registry scans directory on `init` hook
4. Reads file headers to discover post types
5. Parses configuration array
6. Registers with WordPress via `register_post_type()`

**Benefits:**

- ✅ Automatic registration (no manual hooks)
- ✅ Configuration files isolated
- ✅ Easy to add new types (just create file)
- ✅ Consistent structure

**Extending:**

```php
// Create new post type
// File: src/post-types/case-study.php

<?php
// PostType: Case Study
// Name: Case Studies

return [
    'args' => [
        'label' => 'Case Studies',
        'public' => true,
        // ... more args
    ],
];

// Automatically registered on init!
```

### Pattern 2: Configuration Pattern (Theme Settings)

**Problem:** Settings hardcoded throughout codebase  
**Solution:** Centralized configuration with typed access

**Implementation:**

```
src/config.php → Theme_Config class → Usage code
(Arrays)            (Static access)
```

**How It Works:**

1. All settings defined in `src/config.php`
2. `Theme_Config` static class provides typed access
3. Configuration lazy-loaded on first use
4. Cached for performance

**Benefits:**

- ✅ Single source of truth
- ✅ Type-safe access
- ✅ Easy to audit settings
- ✅ Simple to override

**Usage:**

```php
// Centralized access with type safety
$text_domain = Theme_Config::get_text_domain(); // string|null
$menus = Theme_Config::get_menus(); // array<string, string>
```

### Pattern 3: Component Pattern (Reusable Templates)

**Problem:** Markup duplicated across templates  
**Solution:** Reusable Twig components

**Implementation:**

```
templates/components/ + templates/macros.twig
        ↓
  Included in templates
        ↓
    Rendered markup
```

**How It Works:**

1. Component template: `templates/components/button.twig`
2. Defines props in comments
3. Included via `{% include %}` with props
4. Rendered with passed data

**Benefits:**

- ✅ DRY principle (no duplication)
- ✅ Consistent styling and behavior
- ✅ Easier maintenance
- ✅ Props document expected data

**Usage:**

```twig
{# Define component #}
{# templates/components/button.twig #}
<button class="btn">{{ text }}</button>

{# Use component #}
{% include "components/button.twig" with { text: "Click me" } %}
```

### Pattern 4: Macro Pattern (Template Functions)

**Problem:** Repeated template logic  
**Solution:** Twig macros as template functions

**Implementation:**

```
templates/macros.twig
    ├── badge(text, variant)
    ├── icon(name, classes)
    ├── truncate(text, length)
    └── ... more macros
        ↓
  Imported in templates
        ↓
    Invoked as functions
```

**How It Works:**

1. Define macros in `templates/macros.twig`
2. Import in templates: `{% import "macros.twig" as m %}`
3. Call like functions: `{{ m.badge(text, variant) }}`

**Benefits:**

- ✅ Reusable template logic
- ✅ Cleaner template code
- ✅ Consistent output formatting
- ✅ Easier testing

**Usage:**

```twig
{% import "macros.twig" as m %}

{# Use macro #}
{{ m.badge("New", "success") }}
{{ m.price(99.99, "USD") }}
{{ m.rating(4, 5) }}
```

### Pattern 5: BEM Naming Pattern (CSS Organization)

**Problem:** CSS specificity wars, hard to maintain  
**Solution:** BEM (Block-Element-Modifier) methodology

**Implementation:**

```scss
// Block: Independent component
.button {
}

// Element: Part of block (__)
.button__text {
}
.button__icon {
}

// Modifier: Variation (--)
.button--primary {
}
.button--large {
}
```

**How It Works:**

1. Each component is a "Block" (`.button`)
2. Parts are "Elements" (`.button__text`)
3. Variations are "Modifiers" (`.button--primary`)
4. No nesting beyond elements (keeps specificity low)

**Benefits:**

- ✅ Predictable naming
- ✅ Low CSS specificity
- ✅ Easy to refactor
- ✅ Scales well

**Usage:**

```html
<button class="button button--primary">
  <span class="button__text">Click me</span>
</button>
```

### Pattern 6: Hook Pattern (WordPress Extension)

**Problem:** Need to modify behavior without editing core files  
**Solution:** WordPress hooks (actions and filters)

**Implementation:**

```php
// Add custom functionality via hooks
add_action( 'init', function() {
    // Custom code runs at init
});

add_filter( 'post_title', function( $title ) {
    // Modify post title
    return $title;
});
```

**Benefits:**

- ✅ Non-invasive modifications
- ✅ Multiple subscribers possible
- ✅ Priority ordering supported
- ✅ Standard WordPress practice

**Usage:**

```php
// Register image size
add_action( 'after_setup_theme', function() {
    add_image_size( 'hero', 1200, 400, true );
});

// Modify post query
add_filter( 'pre_get_posts', function( $query ) {
    if ( is_admin() ) return;

    $query->set( 'posts_per_page', 12 );
    return $query;
});
```

---

## Data Flow

### Page Request → Rendering Pipeline

```
1. Browser Request
   ↓
2. WordPress Routing
   └─ Matches URL to template file (single.php, archive.php, etc.)
   ↓
3. Template File Executes (views/*.php)
   └─ Builds context
   └─ Calls Timber::render()
   ↓
4. Timber Processing
   ├─ Prepares WordPress data
   ├─ Converts to Twig-accessible objects
   └─ Passes context to template
   ↓
5. Twig Template Rendering (templates/*.twig)
   ├─ Imports macros
   ├─ Includes components
   ├─ Loops through data
   └─ Outputs HTML
   ↓
6. Component Rendering
   ├─ Each component processes props
   ├─ Applies CSS classes (BEM)
   └─ Renders partial HTML
   ↓
7. CSS Application
   ├─ Browser loads style.css
   ├─ BEM selectors match HTML elements
   └─ Visual styling applied
   ↓
8. Browser Renders
   └─ HTML + CSS = Visible page
```

### Data Access Flow

```
PHP Code
   ↓
Theme_Config::get()
   ├─ Static method
   ├─ Lazy-loads config.php
   ├─ Caches in memory
   └─ Returns typed value
   ↓
WordPress Functions
   ├─ get_field() for ACF
   ├─ get_posts() for queries
   └─ get_option() for settings
   ↓
Database
   └─ MySQL retrieves/stores data
   ↓
Twig Template
   ├─ Accesses via {{ variable }}
   ├─ Automatically escaped
   └─ Renders in HTML
```

### Form Submission Flow

```
User Submits Form
   ↓
WordPress API
   ├─ Validates nonce
   ├─ Sanitizes input
   └─ Validates data
   ↓
Custom Hook (contact_form_submit)
   ├─ Process form data
   ├─ Send email (via CF7)
   ├─ Save to database (via CF7 DB addon)
   └─ Set success/error message
   ↓
Redirect
   └─ Back to form page with message
   ↓
Page Renders
   ├─ Checks for success/error message
   ├─ Displays alert component
   └─ Shows form again
```

---

## File Organization Philosophy

### Principle 1: Responsibility Boundaries

Files organized by **responsibility and layer**, not just by file type:

```
Core application logic
├── src/              ← Configuration, registries, business rules
├── views/            ← WordPress entry points (routing)
├── functions.php     ← Theme initialization
│
Presentation layer
├── templates/        ← Twig templates (final markup)
├── scss/             ← Styling (BEM organization)
│
Static assets
└── static/           ← Images, fonts, JavaScript
```

This structure ensures:

- Application logic stays in `src/` and `views/`
- Presentation stays in `templates/` and `scss/`
- Clear separation prevents tangled dependencies

### Principle 2: Module Boundaries

Clear boundaries between concerns:

```
src/                  ← Application logic
  ├── config.php      ← Settings only
  ├── theme-config.php ← Access layer
  ├── post-types/     ← Post type definitions
  └── blocks/         ← Block definitions

templates/            ← Presentation
  ├── base.twig       ← Layout
  ├── index.twig      ← Pages
  ├── components/     ← Components
  └── macros.twig     ← Functions

scss/                 ← Styling
  ├── inc/            ← Organized by layer
  │   ├── base/       ← Foundation
  │   ├── components/ ← Components
  │   ├── global/     ← Large sections
  │   └── utilities/  ← Helpers
  └── style.scss      ← Main import
```

### Principle 3: Configuration-Driven

Everything configurable in one place:

```php
// src/config.php is the source of truth
return [
    'post_types' => [ /* all post types */ ],
    'menus' => [ /* all menu locations */ ],
    'image_sizes' => [ /* all image sizes */ ],
    'acf' => [ /* all ACF settings */ ],
];
```

### Principle 3: Automatic Discovery

Minimize boilerplate via auto-discovery patterns:

```
Create file    →    Auto-discovered    →    Auto-registered
src/post-types/my-type.php  →  Registry scans  →  WordPress registers
src/taxonomies/my-tax.php   →  Registry scans  →  WordPress registers
templates/blocks/my-block.twig  →  Scans  →  ACF registers
```

---

## Dependency Management

### Dependency Graph

```
WordPress Core
├── Timber Plugin (data bridge)
├── ACF Pro (field management)
├── WooCommerce (optional)
└── Other plugins

Theme Code
├── src/
│   ├── config.php
│   ├── theme-config.php
│   ├── post-types-registry.php
│   └── timber-acf-wp-blocks.php
├── views/            (routing)
├── functions.php     (initialization)
├── templates/        (Twig markup)
├── scss/             (styling)
└── static/           (assets)
```

### External Dependencies

**Required Plugins:**

- Timber Library (2.x) - Template engine
- Advanced Custom Fields Pro - Field management

**Required Frameworks:**

- Bootstrap 5.3.7 - CSS framework

**Optional Tools:**

- Node.js - SCSS compilation
- Git - Version control

### Managing Dependencies

```bash
# Update plugins via WordPress admin
# Or via wp-cli:
wp plugin update --all

# Update npm dependencies
npm install
npm update

# Check versions
npm list
```

### Avoiding Circular Dependencies

Clear hierarchy prevents circular dependencies:

```
src/config.php          ← No dependencies on other files
    ↓
src/theme-config.php    ← Depends on config.php only
    ↓
functions.php           ← Depends on theme-config.php
    ↓
views/*.php             ← Depend on WordPress, Timber
    ↓
templates/*.twig        ← Depend on data from views
```

---

## Extensibility

### Extending Post Types

```php
// File: src/post-types/my-custom-type.php
<?php
// PostType: My Custom Type
// Name: My Custom Posts

return [
    'args' => [
        'label' => 'My Custom Posts',
        'public' => true,
        'supports' => ['title', 'editor', 'thumbnail'],
    ],
    'fields' => [
        // ACF field groups
        [
            'title' => 'Details',
            'fields' => [
                [
                    'name' => 'field_key',
                    'type' => 'text',
                ],
            ],
        ],
    ],
];
```

### Extending Blocks

```twig
{# File: templates/blocks/my-custom-block.twig #}
{#
Block: My Custom Block
Title: My Block Title
Description: Block description
Category: common
Icon: star
#}

<div class="block-my-custom-block">
    {# Block content #}
</div>
```

### Extending Components

```twig
{# File: templates/components/my-component.twig #}
{# Props: title, content, variant #}

<div class="my-component my-component--{{ variant }}">
    <h3 class="my-component__title">{{ title }}</h3>
    <div class="my-component__content">
        {{ content }}
    </div>
</div>
```

### Extending Configuration

```php
// In src/config.php, add new sections
return [
    'existing_section' => [ /* ... */ ],

    // Add new custom section
    'custom_section' => [
        'setting_key' => 'value',
    ],
];

// Access in code
$custom = Theme_Config::get( 'custom_section' );
```

### Creating Custom Hooks

```php
// File: functions.php or custom plugin file

// Define custom action hook
add_action( 'theme_before_footer', function() {
    // Runs before footer renders
});

// Define custom filter hook
add_filter( 'theme_footer_text', function( $text ) {
    return $text . ' Custom text';
});

// Trigger in template
// views/footer.php or base.twig
do_action( 'theme_before_footer' );
```

---

## Performance Considerations

### 1. Template Caching

Timber can cache template results:

```php
// In functions.php
Timber::$cache = true;  // Enable caching
```

### 2. Asset Optimization

```php
// Enqueue scripts efficiently
wp_enqueue_script(
    'my-script',
    get_stylesheet_directory_uri() . '/js/script.min.js',
    [],
    '1.0.0',
    true  // Load in footer (non-critical)
);

// Lazy load non-critical CSS
wp_enqueue_style(
    'my-style',
    get_stylesheet_directory_uri() . '/style.css',
    [],
    '1.0.0',
    'all'  // Media type
);
```

### 3. Database Query Optimization

```php
// Use focused query parameters
$posts = Timber::get_posts( [
    'post_type' => 'post',
    'posts_per_page' => 10,
    'orderby' => 'date',
] );
```

### 4. Image Optimization

Define multiple image sizes in configuration and use appropriately in templates:

```php
// src/config.php
'image_sizes' => [
    'thumbnail' => ['width' => 150, 'height' => 150],
    'medium' => ['width' => 300, 'height' => 300],
    'large' => ['width' => 800, 'height' => 600],
    'hero' => ['width' => 1200, 'height' => 400],
];
```

### 5. SCSS Organization

Organized by layer to support selective includes:

```scss
// style.scss - import strategically
@import "scss/inc/base/vars"; // Always needed
@import "scss/inc/base/btns"; // Always needed
@import "scss/inc/components/card"; // Only if card is used
```

---

## Security Architecture

### 1. Input Validation

```php
// Always validate and sanitize user input
$input = sanitize_text_field( $_POST['input'] );
$email = sanitize_email( $_POST['email'] );
$url = esc_url_raw( $_POST['url'] );
```

### 2. Output Escaping

```php
// PHP
echo esc_html( $post->title );
echo esc_url( $link );

// Twig
{{ post.title }}  {# Escaping rules apply based on context and configuration #}
```

### 3. Nonce Verification

```php
// Verify requests with nonces
if ( ! isset( $_POST['nonce'] ) ||
     ! wp_verify_nonce( $_POST['nonce'], 'action_name' ) ) {
    wp_die( 'Security check failed' );
}
```

### 4. Capability Checking

```php
// Always check user capabilities
if ( current_user_can( 'edit_post', $post_id ) ) {
    // Allow edit
}
```

### 5. Secure Database Access

```php
// Use WordPress prepared statements
$wpdb->query(
    $wpdb->prepare(
        "SELECT * FROM {$wpdb->posts} WHERE ID = %d",
        $post_id
    )
);
```

---

## Scaling Strategies

### For Growing Content

1. **Use Custom Post Types** - Separate content types into their own post types
2. **Implement Pagination** - Don't load all posts at once
3. **Use Custom Taxonomies** - Organize content hierarchically
4. **Index Frequently Queried Fields** - Use ACF for fast lookups

### For Growing Team

1. **Document Code** - Extensive comments and guides
2. **Type Hints** - PHP 7.4+ for IDE support
3. **Consistent Patterns** - Registry, config, component patterns
4. **Code Review Process** - Peer review before merging

### For Growing Functionality

1. **Use Registry Patterns** - Add new post types, taxonomies via auto-discovery
2. **Keep Presentation Layer Clean** - Templates shouldn't contain business logic
3. **Move Domain Logic to Plugins** - If customization becomes client-specific
4. **Reuse Components** - Build new pages from existing components

### For Performance at Scale

1. **Enable Caching** - Timber supports result caching
2. **Optimize Image Sizes** - Use appropriate sizes via configuration
3. **Query Efficiently** - Use focused post queries with appropriate arguments
4. **Code Split SCSS** - Import only needed components

### For Team Scaling

1. **Follow Registry Pattern** - Consistent on-boarding for new developers
2. **Document Architecture** - Team understands how pieces fit together
3. **Type Safety** - PHP type hints provide IDE support
4. **Separation of Concerns** - Developers can work on layers independently

---

## Summary

This architecture provides:

- **Clear Separation of Concerns** - Each layer has a job
- **Extensibility** - Easy to add features
- **Maintainability** - Code is organized and documented
- **Performance** - Optimized defaults
- **Security** - Built-in best practices
- **Scalability** - Patterns support growth
- **Developer Experience** - Type hints and conventions

The registry and configuration patterns reduce boilerplate and make the codebase predictable. The BEM CSS methodology provides a sustainable approach to styling at scale.

---

**Document Version:** 1.0  
**Last Updated:** March 2026  
**Theme Version:** 1.0.0
