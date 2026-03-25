# Gutenberg Blocks & Theme Features

**Status:** 5 of 7 blocks complete | 3 of 10 core features complete

---

## 📦 Blocks To Build

**Remaining:** 1 block (Map)

### 1. Map / Locations

**Priority:** Medium | **Complexity:** Medium | **Est. Time:** 2-4 hrs

Structured locations block for multi-location businesses, branches, stockists, or offices.

**Fields:**

- Title
- Locations repeater (Address, Phone, Hours, Map/Coords, Image, Link)

---

## 📊 Status Summary

| Category               | Count | Status                                                                            |
| ---------------------- | ----- | --------------------------------------------------------------------------------- |
| **Blocks Built**       | 5     | ✅ Related Content, Spacer, Steps, Quote, Form Embed                              |
| **Blocks Remaining**   | 1     | Map                                                                               |
| **Features Built**     | 3     | Form Validation (JS), Input Sanitization (PHP), Block Variations                  |
| **Features Remaining** | 7     | .env, JS Logger, Breadcrumb, Lazy-Loading, Accessibility, Deployment, Performance |

**Build Results:**

- Last build: 178ms
- No errors/warnings
- JS bundle: 12.61 kB (gzip: 3.99 kB)

---

## 🚀 Implementation Guide

### Required Steps for New Blocks

1. Create ACF field config: `src/blocks/block-name.php`
2. Create Twig template: `views/blocks/block-name.twig`
3. Create SCSS: `css/inc/blocks/block-name.scss`
4. Add import to `style.scss`
5. Run `npm run build` to compile
6. Test on page/post
7. Update TODO.md with completion details

### Block Standards

- Use BEM naming convention
- Mobile-first responsive design
- Include dark mode support (media query)
- Print-friendly styling
- Semantic HTML with ARIA labels
- Conditional field logic where applicable

---

---

# 🛠️ Core Features & Infrastructure

Utilities and infrastructure improvements to build alongside/after blocks.

**Roadmap:** 10 features | 3 complete, 7 remaining

## Priority & Effort Matrix

| #   | Feature                        | Priority  | Complexity | Effort | Status |
| --- | ------------------------------ | --------- | ---------- | ------ | ------ |
| 1   | Form Validation Utilities (JS) | 🔴 High   | Low        | 4-6h   | ✅     |
| 2   | Input Sanitization (PHP)       | 🔴 High   | Medium     | 10-14h | ✅     |
| 3   | Breadcrumb Navigation          | 🔴 High   | Medium     | 8-12h  | ⏳     |
| 4   | Image Lazy-Loading             | 🔴 High   | Medium     | 10-15h | ⏳     |
| 5   | Accessibility Audit Guide      | 🔴 High   | Medium     | 12-18h | ⏳     |
| 6   | .env Configuration             | 🟡 Medium | Low        | 6-8h   | ⏳     |
| 7   | JS Error Logging               | 🟡 Medium | Low        | 6-8h   | ⏳     |
| 8   | Deployment Guide & CI/CD       | 🟡 Medium | Medium     | 10-15h | ⏳     |
| 9   | Performance Monitoring         | 🟡 Medium | Low-Med    | 8-10h  | ⏳     |
| 10  | Block Variations Doc           | 🟢 Low    | Low        | 5-7h   | ✅     |

## Phase 1: Quick Wins (Total: ~20 hours)

Parallel tasks with immediate value

**Completed (3 of 4):**

- ✅ Form Validation Utilities Library (JavaScript)
- ✅ Input Sanitization & Validation (PHP)
- ✅ Block Variations Documentation

**Remaining (1 of 4):**

### 2. .env Configuration Support

6-8h | Environment-specific settings (dev/staging/prod)

**Why:** Separate config from code, enable feature flags

**Setup:**

- Add `vlucas/phpdotenv` to composer
- Create `.env.example` template
- Update `src/config.php` to read environment
- Add `.env` to `.gitignore`

**Variables:** `WP_DEBUG`, Feature flags, API keys, Cache TTL

---

### 3. JavaScript Error Logging Utility

6-8h | Centralized error handling and debug logging

**Why:** Track production errors, aid debugging

**Features:**

- `Logger.debug()`, `Logger.error()`, `Logger.warn()`
- Stack trace capture
- Environment-aware behavior

**Files:** `scripts/utils/logger.js`

---

## Phase 2: Performance & UX (Total: ~30 hours)

Core functionality improvements

### 5. Image Lazy-Loading

10-15h | Performance improvement via IntersectionObserver

**Why:** Reduce initial load, faster perceived performance

**Implementation:**

- IntersectionObserver utility module
- Native `loading="lazy"` attributes
- Blurred placeholders
- Dynamic srcset/picture support

**Coverage:** Heroes, galleries, blog posts, products

---

### 6. Breadcrumb Navigation Component

8-12h | Essential UX/SEO element missing from theme

**Why:** Improve navigation, boost SEO, reduce confusion

**Features:**

- Auto-generation for archives/taxonomies
- Gutenberg block for manual placement
- Schema.org BreadcrumbList markup
- Mobile-friendly collapse

---

### 7. Comprehensive Accessibility Guide

12-18h | WCAG 2.1 AA compliance testing framework

**Why:** Ensure legal compliance, improve usability

**Deliverables:**

- ACCESSIBILITY-GUIDE.md
- ARIA labels checklist
- Keyboard navigation procedures
- Screen reader testing guide
- axe testing integration

---

## Phase 3: Infrastructure (Total: ~36 hours)

Backend and DevOps improvements

**Completed (1 of 3):**

- ✅ Input Sanitization & Validation (PHP)

**Remaining (2 of 3):**

### 9. Deployment Guide & CI/CD

10-15h | Automated testing pipeline and procedures

**Why:** Reduce errors, streamline releases

**Content:**

- DEPLOYMENT-GUIDE.md
- GitHub Actions configuration
- Pre-deployment checklist
- Database migrations, backup/rollback procedures

---

### 10. Performance Monitoring & Optimization

8-10h | Web Vitals tracking and optimization guide

**Why:** Measure improvements, establish baselines

**Content:**

- PERFORMANCE-GUIDE.md
- Web Vitals (LCP, FID, CLS) documentation
- Lighthouse CI integration
- Performance budgets and strategies

---

**Recommended Implementation Order:**

1. Phase 1 (Quick Wins) — ~20h — Parallel execution
2. Phase 2 (Performance) — ~30h — Sequential
3. Phase 3 (Infrastructure) — ~36h — Sequential

---

## 📝 Notes

- All code should follow theme standards (BEM, mobile-first, a11y)
- Include dark mode support for visual components
- Write tests where applicable
- Document thoroughly in corresponding GUIDE files
- Reference [BLOCKS-GUIDE.md](./BLOCKS-GUIDE.md) for block patterns
