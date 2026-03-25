# Custom Gutenberg Blocks Guide

**Complete reference for all 35 custom Gutenberg blocks in the theme**

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
9. [Block Variations & Presets](#block-variations--presets)

---

## Block Overview

This theme includes **35 pre-built custom Gutenberg blocks** organized by purpose:

### Layout & Structure Blocks (6)

- **Hero** - Large hero section with background image and buttons
- **Banner** - Promotional banner with optional overlay
- **Image + Text** - Side-by-side image and text layout
- **Gallery** - Responsive image grid with lightbox
- **Video** - Video player with keyboard shortcuts
- **Spacer** - Vertical spacing utility block

### Content & Text Blocks (8)

- **Text** - Basic rich text block with formatting
- **Tabs** - Tabbed content with keyboard navigation
- **Accordion** - Expandable content sections
- **Blog Posts** - Display selected blog posts in grid (manual selection)
- **Cards Grid** - Flexible reusable card grid (3 styles: standard, overlay, horizontal)
- **FAQ Filtered** - Advanced FAQ with search and category filtering
- **Related Content** - Query-based automatic content feed (latest posts, filtering)
- **Quote** - Testimonial/blockquote with attribution

### Navigation & Lists (4)

- **Breadcrumb** - Navigation hierarchy display with schema markup
- **Icon List** - Features/benefits list with icons
- **Logo Grid** - Client/partner logo carousel
- **Slider** - Image/content carousel with navigation

### Services & Commerce (4)

- **Service Showcase** - Featured services display
- **Service Pricing** - Service pricing tiers/plans
- **Service Comparison** - Table comparison of services/features
- **Pricing/Plans** - Subscription tiers display (2 layouts: horizontal cards, comparison table)

### Interactive & Forms (4)

- **Newsletter** - Email subscription form
- **Contact** - Contact information section
- **Form Embed** - Embedded form integrations (Gravity Forms, Contact Form 7, etc.)
- **Share Buttons** - Social media sharing buttons

### Team & Testimonials (3)

- **Team** - Team member profiles grid
- **Testimonial** - Single testimonial with rating/attribution
- **Service Testimonials Carousel** - Auto-rotating testimonials carousel

### Utility & Components (4)

- **Announcement** - Dismissible announcement bar
- **Stats** - Statistics/metrics display with counters
- **Steps** - Step-by-step process workflow
- **Downloads** - File/asset download links list

### Maps & Location (1)

- **Map** - Embedded map display (Google Maps, Mapbox)

---

## Complete Block Count Summary

| Category             | Count  | Blocks                                                                     |
| -------------------- | ------ | -------------------------------------------------------------------------- |
| Layout & Structure   | 6      | Hero, Banner, Image+Text, Gallery, Video, Spacer                           |
| Content & Text       | 8      | Text, Tabs, Accordion, Blog Posts, Cards Grid, FAQ, Related Content, Quote |
| Navigation & Lists   | 4      | Breadcrumb, Icon List, Logo Grid, Slider                                   |
| Services & Commerce  | 4      | Service Showcase, Service Pricing, Service Comparison, Pricing/Plans       |
| Interactive & Forms  | 4      | Newsletter, Contact, Form Embed, Share Buttons                             |
| Team & Testimonials  | 3      | Team, Testimonial, Service Testimonials Carousel                           |
| Utility & Components | 4      | Announcement, Stats, Steps, Downloads                                      |
| Maps & Location      | 1      | Map                                                                        |
| **TOTAL**            | **35** | **All custom blocks**                                                      |

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

### 15. Pricing / Plans Block

**Purpose:** Display subscription tiers and pricing plans with feature comparison and CTA buttons

**Category:** Common / Commerce  
**Post Types:** page, post

**Available Fields:**

| Field               | Type     | Required | Description                              |
| ------------------- | -------- | -------- | ---------------------------------------- |
| Title               | Text     | No       | Section headline                         |
| Description         | Textarea | No       | Section intro text                       |
| Currency            | Select   | No       | USD, EUR, GBP, JPY, INR, CAD, AUD        |
| Layout Mode         | Select   | No       | horizontal (cards) or comparison (table) |
| Column Count        | Select   | No       | 2, 3, or 4 columns per row               |
| Plans (Repeater)    | Repeater | Yes      | Individual pricing tier                  |
| Plan Name           | Text     | Yes      | Tier name (e.g., "Starter")              |
| Price               | Number   | Yes      | Monthly/annual price                     |
| Billing Period      | Text     | Yes      | Billing text (e.g., "/month")            |
| Description         | Textarea | No       | Plan description                         |
| Features (Repeater) | Repeater | No       | List of features included                |
| Feature Name        | Text     | Yes      | Feature label                            |
| Include Feature     | Toggle   | No       | Include in this plan                     |
| CTA Link            | Link     | No       | Call-to-action button                    |
| Featured Plan       | Toggle   | No       | Highlight as recommended tier            |

**Layouts:**

- **Horizontal Cards** - Side-by-side pricing cards with featured tier elevated
- **Comparison Table** - Tabular view of all plans and features with checkmarks

**Features:**

- Currency selector (auto-formats prices)
- Featured plan visual distinction
- Feature inclusion toggles per tier
- Dynamic column layout
- Mobile-responsive stacking
- Runs JSON pricing schema for SEO

**CSS Class:** `.pricing-block` with modifiers:

- `.pricing-block--horizontal` (card layout)
- `.pricing-block--comparison` (table layout)
- `.pricing-plan--featured` (highlighted tier)

---

### 16. Cards Grid Block

**Purpose:** Flexible, reusable grid for displaying cards (services, features, portfolio items, etc.)

**Category:** Design / Layout  
**Post Types:** page, post

**Available Fields:**

| Field            | Type     | Required | Description                        |
| ---------------- | -------- | -------- | ---------------------------------- |
| Cards (Repeater) | Repeater | Yes      | Individual card items              |
| Card Image       | Image    | Yes      | Featured image (alt text required) |
| Card Title       | Text     | Yes      | Card heading                       |
| Card Description | Textarea | Yes      | Card content                       |
| Card Link        | Link     | No       | Optional destination URL           |
| Highlight Card   | Toggle   | No       | Visual emphasis (border/shadow)    |
| Card Style       | Select   | No       | standard, overlay, or horizontal   |
| Image Height     | Select   | No       | 200px, 250px, or 300px             |
| Column Count     | Select   | No       | 2, 3, or 4 columns per row         |

**Layouts:**

- **Standard** - Image on top, text below in card
- **Overlay** - Full-height image with hovering text overlay
- **Horizontal** - Image left (40%), text right (60%), responsive stack

**Features:**

- Three distinct visual styles
- Highlight toggle for featured cards
- Lazy-loaded images with alt text
- Link support with arrow icon
- Responsive column collapsing
- Custom image height control via CSS variables

**CSS Class:** `.cards-grid` with modifiers:

- `.cards-grid--standard`
- `.cards-grid--overlay`
- `.cards-grid--horizontal`
- `.cards-grid__card--highlight` (individual card)

---

### 17. FAQ / Search Block

**Purpose:** Advanced FAQ section with real-time search filtering and category selection

**Category:** Common / Interactive  
**Post Types:** page, post

**Available Fields:**

| Field                  | Type     | Required | Description                          |
| ---------------------- | -------- | -------- | ------------------------------------ |
| Title                  | Text     | No       | Section headline                     |
| Description            | Textarea | No       | Section intro text                   |
| Enable Search          | Toggle   | No       | Show keyword search input            |
| Enable Category Filter | Toggle   | No       | Show category dropdown               |
| Expand First Item      | Toggle   | No       | Open first FAQ by default            |
| FAQ Items (Repeater)   | Repeater | Yes      | Individual Q&A pairs                 |
| Question               | Text     | Yes      | FAQ question (searchable)            |
| Answer                 | WYSIWYG  | Yes      | Answer with rich text support        |
| Category               | Select   | No       | Category for filtering               |
| Categories List        | Text     | No       | Comma-separated available categories |
| Schema Markup Enabled  | Toggle   | No       | Output FAQPage for Google            |

**Features:**

- Real-time search by keyword (question-based)
- Category filtering dropdown
- Accordion UI with smooth animations
- First item expand option
- No-results message display
- FAQPage JSON-LD schema for search results
- Full keyboard navigation support
- Semantic HTML with ARIA labels

**Interactions:**

- Search updates instantly as user types
- Category filter updates visible FAQs
- Combined search + filter coordination
- Click question to expand/collapse answer
- Keyboard: Enter/Space to toggle, Tab to navigate

**CSS Class:** `.faq-search` with elements:

- `.faq-search__trigger` (question button)
- `.faq-search__panel` (answer container)
- `.faq-search__search-input`
- `.faq-search__filter-select`

---

### 18. Related Content / Latest Content Block

**Purpose:** Query-based automatic content feed for displaying related or latest posts dynamically

**Category:** Common / Content  
**Post Types:** page, post, product

**Available Fields:**

| Field                       | Type     | Required | Description                                   |
| --------------------------- | -------- | -------- | --------------------------------------------- |
| Title                       | Text     | No       | Section headline                              |
| Description                 | Textarea | No       | Section intro text                            |
| Content Type                | Select   | Yes      | Post type to query (post, page, product)      |
| Filter by Category/Taxonomy | Select   | No       | Taxonomy to filter by                         |
| Filter by Specific Term     | Select   | No       | Specific category/tag/term                    |
| Number of Items             | Number   | Yes      | 1-20 items to display                         |
| Order By                    | Select   | Yes      | Latest, Oldest, Updated, Alphabetical, Random |
| Columns                     | Select   | Yes      | 2, 3, or 4 columns                            |
| Show Excerpt                | Toggle   | No       | Display post excerpt (150 chars)              |
| Show Featured Image         | Toggle   | No       | Display featured image thumbnail              |
| Show Publish Date           | Toggle   | No       | Display date and author info                  |
| Exclude Current Post        | Toggle   | No       | Don't include current post in results         |
| Link Behavior               | Select   | Yes      | Open in same window or new window             |

**Layouts:**

- **3-Column Grid** - Desktop: 3 cols → Tablet: 2 cols → Mobile: 1 col
- **Card Layout** - Image on top (250px), content below, hover lift effect
- **Tablet Variant** - Image left (40%), content right (60%), stacks on mobile
- **Mobile Layout** - Stacked vertical cards with hover on focus

**Features:**

- Query-based (automatic) - not manual post selection
- Dynamic post type support (works with any registered post type)
- Taxonomy filtering (category, tag, custom taxonomies)
- Flexible sorting: date (newest/oldest), title, random
- Exclude current post option (prevent self-reference)
- Configurable excerpt length (150 chars max)
- Optional featured image display
- Meta info: publish date + author name
- Empty state message when no posts match filters
- Responsive column collapsing (3 → 2 → 1)
- Card hover: +4px lift, border color change, shadow
- Image hover: 1.05x scale with overflow hidden
- Read More link with animated arrow icon
- Full keyboard navigation support
- Semantic HTML with proper heading hierarchy

**Example Use Cases:**

- "Related Articles" section on blog posts
- "Latest News" feed on homepage
- "Recent Products" grid on product pages
- "Latest from Category X" sidebar widget
- "Trending" or "Random" content promoter

**Differences from Blog Posts Block:**

| Feature   | Blog Posts                  | Related Content                 |
| --------- | --------------------------- | ------------------------------- |
| Selection | Manual (editor picks posts) | Automatic (WP_Query)            |
| Updates   | Static (fixed content)      | Dynamic (reflects latest posts) |
| Filtering | None                        | By post type, taxonomy, term    |
| Sorting   | By publish date             | Latest, oldest, random, title   |
| Use Case  | Curated list                | Auto-updating feed              |

**CSS Classes:**

- `.related-content` (block wrapper)
- `.related-content__items` (grid container)
- `.related-content__item` (individual card)
- `.related-content__image` (image container)
- `.related-content__content` (text content area)
- `.related-content__title-item` (post title)
- `.related-content__meta` (date/author info)
- `.related-content__excerpt` (post excerpt)
- `.related-content__read-more` (CTA link)
- `.related-content__empty` (no posts state)

---

### 19. Breadcrumb Block

**Purpose:** Navigation breadcrumb trail showing page hierarchy for SEO and UX

**Category:** Navigation  
**Post Types:** page, post, product

**Available Fields:**

| Field             | Type   | Required | Description                                 |
| ----------------- | ------ | -------- | ------------------------------------------- |
| Enable Breadcrumb | Toggle | No       | Show/hide breadcrumb                        |
| Show Home Link    | Toggle | No       | Include home page link                      |
| Separator         | Text   | No       | Breadcrumb separator character (default: /) |

**Features:**

- Automatic hierarchy detection
- Schema.org BreadcrumbList JSON-LD markup
- Mobile-responsive
- WCAG 2.1 AA compliant
- Works with all post types
- Customizable separator

**CSS Classes:** `.breadcrumb` with elements `.breadcrumb__item`, `.breadcrumb__link`, `.breadcrumb__separator`

---

### 20. Icon List Block

**Purpose:** Display list of features, benefits, or items with icons

**Category:** Lists & Navigation  
**Post Types:** page, post

**Available Fields:**

| Field            | Type        | Required | Description                     |
| ---------------- | ----------- | -------- | ------------------------------- |
| Title            | Text        | No       | Section heading                 |
| Items (Repeater) | Repeater    | Yes      | List items with icons           |
| Item Icon        | Icon Picker | Yes      | Icon for list item              |
| Item Title       | Text        | Yes      | Item heading                    |
| Item Description | Textarea    | No       | Item description                |
| Columns          | Select      | No       | 1, 2, or 3 columns (responsive) |
| Show Icons       | Toggle      | No       | Display icons (toggle on/off)   |

**Features:**

- Icon picker with 100+ icons
- Responsive grid layout
- Optional descriptions
- Hover effects
- Dark mode support

**CSS Classes:** `.icon-list` with `.icon-list__item`, `.icon-list__icon`, `.icon-list__title`, `.icon-list__description`

---

### 21. Logo Grid Block

**Purpose:** Display client logos, partner logos, or brand carousel

**Category:** Lists & Navigation  
**Post Types:** page, post

**Available Fields:**

| Field            | Type     | Required | Description              |
| ---------------- | -------- | -------- | ------------------------ |
| Title            | Text     | No       | Section heading          |
| Logos (Repeater) | Repeater | Yes      | Logo items               |
| Logo Image       | Image    | Yes      | Logo/brand image         |
| Logo Link        | URL      | No       | Link when clicked        |
| Columns          | Select   | No       | 2-5 columns (responsive) |

**Features:**

- Responsive grid layout
- Optional logo links
- Image optimization
- Auto aspect-ratio
- Clickable logos

**CSS Classes:** `.logo-grid` with `.logo-grid__item`, `.logo-grid__image`, `.logo-grid__link`

---

### 22. Slider / Carousel Block

**Purpose:** Image or content carousel with navigation controls

**Category:** Layout & Structure  
**Post Types:** page, post

**Available Fields:**

| Field              | Type     | Required | Description                         |
| ------------------ | -------- | -------- | ----------------------------------- |
| Slides (Repeater)  | Repeater | Yes      | Carousel slides                     |
| Slide Image        | Image    | Yes      | Slide image                         |
| Slide Title        | Text     | No       | Slide headline                      |
| Slide Description  | WYSIWYG  | No       | Slide content                       |
| Auto-play          | Toggle   | No       | Auto-rotate slides                  |
| Auto-play Interval | Number   | No       | Seconds between slides (default: 5) |
| Display Controls   | Toggle   | No       | Show prev/next buttons              |
| Display Indicators | Toggle   | No       | Show dots indicator                 |

**Features:**

- Auto-rotate with configurable interval
- Previous/next navigation
- Dot indicators
- Keyboard controls
- Touch/swipe support
- Pause on hover
- Accessible with ARIA labels

**CSS Classes:** `.slider` with `.slider__item`, `.slider__image`, `.slider__title`, `.slider__controls`

---

### 23. Service Showcase Block

**Purpose:** Feature prominent services with images and descriptions

**Category:** Services  
**Post Types:** page

**Available Fields:**

| Field               | Type        | Required | Description             |
| ------------------- | ----------- | -------- | ----------------------- |
| Title               | Text        | No       | Section title           |
| Services (Repeater) | Repeater    | Yes      | Service items           |
| Service Title       | Text        | Yes      | Service name            |
| Service Icon        | Icon Picker | No       | Service icon            |
| Service Image       | Image       | No       | Service image           |
| Service Description | WYSIWYG     | Yes      | Service details         |
| Service Link        | URL         | No       | Link to service page    |
| Layout              | Select      | No       | Grid, list, or carousel |
| Columns             | Select      | No       | 2 or 3 columns          |

**Features:**

- Service cards with icons
- Featured images
- Call-to-action links
- Multiple layout options
- Dark mode support

**CSS Classes:** `.service-showcase` with `.service-showcase__item`, `.service-showcase__title`, `.service-showcase__description`

---

### 24. Service Pricing Block

**Purpose:** Display service pricing tiers and options

**Category:** Services  
**Post Types:** page

**Available Fields:**

| Field             | Type     | Required | Description                      |
| ----------------- | -------- | -------- | -------------------------------- |
| Title             | Text     | No       | Section heading                  |
| Prices (Repeater) | Repeater | Yes      | Pricing tiers                    |
| Plan Name         | Text     | Yes      | Tier name                        |
| Price             | Text     | Yes      | Price amount                     |
| Price Unit        | Text     | No       | /month, /year, etc.              |
| Features          | Textarea | Yes      | Feature list (newline separated) |
| Call-to-Action    | Text     | No       | Button label                     |
| Link              | URL      | No       | Signup link                      |

**Features:**

- Multiple pricing tiers
- Feature lists
- Call-to-action buttons
- Compare pricing
- Dark mode support

**CSS Classes:** `.service-pricing` with `.service-pricing__tier`, `.service-pricing__price`, `.service-pricing__features`

---

### 25. Service Comparison Block

**Purpose:** Compare services, features, or plans in table format

**Category:** Services  
**Post Types:** page

**Available Fields:**

| Field          | Type        | Required | Description         |
| -------------- | ----------- | -------- | ------------------- |
| Title          | Text        | No       | Section title       |
| Services       | Repeater    | Yes      | Service columns     |
| Service Name   | Text        | Yes      | Column header       |
| Features       | Repeater    | Yes      | Feature rows        |
| Feature Name   | Text        | Yes      | Feature description |
| Feature Values | Repeater    | Yes      | Values per service  |
| Feature Value  | Text/Toggle | Yes      | Yes/No/text value   |

**Features:**

- Responsive comparison table
- Toggle values (Yes/No)
- Custom values per service
- Sticky header on scroll
- Sortable columns
- Mobile-friendly card layout

**CSS Classes:** `.service-comparison` with `.service-comparison__table`, `.service-comparison__feature`, `.service-comparison__value`

---

### 26. Quote / Testimonial Block

**Purpose:** Display a blockquote or testimonial with attribution

**Category:** Content & Text  
**Post Types:** page, post

**Available Fields:**

| Field        | Type    | Required | Description                     |
| ------------ | ------- | -------- | ------------------------------- |
| Quote Text   | WYSIWYG | Yes      | The quote content               |
| Author Name  | Text    | No       | Quote attribution               |
| Author Title | Text    | No       | Author position/title           |
| Author Image | Image   | No       | Author photo                    |
| Style        | Select  | No       | Default, bordered, accent color |
| Citation     | Text    | No       | Source/reference                |

**Features:**

- Formatted blockquote styling
- Author attribution
- Optional author photo
- Multiple style options
- Pull quotes
- Semantic HTML (<blockquote>)

**CSS Classes:** `.quote` with `.quote__text`, `.quote__citation`, `.quote__author`, `.quote__author-image`

---

### 27. Spacer Block

**Purpose:** Add vertical spacing between blocks

**Category:** Utility & Components  
**Post Types:** page, post

**Available Fields:**

| Field            | Type   | Required | Description           |
| ---------------- | ------ | -------- | --------------------- |
| Desktop Height   | Number | No       | Space on desktop (px) |
| Tablet Height    | Number | No       | Space on tablet (px)  |
| Mobile Height    | Number | No       | Space on mobile (px)  |
| Background Color | Color  | No       | Optional background   |

**Features:**

- Responsive height configuration
- Optional background color
- Visual editor preview
- Custom height per breakpoint

**CSS Classes:** `.spacer` with inline styles

---

### 28. Form Embed Block

**Purpose:** Embed contact forms, signup forms, or external form integrations

**Category:** Interactive & Forms  
**Post Types:** page, post

**Available Fields:**

| Field            | Type   | Required | Description                           |
| ---------------- | ------ | -------- | ------------------------------------- |
| Form Type        | Select | Yes      | Gravity Forms, CF7, custom, Mailchimp |
| Form Selection   | Select | Yes      | Which form to display                 |
| Show Title       | Toggle | No       | Display form title                    |
| Show Description | Toggle | No       | Display form description              |
| Form Theme       | Select | No       | Light, dark, custom CSS               |

**Features:**

- Gravity Forms integration
- Contact Form 7 support
- Mailchimp signup support
- Custom form shortcodes
- Theme styling
- Responsive layout

**CSS Classes:** `.form-embed` with `.form-embed__form`, `.form-embed__field`, `.form-embed__submit`

---

### 29. Team Block

**Purpose:** Display team members with profiles, roles, and contact info

**Category:** Team & Testimonials  
**Post Types:** page

**Available Fields:**

| Field                   | Type     | Required | Description           |
| ----------------------- | -------- | -------- | --------------------- |
| Title                   | Text     | No       | Section heading       |
| Team Members (Repeater) | Repeater | Yes      | Member profiles       |
| Member Name             | Text     | Yes      | Full name             |
| Member Photo            | Image    | Yes      | Profile image         |
| Member Title            | Text     | No       | Job title/role        |
| Member Bio              | WYSIWYG  | No       | Brief bio             |
| Member Email            | Email    | No       | Contact email         |
| Member Phone            | Phone    | No       | Contact phone         |
| Social Links            | Repeater | No       | Social media profiles |
| Columns                 | Select   | No       | 2, 3, or 4 columns    |

**Features:**

- Member profiles with photos
- Job titles and bios
- Contact information
- Social media links
- Responsive grid
- Hover effects

**CSS Classes:** `.team` with `.team__member`, `.team__photo`, `.team__name`, `.team__title`, `.team__bio`

---

### 30. Testimonial Block

**Purpose:** Display single testimonial with name, role, and optional rating

**Category:** Team & Testimonials  
**Post Types:** page, post

**Available Fields:**

| Field            | Type    | Required | Description             |
| ---------------- | ------- | -------- | ----------------------- |
| Testimonial Text | WYSIWYG | Yes      | Quote content           |
| Author Name      | Text    | Yes      | Customer/client name    |
| Author Role      | Text    | No       | Job title or company    |
| Author Image     | Image   | No       | Profile photo           |
| Rating           | Select  | No       | 1-5 star rating         |
| Style            | Select  | No       | Card, bordered, minimal |

**Features:**

- Testimonial cards
- Star ratings (1-5)
- Author photos
- Multiple style options
- Dark mode support
- Responsive layout

**CSS Classes:** `.testimonial` with `.testimonial__text`, `.testimonial__author`, `.testimonial__rating`

---

### 31. Service Testimonials Carousel Block

**Purpose:** Auto-rotating carousel of customer testimonials

**Category:** Team & Testimonials  
**Post Types:** page

**Available Fields:**

| Field                   | Type     | Required | Description       |
| ----------------------- | -------- | -------- | ----------------- |
| Title                   | Text     | No       | Section heading   |
| Testimonials (Repeater) | Repeater | Yes      | Testimonial items |
| Quote                   | WYSIWYG  | Yes      | Testimonial text  |
| Name                    | Text     | Yes      | Author name       |
| Title                   | Text     | No       | Author title      |
| Image                   | Image    | No       | Author photo      |
| Rating                  | Number   | No       | Star rating (1-5) |
| Auto-play               | Toggle   | No       | Auto-rotate       |
| Duration                | Number   | No       | Seconds per slide |

**Features:**

- Auto-rotating carousel
- Manual navigation controls
- Star ratings
- Author photos
- Pause on hover
- Keyboard controls
- Touch swipe support

**CSS Classes:** `.testimonials-carousel` with `.carousel__item`, `.carousel__quote`, `.carousel__author`

---

### 32. Stats / Counters Block

**Purpose:** Display statistics, metrics, or counters with animations

**Category:** Utility & Components  
**Post Types:** page, post

**Available Fields:**

| Field            | Type        | Required | Description        |
| ---------------- | ----------- | -------- | ------------------ |
| Title            | Text        | No       | Section title      |
| Stats (Repeater) | Repeater    | Yes      | Stat items         |
| Number           | Number      | Yes      | Stat number/value  |
| Label            | Text        | Yes      | Stat description   |
| Prefix           | Text        | No       | + \$ % etc.        |
| Suffix           | Text        | No       | k M % etc.         |
| Icon             | Icon Picker | No       | Stat icon          |
| Columns          | Select      | No       | 2, 3, or 4 columns |

**Features:**

- Animated counters
- Custom prefixes/suffixes
- Icons with stats
- Responsive grid
- Number formatting
- Dark mode support

**CSS Classes:** `.stats` with `.stats__item`, `.stats__number`, `.stats__label`, `.stats__icon`

---

### 33. Steps / Process Block

**Purpose:** Display step-by-step process, workflow, or instructions

**Category:** Utility & Components  
**Post Types:** page, post

**Available Fields:**

| Field            | Type        | Required | Description          |
| ---------------- | ----------- | -------- | -------------------- |
| Title            | Text        | No       | Section title        |
| Steps (Repeater) | Repeater    | Yes      | Step items           |
| Step Number      | Number      | Auto     | Auto-numbered        |
| Step Title       | Text        | Yes      | Step name            |
| Step Description | WYSIWYG     | Yes      | Step details         |
| Step Icon        | Icon Picker | No       | Step icon            |
| Layout           | Select      | No       | Vertical, horizontal |

**Features:**

- Numbered steps
- Icon support
- Descriptions
- Vertical/horizontal layout
- Responsive design
- Progress indicators

**CSS Classes:** `.steps` with `.steps__item`, `.steps__number`, `.steps__title`, `.steps__description`

---

### 34. Downloads Block

**Purpose:** Display downloadable files, resources, or documents

**Category:** Utility & Components  
**Post Types:** page, post

**Available Fields:**

| Field                | Type        | Required | Description       |
| -------------------- | ----------- | -------- | ----------------- |
| Title                | Text        | No       | Section heading   |
| Downloads (Repeater) | Repeater    | Yes      | Download items    |
| File Name            | Text        | Yes      | Display name      |
| File                 | File        | Yes      | File to download  |
| File Type            | Text        | No       | PDF, Doc, Video   |
| File Size            | Text        | No       | Display file size |
| Description          | Textarea    | No       | Item description  |
| Icon                 | Icon Picker | No       | File type icon    |

**Features:**

- File download links
- File size display
- Icon indicators
- File type detection
- Download tracking
- Responsive list

**CSS Classes:** `.downloads` with `.downloads__item`, `.downloads__link`, `.downloads__size`, `.downloads__icon`

---

### 35. Map Block

**Purpose:** Embed Google Maps or Mapbox map with markers and controls

**Category:** Maps & Location  
**Post Types:** page, post

**Available Fields:**

| Field              | Type     | Required | Description           |
| ------------------ | -------- | -------- | --------------------- |
| Map Provider       | Select   | Yes      | Google Maps or Mapbox |
| API Key            | Text     | Yes      | Maps API key          |
| Latitude           | Number   | Yes      | Center latitude       |
| Longitude          | Number   | Yes      | Center longitude      |
| Zoom Level         | Number   | No       | Map zoom (1-20)       |
| Markers (Repeater) | Repeater | No       | Location markers      |
| Marker Title       | Text     | No       | Marker label          |
| Marker Lat         | Number   | Yes      | Marker latitude       |
| Marker Lng         | Number   | Yes      | Marker longitude      |
| Marker Info        | Textarea | No       | Info window text      |
| Map Height         | Number   | No       | Height in pixels      |
| Map Controls       | Toggle   | No       | Show zoom controls    |

**Features:**

- Google Maps/Mapbox support
- Multiple markers
- Info windows
- Custom controls
- Mobile responsive
- Touch-friendly
- Street View support

**CSS Classes:** `.map` with `.map__container`, `.map__marker`, `.map__info-window`

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

## Block Variations & Presets

Block variations and presets improve the editor experience by providing pre-configured block instances that editors can insert with one click, reducing setup time and ensuring consistency across the site.

### What Are Block Variations?

**Block variations** are pre-configured versions of a block with different field values, styles, or layouts. They appear as additional options when users insert a block in the WordPress editor.

**Example:** A Hero block might have variations for:

- "Dark Overlay" - Hero with dark background overlay
- "Light Overlay" - Hero with light background overlay
- "No Overlay" - Full image visibility
- "Video Background" - Hero with video background

Instead of creating one "Hero" block type, you create **one block with multiple variations**, each with different default field values.

### Registering Block Variations with ACF

Block variations are registered in the block's PHP configuration file using ACF filters.

#### Basic Variation Registration

**File:** `src/blocks/hero.php`

```php
<?php

// Register the block
acf_register_block_type( array(
    'name'            => 'hero',
    'title'           => 'Hero',
    'description'     => 'Large hero section with image and text',
    'render_template' => 'views/blocks/hero.twig',
    'category'        => 'layout',
    'icon'            => 'cover-image',
    'keywords'        => array( 'hero', 'banner', 'header' ),
    'supports'        => array(
        'anchor'  => true,
        'multiple' => true,
    ),
) );

// Register variations for this block
add_filter( 'register_block_type_args', function( $args, $name ) {
    if ( $name !== 'acf/hero' ) {
        return $args;
    }

    // Add variations
    $args['variations'] = array(
        array(
            'name'       => 'dark-overlay',
            'title'      => 'Dark Overlay',
            'description' => 'Hero with dark background overlay',
            'icon'       => 'cover-image',
            'attributes' => array(
                'data' => array(
                    'overlay_style' => 'dark',
                    'overlay_opacity' => 50,
                ),
            ),
        ),
        array(
            'name'       => 'light-overlay',
            'title'      => 'Light Overlay',
            'description' => 'Hero with light background overlay',
            'icon'       => 'cover-image',
            'attributes' => array(
                'data' => array(
                    'overlay_style' => 'light',
                    'overlay_opacity' => 30,
                ),
            ),
        ),
        array(
            'name'       => 'no-overlay',
            'title'      => 'No Overlay',
            'description' => 'Full image visibility',
            'icon'       => 'cover-image',
            'attributes' => array(
                'data' => array(
                    'overlay_style' => 'none',
                ),
            ),
        ),
    );

    return $args;
}, 10, 2 );
```

### Using Default Values in Variations

Since ACF blocks use custom field groups, you should set default field values in the block configuration:

```php
// In acf_add_local_field_group()
acf_add_local_field_group( array(
    'key'    => 'group_hero',
    'title'  => 'Hero Block Fields',
    'fields' => array(
        array(
            'key'           => 'field_hero_overlay_style',
            'label'         => 'Overlay Style',
            'name'          => 'overlay_style',
            'type'          => 'button_group',
            'choices'       => array(
                'none'  => 'No Overlay',
                'light' => 'Light Overlay',
                'dark'  => 'Dark Overlay',
            ),
            'default_value' => 'dark',  // Default variation
        ),
        array(
            'key'           => 'field_hero_overlay_opacity',
            'label'         => 'Overlay Opacity',
            'name'          => 'overlay_opacity',
            'type'          => 'range',
            'min'           => 0,
            'max'           => 100,
            'default_value' => 50,
        ),
    ),
    'location' => array(
        array(
            array(
                'param'    => 'block',
                'operator' => '==',
                'value'    => 'acf/hero',
            ),
        ),
    ),
) );
```

### Advanced: Variation-Specific Field Configuration

For more control, conditionally modify field groups based on the selected variation:

```php
add_filter( 'acf/prepare_field', function( $field ) {
    // Only apply to specific fields in hero block
    if ( $field['name'] !== 'overlay_opacity' ) {
        return $field;
    }

    // Get current block data
    $block = get_field( 'overlay_style' );

    // Hide opacity field if no overlay selected
    if ( $block === 'none' ) {
        $field['disabled'] = true;
        $field['instructions'] = 'Not applicable when no overlay is used';
    }

    return $field;
} );
```

### Practical Examples

#### 1. Spacer Block Variations

**Use case:** Different spacing presets for quick insertion

```php
$args['variations'] = array(
    array(
        'name'  => 'small-space',
        'title' => 'Small Space',
        'attributes' => array(
            'data' => array(
                'height_preset' => 'sm',
                'height_custom'  => '',
            ),
        ),
    ),
    array(
        'name'  => 'medium-space',
        'title' => 'Medium Space',
        'attributes' => array(
            'data' => array(
                'height_preset' => 'md',
                'height_custom'  => '',
            ),
        ),
    ),
    array(
        'name'  => 'large-space',
        'title' => 'Large Space',
        'attributes' => array(
            'data' => array(
                'height_preset' => 'lg',
                'height_custom'  => '',
            ),
        ),
    ),
);
```

#### 2. Quote Block Variations

**Use case:** Different quote styles pre-configured

```php
$args['variations'] = array(
    array(
        'name'  => 'simple-quote',
        'title' => 'Simple Quote',
        'attributes' => array(
            'data' => array(
                'style'     => 'simple',
                'alignment' => 'left',
            ),
        ),
    ),
    array(
        'name'  => 'highlighted-quote',
        'title' => 'Highlighted Quote',
        'attributes' => array(
            'data' => array(
                'style'     => 'highlighted',
                'alignment' => 'center',
            ),
        ),
    ),
    array(
        'name'  => 'card-quote',
        'title' => 'Card Quote',
        'attributes' => array(
            'data' => array(
                'style'     => 'card',
                'alignment' => 'center',
            ),
        ),
    ),
);
```

#### 3. Form Embed Variations

**Use case:** Different form layouts for Contact Form 7

```php
$args['variations'] = array(
    array(
        'name'  => 'wide-form',
        'title' => 'Wide Form',
        'attributes' => array(
            'data' => array(
                'layout'    => 'default',
                'max_width' => 'full',
            ),
        ),
    ),
    array(
        'name'  => 'narrow-form',
        'title' => 'Narrow Form',
        'attributes' => array(
            'data' => array(
                'layout'    => 'default',
                'max_width' => 'sm',
            ),
        ),
    ),
    array(
        'name'  => 'boxed-form',
        'title' => 'Boxed Form',
        'attributes' => array(
            'data' => array(
                'layout'    => 'boxed',
                'max_width' => 'md',
            ),
        ),
    ),
);
```

### Using Variations in Templates

No special template code needed—variations use the same template as the base block. The selected variation just pre-fills field values.

```twig
{# views/blocks/hero.twig #}
<section class="hero hero--overlay-{{ fields.overlay_style }}" style="opacity: {{ fields.overlay_opacity / 100 }}">
  {# Fields are already populated based on selected variation #}
  <h1>{{ fields.title }}</h1>
  <p>{{ fields.description }}</p>
</section>
```

### Best Practices for Variations

1. **Keep variations focused** — Each variation should represent a distinct use case
2. **Name variations clearly** — Names should describe what editors will see ("Dark Overlay" not "hero-var-1")
3. **Set sensible defaults** — Every variation should be immediately usable without editing
4. **Limit to 3-5 per block** — Too many variations confuse editors
5. **Document in BLOCKS-GUIDE.md** — List all variations for your custom blocks
6. **Use descriptions** — Help editors understand when to use each variation
7. **Keep variations DRY** — Use a loop to register similar variations:

```php
foreach ( array( 'sm', 'md', 'lg' ) as $size ) {
    $args['variations'][] = array(
        'name'  => "spacer-{$size}",
        'title' => ucfirst( $size ) . ' Space',
        'attributes' => array(
            'data' => array(
                'height_preset' => $size,
            ),
        ),
    );
}
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
