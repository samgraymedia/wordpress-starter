# Gutenberg Blocks & Theme Features

**Status:** 7 of 10 core features complete | 3 remaining

---

## 📊 Status Summary

| Category                     | Count | Status                                   |
| ---------------------------- | ----- | ---------------------------------------- |
| **Total Blocks Implemented** | 35    | All implemented and documented           |
| **Features Remaining**       | 3     | .env, Deployment, Performance            |
| **Total Phases**             | 7     | 143-196 hours remaining estimated effort |

**Build Results:**

- Last build: 192ms
- No errors/warnings
- JS bundle: 15.78 kB (gzip: 4.71 kB)

---

## Phase 1: Quick Wins (12-16h remaining)

### .env Configuration Support

**Why:** Separate config from code, enable feature flags

**Setup:**

- Add `vlucas/phpdotenv` to composer
- Create `.env.example` template
- Update `src/config.php` to read environment
- Add `.env` to `.gitignore`

**Variables:** `WP_DEBUG`, Feature flags, API keys, Cache TTL

---

### ✅ JavaScript Error Logging Utility [COMPLETED]

**Status:** Implemented - 6-8h

**Completed Items:**

- ✅ Created `scripts/utils/logger.js` with full logging utility
- ✅ Created `scripts/utils/error-reporter.js` with server integration
- ✅ Exposed `WP_DEBUG` flag in `window.themeConfig.debug` via functions.php
- ✅ Integrated Logger into `scripts/index.js` for early initialization
- ✅ Created comprehensive unit tests in `tests/logger.test.js`
- ✅ Added Logger usage guide to DEVELOPER-GUIDE.md with examples
- ✅ Implemented automatic error reporting to server endpoint
- ✅ Added batch error collection and retry logic with exponential backoff
- ✅ Configured environment-aware logging (verbose dev, minimal production)

**Files Created/Modified:**

- `scripts/utils/logger.js` - Main logger utility with debug/info/warn/error methods
- `scripts/utils/error-reporter.js` - Server-side error reporting with batching
- `functions.php` - Added `'debug' => WP_DEBUG` to wp_localize_script
- `scripts/index.js` - Added logger imports and initialization at theme startup
- `tests/logger.test.js` - Comprehensive unit tests with 30+ test cases
- `DEVELOPER-GUIDE.md` - Added complete logging and debugging section

**Notes:** Logger is production-ready. To enable error reporting endpoint, implement REST API route at `/wp-json/theme/v1/errors` in theme PHP code. See DEVELOPER-GUIDE.md for usage examples.

---

## Phase 3: Infrastructure (18-25h remaining)

### Deployment Guide & CI/CD

**Why:** Reduce errors, streamline releases

**Content:**

- DEPLOYMENT-GUIDE.md
- GitHub Actions configuration
- Pre-deployment checklist
- Database migrations, backup/rollback procedures

---

### Performance Monitoring & Optimization

**Why:** Measure improvements, establish baselines

**Content:**

- PERFORMANCE-GUIDE.md
- Web Vitals (LCP, FID, CLS) documentation
- Lighthouse CI integration
- Performance budgets and strategies

---

## 🔴 Phase 4: Critical Fixes (30-42h remaining)

**Status:** Newly identified during codebase audit

### Outdated Timber & PHPUnit Dependencies

**Why:** Security vulnerabilities, incompatibility with modern WordPress

**Effort:** 4-6h

**Tasks:**

- Upgrade Timber from 1.x to 2.x (breaking changes require API updates)
- Upgrade PHPUnit from 5.7.16/6.x to 9.x or latest stable
- Update related test files for compatibility
- Test all theme functionality with new versions

---

### Missing Type Definitions & TypeScript Support

**Why:** Catch type-related bugs early, improve IDE refactoring support

**Effort:** 20-30h

**Tasks:**

- Add `tsconfig.json` for JavaScript type checking
- Convert key modules to TypeScript or add comprehensive JSDoc type annotations
- Focus on [scripts/modules/](scripts/modules/) first
- Set up TypeScript compilation in build pipeline

---

### Production Console.log Cleanup

**Why:** Remove debugging noise from production browser console

**Effort:** 2-3h

**Tasks:**

