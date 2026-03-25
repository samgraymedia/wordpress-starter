# Accessibility Audit Guide

## Overview

This theme is built with accessibility (a11y) as a core principle. This guide provides comprehensive procedures for maintaining, testing, and improving accessibility across the theme.

**Accessibility Standards:** WCAG 2.1 Level AA (Web Content Accessibility Guidelines)

---

## Quick Reference

- **ARIA Labels**: Required on custom components
- **Keyboard Navigation**: Full keyboard access (Tab, Enter, Space, Esc, Arrow keys)
- **Color Contrast**: Minimum 4.5:1 for text, 3:1 for graphics
- **Focus Indicators**: Always visible (outline, underline, or background change)
- **Semantic HTML**: Use proper heading hierarchy, nav elements, buttons vs links
- **Testing**: axe DevTools, Screen readers, Keyboard only

---

## 1. ARIA Implementation Checklist

### 1.1 ARIA Landmarks

Landmarks help screen reader users navigate page structure. Every page should have:

- `<header role="banner">` — Site-wide header
- `<nav role="navigation">` — Navigation regions
- `<main role="main">` — Main content area (use native `<main>` tag)
- `<aside role="complementary">` — Sidebars, related content
- `<footer role="contentinfo">` — Site footer

**Template Example:**

```twig
<header role="banner">
  {# Header content #}
</header>

<main role="main">
  {# Main content #}
</main>

<aside role="complementary">
  {# Sidebar #}
</aside>

<footer role="contentinfo">
  {# Footer content #}
</footer>
```

### 1.2 ARIA Labels for Custom Components

#### Navigation

```twig
{# Breadcrumb Navigation #}
<nav aria-label="Breadcrumb">
  <ol class="breadcrumb__list">
    {# items #}
  </ol>
</nav>

{# Skip to Main Content Link #}
<a href="#main" class="skip-link">Skip to main content</a>

{# Mobile Menu Button #}
<button aria-label="Toggle navigation menu" aria-expanded="false" id="menu-toggle">
  Menu
</button>
```

#### Forms

```twig
{# Explicit label association #}
<label for="email">Email Address</label>
<input type="email" id="email" name="email" required>

{# Hidden label (use aria-label as fallback) #}
<input type="search" placeholder="Search" aria-label="Search the site">

{# Form validation #}
<input type="email" aria-invalid="true" aria-describedby="email-error">
<span id="email-error" class="error">Please enter a valid email</span>

{# Required fields #}
<label for="name">Name <span aria-label="required">*</span></label>
<input type="text" id="name" aria-required="true">
```

#### Modals & Dialogs

```twig
<div role="dialog" aria-labelledby="modal-title" aria-modal="true">
  <h2 id="modal-title">Confirm Action</h2>
  <p>Are you sure you want to proceed?</p>
  <button>Cancel</button>
  <button>Confirm</button>
</div>
```

#### Buttons & Interactive Elements

```twig
{# Button with hidden text description #}
<button aria-label="Close menu">
  <span aria-hidden="true">&times;</span>
</button>

{# Toggle buttons #}
<button aria-pressed="false" aria-label="Favorite this post">
  ♥ Favorite
</button>

{# Icon-only buttons must have labels #}
<button aria-label="Search">
  <svg aria-hidden="true"><!-- search icon --></svg>
</button>

{# Links that behave as buttons #}
<a href="#" role="button" aria-pressed="false">Toggle</a>
```

#### Dropdowns & Menus

```twig
<button aria-expanded="false" aria-haspopup="true" id="dropdown-toggle">
  Options <span aria-hidden="true">▼</span>
</button>
<ul role="menu" aria-labelledby="dropdown-toggle" id="dropdown-menu">
  <li role="menuitem"><a href="#">Option 1</a></li>
  <li role="menuitem"><a href="#">Option 2</a></li>
  <li role="menuitem"><a href="#">Option 3</a></li>
</ul>
```

#### Dynamic Content

```twig
{# Live regions that announce updates #}
<div aria-live="polite" aria-atomic="true">
  {# Content that updates without page reload #}
</div>

{# Status/alert messages #}
<div role="alert" aria-live="assertive">
  Your form has been submitted successfully!
</div>

{# Progress indicators #}
<progress aria-label="Page load progress" value="70" max="100"></progress>
```

