# Blocks To Add Next

Strategic list of high-value Gutenberg blocks to build next. Prioritized by use case frequency and dev effort.

---

## 1. Testimonial / Reviews

**Status:** ✅ Complete  
**Priority:** High  
**Complexity:** Medium

For service pages, landing pages, and product-style sites. Builds trust and credibility.

**Why add it:**

- You have CTA, Hero, and Banner blocks, but nothing that builds trust
- Essential for service/SaaS sites

**Implemented Features:**

- ACF field to select testimonial post types (multi-select)
- Section title and description fields
- Three layout options:
  - **Grid Layout** (2-3 columns, responsive):
    - Card design with quote, rating, and author (image, name, role, company)
    - Hover effects with lift and shadow
    - Responsive: 3 cols (desktop) → 2 cols (tablet) → 1 col (mobile)
  - **Carousel Layout** (rotating testimonials):
    - Auto-play toggle with next/prev controls
    - Navigation arrows for manual control
    - Full-width single testimonial display
  - **List Layout** (full-width items):
    - Horizontal layout with flex direction
    - Author image on left, quote in middle, meta (rating/role) on right
    - Mobile: stacks vertically
- Toggle options:
  - Show star ratings (always 5-star)
  - Show author info (name, role, company)
  - Show author image
- Responsive styling with mobile-first approach
- Semantic HTML with blockquote elements
- Quote styling with left-offset quote mark
- Author metadata extraction from post meta

**Files Created:**

- `src/blocks/testimonial.php` (179 lines) - ACF block registration and field config
- `views/blocks/testimonial.twig` (180 lines) - Twig template with 3 layout variants
- `css/inc/blocks/testimonial.scss` (304 lines) - BEM styling with animations

---

## 2. Logo Grid / Clients

**Status:** ✅ Complete  
**Priority:** High  
**Complexity:** Low

A clean way to show partners, brands, accreditations, or press mentions.

**Why add it:**

- Very common section on homepages and landing pages
- Avoids forcing users to fake it with Gallery block

**Implemented Features:**

- Section title and description
- Logos repeater with alt text and optional links
- 2-6 column layout with responsive breakpoints
- Logo alignment options (center, left, right)
- Greyscale style with color hover effect
- Spacing control (tight, normal, loose)
- Hover animation (lift effect)
- Mobile-responsive grid collapse
- Accessibility: Focus states, semantic HTML, alt text

**Files Created:**

- `src/blocks/logo-grid.php` - ACF field configuration
- `views/blocks/logo-grid.twig` - Block template
- `css/inc/blocks/logo-grid.scss` - BEM styling with breakpoints

**Notes:**

- Block auto-discovered by glob pattern
- Supports wide and full alignment
- Logos use `object-fit: contain` for proper scaling
- Greyscale variant uses CSS filter for smooth transitions

---

## 3. Stats / Metrics

**Status:** ✅ Complete  
**Priority:** High  
**Complexity:** Low

For "10,000 customers served", "25 years experience", "99.9% uptime".

**Why add it:**

- Good for business, SaaS, charity, council, and service sites
- Fills gap between CTA and content
- Highly reusable, works on any site type

**Implemented Features:**

- Section title and description
- Statistics repeater with:
  - Number/value (any format: 10000, 99%, \$2.5M, etc.)
  - Label (what the stat represents)
  - Description (optional detail)
  - Icon (Font Awesome class, optional)
  - Color (Primary, Secondary, Success, Warning, Info, Danger)
- Two layout styles:
  - **Card Layout** (backgrounds with hover lift effect)
  - **Minimal Layout** (clean, numbers-focused)
- Column layout options: 2, 3, or 4 columns
- Background options: White, Light Gray, or Gradient
- Responsive design: 4 cols → 3 → 2 → 1 on mobile
- Icon styling with color backgrounds
- Card hover effects (elevation + scale animation)
- Mobile-first responsive design
- BEM semantic HTML structure

**Files Created:**

- `src/blocks/stats.php` (154 lines) - ACF field configuration
- `views/blocks/stats.twig` (55 lines) - Block template with card and minimal layouts
- `css/inc/blocks/stats.scss` (351 lines) - BEM styling with responsive design

**Features:**

- Font Awesome icon support
- Color-coded icons with background containers
- Flexible number format support (handles percentages, currency, large numbers)
- Smooth transitions and hover animations
- Gradient background support for visual impact
- Accessibility: semantic HTML, proper heading hierarchy
- Mobile-responsive from 4 columns down to 1