- Audit [scripts/modules/search.js](scripts/modules/search.js)
- Audit [scripts/modules/forms.js](scripts/modules/forms.js)
- Audit [scripts/modules/cart.js](scripts/modules/cart.js)
- Replace with conditional logger that respects WP_DEBUG flag

---

## 🟠 Phase 5: High Priority (56-75h remaining)

**Status:** Newly identified during codebase audit

---

### Comprehensive Test Coverage Expansion

**Why:** Ensure reliability, catch regressions, improve maintainability

**Effort:** 30-40h

**Tests Missing:**

- [src/helpers/](src/helpers/) validation & sanitization functions
- Post type & taxonomy registration
- ACF block integration
- [scripts/modules/](scripts/modules/) using Jest
- Theme configuration system
- Breadcrumb component edge cases
- WooCommerce integration features

**Tasks:**

- Expand [tests/](tests/) directory structure
- Add Jest configuration for JavaScript testing
- Create test fixtures for theme data
- Set up coverage reporting (target 70%+ coverage)

---

### Enable Source Maps for Development

**Why:** Speed up debugging, improve developer experience

**Effort:** 1-2h

**Tasks:**

- Update [vite.config.js](vite.config.js) to enable source maps in dev
- Ensure source maps remain disabled in production builds
- Test with browser DevTools

---

### Complete SEO Implementation

**Why:** Improve search engine visibility and social sharing

**Effort:** 8-10h

**Tasks:**

- Add Open Graph meta tags to [templates/html-header.twig](templates/html-header.twig)
- Add Twitter Card meta tags
- Add canonical tags
- Add structured data (JSON-LD) for all post types
- Create SEO helper functions
- Test with SEO validators (SEMRush, Moz, Facebook debugger)

---

### AJAX Request Validation & Nonce Middleware

**Why:** Security, consistency, better error handling

**Effort:** 8-10h

**Tasks:**

- Create centralized AJAX request validator class
- Implement automatic nonce verification middleware
- Standardize AJAX response format across all handlers
- Add consistent error response handling
- Document AJAX endpoint patterns

---

### Missing ESLint Configuration for PHP

**Why:** Catch PHP errors early, maintain code quality

**Effort:** 4-6h

**Tasks:**

- Create `phpcs.xml` with WordPress coding standards
- Add Psalm configuration for static analysis
- Integrate PHPCS into CI pipeline
- Configure IDE integration for real-time checking

---

## 🟡 Phase 6: Medium Priority (42-56h remaining)

**Status:** Newly identified during codebase audit

### Environment-Specific Configuration Setup

**Why:** Simplify environment setup, reduce configuration errors

**Effort:** 2-3h

**Tasks:**

- Create `.env.example` template with all required variables
- Document environment setup process
- Add WP_DEBUG per-environment configuration examples
- Document build output paths for different environments

---

### Global Variable Encapsulation

**Why:** Improve maintainability, reduce bugs, easier testing

**Effort:** 4-6h

**Tasks:**

- Audit [src/page-templates-registry.php](src/page-templates-registry.php)
- Audit [src/woocommerce-integration.php](src/woocommerce-integration.php)
- Audit [functions.php](functions.php)
- Create accessor methods instead of direct global references
- Update all callers to use new methods

---

### Create CHANGELOG & Version Management

**Why:** Track changes, improve release communication

**Effort:** 2-3h

**Tasks:**

- Create [CHANGELOG.md](CHANGELOG.md)
- Adopt semantic versioning (major.minor.patch)
- Create VERSION file
- Document release process

---

### WooCommerce Integration Hardening

**Why:** Ensure reliability with WooCommerce installations

**Effort:** 6-8h

**Tasks:**

- Add comprehensive WooCommerce feature tests
- Document integration caveats and limitations
- Test fragment cache behavior in various scenarios
- Create troubleshooting guide for WooCommerce issues

---

### REST API Documentation

**Why:** Help developers integrate with theme APIs

**Effort:** 4-5h

**Tasks:**

- Document REST endpoints in [src/theme-toggle-options.php](src/theme-toggle-options.php)
- Create OpenAPI/Swagger specification
- Add endpoint examples and response formats
- Add authentication/permission documentation

---

### Async Error Handling & User Notifications

