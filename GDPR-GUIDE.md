# GDPR & Cookie Consent Guide

Complete guide to the GDPR-compliant cookie consent system built into the Starter Theme.

---

## Table of Contents

1. [Overview](#overview)
2. [Features](#features)
3. [How It Works](#how-it-works)
4. [Configuration](#configuration)
5. [Cookie Categories](#cookie-categories)
6. [Usage in JavaScript](#usage-in-javascript)
7. [Usage in Twig Templates](#usage-in-twig-templates)
8. [Storing & Managing Preferences](#storing--managing-preferences)
9. [Integration with Analytics](#integration-with-analytics)
10. [Custom Implementations](#custom-implementations)
11. [Best Practices](#best-practices)

---

## Overview

The cookie consent system provides a **GDPR-compliant** way to:

- Display a cookie consent banner to visitors
- Let users choose which cookie categories to allow
- Store and persist user preferences
- Conditionally load tracking scripts based on consent
- Provide detailed cookie preference management

**Key Components:**

- **Banner** - Fixed bottom banner that appears on first visit
- **Preferences Modal** - Detailed preferences dialog for granular control
- **Consent Management** - JavaScript utilities for reading/writing preferences
- **Categories** - Four cookie categories: Necessary, Analytics, Marketing, Preferences

---

## Features

✅ **GDPR Compliant** - Opt-in model (no tracking without consent)  
✅ **Persistent Storage** - Preferences saved to localStorage (365 days)  
✅ **Customizable UI** - Easy to modify banner text and styling  
✅ **Modal Preferences** - Detailed cookie preferences dialog  
✅ **Category-Based** - Organize cookies by purpose  
✅ **Analytics Integration** - Works with Google Analytics 4 (GA4, gtag)  
✅ **Accessible** - WCAG 2.1 AA compliant with keyboard navigation  
✅ **Responsive** - Mobile-optimized UI  
✅ **ES6 Modules** - Modern JavaScript architecture  
✅ **Zero Dependencies** - Pure JavaScript, no jQuery required

---

## How It Works

### 1. First Visit

User arrives at site → Consent banner appears at bottom → No tracking cookies set yet

### 2. User Choice

User clicks:

- **Accept All** → All categories allowed → Banner closes
- **Reject All** → Only necessary cookies → Banner closes
- **Customize** → Modal opens → User selects preferences

### 3. Storage

Preferences saved to `localStorage['cookie-consent']`:

```json
{
  "necessary": true,
  "analytics": false,
  "marketing": false,
  "preferences": false
}
```

### 4. Subsequent Visits

User returns → Preferences found in storage → Banner doesn't show → Scripts load based on previous choices

### 5. Script Loading

Optional scripts (GA, ads, etc.) loaded conditionally based on consent:

```javascript
if (hasConsent("analytics")) {
  // Load GA tracking
}
```

---

## Configuration

### Basic Banner Text

Edit the banner section in `templates/components/cookie-consent.twig`:

```twig
<h3 class="cookie-consent-banner__title">We use cookies</h3>
<p class="cookie-consent-banner__description">
  We use cookies to enhance your experience...
  <a href="/privacy-policy">Privacy Policy</a>
</p>
```

### Customizing Descriptions

Edit each cookie category description in the modal preferences section:

```twig
<div class="cookie-preferences-modal__section">
  <div class="cookie-preferences-modal__item">
    <label class="cookie-preferences-modal__label">
      <strong>Analytics Cookies</strong>
    </label>
    <p class="cookie-preferences-modal__description">
      YOUR CUSTOM DESCRIPTION HERE
    </p>
  </div>
</div>
```

### Privacy Policy Link

Update the privacy policy link in both banner and modal:

```twig
{# Banner #}
<a href="/privacy-policy" class="cookie-consent-banner__link">Privacy Policy</a>

{# Modal #}
You can also link to a specific section like:
<a href="/privacy-policy#cookies">Cookie Policy</a>
```

### Banner Styling

Customize banner colors in `css/inc/components/cookie-consent.scss`:

```scss
.cookie-consent-banner {
  background-color: $white; // Banner background
}

.cookie-consent-banner__btn--primary {
  background-color: $primary; // Accept button color
}

.cookie-consent-banner__btn--secondary {
  background-color: $white; // Reject button color
}
```

---

## Cookie Categories

### Necessary Cookies (Always On)

**Purpose:** Essential for site functionality  
**Examples:** Session management, security tokens, language preference  
**Status:** Cannot be disabled by user  
**GDPR:** Exempt from consent requirement

```javascript
if (hasConsent("necessary")) {
  // Always true
  setSessionCookie();
}
```

### Analytics Cookies

**Purpose:** Track site usage, performance metrics  
**Examples:** Google Analytics, page view counters, heatmaps  
**Tools:** GA4, Mixpanel, Plausible  
**User Option:** Can disable

```javascript
if (hasConsent("analytics")) {
  // Load GA4 tracking
  gtag("consent", "update", {
    analytics_storage: "granted",
  });
}
```

### Marketing Cookies

**Purpose:** Personalized advertising, retargeting  
**Examples:** Facebook Pixel, Google Ads, LinkedIn Insight Tag  
**Tools:** Meta, Google, LinkedIn, TikTok  
**User Option:** Can disable

```javascript
if (hasConsent("marketing")) {
  // Load ad network pixels
  gtag("consent", "update", {
    ad_storage: "granted",
  });
}
```

### Preferences Cookies

**Purpose:** Remember user preferences, personalization  
**Examples:** Theme preference, font size, sidebar state  
**Tools:** Local settings, vendor personalization  
**User Option:** Can disable

```javascript
if (hasConsent("preferences")) {
  // Load preference-based features
  gtag("consent", "update", {
    personalization_storage: "granted",
  });
}
```

---

## Usage in JavaScript

### 1. Checking Consent

```javascript
import {
  hasConsent,
  getConsentPreferences,
  getCurrentConsent,
} from "./utils/cookies.js";

// Check specific category
if (hasConsent("analytics")) {
  console.log("User allows analytics");
}

// Get all preferences
const prefs = getCurrentConsent();
console.log(prefs);
// { necessary: true, analytics: true, marketing: false, preferences: false }
```

### 2. Loading Conditional Scripts

```javascript
import { hasConsent } from "./utils/cookies.js";

// Load Google Analytics only if consented
if (hasConsent("analytics")) {
  // Load GA script
  const script = document.createElement("script");
  script.src = "https://www.googletagmanager.com/gtag/js?id=G-XXXXXX";
  document.head.appendChild(script);

  window.dataLayer = window.dataLayer || [];
  gtag("consent", "update", {
    analytics_storage: "granted",
  });
}

// Load Facebook Pixel only if analytics allowed
if (hasConsent("marketing")) {
  // Load Pixel code
  fbq("consent", "grant");
}
```

### 3. Tracking Events Safely

```javascript
import { trackEvent } from "./modules/cookie-consent.js";

// Will only track if analytics is consented
trackEvent("button_click", {
  button_name: "Sign Up",
  location: "header",
});
```

### 4. Listening for Consent Changes

```javascript
// Listen for consent updates
document.addEventListener("cookieConsent", (event) => {
  const { detail: preferences } = event;

  if (preferences.analytics) {
    console.log("Analytics just enabled");
    // Trigger GA initialization
  }

  if (preferences.marketing) {
    console.log("Marketing just enabled");
    // Trigger ad pixel initialization
  }
});
```

### 5. Storing in Cookies vs LocalStorage

**Preferences Storage:**

- Primary: `localStorage['cookie-consent']` (client-only, not sent to server)
- Backup: Optional HTTP cookie for server-side checking

```javascript
// Read from storage
import { getConsentPreferences } from "./utils/cookies.js";
const prefs = getConsentPreferences();

// Set manually (not needed usually)
import { setConsentPreferences } from "./utils/cookies.js";
setConsentPreferences({
  necessary: true,
  analytics: true,
});
```

---

## Usage in Twig Templates

### Check Consent in Templates

While JavaScript is responsible for most consent logic, you can pass consent data to Twig:

```twig
{# In your view file, pass consent data to template #}
{% set consent = function('wp_extract_cookie_consent') %}

{# Conditionally show tracking pixel #}
{% if consent.marketing %}
  <!-- Facebook Pixel -->
  <img height="1" width="1" src="https://t.facebook.com/tr?id=..." />
{% endif %}

{# Show notice about analytics #}
<p>We use analytics to improve your experience.
  {% if consent.analytics %}
    You can <a href="/privacy-policy">change your preferences</a>.
  {% endif %}
</p>
```

---

## Storing & Managing Preferences

### Data Structure

```javascript
{
  "necessary": true,        // Always true
  "analytics": true,        // User choice
  "marketing": false,       // User choice
  "preferences": false      // User choice
}
```

### Storage Locations

**Primary (Client-Side):**

- `localStorage['cookie-consent']` - Client-only, 365 days

**Optional (Server-Side):**

- HTTP cookie `cookie-consent` - For server-side scripts

### Clearing Preferences

```javascript
import { clearConsentData } from "./utils/cookies.js";

// Clear all consent data (user can choose again)
clearConsentData();
```

### Retrieving Stored Preferences

```javascript
import { getConsentPreferences } from "./utils/cookies.js";

// Get stored preferences (null if not set)
const stored = getConsentPreferences();

if (stored) {
  console.log("User has made a choice:", stored);
} else {
  console.log("User has not seen banner yet");
}
```

---

## Integration with Analytics

### Google Analytics 4 (gtag.js)

Include GA with default consent mode **disabled**:

```html
<!-- In html-header.twig, include this BEFORE other GA code -->
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag() {
    dataLayer.push(arguments);
  }
  gtag("consent", "default", {
    analytics_storage: "denied",
    ad_storage: "denied",
    ad_user_data: "denied",
    ad_personalization: "denied",
    wait_for_update: 500,
  });
</script>

<!-- Load GA script -->
<script
  async
  src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXX"
></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag() {
    dataLayer.push(arguments);
  }
  gtag("js", new Date());
  gtag("config", "G-XXXXXX");
</script>
```

The cookie consent module will automatically update consent when user chooses:

```javascript
// Module automatically calls:
gtag("consent", "update", {
  analytics_storage: "granted", // If analytics allowed
  ad_storage: "granted", // If marketing allowed
  personalization_storage: "granted", // If preferences allowed
});
```

### Other Analytics Tools

**Plausible Analytics:**

```javascript
if (hasConsent("analytics")) {
  // Load Plausible
  const script = document.createElement("script");
  script.defer = true;
  script.src = "https://plausible.io/js/script.js";
  script.dataset.domain = "example.com";
  document.head.appendChild(script);
}
```

**Mixpanel:**

```javascript
if (hasConsent('analytics')) {
  // Load Mixpanel
  (function(f,b){if(!b.__SV){var v = window;try{var e,l,i,j=v.location,d=j.hash;e=d.split("&");for(i=0;i<e.length;i++){l=e[i].split("=");if(l[0]==" mp_optout")break}}catch(e){}...
}
```

---

## Custom Implementations

### Override Banner Text Dynamically

```javascript
import { querySelector } from "./utils/dom.js";

// Change banner title based on page context
const title = querySelector(".cookie-consent-banner__title");
if (title && isCheckoutPage()) {
  title.textContent = "We use cookies to process your order safely";
}
```

### Custom Banner Styling

```scss
// Override in your custom SCSS file
.cookie-consent-banner {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

  .cookie-consent-banner__title {
    color: white;
  }

  .cookie-consent-banner__description {
    color: rgba(white, 0.9);
  }
}
```

### A/B Testing Banner Messages

```javascript
import { trackEvent } from "./modules/cookie-consent.js";

function showBannerVariant() {
  const variant = Math.random() > 0.5 ? "A" : "B";

  trackEvent("cookie_banner_shown", {
    variant: variant,
  });

  if (variant === "B") {
    // Change to aggressive variant
    const title = querySelector(".cookie-consent-banner__title");
    title.textContent = "All cookies enabled for best experience";
  }
}
```

### Server-Side Consent Check

```php
<?php
// In functions.php or custom PHP file

/**
 * Get user's cookie consent from localStorage via PHP
 * Note: PHP can't access localStorage directly; this requires JavaScript
 */
function get_user_consent_analytics() {
  // If you saved consent to a cookie:
  if ( isset( $_COOKIE['cookie-consent-analytics'] ) ) {
    return filter_var( $_COOKIE['cookie-consent-analytics'], FILTER_VALIDATE_BOOLEAN );
  }

  // Default: deny if not explicitly granted
  return false;
}

// Use in templates:
if ( get_user_consent_analytics() ) {
  // Render tracking pixel or script
}
?>
```

---

## Best Practices

### 1. Always Include Privacy Policy

✅ **DO:**

```twig
<a href="/privacy-policy" class="link">Privacy Policy</a>
<a href="/privacy-policy#cookies" class="link">Cookie Policy</a>
```

❌ **DON'T:**

- Hide policy link
- Make it hard to find
- Use vague language

### 2. Be Transparent About Cookies

✅ **DO:**

- Clearly explain what each cookie type is for
- Be honest about "necessary" vs "marketing"
- Allow easy preferences management

❌ **DON'T:**

- Mark marketing cookies as "necessary"
- Hide consent preferences
- Use dark patterns (like making "Accept All" huge)

### 3. Respect User Choices

✅ **DO:**

- Store preferences for 365 days
- Allow easy preference changes
- Don't re-ask if already decided

❌ **DON'T:**

- Reset preferences on each page
- Ignore user's "Reject" choice
- Load scripts before getting consent

### 4. Load Scripts Conditionally

✅ **DO:**

```javascript
if (hasConsent("analytics")) {
  loadAnalytics();
}
```

❌ **DON'T:**

```javascript
// Always load, ask later
loadAnalytics(); // 🚫 GDPR violation
```

### 5. Default to "Deny"

✅ **DO:**

```javascript
// Only load with explicit consent
if (hasConsent("marketing")) {
  loadPixel();
}
```

❌ **DON'T:**

```javascript
// Pre-tick checkboxes
<input type="checkbox" checked /> // 🚫
```

### 6. Make Preferences Easy to Change

✅ **DO:**

- Provide "Customize" option prominently
- Keep preferences saved persistently
- Allow changes from footer

❌ **DON'T:**

- Hide preferences in obscure settings
- Delete preferences after short time
- Force "customize" to require scrolling

### 7. Use Accessible Markup

✅ **DO:**

```html
<button aria-label="Close preferences" data-close>×</button>
<input type="checkbox" aria-label="Allow analytics cookies" />
```

❌ **DON'T:**

```html
<div class="close-btn"></div>
<!-- Not accessible -->
<input type="checkbox" />
<!-- No label -->
```

### 8. Clear Consent on Logout

```javascript
// When user logs out, optionally clear consent
function handleLogout() {
  // If you want to require re-consent per session:
  // clearConsentData();

  // But usually keep preferences across sessions
  logoutUser();
}
```

---

## Testing & Verification

### 1. Test on First Visit

```javascript
// Open DevTools Console
localStorage.clear();
location.reload();
```

Banner should appear.

### 2. Test Preference Saving

```javascript
// Check stored preferences
JSON.parse(localStorage.getItem("cookie-consent"));
```

Should show choices like:

```json
{
  "necessary": true,
  "analytics": true,
  "marketing": false,
  "preferences": false
}
```

### 3. Test Script Loading

```javascript
// Verify GA is loaded only with consent
hasConsent("analytics"); // true or false
typeof gtag; // 'function' if loaded
```

### 4. Test Modal Interactions

- Click "Customize" → Modal should open
- Click overlay → Modal should close
- Check/uncheck boxes → Should update
- Click "Save" → Modal closes, banner disappears
- Refresh page → Banner should NOT reappear

### 5. Verify GDPR Compliance

Use browser DevTools to verify:

✅ No tracking cookies set on first visit  
✅ Consent banner visible without dismissing  
✅ All cookie types explained clearly  
✅ Links to privacy policy present  
✅ User can change preferences later  
✅ Preferences persist across sessions  
✅ "Necessary" marked as always-on

---

## Troubleshooting

### Banner Not Appearing

```javascript
// Check if localStorage has preference
localStorage.getItem("cookie-consent"); // Should be null on first visit

// Check if component is in DOM
document.getElementById("cookie-consent-banner"); // Should exist

// Check if JavaScript initialized
window.__cookieConsentInit; // Should be true
```

### Preferences Not Saving

```javascript
// Verify localStorage works
localStorage.setItem("test", "value");
console.log(localStorage.getItem("test")); // Should print 'value'

// Check for localStorage quota exceeded
try {
  localStorage.setItem("test", "x".repeat(5242880)); // 5MB
} catch (e) {
  console.error("Storage full:", e);
}
```

### Scripts Loading When They Shouldn't

```javascript
import { hasConsent } from "./utils/cookies.js";

// Debug what's conse
nted;
console.log("Analytics consented:", hasConsent("analytics"));
console.log("Marketing consented:", hasConsent("marketing"));

// Check preferences object
const prefs = JSON.parse(localStorage.getItem("cookie-consent"));
console.log("Full preferences:", prefs);
```

---

## Files & Locations

| File                                       | Purpose                     |
| ------------------------------------------ | --------------------------- |
| `scripts/utils/cookies.js`                 | Cookie management utilities |
| `scripts/modules/cookie-consent.js`        | Main consent module         |
| `templates/components/cookie-consent.twig` | Banner & modal HTML         |
| `css/inc/components/cookie-consent.scss`   | Styling                     |
| `templates/base.twig`                      | Component inclusion         |
| `scripts/index.js`                         | Module initialization       |
| `style.scss`                               | Stylesheet import           |

---

## Resources & References

- [GDPR Cookie Requirements](https://gdpr.eu/cookies/)
- [ICO Cookie Guidance (UK)](https://ico.org.uk/for-organisations/guide-to-data-protection/guide-to-the-general-data-protection-regulation-gdpr/)
- [ePrivacy Directive](https://ec.europa.eu/digital-single-market/en/news/proposal-update-eu-privacy-rules-cookies-and-other-tracking)
- [Google Analytics 4 Consent Mode](https://support.google.com/analytics/answer/9976101)
- [Web Content Accessibility Guidelines (WCAG 2.1)](https://www.w3.org/WAI/WCAG21/quickref/)

---

## Support

For help with cookie consent configuration, see:

1. [QUICK-REFERENCE.md](./QUICK-REFERENCE.md) - Quick commands
2. [JAVASCRIPT-GUIDE.md](./JAVASCRIPT-GUIDE.md) - JS module patterns
3. [BEM-GUIDE.md](./BEM-GUIDE.md) - CSS naming conventions
4. [DEVELOPER-GUIDE.md](./DEVELOPER-GUIDE.md) - Overall architecture