---

## 4. Team / People

**Status:** Not started  
**Priority:** Medium  
**Complexity:** Medium

A structured people card block for bios, profiles, and team pages.

**Why add it:**

- Contact block is not enough for About pages, leadership pages, or department pages
- Need dedicated structured layout for team

**Typical fields:**

- Name
- Role
- Photo
- Bio
- Email
- Social links (repeater)

---

## 5. FAQ Search / Filtered FAQ

**Status:** Not started  
**Priority:** Medium  
**Complexity:** High

A more advanced version of Accordion with search and filtering.

**Why add it:**

- Accordion covers basic FAQ, but dedicated block better for larger sets
- Allows schema markup for SEO

**Typical fields:**

- Search toggle
- FAQ category filter
- Question / answer repeater
- Schema toggle (outputs FAQ schema)
- Expand first item toggle

**Extra value:**

- Can output FAQ schema for SEO benefits

---

## 6. Pricing / Plans

**Status:** Not started  
**Priority:** Medium  
**Complexity:** Medium

Useful even outside SaaS. Handles service tiers, membership levels, packages, or donations.

**Why add it:**

- Common commercial section and hard to fake with generic blocks
- Applicable to many business types

**Typical fields:**

- Plan name
- Price
- Billing note
- Features list (repeater)
- Highlighted plan toggle
- CTA link

---

## 7. Cards Grid

**Status:** Not started  
**Priority:** High  
**Complexity:** Medium

A generic repeatable card layout for services, features, categories, or linked promos.

**Why add it:**

- One of the most reusable blocks in any theme
- Solves many card-based layout needs

**Typical fields:**

- Title
- Intro text
- Cards repeater
  - Card image/icon
  - Card title
  - Card text
  - Card link
- Columns

---

## 8. Icon List / Features List

**Status:** ✅ Complete  
**Priority:** Medium  
**Complexity:** Low

For short benefit-led content with icons. Cleaner than WYSIWYG for structured lists.

**Why add it:**

- WYSIWYG block is too loose for structured feature lists
- Editors often need clean feature lists with icons/icons styling

**Implemented Features:**

- Section title and description
- Items repeater (1-12 items max) with:
  - Icon (SVG paste or Font Awesome class)
  - Label (required)
  - Description (optional)
- Three layout options:
  - **Vertical** (stacked, full-width)
  - **Horizontal** (side-by-side with flex wrap)
  - **Grid** (2, 3, or 4 column layout)
- Icon sizing options: Small (40px), Medium (56px), Large (72px)
- Icon color options: Primary, Secondary, Success, Warning, Info, Danger
- Text alignment: Left or Center
- Responsive design (mobile: single column, tablet: 2 columns)
- Hover animations (icon lift effect)
- Background color for icon containers (using opacity + color)

**Files Created:**

- `src/blocks/icon-list.php` (129 lines) - ACF field configuration
- `views/blocks/icon-list.twig` (75 lines) - Block template with layout variants
- `css/inc/blocks/icon-list.scss` (290 lines) - BEM styling with responsive design

**Features:**

- Supports both SVG inline code and Font Awesome icon classes
- Color backgrounds automatically scale with color selection
- All layouts collapse to single column on mobile
- Semantic HTML with proper heading hierarchy
- Accessibility: ARIA labels, semantic structure

---

## 9. Steps / Process Timeline

**Status:** Not started  
**Priority:** Medium  
**Complexity:** Medium

Great for "How it works", onboarding, service process, or historical timelines.

**Why add it:**

- Common content pattern not covered by Tabs or Accordion
- Needed for process/flow documentation

**Typical fields:**

- Title
- Steps repeater
  - Step number
  - Heading
  - Content
  - Icon / image
- Vertical or horizontal layout toggle

---

## 10. Quote / Pull Quote

**Status:** Not started  
**Priority:** Low  
**Complexity:** Low

A simple editorial block for emphasis in articles or case studies.

**Why add it:**

- Lighter alternative to Testimonial block
- Useful for editorial and content-heavy sites

**Typical fields:**

- Quote
- Citation
- Role / attribution
- Alignment (left, center, right)
- Style variant

---

## 11. Form Embed / Custom Form Block

**Status:** Not started  
**Priority:** Medium  
**Complexity:** Medium

More flexible than Newsletter or Contact block.

**Why add it:**