**Why:** Better UX, visibility into failures

**Effort:** 3-4h

**Tasks:**

- Audit [scripts/utils/http.js](scripts/utils/http.js)
- Audit [scripts/modules/search.js](scripts/modules/search.js)
- Implement user-facing error notifications
- Standardize error response handling across modules
- Add retry logic for transient failures

---

## 🔵 Phase 7: Low Priority Backlog (23-31h remaining)

**Status:** Newly identified during codebase audit

### Update ESLint to Version 9.x

**Why:** Keep tools modern, use new flat config system

**Effort:** 2-3h

**Tasks:**

- Upgrade ESLint from 8.53 to 9.x
- Migrate to new flat config format
- Update `.eslintrc` to `eslint.config.js`
- Test with all project JavaScript

---

### Update Deprecated Dependencies (npm audit)

**Why:** Fix deprecation warnings, patch security issues

**Effort:** 1-2h

**Tasks:**

- Run `npm audit fix` to update glob to latest
- Update rimraf to v4+
- Test build process after updates

---

### Enhanced Form Field Validation

**Why:** Better UX, catch errors before submission

**Effort:** 3-4h

**Tasks:**

- Review [scripts/modules/forms.js](scripts/modules/forms.js) validation
- Add credit card format validation
- Add phone number validation
- Add email validation improvements

---

### Adaptive Cache Configuration

**Why:** Use available resources efficiently, improve performance

**Effort:** 1-2h

**Tasks:**

- Update [src/config.php](src/config.php) cache configuration
- Add WP_MEMORY_LIMIT checks
- Create environment-specific cache strategies

---

### Add CSS/SCSS Linting

**Why:** Maintain SCSS consistency, catch errors

**Effort:** 2-3h

**Tasks:**

- Create `stylelint.config.js` with WordPress rules
- Integrate into CI pipeline
- Configure IDE integration

---

### Git Hooks & Pre-commit Checks

**Why:** Prevent broken commits, improve code quality

**Effort:** 2-3h

**Tasks:**

- Set up Husky for git hooks
- Add pre-commit lint check
- Add pre-commit test execution
- Document workflow for developers

---

### Lighthouse CI Integration

**Why:** Catch performance regressions, ensure accessibility

**Effort:** 6-8h

**Tasks:**

- Create `.github/workflows/lighthouse.yml`
- Configure Lighthouse thresholds
- Add performance budget enforcement
- Integrate results into PR checks

---

### Custom ACF Block Extension Guide

**Why:** Help developers create custom blocks

**Effort:** 4-5h

**Tasks:**

- Document block registration patterns
- Create block extension examples
- Document ACF configuration for blocks
- Add troubleshooting guide

---

### Implement Additional Utility Blocks (8 New Blocks)

**Why:** Expand block library with commonly needed components

**Effort:** 8-15h

**Suggested New Blocks:**

1. **Section/Container** - Layout wrapper with background color, padding, and spacing options
2. **Standalone Button** - Individual reusable button block (not just CTA)
3. **Alert/Notification** - Info/warning/error/success message box with icons
4. **Timeline** - Vertical timeline with milestones and dates
5. **Before/After** - Image comparison slider for showcasing transformations
6. **Video Playlist** - Multiple videos with playlist controls and navigation
7. **Event Listing** - Calendar-based event display with filtering

**Benefits:**

- Increases block library utility
- Reduces need for custom code in pages
- Improves editor experience
- Fills common use case gaps

**Tasks:**

- Analyze usage patterns to prioritize which blocks to build first
- Create templates and ACF field groups for each block
- Add SCSS styling with dark mode support
- Write documentation in BLOCKS-GUIDE.md
- Test accessibility and responsiveness
- Add to auto-discovery system

---

### Backup & Recovery Documentation

**Why:** Prepare for emergencies, reduce downtime

**Effort:** 2-3h

**Tasks:**

- Document WordPress backup strategy
- Add database backup procedures
- Document recovery process
- Create emergency contact procedures

---

## 📊 Complete Feature Matrix

