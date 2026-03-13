# Custom Gutenberg Blocks Guide

**Complete reference for all 15 custom Gutenberg blocks in the theme**

This guide documents all available blocks, their fields, usage, and styling.

## Table of Contents

1. [Block Overview](#block-overview)
2. [Architecture](#architecture)
3. [All Blocks Reference](#all-blocks-reference)
4. [Creating New Blocks](#creating-new-blocks)
5. [Block Field Types](#block-field-types)
6. [Styling Blocks](#styling-blocks)
7. [Block JavaScript](#block-javascript)
8. [Best Practices](#best-practices)

---

## Block Overview

This theme includes **15 pre-built custom Gutenberg blocks** organized by purpose:

### Layout Blocks (5)

- **Hero** - Large hero section with background image and buttons
- **Banner** - Promotional banner with optional overlay
- **Image + Text** - Side-by-side image and text layout
- **Gallery** - Responsive image grid with lightbox
- **Video** - Video player with keyboard shortcuts

### Content Blocks (4)

- **Text** - Basic rich text block with formatting
- **Tabs** - Tabbed content with keyboard navigation
- **Accordion** - Expandable content sections
- **Blog Posts** - Display selected blog posts in grid

### Call-to-Action Blocks (2)

- **CTA** - Call-to-action with configurable buttons
- **Hero** - Large hero banner (also used for CTAs)

### Interactive Blocks (3)

- **Newsletter** - Email subscription form
- **Contact** - Contact information section
- **Share Buttons** - Social media sharing buttons

### Utility Blocks (1)

- **Announcement** - Dismissible announcement bar

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
│   ├── hero.php            # Hero block fields
│   ├── banner.php          # Banner block fields
│   ├── tabs.php            # Tabs block fields
│   └── ... (one file per block)
│
views/
└── blocks/                  # Block templates
    ├── hero.twig           # Hero block template
    ├── banner.twig         # Banner block template
    ├── tabs.twig           # Tabs block template
    └── ... (one file per block)

css/inc/blocks/             # Block-specific styles
├── hero.scss
├── banner.scss
├── tabs.scss
└── ... (one file per block)
```

---

## All Blocks Reference

### 1. Hero Block

**Purpose:** Large hero section with background image, title, subtitle, and action buttons

**Category:** Design / Layout  
**Post Types:** page, post

**Available Fields:**

| Field              | Type     | Required | Max Length | Description                     |
| ------------------ | -------- | -------- | ---------- | ------------------------------- |
| Title              | Text     | Yes      | 200        | Hero section title              |
| Subtitle           | Textarea | No       | —          | Hero section subtitle (3 rows)  |
| Background Image   | Image    | No       | —          | Background image (array format) |
| Buttons (Repeater) | Repeater | No       | 2 max      | Action buttons                  |
| Button Label       | Text     | Yes      | 100        | Text on button                  |
| Button URL         | URL      | Yes      | —          | Button link destination         |
| Button Style       | Select   | No       | —          | primary or secondary            |

**Example Usage:**

```twig
{% include "blocks/hero" with {
    title: "Welcome to Our Site",
    subtitle: "Discover amazing content",
    background_image: image_id,
    buttons: [
        { label: "Get Started", url: "/about", style: "primary" },
        { label: "Learn More", url: "/features", style: "secondary" }
    ]
} %}
```

**CSS Class:** `.hero`

---

### 2. Banner Block

**Purpose:** Promotional banner with optional background image and overlay

**Category:** Design  
**Post Types:** page, post

**Available Fields:**

| Field         | Type  | Required | Description                  |
| ------------- | ----- | -------- | ---------------------------- |
| Title         | Text  | Yes      | Banner headline              |
| Subtitle      | Text  | No       | Optional subtitle            |
| Image         | Image | No       | Background image             |
| Overlay Color | Color | No       | Overlay color (if image set) |

**Features:**

- Conditional overlay styling (only displays when image is set)
- Responsive height
- Customizable overlay color

**CSS Class:** `.banner` with modifier `.banner--has-image` when image is present

**Related Files:**

- Template: `views/blocks/banner.twig`
- Styles: `css/inc/blocks/banner.scss`
- Fields: `src/blocks/banner.php`

---

### 3. Image + Text Block

**Purpose:** Side-by-side layout with image on one side and text on the other

**Category:** Design  
**Post Types:** page, post

**Available Fields:**

| Field      | Type    | Required | Description                       |
| ---------- | ------- | -------- | --------------------------------- |
| Image      | Image   | Yes      | Featured image                    |
| Title      | Text    | Yes      | Section title                     |
| Content    | WYSIWYG | Yes      | Rich text content                 |
| Image Side | Select  | No       | "left" or "right" (default: left) |
| CTA Button | Link    | No       | Call-to-action button             |

**CSS Class:** `.image-text` with modifier `.image-text--reverse` for right-side image

---

### 4. Gallery Block

**Purpose:** Responsive image grid with lightbox functionality

**Category:** Media  
**Post Types:** page, post

**Available Fields:**

| Field       | Type     | Required | Description             |
| ----------- | -------- | -------- | ----------------------- |
| Title       | Text     | No       | Gallery section title   |
| Images      | Repeater | Yes      | Collection of images    |
| Columns     | Select   | No       | Layout: 2, 3, 4 columns |
| Enable Link | Toggle   | No       | Open in lightbox        |

**Features:**

- Responsive grid (adjusts columns on mobile)
- Lightbox zoom effect
- Image hover animations

**CSS Class:** `.gallery`

---

### 5. Video Block

**Purpose:** Video player with keyboard shortcuts and responsive sizing

**Category:** Media  
**Post Types:** page, post

**Available Fields:**

| Field         | Type   | Required | Description                |
| ------------- | ------ | -------- | -------------------------- |
| Title         | Text   | No       | Video section title        |
| Video URL     | URL    | Yes      | YouTube, Vimeo, or MP4 URL |
| Autoplay      | Toggle | No       | Play on page load          |
| Muted         | Toggle | No       | Start without sound        |
| Show Controls | Toggle | No       | Display player controls    |

**Keyboard Shortcuts:**

- `Space` - Play/Pause
- `M` - Mute/Unmute
- `F` - Fullscreen
- `←/→` - Rewind/Forward (5 seconds)

**CSS Class:** `.video-block`

---

### 6. Text Block

**Purpose:** Basic rich text block with formatting options

**Category:** Common / Content  
**Post Types:** page, post

**Available Fields:**

| Field      | Type    | Required | Description          |
| ---------- | ------- | -------- | -------------------- |
| Content    | WYSIWYG | Yes      | Rich text editor     |
| Text Align | Select  | No       | left, center, right  |
| Text Size  | Select  | No       | small, normal, large |

**Features:**

- Headings (H1-H6)
- Bold, italic, underline
- Lists (ordered and unordered)
- Blockquotes
- Link insertion

**CSS Class:** `.text-block`

---

### 7. Tabs Block

**Purpose:** Display content in organized tabs with click and keyboard navigation

**Category:** Common  
**Post Types:** page, post

**Available Fields:**

| Field              | Type     | Required | Description             |
| ------------------ | -------- | -------- | ----------------------- |
| Title              | Text     | No       | Block title             |
| Tabs (Repeater)    | Repeater | Yes      | Tab definitions         |
| Tab Label          | Text     | Yes      | Tab button text         |
| Tab Content        | WYSIWYG  | Yes      | Tab content (rich text) |
| Default Active Tab | Select   | No       | Which tab to show first |

**Keyboard Navigation:**

- `Tab` - Move between tabs
- `Enter/Space` - Activate tab
- `Arrow Keys` - Navigate between tabs

**Features:**

- Smooth content transitions
- Accessible keyboard navigation
- ARIA labels for screen readers
- Multiple tabs per page

**CSS Class:** `.tabs-block`

---

### 8. Accordion Block

**Purpose:** Expandable content sections that collapse/expand on click

**Category:** Common  
**Post Types:** page, post

**Available Fields:**

| Field                 | Type     | Required | Description         |
| --------------------- | -------- | -------- | ------------------- |
| Title                 | Text     | No       | Block title         |
| Accordion Items       | Repeater | Yes      | Expandable items    |
| Item Title            | Text     | Yes      | Section heading     |
| Item Content          | WYSIWYG  | Yes      | Section content     |
| Allow Multiple Active | Toggle   | No       | Open multiple items |

**Features:**

- Click to expand/collapse
- Keyboard navigation support
- Optional: only one section open at once
- Smooth animations

**CSS Class:** `.accordion`

---

### 9. Blog Posts Block

**Purpose:** Display selected blog posts in a responsive grid

**Category:** Common  
**Post Types:** page

**Available Fields:**

| Field        | Type         | Required | Max       | Description                |
| ------------ | ------------ | -------- | --------- | -------------------------- |
| Title        | Text         | No       | —         | Block title                |
| Blog Posts   | Relationship | Yes      | Unlimited | Select which posts to show |
| Show Excerpt | Toggle       | No       | —         | Display post excerpt       |
| Show Date    | Toggle       | No       | —         | Display publication date   |
| Columns      | Select       | No       | —         | 1, 2, or 3 columns         |

**Features:**

- Manual post selection via ACF Relationship field
- Responsive grid (3 columns → 2 → 1 on mobile)
- Image, title, meta, excerpt display
- "View All Blog Posts" link
- Posts converted to Timber objects for template compatibility

**CSS Class:** `.blog-posts-block`

**Related Files:**

- Template: `views/blocks/blog-posts.twig`
- Styles: `css/inc/blocks/blog-posts.scss`
- Fields: `src/blocks/blog-posts.php`

---

### 10. Share Buttons Block

**Purpose:** Display social sharing buttons for the current page

**Category:** Widgets  
**Post Types:** page, post

**Available Fields:**

| Field     | Type       | Required | Options                                                                   | Description      |
| --------- | ---------- | -------- | ------------------------------------------------------------------------- | ---------------- |
| Title     | Text       | No       | —                                                                         | Block title      |
| Platforms | Checkboxes | No       | Facebook, Twitter, LinkedIn, Pinterest, Email, WhatsApp, Reddit, Telegram | Select platforms |
| Alignment | Select     | No       | left, center, right                                                       | Button alignment |
| Style     | Select     | No       | icons, labels, both                                                       | Display style    |
| Size      | Select     | No       | small, medium, large                                                      | Button size      |

**Supported Platforms:**

- **Facebook** - Share post link
- **Twitter** - Tweet with link and title
- **LinkedIn** - Share to LinkedIn profile
- **Pinterest** - Pin the featured image
- **Email** - Send page via email
- **WhatsApp** - Share via WhatsApp
- **Reddit** - Post to subreddit
- **Telegram** - Share via Telegram

**Features:**

- Open in new window/tab
- Uses current page URL and title
- Customizable display (icons only, labels only, or both)
- Responsive sizing
- Built-in social platform SVG icons

**CSS Class:** `.share-buttons` with modifiers:

- `.share-buttons--left`, `.share-buttons--center`, `.share-buttons--right`
- `.share-buttons--small`, `.share-buttons--medium`, `.share-buttons--large`
- `.share-buttons--icons`, `.share-buttons--labels`, `.share-buttons--both`

---

### 11. CTA (Call-to-Action) Block

**Purpose:** Promotional section with headline, description, and action buttons

**Category:** Design  
**Post Types:** page, post

**Available Fields:**

| Field              | Type     | Required | Description                  |
| ------------------ | -------- | -------- | ---------------------------- |
| Headline           | Text     | Yes      | Attention-grabbing title     |
| Description        | Textarea | No       | Supporting text (3 rows)     |
| Background         | Select   | No       | solid, gradient, image       |
| Background Color   | Color    | No       | For solid backgrounds        |
| Background Image   | Image    | No       | For image backgrounds        |
| Buttons (Repeater) | Repeater | No       | 2 max call-to-action buttons |
| Button Label       | Text     | Yes      | Button text                  |
| Button URL         | URL      | Yes      | Link destination             |
| Button Style       | Select   | No       | primary, secondary           |

**CSS Class:** `.cta` with modifiers `.cta--solid`, `.cta--gradient`, `.cta--image`

---

### 12. Newsletter Block

**Purpose:** Email subscription form for building mailing lists

**Category:** Widgets  
**Post Types:** page, post

**Available Fields:**

| Field            | Type     | Required | Description             |
| ---------------- | -------- | -------- | ----------------------- |
| Title            | Text     | Yes      | Form headline           |
| Subtitle         | Textarea | No       | Supporting message      |
| Placeholder Text | Text     | No       | Input field placeholder |
| Button Text      | Text     | No       | Subscribe button label  |
| Success Message  | Text     | No       | Confirmation message    |

**Features:**

- Email validation
- Success/error messages
- Spam protection (honeypot field)
- Integration with mailing service
- Responsive form layout

**CSS Class:** `.newsletter-form`

---

### 13. Contact Block

**Purpose:** Display contact information and optional contact form

**Category:** Widgets  
**Post Types:** page

**Available Fields:**

| Field        | Type   | Required | Description             |
| ------------ | ------ | -------- | ----------------------- |
| Title        | Text   | Yes      | Section title           |
| Show Phone   | Toggle | No       | Display phone number    |
| Show Email   | Toggle | No       | Display email address   |
| Show Address | Toggle | No       | Display mailing address |
| Show Map     | Toggle | No       | Display contact map     |
| Map Embed    | Text   | No       | Google Maps embed HTML  |

**Sources:**

- Phone/Email from theme options
- Address from theme settings
- Optional embedded map

**CSS Class:** `.contact-section`

---

### 14. Announcement Block

**Purpose:** Important announcement or alert banner with dismiss option

**Category:** Widgets  
**Post Types:** page, post

**Available Fields:**

| Field        | Type   | Required | Description                   |
| ------------ | ------ | -------- | ----------------------------- |
| Message      | Text   | Yes      | Announcement text             |
| Type         | Select | No       | info, success, warning, error |
| Icon Display | Toggle | No       | Show icon before text         |
| Dismissible  | Toggle | No       | Allow user to close           |

**Features:**

- Color-coded by type (info/success/warning/error)
- Optional dismiss button (stores in localStorage)
- Icon display options
- Persistent dismissal (doesn't reappear for 7 days)

**CSS Class:** `.announcement` with modifiers:

- `.announcement--info`
- `.announcement--success`
- `.announcement--warning`
- `.announcement--error`

---

## Creating New Blocks

### Step 1: Create Block Template

Create a new file in `views/blocks/my-block.twig`:

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

{% set my_field = fields.my_field | default('Default value') %}

<section class="my-block {{block.classes}}">
    <div class="wrapper">
        <h2>{{ my_field }}</h2>
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
    'title' => 'My Block Fields',
    'fields' => array(
        array(
            'key' => 'field_my_block_title',
            'label' => 'Title',
            'name' => 'title',
            'type' => 'text',
            'instructions' => 'Enter block title',
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
@import "../base/vars";

.my-block {
  padding: $spacing-4;
  background-color: $bg-color;

  &__title {
    font-size: $font-size-lg;
    color: $text-color;
  }
}
```

### Step 4: That's It!

The theme **auto-discovers and auto-registers** everything:

1. **Block template** - `views/blocks/my-block.twig` is auto-discovered by `src/timber-acf-wp-blocks.php`
2. **Block fields** - `src/blocks/my-block.php` is auto-loaded via the glob pattern in `functions.php`:
   ```php
   foreach ( glob( __DIR__ . '/src/blocks/*.php' ) as $block_file ) {
       require_once $block_file;
   }
   ```

No manual registration needed! Just create the files and they work.

---

## Block Field Types

### Common Field Types

| Type         | Description                 | Example             |
| ------------ | --------------------------- | ------------------- |
| text         | Single-line text input      | Post title          |
| textarea     | Multi-line text input       | Short description   |
| wysiwyg      | Rich text editor            | Post content        |
| image        | Image upload and selection  | Featured image      |
| repeater     | Dynamic list of fields      | Multiple buttons    |
| select       | Dropdown list               | Button style choice |
| toggle       | On/off checkbox             | Show image option   |
| color        | Color picker                | Background color    |
| url          | URL input with validation   | Button link         |
| relationship | Post/term selection (multi) | Related posts       |
| link         | Link chooser                | CTA button link     |

---

## Styling Blocks

### Block Style Files

Create a new SCSS file for each block in `css/inc/blocks/`:

```
css/inc/blocks/my-block.scss
```

**Import this file in `style.scss`** to include it in the compiled stylesheet.

### BEM Methodology & SCSS Variables

This theme uses **BEM (Block-Element-Modifier)** naming for CSS and centralized **SCSS variables** for colors, spacing, breakpoints, and transitions.

For detailed styling patterns, variable reference, and best practices, see **[BEM-GUIDE.md](BEM-GUIDE.md)**.

---

## Block JavaScript

### Adding JavaScript to Blocks

Some blocks require JavaScript interactivity. The theme includes:

**Interactive Blocks:**

- **Tabs** (`scripts/tabs.js`) - Tab switching and keyboard navigation
- **Accordion** - Expand/collapse functionality
- **Gallery** - Lightbox and zoom effects
- **Share Buttons** (`scripts/share-buttons.js`) - Share functionality
- **Announcement** - Dismiss button logic
- **Newsletter** - Form validation and submission

### Creating Block JavaScript

Create a new file in `scripts/modules/my-block.js`:

```javascript
/**
 * My Block Functionality
 */

export function initMyBlock() {
  const blocks = document.querySelectorAll(".my-block");

  blocks.forEach((block) => {
    const button = block.querySelector(".my-block__button");

    if (button) {
      button.addEventListener("click", () => {
        block.classList.toggle("is-active");
      });
    }
  });
}
```

Then import and initialize in `scripts/index.js`:

```javascript
import { initMyBlock } from "./modules/my-block.js";

document.addEventListener("DOMContentLoaded", () => {
  initMyBlock();
});
```

---

### 4. Accessibility

- Use semantic HTML (`<section>`, `<article>`, etc.)
- Add ARIA labels for interactive elements
- Ensure keyboard navigation works
- Maintain sufficient color contrast
- Include focus states on buttons

### 5. Performance

- Minimize JavaScript on each block
- Use CSS transitions instead of JavaScript animations
- Lazy-load images in galleries
- Avoid inline styles (use SCSS classes)

### 6. Consistent Defaults

Always provide sensible defaults in your Twig template:

```twig
{% set alignment = fields.alignment | default('center') %}
{% set show_image = fields.show_image | default(true) %}
{% set style = fields.style | default('default') %}
```

---

## Troubleshooting

### Block Not Appearing in Editor

1. Check that `views/blocks/my-block.twig` exists and has **Title** and **Category** headers in the metadata section
2. Verify ACF field group exists in `src/blocks/my-block.php` with proper location matching `acf/my-block`
3. Verify the block field file `src/blocks/my-block.php` exists (it's auto-loaded)
4. Clear WordPress cache and refresh the WordPress admin page
5. Check browser console for JavaScript errors

### Fields Not Showing

1. Verify `acf_add_local_field_group()` is loaded
2. Check that Location matches block name (`acf/my-block`)
3. Ensure all required field properties are set
4. Check that ACF Pro is activated and up-to-date

### Styling Not Applied

1. Verify SCSS file exists in `css/inc/blocks/my-block.scss`
2. Check that main `style.scss` imports the block styles
3. Run `npm run build` to compile changes
4. Clear browser cache (Ctrl+F5 or Cmd+Shift+R)
5. Check for conflicting CSS specificity

### JavaScript Not Working

1. Verify the script file exists in `scripts/` (e.g., `scripts/my-block.js`)
2. Check that the script is enqueued in `functions.php` using `wp_enqueue_script()`
3. Run `npm run build` to compile JavaScript changes
4. Check browser console (F12) for JavaScript errors
5. Verify the script selector targets the correct elements (`.my-block` class)

---

## See Also

- [DEVELOPER-GUIDE.md](DEVELOPER-GUIDE.md) - Full development reference
- [BEM-GUIDE.md](BEM-GUIDE.md) - CSS/SCSS methodology
- [JAVASCRIPT-GUIDE.md](JAVASCRIPT-GUIDE.md) - JavaScript setup and patterns
- [COMPONENTS-GUIDE.md](COMPONENTS-GUIDE.md) - Reusable Twig components