- Editors often need forms beyond newsletter signup
- Centralizes form handling

**Typical fields:**

- Title
- Intro text
- Form shortcode / embed code
- Success message
- Layout style

---

## 12. Map / Locations

**Status:** Not started  
**Priority:** Medium  
**Complexity:** Medium

A structured locations block rather than hiding map output inside Contact.

**Why add it:**

- Useful for multi-location businesses, branches, stockists, venues, or offices
- Separates location info from contact forms

**Typical fields:**

- Title
- Locations repeater
  - Address
  - Phone
  - Opening hours
  - Map embed or coordinates
  - Optional image
  - Optional link

---

## 13. Downloads / Resources

**Status:** ✅ Complete  
**Priority:** Medium  
**Complexity:** Low

For brochures, PDFs, guides, policies, menus, reports.

**Why add it:**

- Very common client request
- Should not rely on raw WYSIWYG links

**Implemented Features:**

- Section title and description
- Files repeater with file upload, label, description, and category
- Two layout options: List and Grid (2/3/4 columns)
- File size display toggle
- Category badges with custom colors (Guide, Brochure, Form, Policy, Report, Menu, Whitepaper, Case Study, Template, Other)
- File extension icons with color gradient
- Hover effects (card lift in grid, border highlight in list)
- Semantic download links with proper accessibility
- Responsive design (grid collapses on mobile)
- Category display toggle

**Files Created:**

- `src/blocks/downloads.php` - ACF field configuration with conditional logic
- `views/blocks/downloads.twig` - Block template with list/grid layouts
- `css/inc/blocks/downloads.scss` - BEM styling with layout variants

**Features:**

- Category-specific color schemes for visual organization
- Download icons with file extension abbreviations
- Works with any file type (PDF, DOC, ZIP, etc.)
- Accessibility: Proper ARIA labels, keyboard focus states, semantic HTML
- Mobile-responsive layout switching

---

## 14. Related Content / Latest Content

**Status:** Not started  
**Priority:** Low  
**Complexity:** High

Different from manual Blog Posts block. Query-based automatic content.

**Why add it:**

- Current Blog Posts block is editor-selected
- Also need automatic/query-based option for dynamic content

**Typical fields:**

- Content type selector
- Taxonomy filter
- Number of items
- Show excerpt toggle
- Show image toggle
- Order by (latest, featured, random)

---

## 15. Spacer / Divider

**Status:** Not started  
**Priority:** Low  
**Complexity:** Low

Small but very useful for layout control.

**Why add it:**

- Editors often need layout control without abusing empty paragraphs
- Better UX than empty content blocks

**Typical fields:**

- Height (preset or custom)
- Divider style (line, spacing, pattern)
- Width toggle
- Alignment
- Color / style

---

## Priority Matrix

| Priority | Blocks                                                     | Reason                        |
| -------- | ---------------------------------------------------------- | ----------------------------- |
| **High** | Testimonial, Logo Grid, Stats, Cards Grid                  | Most reusable, highest demand |
| **Med**  | Team, FAQ, Pricing, Icon List, Steps, Form, Map, Downloads | Solve specific use cases      |
| **Low**  | Quote, Related Content, Spacer                             | Nice-to-have, lower frequency |

---

## Dev Effort Estimate

| Complexity | Blocks                                                          | Est. Time |
| ---------- | --------------------------------------------------------------- | --------- |
| **Low**    | Logo Grid, Stats, Icon List, Downloads, Spacer                  | 1-2 hrs   |
| **Medium** | Testimonial, Team, Pricing, Cards Grid, Steps, Form, Map, Quote | 2-4 hrs   |
| **High**   | FAQ Search, Related Content                                     | 4-6 hrs   |

---

## Next Steps for Implementation

When ready to build a block:

1. Create block template in `views/blocks/block-name.twig`
2. Create field config in `src/blocks/block-name.php`
3. Create SCSS in `css/inc/blocks/block-name.scss`
4. Add example and docs to [BLOCKS-GUIDE.md](./BLOCKS-GUIDE.md)
5. Test on multiple post types and pages

