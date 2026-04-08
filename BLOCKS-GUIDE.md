# Custom Gutenberg Blocks Guide

**Complete reference for the Text block available in the theme**

This guide documents the available block, its fields, usage, and styling.

## Table of Contents

1. [Block Overview](#block-overview)
2. [Architecture](#architecture)
3. [Text Block Reference](#text-block-reference)
4. [Creating New Blocks](#creating-new-blocks)
5. [Styling Blocks](#styling-blocks)
6. [Best Practices](#best-practices)

---

## Block Overview

This theme includes **1 custom Gutenberg block**:

### Content & Text

- **Text** - Basic rich text block with formatting

---

## Architecture

### How Blocks Are Auto-Registered

Blocks are **automatically discovered and registered** by the theme without requiring manual registration in `functions.php`:

**Flow:**

1. `src/timber-acf-wp-blocks.php` - Auto-discovery system that scans `views/blocks/` for block templates
2. Block template files in `views/blocks/*.twig` with metadata headers (Title, Category, etc.)
3. Block field definitions in `src/blocks/*.php` (auto-loaded via glob pattern in `functions.php`)
4. When a `.twig` template with proper headers is found, it's automatically registered as a Gutenberg block
5. ACF field groups in `src/blocks/*.php` automatically attach to the corresponding block via the `acf/my-block` location

**No manual registration needed!** Just create the files following the naming convention.

**File Locations:**

- **Block templates:** `views/blocks/*.twig`
- **Block fields:** `src/blocks/*.php`
- **Block styles:** `css/inc/blocks/*.scss`

### Block Metadata Format

Each block template uses Twig comment headers to define its configuration. These headers are scanned by the auto-discovery system:

```twig
{#
Title: My Block
Description: What this block does
Category: common
Icon: <svg>...</svg>
Keywords: keyword1 keyword2
Mode: auto
PostTypes: page post
SupportsMode: true
SupportsMultiple: true
#}
```

**Required Headers:**

- `Title` - Block name in the editor
- `Category` - Block category (common, design, widgets, media, etc.)

**Optional Headers:**

- `Description` - Block description in editor
- `Icon` - SVG or dashicon name
- `Keywords` - Space-separated keywords for searching
- `Mode` - "auto", "preview", or "edit"
- `PostTypes` - Limit block to specific post types (space-separated)
- `SupportsMode` - Allow switching between edit/preview modes
- `SupportsMultiple` - Allow multiple instances of the block

### File System

```
src/
├── blocks/                  # ACF field configuration
│   └── text.php            # Text block fields
│
views/
└── blocks/                  # Block templates
    └── text.twig           # Text block template

css/inc/blocks/             # Block-specific styles
└── text.scss
```

---

## Text Block Reference

### Text Block

**Purpose:** Basic rich text block with formatting options for content creation

**Category:** Text / Common  
**Post Types:** page, post  
**Supports Multiple:** Yes

**Description:**

The Text block provides a simple rich text editor for creating formatted content with support for:

- Headings (h1-h6)
- Paragraphs
- Lists (ordered and unordered)
- Bold, italic, strikethrough text
- Links
- Blockquotes
- Code blocks

**Render:**

The block renders plain text content using WordPress's rich text processing.

**CSS Class:** `.text`

**Related Files:**

- Template: `views/blocks/text.twig`
- Styles: `css/inc/blocks/text.scss`
- Fields: `src/blocks/text.php`

---

## Creating New Blocks

### Step 1: Create Block Template

Create a new file in `views/blocks/my-block.twig`:

```twig
{#
Title: My Block
Description: What this block does
Category: common
Icon: <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
Keywords: my block custom
Mode: auto
PostTypes: page post
SupportsMode: true
SupportsMultiple: true
#}

<section class="my-block {{ block.classes }}">
  <div class="wrapper">
    {% if fields.title %}
      <h2 class="my-block__title">{{ fields.title }}</h2>
    {% endif %}
    
    {% if fields.content %}
      <div class="my-block__content">
        {{ fields.content }}
      </div>
    {% endif %}
  </div>
</section>
```

### Step 2: Create Block Fields

Create a new file in `src/blocks/my-block.php`:

```php
<?php

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
    'key' => 'group_my_block',
    'title' => 'My Block',
    'fields' => array(
        array(
            'key' => 'field_my_block_title',
            'label' => 'Title',
            'name' => 'title',
            'type' => 'text',
            'required' => 1,
        ),
        array(
            'key' => 'field_my_block_content',
            'label' => 'Content',
            'name' => 'content',
            'type' => 'wysiwyg',
            'required' => 1,
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

### Step 3: Create Block Styles

Create a new file in `css/inc/blocks/my-block.scss`:

```scss
.my-block {
  padding: $spacing-5 0;
  
  &__title {
    @include heading-xl;
    margin-bottom: $spacing-3;
  }
  
  &__content {
    @include body-text;
    
    p {
      margin-bottom: $spacing-2;
    }
  }
}
```

### Step 4: Auto-Registration

That's it! The block is now automatically:

1. ✅ Discovered by the auto-discovery system
2. ✅ Registered as a Gutenberg block
3. ✅ Styled via SCSS
4. ✅ Available in the block editor

No manual registration or hooks needed!

---

## Styling Blocks

### Block-Specific Styles

Store all block styles in `css/inc/blocks/block-name.scss`. They are automatically compiled and included in the main stylesheet.

**BEM Naming Convention:**

```scss
// Block container
.my-block {
  
  // Block elements
  &__title {
    @include heading-lg;
  }
  
  &__content {
    @include body-text;
  }
  
  // Modifiers
  &--featured {
    background: $color-primary;
  }
}
```

### Editor-Only Styles

For styles that should only appear in the editor (not frontend), use the ACF block editor stylesheet. Create `css/inc/blocks/my-block-editor.scss`:

```scss
// Styles only for the Gutenberg editor
.wp-block-acf-my-block {
  outline: 2px dashed $color-primary;
  padding: $spacing-3;
}
```

---

## Best Practices

### 1. Use Meaningful Block Names

```
✅ GOOD: hero.twig, cta-button.twig, pricing-table.twig
❌ BAD: block1.twig, section.twig, content.twig
```

### 2. Document Metadata Headers

Always include all relevant headers so blocks are discoverable:

```twig
{#
Title: Newsletter Signup
Description: Email subscription form for newsletter
Category: forms
Icon: <svg>...</svg>
Keywords: newsletter email subscribe signup
PostTypes: page
#}
```

### 3. Keep Fields Organized

Group related fields using ACF field groups and use field names that match Twig variable names for easy template access:

```php
array(
    'key' => 'field_headline',
    'label' => 'Headline',
    'name' => 'headline',  // Access as {{ fields.headline }} in Twig
    'type' => 'text',
),
```

### 4. Mobile-First Responsive Design

Design blocks to be responsive on mobile first:

```scss
.my-block {
  padding: $spacing-2;  // Mobile default
  
  @media (min-width: $bp-tablet) {
    padding: $spacing-4;
  }
  
  @media (min-width: $bp-desktop) {
    padding: $spacing-5;
  }
}
```

### 5. Test in Editor

Always test your block in the Gutenberg editor to ensure:

- Fields display correctly
- Styles match the preview
- Validation works as expected
- Multiple instances can coexist without conflicts

### 6. Accessibility

Ensure blocks meet WCAG standards:

- Use semantic HTML (h1-h6, p, ul, ol, etc.)
- Include alt text for images
- Maintain proper heading hierarchy
- Use color combinations with sufficient contrast
- Provide keyboard navigation where needed

---

## Summary

The theme currently focuses on a simple Text block for rich text content creation. The auto-discovery system allows for easy expansion—add new blocks by:

1. Creating template + fields + styles
2. Following the naming conventions
3. Adding appropriate metadata headers

That's all! The theme handles the rest automatically.