| Phase | Feature                      | Priority | Effort | Status |
| ----- | ---------------------------- | -------- | ------ | ------ |
| 1     | .env Configuration           | 🟡 Med   | 6-8h   | ⏳     |
| 1     | JS Error Logging             | 🟡 Med   | 6-8h   | ✅     |
| 3     | Deployment Guide & CI/CD     | 🟡 Med   | 10-15h | ⏳     |
| 3     | Performance Monitoring       | 🟡 Med   | 8-10h  | ⏳     |
| 4     | Timber/PHPUnit Upgrade       | 🔴 Crit  | 4-6h   | ⏳     |
| 4     | TypeScript Support           | 🔴 Crit  | 20-30h | ⏳     |
| 4     | Console.log Cleanup          | 🔴 Crit  | 2-3h   | ⏳     |
| 5     | Test Coverage Expansion      | 🟠 High  | 30-40h | ⏳     |
| 5     | Source Maps in Development   | 🟠 High  | 1-2h   | ⏳     |
| 5     | Complete SEO Setup           | 🟠 High  | 8-10h  | ⏳     |
| 5     | AJAX Nonce Middleware        | 🟠 High  | 8-10h  | ⏳     |
| 5     | PHP Linting Setup            | 🟠 High  | 4-6h   | ⏳     |
| 6     | Environment Configuration    | 🟡 Med   | 2-3h   | ⏳     |
| 6     | Global Variable Cleanup      | 🟡 Med   | 4-6h   | ⏳     |
| 6     | CHANGELOG & Versioning       | 🟡 Med   | 2-3h   | ⏳     |
| 6     | WooCommerce Hardening        | 🟡 Med   | 6-8h   | ⏳     |
| 6     | REST API Documentation       | 🟡 Med   | 4-5h   | ⏳     |
| 6     | Error Notifications          | 🟡 Med   | 3-4h   | ⏳     |
| 7     | ESLint 9.x Upgrade           | 🔵 Low   | 2-3h   | ⏳     |
| 7     | npm audit Fixes              | 🔵 Low   | 1-2h   | ⏳     |
| 7     | Form Validation Enhancements | 🔵 Low   | 3-4h   | ⏳     |
| 7     | Adaptive Cache Config        | 🔵 Low   | 1-2h   | ⏳     |
| 7     | CSS Linting Setup            | 🔵 Low   | 2-3h   | ⏳     |
| 7     | Git Hooks/Husky              | 🔵 Low   | 2-3h   | ⏳     |
| 7     | Lighthouse CI                | 🔵 Low   | 6-8h   | ⏳     |
| 7     | ACF Block Guide              | 🔵 Low   | 4-5h   | ⏳     |
| 7     | **New Utility Blocks** (8)   | 🔵 Low   | 8-15h  | ⏳     |
| 7     | Backup Documentation         | 🔵 Low   | 2-3h   | ⏳     |

---

## 🎯 Recommended Priority Order

**Immediate (Critical Path - 30-42h):**

1. Timber/PHPUnit Upgrade
2. TypeScript/JSDoc Type Support
3. Console.log Cleanup
4. Environment Configuration

**Next Sprint (High Priority - 56-75h):**

1. ✅ **Block Documentation** - COMPLETE
2. Test Coverage Expansion (30-40h)
3. Complete SEO Setup (8-10h)
4. AJAX Security Middleware (8-10h)
5. Source Maps & Debugging (1-2h)
6. PHP Linting Setup (4-6h)

**Current Phase (Medium - 42-56h):**

1. Global Variable Encapsulation
2. WooCommerce Hardening
3. REST API Documentation
4. Error Notification System
5. CHANGELOG Management

**Backlog (Low Priority - 23-31h):**

1. Dependency Updates
2. Tooling Upgrades
3. Documentation Enhancements
4. CI/CD Integrations

---

## 📝 Notes

- All code should follow theme standards (BEM, mobile-first, a11y)
- Include dark mode support for visual components
- Write tests where applicable
- Document thoroughly in corresponding GUIDE files
- Reference [BLOCKS-GUIDE.md](./BLOCKS-GUIDE.md) for block patterns
- **Total Remaining Effort:** 151-204 hours across all phases
- **Audit Date:** March 25, 2026
- **Audit Findings:** 28 issues identified (4 critical, 8 high, 9 medium, 7 low)
- **Last Update:** March 25, 2026