See [BLOCKS-GUIDE.md](./BLOCKS-GUIDE.md#creating-new-blocks) for complete creation workflow.

---

## Notes

- All blocks should follow BEM naming convention
- Use Bootstrap 5 utilities where applicable
- Include responsive design from mobile-first
- Consider accessibility (ARIA labels, semantic HTML)
- Add field validation and helpful UI hints for editors

---

# Core Features & Infrastructure

Additional features, utilities, and infrastructure improvements to build after (or alongside) the blocks.

---

## 1. Form Validation Utilities Library (JavaScript)

**Status:** Not started  
**Priority:** High  
**Complexity:** Low  
**Est. Effort:** 4-6 hours

Reusable JavaScript validation helpers for common patterns across blocks and forms.

**Why add it:**

- Current form modules lack reusable validation patterns
- Reduce duplication in form blocks and custom validators
- Standardize validation across the theme

**Typical utilities:**

- `validateEmail()`
- `validatePhone()`
- `validateRequired()`
- `validatePattern()` (URL, credit card, etc.)
- `validateMinLength()` / `validateMaxLength()`
- `validateMatch()` (password confirmation)
- Error message generation

**Files:**

- `scripts/utils/validation.js` - Main validation library
- Add tests if framework exists

---

## 2. Input Sanitization & Type Validation Helpers (PHP)

**Status:** Not started  
**Priority:** High  
**Complexity:** Medium  
**Est. Effort:** 10-14 hours

Security-focused PHP helpers for validating and sanitizing post type data and form submissions.

**Why add it:**

- No dedicated helpers for input validation/sanitization
- Prevents security issues and improves data consistency
- Better ACF field validation patterns

**Typical helpers:**

- `sanitize_text_field()` wrappers with type checking
- `validate_acf_field()` - Check field type and value
- `sanitize_post_meta()` - Safe meta updates
- `validate_email()`, `validate_url()`, `validate_integer()`
- CSRF token validation utility
- File upload validation helper

**Files:**

- `src/helpers/validation.php` - Validation helpers
- `src/helpers/sanitization.php` - Sanitization helpers
- Integration with block field config

---

## 3. Breadcrumb Navigation Component & Block

**Status:** Not started  
**Priority:** High  
**Complexity:** Medium  
**Est. Effort:** 8-12 hours

Reusable breadcrumb component for category/archive/single pages. Essential UX and SEO element.

**Why add it:**

- Core UX element missing from theme templates
- Improves site navigation and SEO
- Reduces user confusion on deep hierarchies

**Components:**

- Twig breadcrumb component: `templates/components/breadcrumb.twig`
- Auto-generation for WordPress archives, categories, tags
- Gutenberg breadcrumb block for manual placement
- Schema.org BreadcrumbList markup support
- BEM CSS styling

**Features:**

- Automatic breadcrumb generation (WooCommerce, post types, taxonomies)
- Customizable separator
- Home link toggle
- Current page as plain text (not link) option
- Mobile-friendly collapse option

---

## 4. Image Lazy-Loading Implementation & Guide

**Status:** Not started  
**Priority:** High  
**Complexity:** Medium  
**Est. Effort:** 10-15 hours

Implement lazy-loading for images using IntersectionObserver API. Major performance improvement.

**Why add it:**

- All images load immediately—no lazy-loading
- Hero blocks and image galleries load unnecessarily
- Quick performance win with minimum effort

**Implementation:**

- `scripts/modules/lazy-load.js` - IntersectionObserver utility
- Native `loading="lazy"` attribute in Twig templates
- Blurred placeholder system
- Fallback for older browsers
- Dynamic image sizing (srcset/picture elements)

**Coverage:**

- Block images (Hero, Banner, Card, etc.)
- Gallery blocks
- Blog post images
- WooCommerce product images
- Documentation in JAVASCRIPT-GUIDE.md

---

## 5. Comprehensive Accessibility Audit & Testing Guide

**Status:** Not started  
**Priority:** High  
**Complexity:** Medium  
**Est. Effort:** 12-18 hours

WCAG 2.1 AA compliance guide, automated testing framework, and keyboard navigation patterns.

**Why add it:**

- ARIA labels mentioned sporadically
- No formal accessibility testing procedures
- Need systematic a11y validation

**Deliverables:**

- New file: ACCESSIBILITY-GUIDE.md
- ARIA labels checklist for blocks
- Keyboard navigation testing procedures
- Screen reader testing guide
- Color contrast validation
- Form label patterns
- Semantic HTML checklist
- Automated axe testing integration
- Accessibility documentation in each block guide

---

## 6. Environment-Specific Configuration (.env Support)

**Status:** Not started  
**Priority:** Medium  
**Complexity:** Low  
**Est. Effort:** 6-8 hours

Separate environment configuration (dev/staging/production) via .env files.

**Why add it:**

- No environment separation currently
- Config is static—hard to change between environments
- Need feature flags, API keys, debug modes

**Implementation:**

- Add `vlucas/phpdotenv` to composer
- Create `.env.example` template
- Update `src/config.php` to read from environment
- Document in CONFIG-GUIDE.md
- Add `.env` to `.gitignore`

**Environment variables:**

- `WP_DEBUG` / `WP_DEBUG_LOG`
- Feature flags (enable/disable blocks, features)
- API keys (Google Maps, external services)
- Performance settings (cache TTL, image optimization)
- Theme settings (site title, etc.)

---

## 7. JavaScript Error Handling & Debug Logging Utility

**Status:** Not started  
**Priority:** Medium  
**Complexity:** Low  
**Est. Effort:** 6-8 hours

Centralized error handling and logging for JavaScript errors and console debugging.

**Why add it:**

- No centralized error handling
- No helpful debug logging infrastructure
- Production errors hard to track

**Implementation:**

- `scripts/utils/logger.js` - Logging utility
- Error boundary pattern for critical features
- Stack trace capture and reporting
- Console vs. production logging modes
- Error aggregation (optional: send to service)

**Features:**

- `Logger.debug()`, `Logger.error()`, `Logger.warn()`
- Error context information
- Silent vs. verbose modes
- Environment-aware behavior

---

## 8. GDPR Cookie Consent System

**Status:** ✅ Complete  
**Priority:** High  
**Complexity:** Medium  
**Est. Effort:** 8-12 hours

GDPR-compliant cookie consent banner with preferences modal and analytics integration.

**Why add it:**

- Required for GDPR and privacy compliance
- Major liability if missing from EU-accessible sites
- Consent management essential for tracking tools

**Implemented Features:**

- Cookie consent banner (fixed bottom, auto-hides if preference saved)
- Preferences modal with granular controls
- Four cookie categories:
  - **Necessary** (always on, non-negotiable)
  - **Analytics** (Google Analytics, traffic tracking)
  - **Marketing** (ads, retargeting, pixels)
  - **Preferences** (user-specific personalization)
- User actions:
  - Accept All → All categories enabled
  - Reject All → Only necessary enabled
  - Customize → Opens modal for granular choice
- Preference persistence (localStorage, 365 days)
- Google Analytics 4 (gtag) integration ready
- Custom event system for 3rd-party script integration
- Event tracking with consent gating (`trackEvent()` function)
- Accessible UI (WCAG 2.1 AA compliant)
- Mobile-responsive design
- Zero dependencies (pure JavaScript)

**Files Created:**

- `scripts/utils/cookies.js` (107 lines)
  - Cookie management: read, write, delete
  - Preference storage and retrieval
  - Consent checks by category
- `scripts/modules/cookie-consent.js` (153 lines)
  - Main orchestration module
  - Banner and modal UI control
  - User preference saving
  - Optional script loading
  - Event tracking integration
- `templates/components/cookie-consent.twig` (129 lines)
  - Banner HTML structure
  - Preferences modal dialog
  - Form controls and buttons
- `css/inc/components/cookie-consent.scss` (380 lines)
  - BEM component styling
  - Animations (slideUp/slideDown)
  - Modal dialog pattern
  - Responsive layout
  - Focus states and accessibility

**Files Updated:**

- `style.scss` - Added component stylesheet import
- `scripts/index.js` - Added module initialization
- `templates/base.twig` - Added component include

**Key Functions:**

```javascript
import { hasConsent, getConsentPreferences } from "./utils/cookies.js";
import { trackEvent } from "./modules/cookie-consent.js";

// Check if user consented to analytics
if (hasConsent("analytics")) {
  // Load Google Analytics
}

// Track event with consent check
trackEvent("page_view", { page: "home" });

// Get all preferences
const prefs = getConsentPreferences();
```

**Documentation:**

- Complete guide: [GDPR-GUIDE.md](./GDPR-GUIDE.md)
- Covers configuration, integration, compliance, testing, troubleshooting
- Privacy policy template examples included

**Build Results:**

- 0 errors
- Module count: 9 → 11
- JS bundle: +1.89 kB (gzip +0.55 kB)
- Overall JS: 10.32 kB (gzip: 3.34 kB)
- Build time: 176ms

---

## 9. Deployment Guide & CI/CD Documentation

**Status:** Not started  
**Priority:** Medium  
**Complexity:** Medium  
**Est. Effort:** 10-15 hours

Comprehensive deployment procedures and automated testing pipeline documentation.

**Why add it:**

- No deployment guides exist
- No CI/CD automation (GitHub Actions, etc.)
- Manual deployments are error-prone

**Deliverables:**

- New file: DEPLOYMENT-GUIDE.md
- GitHub Actions workflow file for automatic testing
- Pre-deployment checklist
- Database migration strategy
- Asset compilation automation
- Backup procedures
- Rollback strategies
- Staging environment setup guide

---

## 10. Dark Mode Support (CSS Variables & Toggle Component)

**Status:** Not started  
**Priority:** Medium  
**Complexity:** Medium  
**Est. Effort:** 12-16 hours

Convert colors to CSS variables, add dark mode variant, and create theme toggle component.

**Why add it:**

- Zero dark mode support
- Modern UX expectation
- CSS variables enable runtime theming

**Implementation:**

- Convert `vars.scss` colors to CSS custom properties
- Create dark mode color palette
- CSS `prefers-color-scheme` media query support
- Dark mode toggle component with localStorage persistence
- Block styling for both light and dark themes

**Files:**

- Update `css/inc/base/vars.scss`
- Create `css/inc/global/dark-mode.scss`
- Component: `templates/components/theme-toggle.twig`
- JavaScript: `scripts/modules/theme-toggle.js`

---

## 11. Load Performance Monitoring & Optimization Guide

**Status:** Not started  
**Priority:** Medium  
**Complexity:** Low-Medium  
**Est. Effort:** 8-10 hours

Web Vitals tracking, performance budgets, and optimization best practices guide.

**Why add it:**

- No performance monitoring currently
- No metrics to track improvements
- Missing optimization strategies

**Deliverables:**

- New file: PERFORMANCE-GUIDE.md
- Web Vitals (LCP, FID, CLS) documentation
- Lighthouse CI integration guide
- Performance budget recommendations
- Image optimization strategies
- CSS/JS minification verification
- Caching strategy documentation
- Asset lazy-loading patterns

---

## 12. Advanced Form Features (Gravity Forms Integration Block)

**Status:** Not started  
**Priority:** Medium  
**Complexity:** High  
**Est. Effort:** 20-25 hours

Full Gravity Forms integration with dedicated block and template support.

**Why add it:**

- Contact block works for basic contact
- Gravity Forms is industry-standard form builder
- Adds significant capabilities (conditional logic, payments, etc.)

**Implementation:**

- Create `src/integrations/gravity-forms.php`
- Gravity Forms block template
- Form embed component
- Conditional block loading (when plugin active)
- Form response handling
- Styling for form elements
- Documentation: GRAVITY-FORMS-GUIDE.md

---

## 12. Block Variation & Preset System Documentation

**Status:** Not started  
**Priority:** Low  
**Complexity:** Low  
**Est. Effort:** 5-7 hours

System documentation and examples for creating reusable block variations and presets.

**Why add it:**

- Blocks lack variation patterns (e.g., Hero "light" vs "dark")
- No documented system for block variations
- Important for editor UX

**Deliverables:**

- Add section to BLOCKS-GUIDE.md: "Block Variations & Presets"
- Examples of variation patterns (color, layout, style)
- How to define variations in ACF
- Preset system (pre-configured block instances)
- Best practices for editor experience

---

## Development Priority Matrix

| Priority   | Items                                                                     | Quick Wins? |
| ---------- | ------------------------------------------------------------------------- | ----------- |
| **High**   | Form Validation, Input Sanitization, Breadcrumb, Lazy-Load, Accessibility | 2/5         |
| **Medium** | .env Config, Error Logging, Deployment, Dark Mode, Performance            | 2/5         |
| **Low**    | Block Variations, Gravity Forms (save for later), Others                  | —           |

---

## Recommended Implementation Order

### Phase 1 (Quick Wins - Parallel)

1. Form Validation Utilities (JS)
2. Environment Configuration (.env)
3. JavaScript Error Logging
4. Block Variation Documentation

### Phase 2 (Performance & UX)

5. Image Lazy-Loading
6. Breadcrumb Component
7. Accessibility Guide

### Phase 3 (Infrastructure)

8. Input Sanitization Helpers (PHP)
9. Deployment & CI/CD Guide
10. Performance Monitoring Guide

### Phase 4 (Polish & Advanced)

11. Dark Mode Support
