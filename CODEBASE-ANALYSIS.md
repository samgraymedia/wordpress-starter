# Starter Theme - Comprehensive Codebase Analysis

**A Complete Technical Reference for WordPress Theme Architecture, Block System, and Development Patterns**

Last Updated: April 2026  
Applicable to: starter-theme-1.x  
Version: 1.0.0

---

## Table of Contents

1. [Project Architecture Overview](#project-architecture-overview)
2. [Block System - Complete Reference](#block-system---complete-reference)
3. [ACF Integration System](#acf-integration-system)
4. [Timber/Twig Template System](#timbertwig-template-system)
5. [Build System (Vite)](#build-system-vite)
6. [CSS Architecture (BEM + SCSS)](#css-architecture-bem--scss)
7. [JavaScript Pattern & Module System](#javascript-pattern--module-system)
8. [WooCommerce Integration](#woocommerce-integration)
9. [Helper Functions & Utilities](#helper-functions--utilities)
10. [Configuration System](#configuration-system)
11. [File Organization & Conventions](#file-organization--conventions)
12. [Best Practices & Patterns](#best-practices--patterns)
13. [Creating New Blocks - Step-by-Step](#creating-new-blocks---step-by-step)
14. [Common Code Patterns](#common-code-patterns)

---

## Project Architecture Overview

### Layered Architecture

This theme follows a **4-layer architecture** with clear separation of concerns:

```
┌─────────────────────────────────────────────────────────┐
│  Presentation Layer                                     │
│  Twig Templates (views/, templates/)                    │
│  HTML output, component rendering                       │
└─────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────┐
│  Application Layer                                      │
│  WordPress entry points (index.php, single.php, etc.)   │
│  Template selection, context preparation                │
└─────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────┐
│  Service/Registry Layer                                 │
│  Auto-discovery systems:                                │
│  - Post types registry (src/post-types-registry.php)    │
│  - Page templates registry                              │
│  - Blocks auto-discovery (timber-acf-wp-blocks.php)     │
│  - Theme configuration system                           │
└─────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────┐
│  Platform/Data Layer                                    │
│  WordPress Core, Timber, ACF, MySQL                     │
└─────────────────────────────────────────────────────────┘
```

### Folder Structure

```
starter-theme-1.x/
├── src/                          # PHP source code
│   ├── theme-config.php          # Configuration loader & registry
│   ├── theme-options.php         # Theme options initialization
│   ├── config.php                # Centralized config
│   ├── theme-toggle-options.php  # Dark mode / theme toggle
│   ├── seo-helpers.php           # SEO utilities
│   ├── schema-organization.php   # Schema.org markup
│   │
│   ├── blocks/                   # Block field configurations
│   │   ├── hero.php             # Block ACF fields
│   │   ├── image-pair.php
│   │   ├── collection-products.php
│   │   ├── banner-cta.php
│   │   ├── testimonial.php
│   │   └── ...
│   │
│   ├── post-types/               # Post type definitions
│   │   ├── blog.php
│   │   ├── testimonials.php
│   │   ├── products.php
│   │   └── post.php (disabled)
│   ├── post-types-registry.php   # Auto-discovery registry
│   │
│   ├── page-templates/           # Custom page templates
│   ├── page-templates-registry.php
│   │
│   ├── taxonomies/               # Taxonomy definitions
│   ├── taxonomies-registry.php
│   │
│   ├── helpers/                  # Utility functions
│   │   ├── sanitization.php
│   │   └── validation.php
│   │
│   ├── admin/                    # Admin customizations
│   │   ├── customizer-options.php
│   │   ├── admin-dashboard.php
│   │   ├── product-performance.php
│   │   └── ...
│   │
│   ├── timber-acf-wp-blocks.php  # Block auto-discovery
│   ├── woocommerce-integration.php
│   ├── woocommerce-*.php         # WooCommerce features
│   └── image-alt-text-guide.php
│
├── views/                        # WordPress entry points
│   ├── blocks/                   # Block templates (Twig)
│   │   ├── hero.twig
│   │   ├── image-pair.twig
│   │   ├── banner-cta.twig
│   │   ├── collection-products.twig
│   │   ├── testimonial.twig
│   │   └── ...
│   │
│   └── components/               # Reusable component templates
│       ├── button.twig
│       ├── product-card.twig
│       └── ...
│
├── templates/                    # Main Twig templates
│   ├── base.twig                # Master layout
│   ├── index.twig               # Archive/blog
│   ├── single.twig              # Single post/page
│   ├── page.twig                # Page template
│   ├── single-product.twig      # WooCommerce
│   ├── cart.twig
│   ├── checkout.twig
│   │
│   ├── components/              # Global components
│   │   ├── button.twig
│   │   ├── breadcrumb.twig
│   │   ├── post-card.twig
│   │   └── ...
│   │
│   ├── macros/                  # Reusable template functions
│   └── partial/                 # Partial templates
│
├── css/                         # SCSS source files
│   └── inc/                     # Organized by layer (BEM/SMACSS)
│       ├── base/               # Base styles, variables, resets
│       ├── components/         # BEM components
│       ├── global/             # Global sections (nav, forms, etc)
│       ├── blocks/             # Block-specific styles
│       ├── pages/              # Page-specific styles
│       └── utilities/          # Helper classes
│
├── scripts/                     # JavaScript modules (ES6)
│   ├── index.js                # Main entry point
│   ├── hamburger.js            # Navigation toggle
│   ├── collection-products.js  # Block scripts
│   ├── testimonial-scroll.js   # Carousel/scroll
│   ├── gallery.js
│   ├── video.js
│   ├── accordion.js
│   │
│   ├── components/             # Reusable JS components
│   │   ├── Modal.js
│   │   ├── Dropdown.js
│   │   └── AnimatedArrow.js
│   │
│   ├── modules/                # Feature modules
│   │   ├── cart.js
│   │   ├── checkout.js
│   │   ├── search.js
│   │   ├── forms.js
│   │   ├── cookie-consent.js
│   │   └── theme-toggle.js
│   │
│   └── utils/                  # Utility functions
│       ├── dom.js              # DOM helpers
│       ├── events.js           # Event helpers (throttle, debounce)
│       ├── lazy-loading.js
│       ├── logger.js           # Debug logging
│       └── error-reporter.js
│
├── dist/                       # Build output (Vite)
│   ├── site.js                # Bundled JavaScript
│   └── [name]-[hash].js       # Code-split chunks
│
├── static/                     # Static files
├── blocks/                     # Custom block folder
│   └── wishlist-button/        # Wishlist block
│
├── woocommerce/               # WooCommerce template overrides
├── functions.php              # Theme setup & initialization
├── index.php                  # Fall-back WordPress template
├── archive.php                # Archive template
├── single.php                 # Single post template
├── page.php                   # Page template
├── style.scss                 # Main SCSS entry point
├── vite.config.js             # Vite build configuration
├── package.json               # NPM dependencies
├── composer.json              # PHP dependencies
└── README.md & docs/          # Documentation files
```

### Key Design Principles

1. **Auto-Discovery Registry Pattern**
   - Post types auto-discovered from `src/post-types/`
   - Blocks auto-discovered from `views/blocks/` + `src/blocks/`
   - Templates auto-discovered from `src/page-templates/`
   - Zero manual registration needed

2. **Centralized Configuration**
   - All settings in `src/config.php`
   - Type-safe access via `Theme_Config` class
   - Single source of truth

3. **Separation of Concerns**
   - PHP (logic) ≠ Twig (markup) ≠ SCSS (styling) ≠ JS (interaction)
   - Each layer has clear responsibilities

4. **Convention over Configuration**
   - File names determine functionality
   - Naming conventions are predictable
   - Reduces boilerplate code

---

## Block System - Complete Reference

### How Blocks Work

The theme implements a **block auto-discovery system** that eliminates manual registration. Here's how it works:

#### Block Discovery Flow

```
1. WordPress loads theme → functions.php hooks acf/init
   ↓
2. Timber_Acf_Wp_Blocks::timber_block_init() runs
   ↓
3. Scans views/blocks/ for .twig files with metadata headers
   ↓
4. Reads file headers: Title, Category, Description, etc.
   ↓
5. Automatically registers block via acf_register_block_type()
   ↓
6. Block fields auto-loaded from src/blocks/*.php (acf_add_local_field_group)
   ↓
7. Block ready for use in Gutenberg editor
```

### Block File Structure

**Three files needed to create a block:**

#### 1. Block Template (views/blocks/)

**Location:** `views/blocks/{block-name}.twig`

**Example:** `views/blocks/hero.twig`

```twig
{#
Title: Hero
Description: A Custom Hero block
Category: formatting
Icon: <svg>...</svg>
Keywords: hero banner
Mode: auto
Align: center
PostTypes: page post
SupportsAlign: left right center wide full
SupportsMode: true
SupportsMultiple: true
#}

<div id="hero-{{ block.id }}" class="block hero {{ block.classes }}">
	<div class="hero__bg" style="background-image: url('{{ fields.hero_image.url }}');"></div>

	<div class="wrapper">
		<div class="hero__wrapper">
			<h1>{{ fields.hero_title }}</h1>
			<h2>{{ fields.hero_lead }}</h2>
			<p>{{ fields.hero_description }}</p>
		</div>
	</div>
</div>
```

**Metadata Headers (in Twig comment):**

| Header | Required | Values | Example |
|--------|----------|--------|---------|
| Title | ✅ Yes | Block name | Hero |
| Category | ✅ Yes | common, media, formatting, design, widgets, embed | formatting |
| Description | ❌ Optional | Help text | A Custom Hero block |
| Icon | ❌ Optional | SVG or dashicon name | hero-banner |
| Keywords | ❌ Optional | Space-separated | hero banner featured |
| Mode | ❌ Optional | auto, preview, edit | auto |
| Align | ❌ Optional | left, right, center, wide, full | center |
| PostTypes | ❌ Optional | Space-separated post types | page post |
| SupportsAlign | ❌ Optional | true/false | true |
| SupportsMode | ❌ Optional | true/false | true |
| SupportsMultiple | ❌ Optional | true/false | true |

**Access Block Data in Template:**

```twig
{{ fields.field_name }}              {# Access field values #}
{{ block.id }}                       {# Block unique ID #}
{{ block.classes }}                  {# CSS classes #}
{{ block.align }}                    {# Alignment class #}
{{ block.name }}                     {# Block name (e.g., 'acf/hero') #}
```

#### 2. Block Field Configuration (src/blocks/)

**Location:** `src/blocks/{block-name}.php`

**Example:** `src/blocks/hero.php`

```php
<?php
/**
 * Register Hero Block Fields
 */

if( function_exists('acf_add_local_field_group') ):
acf_add_local_field_group(array(
    'key' => 'group_hero_block',
    'title' => 'Hero Block Fields',
    'fields' => array(
        array(
            'key'           => 'field_hero_image',
            'label'         => 'Hero Image',
            'name'          => 'hero_image',
            'type'          => 'image',
            'instructions'  => 'Upload the hero image',
            'required'      => 0,
            'return_format' => 'array',
        ),
        array(
            'key'           => 'field_hero_title',
            'label'         => 'Hero Title',
            'name'          => 'hero_title',
            'type'          => 'text',
            'instructions'  => 'Enter the hero title',
            'required'      => 1,
        ),
        array(
            'key'           => 'field_hero_lead',
            'label'         => 'Hero Lead',
            'name'          => 'hero_lead',
            'type'          => 'text',
            'required'      => 0,
        ),
        array(
            'key'           => 'field_hero_description',
            'label'         => 'Hero Description',
            'name'          => 'hero_description',
            'type'          => 'textarea',
            'required'      => 0,
        ),
    ),
    'location' => array(
        array(
            array(
                'param'    => 'block',
                'operator' => '==',
                'value'    => 'acf/hero',      // Must match block name
            ),
        ),
    ),
));
endif;
```

**Key Pattern:**

- ACF field group must have `location` matching `acf/{block-slug}`
- Block slug is derived from the Twig filename (e.g., `hero.twig` → `acf/hero`)
- Field names become available as `fields.field_name` in Twig template
- Both files auto-loaded - no manual registration needed

#### 3. Block Styles (css/inc/blocks/)

**Location:** `css/inc/blocks/{block-name}.scss`

**Example:** `css/inc/blocks/hero.scss`

```scss
.hero {
  position: relative;
  overflow: hidden;
  min-height: 100svh;

  &__bg {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    will-change: transform;
    transform: translate3d(0, 0, 0) scale(1.08);
    z-index: 0;
  }

  .wrapper {
    min-height: 100svh;
    position: relative;
    z-index: 1;
  }

  &__wrapper {
    display: flex;
    flex-flow: column;
    align-items: center;
  }

  h1 {
    font-family: $font-family-heading;
    color: $white;
    font-size: 6.4rem;
    font-weight: 400;
    letter-spacing: -0.03em;
    line-height: 1;
  }
}
```

### Working Block Examples

#### Example 1: Image Pair Block (Simple)

**File:** `src/blocks/image-pair.php`

```php
<?php
if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group( array(
	'key'      => 'group_block_image_pair',
	'title'    => 'Image Pair Block',
	'fields'   => array(
		array(
			'key'           => 'field_block_image_pair_1',
			'label'         => 'Image 1',
			'name'          => 'image_1',
			'type'          => 'image',
			'return_format' => 'array',
		),
		array(
			'key'           => 'field_block_image_pair_2',
			'label'         => 'Image 2',
			'name'          => 'image_2',
			'type'          => 'image',
			'return_format' => 'array',
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/image-pair',
			),
		),
	),
) );
```

**File:** `views/blocks/image-pair.twig`

```twig
{#
Title: Image Pair
Description: Display up to two images
Category: media
Icon: <svg>...</svg>
Keywords: images gallery photos
Mode: auto
PostTypes: page post
#}

<div class="block image-pair" data-block-id="{{ block.id }}">
	<div class="wrapper">
		<div class="image-pair__grid">
			{% if fields.image_1 %}
				<div class="image-pair__image-1">
					<img src="{{ fields.image_1.url }}" alt="{{ fields.image_1.alt }}"/>
				</div>
			{% endif %}

			{% if fields.image_2 %}
				<div class="image-pair__image-2">
					<img src="{{ fields.image_2.url }}" alt="{{ fields.image_2.alt }}"/>
				</div>
			{% endif %}
		</div>
	</div>
</div>
```

#### Example 2: Collection Products Block (Advanced)

**File:** `src/blocks/collection-products.php`

```php
<?php
acf_add_local_field_group( array(
	'key'      => 'group_block_collection_products',
	'title'    => 'Collection Products Block',
	'fields'   => array(
		array(
			'key'           => 'field_block_collection_products_select',
			'label'         => 'Select Collection Products',
			'name'          => 'collection_products',
			'type'          => 'relationship',
			'return_format' => 'id',
			'post_type'     => 'collection_products',
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/collection-products',
			),
		),
	),
) );
```

**File:** `views/blocks/collection-products.twig`

```twig
{#
Title: Collection Products
Description: Display collection products in a responsive grid
Category: widgets
Keywords: products collection grid display
#}

<div id="collection-products-{{ block.id }}" class="block collection-products" data-speed="0.5">
	<div class="wrapper">
		{% if fields.collection_products %}
			<div class="collection-products__grid">
				{% for product_id in fields.collection_products %}
					{% set product = TimberPost(product_id) %}
					{% if product %}
						<div class="collection-product-item js-scroll-translate" data-index="{{ loop.index }}">
							<div class="collection-product-image">
								<a href="{{ product.link }}">
									<img src="{{ product.thumbnail.src }}" alt="{{ product.post_title }}" loading="lazy"/>
									<h3 class="collection-product-title">{{ product.post_title }}</h3>
								</a>
							</div>
						</div>
					{% endif %}
				{% endfor %}
			</div>
		{% else %}
			<div class="notice">
				<p>No collection products selected. Add products in the block settings.</p>
			</div>
		{% endif %}
	</div>
</div>
```

#### Example 3: Testimonial Block (With Repeater & Relationship)

**File:** `src/blocks/testimonial.php`

```php
<?php
acf_add_local_field_group( array(
	'key'      => 'group_block_testimonials',
	'title'    => 'Testimonials Block',
	'fields'   => array(
		array(
			'key'           => 'field_block_testimonials_select',
			'label'         => 'Select Testimonials',
			'name'          => 'testimonials',
			'type'          => 'relationship',
			'return_format' => 'id',
			'post_type'     => 'testimonial',
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/testimonial',
			),
		),
	),
) );
```

**File:** `views/blocks/testimonial.twig`

```twig
{#
Title: Testimonials
Description: Display client testimonials with images and details
Category: widgets
Keywords: testimonial review quote client
#}

<div id="testimonial" class="block testimonial {{block.classes}}">
	<div class='wrapper'>
		<div class="testimonial-header">
			<h2 class='title'>Client stories</h2>
			<div class="slide-counter" data-counter>
				<span class="current">01</span>/<span class="total">{{ fields.testimonials|length|format('%02d') }}</span>
			</div>
		</div>

		{% if fields.testimonials %}
			<div class='testimonial-slider' data-carousel>
				{% for testimonial_id in fields.testimonials %}
					{% set testimonial = TimberPost(testimonial_id) %}
					{% if testimonial %}
						<div class="testimonial-slide" data-slide>
							<img src="{{ testimonial.person_image.url }}" alt="{{ testimonial.person_image.alt }}"/>
							<blockquote>{{ testimonial.testimonial }}</blockquote>
							<strong>{{ testimonial.name }}</strong>
							<span class="job-title">{{ testimonial.job_title }}</span>
						</div>
					{% endif %}
				{% endfor %}
			</div>
		{% endif %}
	</div>
</div>
```

#### Example 4: Banner CTA Block (With Repeater & Buttons)

**File:** `src/blocks/banner-cta.php`

```php
<?php
acf_add_local_field_group( array(
	'key'      => 'group_block_banner_cta',
	'title'    => 'Banner CTA Block',
	'fields'   => array(
		array(
			'key'           => 'field_block_banner_cta_lead',
			'label'         => 'Lead Text',
			'name'          => 'lead',
			'type'          => 'text',
			'required'      => 0,
		),
		array(
			'key'           => 'field_block_banner_cta_title',
			'label'         => 'Title',
			'name'          => 'title',
			'type'          => 'textarea',
			'required'      => 1,
			'rows'          => 3,
		),
		array(
			'key'           => 'field_block_banner_cta_media',
			'label'         => 'Background Media',
			'name'          => 'media',
			'type'          => 'image',
			'required'      => 0,
			'return_format' => 'array',
		),
		array(
			'key'           => 'field_block_banner_cta_buttons',
			'label'         => 'Buttons',
			'name'          => 'buttons',
			'type'          => 'repeater',
			'required'      => 0,
			'max'           => 2,
			'layout'        => 'table',
			'sub_fields'    => array(
				array(
					'key'   => 'field_block_banner_cta_button_text',
					'label' => 'Button Text',
					'name'  => 'text',
					'type'  => 'text',
					'required' => 1,
				),
				array(
					'key'   => 'field_block_banner_cta_button_url',
					'label' => 'Button URL',
					'name'  => 'url',
					'type'  => 'url',
					'required' => 1,
				),
				array(
					'key'    => 'field_block_banner_cta_button_style',
					'label'  => 'Button Style',
					'name'   => 'style',
					'type'   => 'select',
					'choices' => array(
						'primary'   => 'Primary',
						'secondary' => 'Secondary',
						'outline'   => 'Outline',
					),
					'default_value' => 'primary',
				),
			),
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/banner-cta',
			),
		),
	),
) );
```

**File:** `views/blocks/banner-cta.twig`

```twig
{#
Title: Banner CTA
Description: Display a banner with lead text, title, buttons, and background media
Category: media
Keywords: banner cta call-to-action
#}

<div class="block banner-cta" data-block-id="{{ block.id }}">
	{% if fields.media %}
		<div class="banner-cta__background" style="background-image: url('{{ fields.media.url }}');"></div>
	{% endif %}

	<div class="wrapper">
		<div class="banner-cta__content">
			{% if fields.lead %}
				<span class="banner-cta__lead">{{ fields.lead }}</span>
			{% endif %}

			{% if fields.title %}
				<h2 class="banner-cta__title">{{ fields.title }}</h2>
			{% endif %}

			{% if fields.buttons %}
				<div class="banner-cta__buttons">
					{% for button in fields.buttons %}
						{% set button_class = 'btn--' ~ (button.style ?: 'primary') %}
						{% include 'components/button.twig' with {
							href: button.url,
							text: button.text,
							class: button_class
						} %}
					{% endfor %}
				</div>
			{% endif %}
		</div>
	</div>
</div>
```

### How Block Discovery System Works (Code)

**File:** `src/timber-acf-wp-blocks.php`

```php
<?php
class Timber_Acf_Wp_Blocks {
    public function __construct() {
        add_action( 'acf/init', array( __CLASS__, 'timber_block_init' ), 10, 0 );
    }

    public static function timber_block_init(): void {
        $directories = array( 'views/blocks' );
        
        foreach ( $directories as $dir ) {
            $full_path = locate_template( $dir );
            if ( ! file_exists( $full_path ) ) continue;

            $template_directory = new DirectoryIterator( $full_path );
            foreach ( $template_directory as $template ) {
                if ( $template->isDot() || $template->isDir() ) continue;

                $file_parts = pathinfo( $template->getFilename() );
                if ( 'twig' !== $file_parts['extension'] ) continue;

                // Sanitize block slug
                $slug = sanitize_title( $file_parts['filename'] );
                
                // Read file headers (Twig comment)
                $file_path = locate_template( $dir . "/{$file_parts['filename']}.twig" );
                $file_headers = get_file_data(
                    $file_path,
                    array(
                        'title'       => 'Title',
                        'description' => 'Description',
                        'category'    => 'Category',
                        'icon'        => 'Icon',
                        'keywords'    => 'Keywords',
                        'mode'        => 'Mode',
                        'post_types'  => 'PostTypes',
                    )
                );

                if ( empty( $file_headers['title'] ) || empty( $file_headers['category'] ) ) {
                    continue; // Skip invalid blocks
                }

                // Register using ACF
                acf_register_block_type( array(
                    'name'            => $slug,
                    'title'           => $file_headers['title'],
                    'description'     => $file_headers['description'],
                    'category'        => $file_headers['category'],
                    'icon'            => $file_headers['icon'],
                    'keywords'        => str_getcsv( $file_headers['keywords'], ' ' ),
                    'render_callback' => array( __CLASS__, 'timber_blocks_callback' ),
                    'post_types'      => explode( ' ', $file_headers['post_types'] ),
                ) );
            }
        }
    }

    public static function timber_blocks_callback( $block ) {
        $context = Timber::context();
        $slug    = str_replace( 'acf/', '', $block['name'] );
        
        $context['block']  = $block;
        $context['fields'] = $block['data']; // ACF fields
        
        Timber::render( "blocks/{$slug}.twig", $context );
    }
}

new Timber_Acf_Wp_Blocks();
```

**How it works:**

1. On `acf/init` hook, scanner runs
2. Iterates through `views/blocks/` directory
3. For each `.twig` file, extracts metadata headers using WordPress `get_file_data()`
4. Validates required fields (Title, Category)
5. Registers block using `acf_register_block_type()`
6. When block renders, Timber looks for template at `views/blocks/{slug}.twig`
7. ACF fields are available as `fields.*` in template context

---

## ACF Integration System

### ACF Field Registration Pattern

The theme uses **ACF local field groups** for all custom data. There are three contexts where fields appear:

#### 1. Block Fields (src/blocks/)

Fields attached to Gutenberg blocks:

```php
acf_add_local_field_group( array(
	'key'      => 'group_unique_key',
	'title'    => 'Block Name',
	'fields'   => array(
		// Field definitions...
	),
	'location' => array(
		array(
			array(
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/block-name',  // Matches block slug
			),
		),
	),
) );
```

#### 2. Post Type Fields (src/post-types/)

Fields for custom post types:

```php
return array(
	'args'   => array(
		// post_type registration args
	),
	'fields' => array(
		'key'      => 'group_post_type_fields',
		'title'    => 'Post Type Name',
		'fields'   => array(
			// Field definitions...
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'post_type_slug',
				),
			),
		),
	),
);
```

#### 3. Theme Options (ACF Options Page)

Global site settings:

```php
// File: src/theme-options.php
if ( function_exists( 'acf_add_options_page' ) ) {
	acf_add_options_page( array(
		'page_title' => 'Theme Options',
		'menu_title' => 'Theme Options',
		'menu_slug'  => 'theme-options',
		'capability' => 'manage_options',
		'position'   => 2,
	) );
}

// In functions.php and through custom registrations
acf_add_local_field_group( array(
	'key'      => 'group_global_options',
	'title'    => 'Global Settings',
	'fields'   => array(
		array(
			'key'   => 'field_footer_image',
			'label' => 'Footer Background',
			'name'  => 'footer_image',
			'type'  => 'image',
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'options_page',
				'operator' => '==',
				'value'    => 'theme-options',
			),
		),
	),
) );
```

### ACF Field Types Used in Theme

**Common patterns across blocks:**

| Field Type | Usage Example | Return Format | In Template |
|------------|---------------|----------------|-------------|
| image | Hero images, backgrounds | array | `{{ fields.hero_image.url }}` |
| text | Titles, headings | string | `{{ fields.title }}` |
| textarea | Descriptions, long text | string | `{{ fields.description }}` |
| wysiwyg | Rich content | string (HTML) | `{{ fields.content \| raw }}` |
| relationship | Linked posts | array of IDs | `{% for id in fields.posts %}` |
| repeater | Multiple items | array | `{% for item in fields.items %}` |
| select | Choices | string | `{{ fields.option }}` |
| true_false | Toggles | boolean | `{% if fields.show %}` |
| url | Links | string | `<a href="{{ fields.url }}">` |

### ACF Field Auto-Loading (After Block Discovery)

**File:** `src/post-types-registry.php` (handles post type fields)

The theme auto-discovers and registers ACF field groups for:
- Custom post types (from `src/post-types/*.php`)
- Blocks (from `src/blocks/*.php`)
- Global options

```php
// In functions.php
new Timber_Post_Types();  // Auto-loads fields
new Timber_Page_Templates();
new Timber_Acf_Wp_Blocks();  // Auto-loads block fields
```

---

## Timber/Twig Template System

### Template Hierarchy

The theme uses a **WordPress template hierarchy** combined with **Timber context passing**.

#### WordPress Entry Points

**File:** `index.php` (falls back if no specific template found)

```php
<?php
$context = Timber::context();

// Add custom context here
$context['posts'] = Timber::get_posts();
$context['pagination'] = Timber::get_pagination();

Timber::render( 'index.twig', $context );
```

#### Default Template Entry Points

| WordPress Template | Purpose | Twig Template |
|-------------------|---------|---------------|
| `index.php` | Archives, blog | `templates/index.twig` or `templates/archive-blog.twig` |
| `page.php` | Static pages | `templates/page.twig` |
| `single.php` | Single posts | `templates/single.twig` or `templates/single-blog.twig` |
| `single-product.php` | WooCommerce products | `templates/single-product.twig` |
| `archive-product.php` | WooCommerce shop | `templates/shop.twig` |

### Context Passing Pattern

**File:** `templates/base.twig` (Master Layout)

```twig
{% extends "html-header.twig" %}

{% block head %}
	{# Page-specific styles/scripts #}
{% endblock %}

<body class="{{ body_class }}" data-template="base.twig">
	<a class="skip-link screen-reader-text" href="#content">{{ _e('Skip to content') }}</a>
	
	<header class="header" role="banner">
		{% block header %}
			<nav id="nav-main" class="nav-main">
				{% set header_menu = function('wp_get_nav_menu_items', function('get_nav_menu_locations')['header'] ?? 0) %}
				{% include "menu.twig" with {'items': header_menu} %}
			</nav>
		{% endblock %}
	</header>

	<main id="content" class="content-wrapper">
		{% if breadcrumb_trail %}
			{% include 'components/breadcrumb.twig' %}
		{% endif %}

		{% block content %}
			{# Child templates override this block #}
		{% endblock %}
	</main>

	<footer class="site-footer" role="contentinfo">
		{% include 'footer.twig' %}
	</footer>

	{{ function('wp_footer') }}
</body>
```

### Component and Macro Usage

#### Including Components (Twig Include)

**File:** `templates/components/button.twig`

```twig
{#
Button Component

Props:
- text (required): Button text
- url: Link URL (renders as <a> if provided)
- classes: Additional CSS classes
- type: button, submit, reset
- disabled: Boolean
- target: Link target
#}

{% set button_classes = [
    'btn',
    classes ?? ''
] %}

{% if url %}
	<a href="{{ url }}" class="{{ button_classes|join(' ') }}" target="{{ target ?? '_self' }}" rel="{{ rel ?? '' }}">
		{{ text }}
	</a>
{% else %}
	<button type="{{ type ?? 'button' }}" class="{{ button_classes|join(' ') }}" {% if disabled %}disabled{% endif %}>
		{{ text }}
	</button>
{% endif %}
```

**Using Component:**

```twig
{% include "components/button.twig" with {
	text: "Click Me",
	url: "/path",
	classes: "btn-primary"
} %}
```

#### Macros (Reusable Template Functions)

**File:** `templates/macros.twig`

```twig
{# Define reusable template logic #}

{% macro render_post_card(post, show_excerpt=true) %}
	<article class="post-card">
		<div class="post-card__image">
			<img src="{{ post.thumbnail.src }}" alt="{{ post.title }}">
		</div>
		<div class="post-card__content">
			<h2><a href="{{ post.link }}">{{ post.title }}</a></h2>
			{% if show_excerpt %}
				<p>{{ post.excerpt }}</p>
			{% endif %}
		</div>
	</article>
{% endmacro %}

{% macro render_pagination(pagination) %}
	<nav class="pagination" role="navigation">
		{% for page in pagination %}
			{% if page.link %}
				<a href="{{ page.link }}">{{ page.title }}</a>
			{% else %}
				<span class="current">{{ page.title }}</span>
			{% endif %}
		{% endfor %}
	</nav>
{% endmacro %}
```

**Using Macros:**

```twig
{% import 'macros.twig' as m %}

{% for post in posts %}
	{{ m.render_post_card(post) }}
{% endfor %}

{{ m.render_pagination(pagination) }}
```

### Available Context Variables in Templates

**Standard Timber context:**

```twig
{{ site.name }}                    {# WordPress blog name #}
{{ site.tagline }}                 {# Tagline #}
{{ site.url }}                     {# Site URL #}

{{ post.ID }}                      {# Post ID #}
{{ post.title }}                   {# Post title #}
{{ post.content }}                 {# Post content #}
{{ post.excerpt }}                 {# Post excerpt #}
{{ post.link }}                    {# Post permalink #}
{{ post.thumbnail }}               {# Featured image #}
{{ post.author }}                  {# Author object #}

{{ pagination }}                   {# Pagination links #}
{{ posts }}                        {# Array of posts #}

{{ current_user }}                 {# Current user object #}

{{ body_class }}                   {# WordPress body classes #}
{{ sidebar_class }}                {# Layout class for sidebar #}
```

**Custom context (added in entry point files or filters):**

```twig
{{ fields.* }}                     {# ACF fields (blocks) #}
{{ block.* }}                      {# Block data (blocks only) #}
{{ breadcrumb_trail }}             {# Breadcrumb array #}
{{ woocommerce_cart }}             {# WooCommerce cart object #}
```

### Common Template Patterns

#### Conditional Display

```twig
{% if post.thumbnail %}
	<img src="{{ post.thumbnail.src }}" alt="{{ post.title }}">
{% endif %}
```

#### Looping with Filters

```twig
{% for post in posts if post.post_status == 'publish' %}
	<h3>{{ post.title }}</h3>
{% endfor %}
```

#### Using WordPress Functions

```twig
{{ function('wp_get_nav_menu_items', menu_id) }}
{{ function('get_field', 'field_name', post_id) }}  {# Get ACF field #}
{{ _e('Translatable text', 'textdomain') }}         {# Translated text #}
```

#### Escaping HTML

```twig
{{ post.title }}                    {# Auto-escaped if autoescape enabled #}
{{ html_content | raw }}            {# Render raw HTML #}
{{ post.title | escape }}           {# Force escape #}
```

---

## Build System (Vite)

### Configuration Overview

**File:** `vite.config.js`

```javascript
import { defineConfig } from "vite";

export default defineConfig({
  root: "./",
  base: "/wp-content/themes/starter-theme-1.x/",

  build: {
    outDir: "dist",
    emptyOutDir: false,
    minify: "terser",
    sourcemap: false,
    target: "esnext",

    rollupOptions: {
      input: {
        main: "./scripts/index.js",
      },
      output: {
        entryFileNames: "site.js",
        chunkFileNames: "[name]-[hash].js",
        assetFileNames: "[name]-[hash][extname]",
        manualChunks: () => undefined,  // No code splitting
      },
    },
  },

  optimizeDeps: {
    include: ["bootstrap"],
  },

  resolve: {
    alias: {
      "@": "/scripts",
      "@utils": "/scripts/utils",
      "@components": "/scripts/components",
      "@modules": "/scripts/modules",
    },
  },
});
```

### Build Commands

**File:** `package.json`

```json
{
	"scripts": {
		"dev": "vite",                      // Dev server with HMR
		"build": "vite build",              // Production build
		"preview": "vite preview",          // Preview prod build
		"sass": "sass style.scss:style.css",
		"watch:css": "sass style.scss --watch",
		"watch": "concurrently \"npm:dev\" \"npm:watch:css\""
	}
}
```

### What Gets Bundled

**JavaScript Entry Point:** `scripts/index.js`

All JavaScript in this file and imports get bundled to `dist/site.js`:

```javascript
// imports all modules
import { initLazyLoading } from "./utils/lazy-loading.js";
import themeToggle from "./modules/theme-toggle.js";
import "./gallery.js";  // Side-effect imports (auto-initialize)
```

**CSS Entry Point:** `style.scss`

Compiled to `style.css` via Sass (not Vite):

```scss
@import "./css/inc/base/vars";
@import "./css/inc/components/*";
@import "./css/inc/blocks/*";
// etc.
```

### Enqueuing in WordPress

**File:** `functions.php`

```php
add_action( 'wp_enqueue_scripts', function() {
	// CSS
	wp_enqueue_style(
		'starter-theme-css',
		get_template_directory_uri() . '/style.css',
		array(),
		filemtime( get_template_directory() . '/style.css' )
	);

	// JavaScript (built by Vite)
	wp_enqueue_script(
		'starter-theme-js',
		get_template_directory_uri() . '/dist/site.js',
		array(),
		filemtime( get_template_directory() . '/dist/site.js' ),
		true  // Load in footer
	);

	// Pass PHP data to JavaScript
	wp_localize_script('starter-theme-js', 'themeConfig', array(
		'siteUrl'   => home_url(),
		'ajaxUrl'   => admin_url('admin-ajax.php'),
		'nonce'     => wp_create_nonce('theme-nonce'),
		'isLoggedIn' => is_user_logged_in(),
		'debug'     => defined('WP_DEBUG') && WP_DEBUG,
	));
} );
```

---

## CSS Architecture (BEM + SCSS)

### SCSS Organization (SMACSS + BEM)

**File:** `style.scss` (Import Order)

```scss
// 1. Base styles (variables, functions, resets)
@import "./css/inc/base/vars";
@import "./css/inc/base/reset";
@import "./css/inc/base/layout";
@import "./css/inc/base/font";

// 2. Base components
@import "./css/inc/base/btns";

// 3. Global sections
@import "./css/inc/global/nav";
@import "./css/inc/global/forms";
@import "./css/inc/global/footer";

// 4. Block-specific styles
@import "./css/inc/blocks/hero";
@import "./css/inc/blocks/banner-cta";
@import "./css/inc/blocks/testimonial";
@import "./css/inc/blocks/collection-products";

// 5. Page-specific styles
@import "./css/inc/pages/blog";
@import "./css/inc/pages/woocommerce";

// 6. Utilities
@import "./css/inc/utilities/helpers";
```

### BEM Naming Convention

**Block:** Independent component

```scss
.hero { }           // Block
.button { }         // Block
.card { }           // Block
```

**Element:** Part of a block

```scss
.hero__bg { }           // Element of .hero
.button__text { }       // Element of .button
.card__image { }        // Element of .card
```

**Modifier:** Variation of block or element

```scss
.button--primary { }    // Primary variation
.button--outline { }    // Outline variation
.card--featured { }     // Featured variation
.button__text--large { } // Large text variant element
```

### SCSS Architecture Example

**File:** `css/inc/blocks/hero.scss`

```scss
.hero {
  position: relative;
  overflow: hidden;
  min-height: 100svh;

  // Element
  &__bg {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    will-change: transform;
    transform: translate3d(0, 0, 0) scale(1.08);
    z-index: 0;
  }

  // Nested selectors
  .wrapper {
    position: relative;
    z-index: 1;
  }

  // Element
  &__wrapper {
    display: flex;
    flex-direction: column;
  }

  // Element
  &__top {
    height: 100svh;
    display: flex;
    justify-content: center;
    align-items: flex-end;
  }

  // Nested element
  &__top h1 {
    font-size: 6.4rem;
    font-weight: 400;
    letter-spacing: -0.03em;
  }

  // Modifier
  &.is-loading {
    opacity: 0.5;
  }

  // Media query (mobile-first)
  @media (max-width: 768px) {
    &__top h1 {
      font-size: 3.2rem;
    }
  }
}
```

### SCSS Base Variables

**File:** `css/inc/base/vars.scss`

```scss
// Colors
$primary: #1a1a1a;
$secondary: #ffffff;
$accent: #0066cc;
$error: #cc0000;
$success: #00cc00;

// Typography
$font-family-base: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
$font-family-heading: 'Georgia', serif;
$font-size-base: 1rem;
$font-size-lg: 1.25rem;
$font-size-sm: 0.875rem;

// Spacing
$spacing-xs: 0.25rem;
$spacing-sm: 0.5rem;
$spacing-md: 1rem;
$spacing-lg: 2rem;
$spacing-xl: 3rem;
$spacing-xxl: 5rem;

// Breakpoints
$bp-sm: 576px;
$bp-md: 768px;
$bp-lg: 992px;
$bp-xl: 1200px;

// Mixins
@mixin reset-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

@mixin flex-center {
  display: flex;
  justify-content: center;
  align-items: center;
}
```

### Using Utilities

**File:** `css/inc/utilities/helpers.scss`

```scss
// Margin utilities
.m-0 { margin: 0; }
.m-1 { margin: $spacing-md; }
.m-2 { margin: $spacing-lg; }

.mt-1 { margin-top: $spacing-md; }
.mb-1 { margin-bottom: $spacing-md; }

// Padding utilities
.p-0 { padding: 0; }
.p-1 { padding: $spacing-md; }

// Text utilities
.text-center { text-align: center; }
.text-bold { font-weight: bold; }
.text-muted { color: $color-muted; }

// Display utilities
.d-flex { display: flex; }
.d-grid { display: grid; }
.d-none { display: none; }

// Responsive visibility
@media (max-width: $bp-md) {
  .hide-mobile { display: none; }
}

@media (min-width: $bp-lg) {
  .show-desktop { display: block; }
}
```

---

## JavaScript Pattern & Module System

### Main Entry Point

**File:** `scripts/index.js`

```javascript
/**
 * Theme Main Entry Point
 * Imports and initializes all modules
 */

// Ensure global config exists
if (typeof window !== "undefined" && !window.themeConfig) {
  window.themeConfig = {
    siteUrl: "/",
    ajaxUrl: "/wp-admin/admin-ajax.php",
    nonce: "",
    isLoggedIn: false,
    debug: true,
  };
}

// Import utilities
import { querySelectorAll, addClass, removeClass } from "./utils/dom.js";
import { throttle, debounce } from "./utils/events.js";
import Logger from "./utils/logger.js";

// Import components
import { Modal } from "./components/Modal.js";
import { Dropdown } from "./components/Dropdown.js";

// Import feature modules (auto-initializing)
import "./hamburger.js";
import "./gallery.js";
import "./accordion.js";
import "./collection-products.js";

// Initialize theme when DOM ready
function initTheme() {
  Logger.debug("[Theme] Initializing...");
  
  // Initialize core features
  initCart();
  initCheckout();
  themeToggle();
  
  Logger.debug("[Theme] Ready!");
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initTheme);
} else {
  initTheme();
}
```

### Module Pattern Examples

#### Feature Module (Auto-Initializing)

**File:** `scripts/hamburger.js`

```javascript
/**
 * Hamburger Menu
 * Auto-initializes on page load
 */

import Logger from "./utils/logger.js";

function initHamburger() {
  const hamburger = document.querySelector(".hamburger");
  const navMenu = document.querySelector(".nav-menu");

  if (!hamburger || !navMenu) {
    Logger.debug("[Hamburger] Menu elements not found");
    return;
  }

  // Toggle menu on click
  hamburger.addEventListener("click", () => {
    hamburger.classList.toggle("is-active");
    navMenu.classList.toggle("is-active");
  });

  // Close menu when link clicked
  navMenu.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
      hamburger.classList.remove("is-active");
      navMenu.classList.remove("is-active");
    });
  });

  // Close when clicking outside
  document.addEventListener("click", (e) => {
    if (!e.target.closest("header")) {
      hamburger.classList.remove("is-active");
      navMenu.classList.remove("is-active");
    }
  });

  Logger.debug("[Hamburger] Initialized");
}

// Auto-initialize when DOM ready
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initHamburger);
} else {
  initHamburger();
}
```

#### Component Class Module

**File:** `scripts/components/Modal.js`

```javascript
/**
 * Modal Component
 * Reusable modal dialog system
 */

export class Modal {
  constructor(selector = ".modal") {
    this.modal = document.querySelector(selector);
    this.trigger = document.querySelector(`[data-modal-trigger="${selector}"]`);
    this.close = this.modal.querySelector("[data-modal-close]");
    
    this.bindEvents();
  }

  bindEvents() {
    if (this.trigger) {
      this.trigger.addEventListener("click", () => this.open());
    }

    if (this.close) {
      this.close.addEventListener("click", () => this.close());
    }

    // Close on background click
    this.modal.addEventListener("click", (e) => {
      if (e.target === this.modal) {
        this.close();
      }
    });

    // Close on ESC
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") {
        this.close();
      }
    });
  }

  open() {
    this.modal.classList.add("is-visible");
    document.body.classList.add("modal-open");
  }

  close() {
    this.modal.classList.remove("is-visible");
    document.body.classList.remove("modal-open");
  }

  toggleOpen() {
    this.modal.classList.toggle("is-visible");
  }
}
```

#### Utility Module

**File:** `scripts/utils/dom.js`

```javascript
/**
 * DOM Utility Functions
 */

export function querySelector(selector, context = document) {
  return context.querySelector(selector);
}

export function querySelectorAll(selector, context = document) {
  return context.querySelectorAll(selector);
}

export function addClass(element, ...classes) {
  if (!element) return;
  element.classList.add(...classes);
}

export function removeClass(element, ...classes) {
  if (!element) return;
  element.classList.remove(...classes);
}

export function toggleClass(element, className, force) {
  if (!element) return;
  element.classList.toggle(className, force);
}

export function setStyles(element, styles) {
  if (!element) return;
  Object.assign(element.style, styles);
}

export function getData(element, key) {
  return element?.dataset?.[key];
}

export function setData(element, key, value) {
  if (element) element.dataset[key] = value;
}
```

#### Logger Utility

**File:** `scripts/utils/logger.js`

```javascript
/**
 * Logging System
 * Provides debug logging with contextual information
 */

class Logger {
  static debug(...args) {
    if (window.themeConfig?.debug) {
      console.log("[Debug]", ...args);
    }
  }

  static info(...args) {
    console.info("[Info]", ...args);
  }

  static warn(...args) {
    console.warn("[Warn]", ...args);
  }

  static error(...args) {
    console.error("[Error]", ...args);
  }

  static setErrorReporter(reporter) {
    this.errorReporter = reporter;
  }
}

export default Logger;
```

### Event Handling Utilities

**File:** `scripts/utils/events.js`

```javascript
/**
 * Event Utility Functions
 * Throttle and debounce for performance
 */

export function throttle(func, delay = 100) {
  let lastCall = 0;

  return function (...args) {
    const now = Date.now();
    if (now - lastCall >= delay) {
      lastCall = now;
      func.apply(this, args);
    }
  };
}

export function debounce(func, delay = 300) {
  let timeoutId;

  return function (...args) {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
      func.apply(this, args);
    }, delay);
  };
}

export function onReady(callback) {
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", callback);
  } else {
    callback();
  }
}
```

### Passing Data from PHP to JavaScript

**In PHP (functions.php):**

```php
wp_localize_script('starter-theme-js', 'themeConfig', array(
	'siteUrl'    => home_url(),
	'ajaxUrl'    => admin_url('admin-ajax.php'),
	'nonce'      => wp_create_nonce('theme-nonce'),
	'isLoggedIn' => is_user_logged_in(),
	'currentUser' => get_current_user_id(),
	'postId'     => get_the_ID(),
));
```

**In JavaScript:**

```javascript
console.log(window.themeConfig.siteUrl);  // Access global config
console.log(window.themeConfig.nonce);    // Use for AJAX calls

// Make AJAX request
fetch(themeConfig.ajaxUrl, {
  method: 'POST',
  body: new FormData(data),
  headers: {
    'X-WP-Nonce': themeConfig.nonce,
  },
}).then(res => res.json())
  .then(data => console.log(data));
```

---

## WooCommerce Integration

### How WooCommerce Is Integrated

The theme **conditionally supports WooCommerce** but doesn't require it. If active, custom Timber templates render the shop.

### WooCommerce Detection

**File:** `functions.php`

```php
// Load WooCommerce features only if active
if ( class_exists( 'WooCommerce' ) ) {
	require_once 'src/woocommerce-integration.php';
	require_once 'src/woocommerce-wishlist.php';
	require_once 'src/woocommerce-comparison.php';
	require_once 'src/woocommerce-custom-emails.php';
}
```

### WooCommerce Template Rendering

**File:** `src/woocommerce-integration.php`

```php
<?php
/**
 * WooCommerce Direct Timber Rendering
 * Intercepts WooCommerce pages and renders Timber templates
 */

add_action( 'template_redirect', function() {
	if ( ! class_exists( 'WooCommerce' ) || ! class_exists( 'Timber' ) ) {
		return;
	}

	// Cart page
	if ( is_cart() ) {
		$context = Timber::context();
		$context['woocommerce_cart'] = WC()->cart;
		
		$cart = WC()->cart;
		$context['cart_totals'] = array(
			'subtotal' => (float) $cart->get_cart_contents_total(),
			'shipping' => (float) $cart->get_shipping_total(),
			'tax'      => (float) $cart->get_total_tax(),
			'total'    => $subtotal + $shipping + $tax
		);
		
		Timber::render( 'cart.twig', $context );
		exit;
	}

	// Checkout page
	if ( is_checkout() && ! is_order_received_page() ) {
		$context = Timber::context();
		$context['checkout'] = WC()->checkout();
		// Calculate totals...
		Timber::render( 'checkout.twig', $context );
		exit;
	}

	// Order received page
	if ( is_order_received_page() ) {
		$context = Timber::context();
		$order_id = sanitize_text_field( $_GET['order'] ?? 0 );
		$order = wc_get_order( $order_id );
		
		if ( $order ) {
			$context['order'] = $order;
			$context['order_items'] = $order->get_items();
			$context['order_total'] = $order->get_total();
		}
		
		Timber::render( 'order-received.twig', $context );
		exit;
	}
});
```

### WooCommerce Custom Post Type

**File:** `src/post-types/products.php`

```php
<?php
/**
 * Post Type: Collection Products (Custom, NOT WooCommerce)
 * For non-WooCommerce product displays
 */

return array(
	'args'   => array(
		'labels'             => array(
			'name'               => __( 'Products', 'starter-theme-1.x' ),
		),
		'public'             => true,
		'rewrite'            => array( 'slug' => 'collection-products' ),
		'menu_icon'          => 'dashicons-tag',
		'supports'           => array( 'title', 'thumbnail', 'excerpt', 'custom-fields' ),
	),
	'fields' => array(
		'key'      => 'group_featured_product',
		'title'    => 'Featured Product',
		'fields'   => array(
			array(
				'key'   => 'field_product_secondary_title',
				'label' => 'Secondary Title',
				'name'  => 'product_secondary_title',
				'type'  => 'text',
			),
			// More fields...
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'collection_products',
				),
			),
		),
	),
);
```

### WooCommerce Features Included

- **Wishlist** (`src/woocommerce-wishlist.php`)
- **Product Comparison** (`src/woocommerce-comparison.php`)
- **Custom Emails** (`src/woocommerce-custom-emails.php`)
- **Category Meta** (`src/woocommerce-category-meta.php`)

---

## Helper Functions & Utilities

### Sanitization Helpers

**File:** `src/helpers/sanitization.php`

```php
<?php
/**
 * Security-focused sanitization utilities
 */

function sanitize_email_field( $email ) {
	$email = sanitize_email( $email );
	if ( ! is_email( $email ) ) {
		return '';
	}
	return $email;
}

function sanitize_url_field( $url ) {
	$url = esc_url_raw( $url );
	return empty( $url ) ? '' : $url;
}

function sanitize_phone_field( $phone ) {
	// Allow digits, spaces, dashes, parentheses, plus
	return preg_replace( '/[^\d+\-\s\(\)]/', '', $phone );
}

function sanitize_text_field_with_html( $text, $allowed_tags = array() ) {
	if ( empty( $allowed_tags ) ) {
		$allowed_tags = array(
			'b' => array(),
			'strong' => array(),
			'i' => array(),
			'em' => array(),
			'a' => array( 'href' => array(), 'title' => array() ),
		);
	}
	return wp_kses( $text, $allowed_tags );
}

function sanitize_acf_field_value( $value, $field ) {
	if ( ! is_array( $field ) ) return $value;
	
	$type = $field['type'] ?? '';
	
	switch ( $type ) {
		case 'email':
			return sanitize_email_field( $value );
		case 'url':
			return sanitize_url_field( $value );
		case 'number':
		case 'range':
			return intval( $value );
		case 'checkbox':
			return is_array( $value ) ? array_map( 'sanitize_text_field', $value ) : sanitize_text_field( $value );
		default:
			return sanitize_text_field( $value );
	}
}
```

### Validation Helpers

**File:** `src/helpers/validation.php`

```php
<?php
/**
 * Data validation utilities
 */

function is_valid_email( $email ) {
	return is_email( $email );
}

function is_valid_url( $url ) {
	return filter_var( $url, FILTER_VALIDATE_URL ) !== false;
}

function is_valid_phone( $phone ) {
	$phone = preg_replace( '/[^\d]/', '', $phone );
	// Check if at least 10 digits (US phone)
	return strlen( $phone ) >= 10;
}

function validate_post_type( $post_type ) {
	return post_type_exists( $post_type );
}

function is_user_authorized( $capability = 'manage_options' ) {
	return current_user_can( $capability );
}

function post_exists( $post_id ) {
	return get_post( $post_id ) !== null;
}
```

---

## Configuration System

### Centralized Configuration

**File:** `src/config.php`

```php
<?php
/**
 * Theme Configuration
 * Single source of truth for all theme settings
 */

return array(
	// Theme metadata
	'theme' => array(
		'name'        => 'Starter Theme',
		'textdomain'  => 'starter-theme-1.x',
		'version'     => '1.0.0',
	),

	// Post types
	'post_types' => array(
		'blog'       => array( 'slug' => 'blog' ),
		'testimonial' => array( 'slug' => 'testimonial' ),
	),

	// Menu locations
	'menus' => array(
		'header' => __( 'Header Navigation', 'starter-theme-1.x' ),
		'footer' => __( 'Footer Navigation', 'starter-theme-1.x' ),
	),

	// Image sizes
	'image_sizes' => array(
		'thumbnail'      => array( 150, 150, true ),
		'large'          => array( 1024, 0, false ),
		'hero'           => array( 1200, 300, true ),
		'woocommerce_thumbnail' => array( 300, 300, true ),
	),

	// Theme support
	'theme_support' => array(
		'automatic-feed-links' => true,
		'title-tag'            => true,
		'post-thumbnails'      => true,
		'menus'                => true,
	),

	// Sidebar areas
	'sidebars' => array(
		'primary' => array(
			'name'          => __( 'Primary Sidebar', 'starter-theme-1.x' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
		),
	),

	// Timber configuration
	'timber' => array(
		'directories' => array( 'templates', 'views' ),
		'autoescape'  => false,
		'cache'       => false,
	),

	// Asset handles
	'assets' => array(
		'main_style'   => 'starter-theme-css',
		'main_script'  => 'starter-theme-js',
	),
);
```

### Type-Safe Config Access

**File:** `src/theme-config.php`

```php
<?php
class Theme_Config {
	private static array $config = array();

	public static function initialize(): void {
		if ( empty( self::$config ) ) {
			self::$config = require get_template_directory() . '/src/config.php';
		}
	}

	public static function get( ?string $section = null ) {
		self::initialize();
		
		if ( null === $section ) {
			return self::$config;
		}

		return self::$config[ $section ] ?? array();
	}

	public static function get_theme_meta(): array {
		return self::get( 'theme' );
	}

	public static function get_post_types(): array {
		return self::get( 'post_types' );
	}

	public static function get_timber(): array {
		return self::get( 'timber' );
	}
}
```

**Usage:**

```php
$theme_name = Theme_Config::get_theme_meta()['name'];
$post_types = Theme_Config::get_post_types();
$timber_config = Theme_Config::get_timber();
```

---

## File Organization & Conventions

### Naming Conventions

#### Post Type Files

**Convention:** `src/post-types/{post-type-slug}.php`

**File Headers:**
```php
<?php
/**
 * Post Type: {Display Name}
 * PostType: {post_type_slug}
 * Name: {Display Name}
 * FieldsKey: {acf_field_group_key}
 */
```

**Example:** `src/post-types/blog.php`

#### Block Files

**Convention:** `views/blocks/{block-slug}.twig` + `src/blocks/{block-slug}.php`

**Template Header:**
```twig
{#
Title: {Block Name}
Description: {Description}
Category: {category}
Icon: {icon}
Keywords: {keywords}
Mode: auto
PostTypes: page post
#}
```

#### CSS/SCSS Files

**Convention:** `css/inc/{layer}/{component}.scss`

**Layers:**
- `base/` - Variables, mixins, resets, layouts
- `components/` - BEM components
- `global/` - Global sections (nav, footer, forms)
- `blocks/` - Block-specific styles
- `pages/` - Page-specific styles
- `utilities/` - Helper classes

#### JavaScript Files

**Convention:** Feature modules auto-initialize on load

```javascript
// Auto-initializing features
./hamburger.js
./gallery.js
./accordion.js

// Exported utilities
./utils/dom.js
./utils/events.js
./utils/logger.js

// Reusable components
./components/Modal.js
./components/Dropdown.js

// Feature modules (exported functions)
./modules/cart.js
./modules/checkout.js
```

---

## Best Practices & Patterns

### 1. Creating New Blocks - Step-by-Step

**Step 1: Create Field Configuration**

`src/blocks/my-block.php`:

```php
<?php
if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

acf_add_local_field_group( array(
	'key'      => 'group_block_my_block',
	'title'    => 'My Block',
	'fields'   => array(
		array(
			'key'   => 'field_my_block_title',
			'label' => 'Title',
			'name'  => 'title',
			'type'  => 'text',
			'required' => 1,
		),
		array(
			'key'   => 'field_my_block_content',
			'label' => 'Content',
			'name'  => 'content',
			'type'  => 'textarea',
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'block',
				'operator' => '==',
				'value'    => 'acf/my-block',
			),
		),
	),
) );
```

**Step 2: Create Block Template**

`views/blocks/my-block.twig`:

```twig
{#
Title: My Block
Description: My custom block
Category: common
Icon: <svg>...</svg>
Keywords: my custom block
Mode: auto
PostTypes: page post
SupportsMode: true
SupportsMultiple: true
#}

<div class="block my-block" id="my-block-{{ block.id }}">
	<div class="wrapper">
		<h2 class="my-block__title">{{ fields.title }}</h2>
		<div class="my-block__content">
			{{ fields.content | raw }}
		</div>
	</div>
</div>
```

**Step 3: Create Block Styles**

`css/inc/blocks/my-block.scss`:

```scss
.my-block {
	padding: $spacing-lg;

	&__title {
		font-size: 2rem;
		font-weight: 600;
		margin-bottom: $spacing-md;
	}

	&__content {
		font-size: 1rem;
		line-height: 1.6;
	}
}
```

**Step 4: (Optional) Add Block JavaScript**

`scripts/my-block.js`:

```javascript
function initMyBlock() {
	const blocks = document.querySelectorAll('.my-block');
	blocks.forEach(block => {
		// Add interactivity here
	});
}

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', initMyBlock);
} else {
	initMyBlock();
}
```

Then import in `scripts/index.js`:

```javascript
import './my-block.js';
```

**Block now available in Gutenberg!** No manual registration needed.

### 2. Creating New Post Types

**File:** `src/post-types/services.php`

```php
<?php
/**
 * Post Type: Services
 * PostType: service
 * Name: Services
 * FieldsKey: group_services
 */

return array(
	'args'   => array(
		'labels'             => array(
			'name'               => __( 'Services', 'starter-theme-1.x' ),
			'singular_name'      => __( 'Service', 'starter-theme-1.x' ),
			'add_new'            => __( 'Add New Service', 'starter-theme-1.x' ),
		),
		'public'             => true,
		'show_ui'            => true,
		'rewrite'            => array( 'slug' => 'service' ),
		'menu_icon'          => 'dashicons-briefcase',
		'supports'           => array( 'title', 'thumbnail', 'excerpt' ),
	),
	'fields' => array(
		'key'      => 'group_services',
		'title'    => 'Service Fields',
		'fields'   => array(
			array(
				'key'   => 'field_service_description',
				'label' => 'Description',
				'name'  => 'description',
				'type'  => 'textarea',
			),
			array(
				'key'   => 'field_service_icon',
				'label' => 'Icon',
				'name'  => 'icon',
				'type'  => 'image',
			),
		),
		'location' => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'service',
				),
			),
		),
	),
);
```

Post type auto-discovered and registered on theme load!

### 3. Security Best Practices

**Always sanitize and escape:**

```php
// On save
$value = sanitize_text_field( $_POST['field'] );
rest_ensure_response( sanitize_email_field( $email ) );

// On output
echo esc_html( $value );
echo esc_attr( $attribute );
echo esc_url( $url );
wp_kses_post( $html );  // For trusted HTML
```

**Nonce verification:**

```php
// On form
wp_nonce_field( 'action_name', 'nonce_field' );

// On verification
if ( ! isset( $_POST['nonce_field'] ) || ! wp_verify_nonce( $_POST['nonce_field'], 'action_name' ) ) {
	wp_die( 'Nonce verification failed' );
}
```

**Capability checks:**

```php
if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( 'Unauthorized' );
}
```

### 4. Performance Optimization

**Lazy load images:**

```twig
<img src="{{ image.url }}" loading="lazy" alt="{{ image.alt }}">
```

**Use asset versioning:**

```php
wp_enqueue_style(
	'style-handle',
	$url,
	array(),
	filemtime( $file_path )  // Use mod time as version
);
```

**Cache expensive queries:**

```php
$posts = wp_cache_get( 'featured_posts' );
if ( false === $posts ) {
	$posts = get_posts( array( 'featured' => true ) );
	wp_cache_set( 'featured_posts', $posts, '', 3600 );
}
```

### 5. Accessibility Patterns

**Always include alt text:**

```twig
<img src="{{ image.url }}" alt="{{ image.alt }}">
```

**Use semantic HTML:**

```twig
<nav role="navigation" aria-label="Main Navigation">
<main id="content" role="main">
<article role="article">
<footer role="contentinfo">
```

**Button accessibility:**

```twig
<button aria-label="Close menu" onclick="...">×</button>
```

**Form labels:**

```twig
<label for="field-id">Field Name</label>
<input id="field-id" name="field" type="text">
```

---

## Creating New Blocks - Step-by-Step

See "Best Practices & Patterns" section above for complete block creation guide.

---

## Common Code Patterns

### Pattern 1: Getting ACF Field on Post

```php
// In post type or block template
$field_value = get_field( 'field_name', $post_id );
```

```twig
{{ TimberPost(post_id).meta('field_name') }}
```

### Pattern 2: Looping Repeater Field

```twig
{% for row in fields.repeater_field %}
	<div>{{ row.sub_field }}</div>
{% endfor %}
```

### Pattern 3: Conditional Markup

```twig
{% if fields.image %}
	<img src="{{ fields.image.url }}" alt="{{ fields.image.alt }}">
{% else %}
	<p>No image available</p>
{% endif %}
```

### Pattern 4: Loop Related Posts

```twig
{% set related_posts = TimberPost(post_id).meta('related_posts') %}
{% for post_id in related_posts %}
	{% set post = TimberPost(post_id) %}
	<h3>{{ post.title }}</h3>
{% endfor %}
```

### Pattern 5: WordPress Hook/Filter

```php
// In functions.php or within a class

// Action - do something
add_action( 'hook_name', function() {
	// Your code
});

// Filter - modify something
add_filter( 'hook_name', function( $value ) {
	return modified_value;
});
```

### Pattern 6: AJAX Endpoint

```php
// Register action
add_action( 'wp_ajax_my_action', 'handle_my_action' );
add_action( 'wp_ajax_nopriv_my_action', 'handle_my_action' );

function handle_my_action() {
	check_ajax_referer( 'nonce_name' );
	
	$data = isset( $_POST['data'] ) ? sanitize_text_field( $_POST['data'] ) : '';
	
	wp_send_json_success( array( 'result' => 'success' ) );
}
```

```javascript
// Call from JavaScript
fetch(themeConfig.ajaxUrl, {
	method: 'POST',
	headers: {
		'X-WP-Nonce': themeConfig.nonce,
		'Content-Type': 'application/x-www-form-urlencoded'
	},
	body: 'action=my_action&data=' + encodeURIComponent(value)
})
.then(r => r.json())
.then(data => console.log(data));
```

---

## Summary

This theme implements a **modern, scalable WordPress theme architecture** with:

✅ **Auto-discovery systems** for blocks, post types, and templates  
✅ **Centralized configuration** for maintainability  
✅ **Modular PHP** with clear single responsibility  
✅ **Type-safe code** with PHP 7.4+ type hints  
✅ **Reusable Twig components** for DRY templates  
✅ **BEM CSS methodology** for scalable styling  
✅ **ES6 JavaScript modules** with Vite bundling  
✅ **Security-first helpers** for sanitization  
✅ **Optional WooCommerce support** without coupling  

**To create anything new:** Follow the file naming conventions and patterns documented above. The auto-discovery systems will handle registration automatically.

---

**End of Codebase Analysis**