#### Images & Media

```twig
{# Meaningful images need alt text #}
<img src="hero.jpg" alt="Team members collaborating on a project">

{# Decorative images should have empty alt #}
<img src="divider.svg" alt="">

{# Complex images need description #}
<figure>
  <img src="chart.jpg" alt="Q1 sales by region">
  <figcaption>Sales increased 15% in the North region</figcaption>
</figure>

{# Background images should have text alternative #}
<div class="hero" style="background-image: url('hero.jpg')">
  <h1>Welcome to our site</h1>
</div>
```

---

## 2. Keyboard Navigation Guide

### 2.1 Essential Keyboard Controls

| Key            | Action                                                   |
| -------------- | -------------------------------------------------------- |
| **Tab**        | Move focus forward through focusable elements            |
| **Shift+Tab**  | Move focus backward through focusable elements           |
| **Enter**      | Activate buttons and links, submit forms                 |
| **Space**      | Activate buttons, toggle checkboxes/radio buttons        |
| **Escape**     | Close modals, dropdowns, menus                           |
| **Arrow Keys** | Navigate within custom components (tabs, menus, sliders) |
| **Home/End**   | Jump to first/last item in lists or menus                |

### 2.2 Focus Management

**All interactive elements must be keyboard accessible:**

```twig
{# Buttons and links are focusable by default #}
<button>Click me</button>
<a href="#">Link</a>

{# Custom interactive elements need tabindex #}
<div role="button" tabindex="0">Custom button</div>

{# Hidden elements shouldn't receive focus #}
<div tabindex="-1">Skip this in tab order</div>

{# Set custom tab order (avoid if possible) #}
<form>
  <input tabindex="1">
  <input tabindex="2">
  <input tabindex="3">
</form>
```

### 2.3 Focus Styles

**Focus indicators must be visible and meet contrast requirements:**

```scss
// Required focus style for all interactive elements
button:focus,
a:focus,
input:focus {
  outline: 2px solid var(--color-focus);
  outline-offset: 2px;
}

// Enhance for custom components
[role="button"]:focus,
[role="menuitem"]:focus {
  background-color: var(--color-focus-bg);
  color: var(--color-focus-text);
}

// Never remove focus styles without replacement
button:focus {
  outline: 2px solid #0066cc; // Always provide visible indicator
}

// Avoid reducing visibility
button:focus {
  outline: 1px dotted gray; // Not sufficient contrast
}
```

### 2.4 Tab Order Testing

**Manual Testing Procedure:**

1. Open page in browser
2. Press **Tab** to move forward through elements
3. Verify:
   - Focus moves in logical order (top-left to bottom-right)
   - Focus indicator is always visible
   - No focus traps (can escape with Tab or Escape)
   - Skip links work (skip to main content)

**Common Issues:**

- Elements receive focus in random order (fix: remove custom tabindex)
- Focus indicator invisible (fix: add outline or background)
- Trapped in dropdown (fix: Escape key closes it)
- Skip link not visible when focused (fix: add focus style)

---

## 3. Color & Contrast

### 3.1 Contrast Requirements

| Element                | Requirement | Example                        |
| ---------------------- | ----------- | ------------------------------ |
| **Body Text**          | 4.5:1       | Black (#000) on white (#fff)   |
| **Large Text** (18pt+) | 3:1         | Gray (#666) on white (#fff)    |
| **UI Components**      | 3:1         | Button border, focus indicator |
| **Graphical Objects**  | 3:1         | Chart bars, icons              |

### 3.2 Testing Contrast

**Browser Extensions:**

- WebAIM Contrast Checker
- WAVE (Web Accessibility Evaluation Tool)
- axe DevTools

**Online Tools:**

- [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/)
- [Contrast Ratio](https://contrast-ratio.com/)

**Example SCSS Variables (Dark Mode Safe):**

```scss
// Light mode
:root {
  --color-text: #000000; // 21:1 on white
  --color-text-light: #333333; // 12:1 on white
  --color-text-muted: #666666; // 5.5:1 on white ✓
  --color-link: #0066cc; // 5.8:1 on white ✓
  --color-focus: #0066cc; // Focus indicator
}

// Dark mode
@media (prefers-color-scheme: dark) {
  :root {
    --color-text: #ffffff; // 21:1 on black
    --color-text-muted: #cccccc; // 9.5:1 on black ✓
    --color-link: #66b2ff; // 5.4:1 on black ✓
  }
}
```

---

## 4. Screen Reader Testing Guide

### 4.1 Setting Up Screen Readers

**macOS:**

- VoiceOver (built-in) — Cmd+F5 to toggle
- NVDA (free, Windows)
- JAWS (paid, industry standard)

**Windows:**

- NVDA (free) — [nvaccess.org](https://www.nvaccess.org/)
- JAWS (paid) — [freedomscientific.com](https://www.freedomscientific.com/)

**iOS/Android:**

- iOS: VoiceOver (Settings > Accessibility > VoiceOver)
- Android: TalkBack (Settings > Accessibility > TalkBack)

### 4.2 VoiceOver Testing (macOS)

**Enable VoiceOver:**

```
System Preferences > Accessibility > VoiceOver > Enable
```

**Basic Commands:**

| Command           | Action                       |
| ----------------- | ---------------------------- |
| **VO+Right**      | Next item                    |
| **VO+Left**       | Previous item                |
| **VO+Up**         | Increase verbosity           |
| **VO+Down**       | Decrease verbosity           |
| **VO+Space**      | Activate button/link         |
| **VO+U**          | Open rotor (navigation menu) |
| **Ctrl+VO+Right** | Next heading                 |
| **Ctrl+VO+L**     | Next landmark                |

**Testing Checklist:**

- [ ] Page title announced
- [ ] Headings read with proper hierarchy
- [ ] Links announced with purpose (not "click here")
- [ ] Form labels associated with inputs
- [ ] Images have meaningful alt text
- [ ] Buttons announced as buttons
- [ ] Current page/active item indicated
- [ ] No repetitive or redundant announcements

### 4.3 NVDA Testing (Windows)

**Download:** https://www.nvaccess.org/download/

**Start NVDA:** Launch application, press Ctrl+Alt+N

**Basic Commands:**

| Command         | Action             |
| --------------- | ------------------ |
| **Down Arrow**  | Read next item     |
| **Up Arrow**    | Read previous item |
| **H**           | Next heading       |
| **N**           | Next landmark      |
| **L**           | Next list          |
| **B**           | Next button        |
| **F**           | Next form field    |
| **Enter/Space** | Activate element   |
| **Insert+F7**   | Open elements list |

### 4.4 Screen Reader Content Strategy

**What Screen Readers Announce:**

1. Page title
2. Headings and structure
3. Links (link text, title attribute)
4. Buttons (button text, aria-label)
5. Form labels
6. Image alt text
7. Lists and list structure
8. Tables (headers, relationships)
9. Dynamic content updates (aria-live)

**Example: Meaningful Link Text**

```twig
{# ❌ Bad: Link purpose unclear #}
<a href="/about">Click here</a>

{# ✓ Good: Context-specific #}
<a href="/about">Learn more about our company</a>

{# ✓ Good: Icon with aria-label #}
<a href="/about" aria-label="Learn more about our company">
  <svg aria-hidden="true"><!-- about icon --></svg>
</a>
```

**Example: Image Alt Text**

```twig
{# ❌ Bad: Generic alt text #}
<img src="photo.jpg" alt="photo">

{# ✓ Good: Descriptive and specific #}
<img src="team-meeting.jpg" alt="Team members discussing project in conference room">

{# ✓ Good: Decorative image (empty alt) #}
<img src="divider.svg" alt="">

{# ✓ Good: Complex chart with description #}
<figure>
  <img src="chart.jpg" alt="Q1 revenue by region">
  <figcaption>North region led sales at $2.3M (45% of total revenue)</figcaption>
</figure>
```

---

## 5. Automated Testing with axe

### 5.1 Installing axe DevTools

**Browser Extensions:**

- [axe DevTools Chrome](https://chrome.google.com/webstore/detail/axe-devtools-web-accessib/)
- [axe DevTools Firefox](https://addons.mozilla.org/firefox/addon/axe-devtools/)

**Steps:**

1. Install extension from store
2. Open DevTools (F12)
3. Find "axe DevTools" tab
4. Click "Scan ALL of my page"

### 5.2 Reading axe Reports

**Violation Categories:**

| Type              | Severity | Action                              |
| ----------------- | -------- | ----------------------------------- |
| **Critical**      | Red      | Fix immediately (blocks access)     |
| **Serious**       | Orange   | Fix soon (prevents independent use) |
| **Moderate**      | Yellow   | Fix (causes difficulty)             |
| **Minor**         | Blue     | Fix if possible (edge cases)        |
| **Best Practice** | Green    | Improvement (not failure)           |

**Example Violations:**

- Missing form label (Critical)
- Color contrast too low (Serious)
- Image missing alt text (Critical for decorative)
- Heading hierarchy skipped (Moderate)
- Redundant aria-label (Minor)

### 5.3 Common Issues Found by axe

| Issue                  | Cause                                | Fix                             |
| ---------------------- | ------------------------------------ | ------------------------------- |
| Missing `<label>`      | Form input not labeled               | Use `<label for="input-id">`    |
| Low contrast           | Text color too similar to background | Increase color difference       |
| Missing alt text       | Image has no description             | Add descriptive alt attribute   |
| Skipped heading levels | h1 → h3 (skips h2)                   | Use sequential hierarchy        |
| Duplicate aria-label   | Multiple labels on one element       | Use only one labeling method    |
| Empty button           | Button has no text or icon           | Add text or aria-label          |
| Unrelated heading      | Heading not connected to content     | Use heading for actual sections |

### 5.4 CI/CD Integration

**Package Installation:**

```bash
npm install --save-dev axe-core @axe-core/cli
```

**GitHub Actions Workflow (.github/workflows/a11y-audit.yml):**

```yaml
name: Accessibility Audit

on:
  pull_request:
    paths:
      - "templates/**"
      - "css/**"
      - "scripts/**"
  push:
    branches:
      - main

jobs:
  a11y-audit:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3

      - name: Setup Node.js
        uses: actions/setup-node@v3
        with:
          node-version: "18"

      - name: Install dependencies
        run: npm ci

      - name: Start dev server
        run: npm run dev &
        timeout-minutes: 5

      - name: Wait for server
        run: sleep 5

      - name: Run axe audit
        run: npx axe http://localhost:3000 --exit
```

---

## 6. Manual Testing Procedures

### 6.1 Pre-Launch Checklist

**Structure & Semantics:**

- [ ] Page has descriptive `<title>`
- [ ] Single `<h1>` per page
- [ ] Headings in logical order (h1 → h2 → h3, no skips)
- [ ] `<main>` element wraps main content
- [ ] `<nav>` elements label navigational regions
- [ ] Lists use semantic `<ul>`, `<ol>`, `<li>`
- [ ] Tables have `<th>` headers and captions if needed
- [ ] Page has skip-to-main-content link

**Typography & Spacing:**

- [ ] Text size ≥ 14px (readable without zoom)
- [ ] Line height ≥ 1.5 (spacing between lines)
- [ ] Line length ≤ 80 characters (readability)
- [ ] Sufficient spacing between interactive elements

**Color & Contrast:**

- [ ] Text color contrast ≥ 4.5:1
- [ ] Large text (18pt+) contrast ≥ 3:1
- [ ] Links distinguishable without color alone (underline, bold, etc.)
- [ ] Focus indicators visible and ≥ 3:1 contrast
- [ ] No information conveyed by color alone

**Forms & Input:**

- [ ] All inputs have associated `<label>`
- [ ] Error messages linked with `aria-describedby`
- [ ] Required fields marked (visually + aria-required)
- [ ] Form validation clear (before/after submit)
- [ ] Keyboard accessible (Tab through form)
- [ ] Submit button is actual button (not generic div)

**Links & Buttons:**

- [ ] Links have descriptive text (not "click here")
- [ ] Links distinguished from body text
- [ ] Buttons have labels (text or aria-label)
- [ ] Icon-only buttons have aria-label
- [ ] Button type correct (<button>, not <a role="button">)

**Images & Media:**

- [ ] All images have alt text
- [ ] Alt text describes image content
- [ ] Decorative images have empty alt (`alt=""`)
- [ ] Complex images have captions/descriptions
- [ ] Background images have text alternative

**Interactive Components:**

- [ ] Dropdowns work with Escape key
- [ ] Modals trap focus and announce purpose
- [ ] Tabs navigable with Arrow keys
- [ ] Accordion items have aria-expanded
- [ ] Carousels have pause button (if auto-play)
- [ ] Tooltips appear on keyboard focus

**Dynamic Content:**

- [ ] Live updates announced (aria-live)
- [ ] Form submissions confirmed
- [ ] Errors/warnings use role="alert"
- [ ] Loading states announced
- [ ] Content changes don't surprise user

### 6.2 Keyboard-Only Testing

**Procedure:**

1. Unplug mouse
2. Disable touchpad
3. Navigate page using only Tab, Enter, Space, Arrow keys, Escape
4. Verify:
   - All content accessible
   - Focus always visible
   - No keyboard traps
   - Meaningful navigation order

### 6.3 Screen Reader Content Testing

**Using VoiceOver on Mac:**

1. Enable VoiceOver: Cmd+F5
2. Open DevTools and reload page
3. Check:
   - Page title announced
   - Headings read correctly
   - Links have meaningful text
   - Form labels associated
   - Images have alt text
   - Landmarks detected (rotor: VO+U)

**Using NVDA on Windows:**

1. Start NVDA
2. Open page in Firefox
3. Use Insert+F7 to view elements
4. Check same points as VoiceOver

---

## 7. Website Testing on Different Devices

### 7.1 Mobile Accessibility

**Touch Target Size:**

- Minimum 44×44 CSS pixels
- Apply to buttons, links, form controls

```scss
button,
a[role="button"],
input[type="checkbox"],
input[type="radio"] {
  min-width: 44px;
  min-height: 44px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}
```

**Mobile Screen Reader Testing:**

**iOS (VoiceOver):**

1. Settings → Accessibility → VoiceOver → On
2. Swipe right to navigate forward
3. Swipe left to navigate backward
4. Swipe down then right to activate element

**Android (TalkBack):**

1. Settings → Accessibility → TalkBack → On
2. Swipe right to next item
3. Swipe left to previous item
4. Double-tap to activate

### 7.2 Testing on Different Devices

- **Desktop:** Chrome, Firefox, Safari on Windows, Mac, Linux
- **Mobile:** iOS (Safari + VoiceOver), Android (Chrome + TalkBack)
- **Screen Readers:** NVDA (Windows), VoiceOver (Mac/iOS), JAWS (premium)
- **Zoom Levels:** Test at 100%, 150%, 200% zoom

---

## 8. Common Accessibility Issues & Fixes

### Issue: Low Color Contrast

**Problem:** Text hard to read against background

```scss
/* ❌ BAD - 3:1 ratio */
.muted-text {
  color: #aaaaaa;
  background: #fff;
}

/* ✓ GOOD - 5.5:1 ratio */
.muted-text {
  color: #666666;
  background: #fff;
}
```

**Fix:** Use [contrast checker](https://webaim.org/resources/contrastchecker/) to verify

### Issue: Missing Form Labels

**Problem:** Screen reader can't identify form fields

```twig
<!-- ❌ BAD -->
<input type="email" placeholder="Email">

<!-- ✓ GOOD -->
<label for="email">Email Address</label>
<input type="email" id="email" name="email">
```

### Issue: Image Without Alt Text

**Problem:** Screen reader can't describe image

```twig
<!-- ❌ BAD -->
<img src="team.jpg">

<!-- ✓ GOOD -->
<img src="team.jpg" alt="Engineering team of 5 people">

<!-- ✓ GOOD (decorative) -->
<img src="divider.svg" alt="">
```

### Issue: Non-Semantic Buttons

**Problem:** Keyboard can't activate custom buttons

```twig
<!-- ❌ BAD -->
<div onclick="handleClick()" role="button">
  Submit
</div>

<!-- ✓ GOOD -->
<button type="button" onclick="handleClick()">
  Submit
</button>
```

### Issue: Focus Indicator Removed

**Problem:** Keyboard users can't see current focus

```scss
/* ❌ BAD - No focus style */
button:focus {
  outline: none;
}

/* ✓ GOOD - Visible focus */
button:focus {
  outline: 2px solid #0066cc;
  outline-offset: 2px;
}
```

### Issue: Color as Only Indicator

**Problem:** Red color alone doesn't indicate error (colorblind users)

```scss
/* ❌ BAD - Color only */
.error-field {
  border-color: red;
}

/* ✓ GOOD - Color + icon + text */
.error-field {
  border: 2px solid red;
  background: url("error-icon.svg") no-repeat center left;
  padding-left: 28px;
}
```

### Issue: Heading Hierarchy Skipped

**Problem:** Screen reader can't understand page structure

```html
<!-- ❌ BAD - Skips h2 -->
<h1>Main Title</h1>
<h3>Subsection</h3>

<!-- ✓ GOOD - Sequential hierarchy -->
<h1>Main Title</h1>
<h2>First Section</h2>
<h3>Subsection</h3>
<h2>Second Section</h2>
```

### Issue: Link Text Unclear

**Problem:** "Click here" doesn't describe link destination

```twig
<!-- ❌ BAD -->
<p>To learn more about our services, <a href="/services">click here</a>.</p>

<!-- ✓ GOOD -->
<p><a href="/services">Learn more about our services</a></p>

<!-- ✓ GOOD (title attribute) -->
<a href="/services" title="Learn more about our services">Services</a>
```

---

## 9. Accessibility Resources

### Standards & Guidelines

- [WCAG 2.1](https://www.w3.org/WAI/WCAG21/quickref/) — Official accessibility standards
- [ARIA Authoring Practices](https://www.w3.org/WAI/ARIA/apg/) — ARIA patterns and examples

### Testing Tools

- [axe DevTools](https://www.deque.com/axe/devtools/) — Automated accessibility testing
- [WAVE](https://wave.webaim.org/) — Web accessibility evaluation tool
- [Lighthouse](https://developers.google.com/web/tools/lighthouse) — Chrome DevTools audit
- [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/)
- [Pa11y](https://pa11y.org/) — Command-line accessibility testing

### Learning Resources

- [WebAIM](https://webaim.org/) — Accessibility articles and tutorials
- [A11y Project](https://www.a11yproject.com/) — Accessibility checklist and guide
- [MDN Accessibility](https://developer.mozilla.org/en-US/docs/Web/Accessibility) — Technical documentation
- [Deque University](https://dequeuniversity.com/) — Free accessibility courses

### Video & Community

- [WCAG Unplugged](https://www.a11yproject.com/resources/) — Video tutorials
- [AccessiBe](https://accessibe.com/blog/) — Accessibility blog
- [Inclusive Components](https://inclusive-components.design/) — Design patterns

---

## 10. Theme-Specific Accessibility

### 10.1 Breadcrumbs (✅ Already Implemented)

**File:** [BREADCRUMB-GUIDE.md](BREADCRUMB-GUIDE.md)

```twig
<nav aria-label="Breadcrumb">
  <ol class="breadcrumb__list">
    {% for item in breadcrumb_trail %}
      <li class="breadcrumb__item">
        {% if item.url %}
          <a href="{{ item.url }}">{{ item.label }}</a>
        {% else %}
          <span aria-current="page">{{ item.label }}</span>
        {% endif %}
      </li>
    {% endfor %}
  </ol>
</nav>
```

**Features:**

- ✅ Semantic nav element with aria-label
- ✅ Current page marked with aria-current="page"
- ✅ Schema.org JSON-LD markup
- ✅ Mobile-responsive abbreviation
- ✅ Keyboard accessible

### 10.2 Navigation Menu

**Expected Accessibility Features:**

Verify in header.twig and menu.twig:

- [ ] Menu toggle button has aria-expanded
- [ ] Menu links are in `<nav>` element
- [ ] Dropdown menus use aria-haspopup
- [ ] Menu closes on Escape key
- [ ] Focus management when menu opens/closes
- [ ] Mobile menu is fully keyboard accessible

### 10.3 Forms

**Best Practices Implemented:**

See: [COMPONENTS-GUIDE.md](./COMPONENTS-GUIDE.md) form section

- [ ] All inputs have labels
- [ ] Error messages use aria-describedby
- [ ] Required fields marked visually and with aria-required
- [ ] Success/error alerts use role="alert"
- [ ] Multi-step forms clearly marked
- [ ] Keyboard accessible form submission

### 10.4 Images & Media

**Lazy-Loaded Images** (with Image Lazy-Loading feature):

```twig
{# Images load with IntersectionObserver #}
<img
  data-src="image.jpg"
  alt="Descriptive text about image"
  class="lazy-image"
  src="data:image/gif;base64,..." {# Placeholder #}
>

{# Script: scripts/accordion.js handles loading #}
```

**Verify:**

- [ ] Alt text present and descriptive
- [ ] Placeholder visible while loading
- [ ] No image reflow on load
- [ ] Fallback for JS disabled

---

## 11. Maintenance & Continuous Improvement

### 11.1 Regular Audits

**Monthly:**

- Run axe DevTools on all templates
- Keyboard-only navigation test
- Screen reader smoke test (VoiceOver/NVDA)

**Quarterly:**

- Full WCAG 2.1 AA manual audit
- User testing with assistive tech users
- Performance impact review

**Annually:**

- Third-party accessibility audit
- WCAG 2.1 AAA feasibility review
- Team accessibility training

### 11.2 Design Reviews

When adding new features:

1. **Accessibility Checklist** (before development)

   - Semantic HTML planned
   - Focus management considered
   - ARIA requirements identified
   - Keyboard navigation designed

2. **Code Review** (before merging)

   - ARIA attributes correct
   - Focus indicators visible
   - Color contrast verified
   - axe scan passes (no critical/serious)

3. **QA Testing** (before launch)
   - Keyboard navigation works
   - Screen readers read correctly
   - Mobile a11y tested
   - axe audit clean

### 11.3 Documentation Updates

When modifying accessibility features:

- Update this guide
- Update component-specific guides ([BREADCRUMB-GUIDE.md](BREADCRUMB-GUIDE.md), etc.)
- Add comments to complex ARIA implementations
- Document any non-standard patterns

---

## 12. Status & Next Steps

**Current Implementation:**

- ✅ WCAG 2.1 AA baseline met
- ✅ Breadcrumb navigation (fully accessible)
- ✅ Lazy-loaded images (with fallbacks)
- ✅ Theme built with semantic HTML
- ✅ Dark mode with contrast preservation

**Areas for Enhancement:**

- Add comprehensive alt text guide per page type
- Implement accessibility mode toggle (simplified design)
- Add keyboard shortcuts documentation
- Create interactive accessibility tutorials
- Expand screen reader testing procedures

**Ongoing:** Monthly axe audits, quarterly manual testing, annual professional review

---

## Quick Start for Developers

**Before committing changes:**

```bash
# 1. Install axe DevTools browser extension
# 2. Open DevTools and run axe scan
# 3. Fix all Critical/Serious violations
# 4. Test with keyboard only (no mouse)
# 5. Test with VoiceOver/NVDA on target content
# 6. Commit when scan is clean
```

**Template Accessibility Checklist:**

- [ ] Use semantic HTML (`<button>`, `<nav>`, `<main>`, etc.)
- [ ] All form inputs have labels
- [ ] All images have alt text
- [ ] All interactive elements are keyboard accessible
- [ ] Focus styles are visible
- [ ] Color contrast is ≥ 4.5:1
- [ ] ARIA attributes only when necessary
- [ ] axe scan shows no critical/serious issues
- [ ] Screen reader announces all content correctly
- [ ] Keyboard navigation works (Tab, Enter, Esc, Arrow keys)

---

**Last Updated:** March 25, 2026  
**Compliance Level:** WCAG 2.1 Level AA  
**Annual Audit:** Recommended
