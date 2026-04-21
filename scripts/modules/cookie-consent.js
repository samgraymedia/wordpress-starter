/**
 * Cookie Consent Module
 *
 * Manages GDPR cookie consent banner display, user preferences, and tracking script integration.
 *
 * @module modules/cookie-consent
 */

import {
  querySelector,
  querySelectorAll,
  addClass,
  removeClass,
  hasClass,
} from "../utils/dom.js";
import {
  getCookie,
  getConsentPreferences,
  setConsentPreferences,
  hasConsent,
} from "../utils/cookies.js";
import Logger from "../utils/logger.js";

const CONSENT_BANNER_ID = "cookie-consent-banner";
const PREFERENCES_MODAL_ID = "cookie-preferences-modal";
const CONSENT_STORAGE_KEY = "cookie-consent";

/**
 * Initialize cookie consent module
 */
export function initCookieConsent() {
  Logger.debug("[Cookie Consent] Initializing...");

  const banner = querySelector(`#${CONSENT_BANNER_ID}`);

  if (!banner) {
    Logger.debug("[Cookie Consent] Banner not found in DOM");
    return; // Banner not in DOM
  }

  // Check if user has already made a choice
  const existingPrefs = getConsentPreferences();
  if (existingPrefs) {
    Logger.debug("[Cookie Consent] User preferences already set", {
      preferences: existingPrefs,
    });
    banner.style.display = "none";
    return;
  }

  // Show banner
  Logger.debug("[Cookie Consent] Showing banner for first time");
  banner.style.display = "block";

  // Setup event listeners
  setupEventListeners();
}

/**
 * Setup event listeners for consent buttons
 */
function setupEventListeners() {
  // Accept all
  const acceptAllBtn = querySelector("[data-accept-all]");
  if (acceptAllBtn) {
    acceptAllBtn.addEventListener("click", handleAcceptAll);
  }

  // Reject all
  const rejectAllBtn = querySelector("[data-reject-all]");
  if (rejectAllBtn) {
    rejectAllBtn.addEventListener("click", handleRejectAll);
  }

  // Customize
  const customizeBtn = querySelector("[data-customize-consent]");
  if (customizeBtn) {
    customizeBtn.addEventListener("click", openPreferencesModal);
  }

  // Preferences modal save
  const savePrefsBtn = querySelector("[data-save-preferences]");
  if (savePrefsBtn) {
    savePrefsBtn.addEventListener("click", handleSavePreferences);
  }

  // Preferences modal close buttons (overlay + close button)
  const closePrefsBtn = querySelectorAll("[data-close-preferences]");
  if (closePrefsBtn) {
    closePrefsBtn.forEach((btn) => {
      btn.addEventListener("click", closePreferencesModal);
    });
  }

  // Load previously saved preferences into checkboxes
  const prefs = getConsentPreferences();
  if (prefs) {
    const analyticsCheckbox = querySelector("[data-pref-analytics]");
    const marketingCheckbox = querySelector("[data-pref-marketing]");
    const preferencesCheckbox = querySelector("[data-pref-preferences]");

    if (analyticsCheckbox) analyticsCheckbox.checked = prefs.analytics || false;
    if (marketingCheckbox) marketingCheckbox.checked = prefs.marketing || false;
    if (preferencesCheckbox)
      preferencesCheckbox.checked = prefs.preferences || false;
  }
}

/**
 * Handle accept all button click
 */
function handleAcceptAll() {
  Logger.info("[Cookie Consent] User accepted all cookies");

  const preferences = {
    necessary: true,
    analytics: true,
    marketing: true,
    preferences: true,
  };

  setConsentPreferences(preferences);
  closeBanner();
  loadOptionalScripts(preferences);
}

/**
 * Handle reject all button click
 */
function handleRejectAll() {
  Logger.info("[Cookie Consent] User rejected non-essential cookies");

  const preferences = {
    necessary: true, // Always required
    analytics: false,
    marketing: false,
    preferences: false,
  };

  setConsentPreferences(preferences);
  closeBanner();
  loadOptionalScripts(preferences);
}

/**
 * Handle save preferences from modal
 */
function handleSavePreferences() {
  const analyticsCheckbox = querySelector("[data-pref-analytics]");
  const marketingCheckbox = querySelector("[data-pref-marketing]");
  const preferencesCheckbox = querySelector("[data-pref-preferences]");

  const preferences = {
    necessary: true, // Always on
    analytics: analyticsCheckbox ? analyticsCheckbox.checked : false,
    marketing: marketingCheckbox ? marketingCheckbox.checked : false,
    preferences: preferencesCheckbox ? preferencesCheckbox.checked : false,
  };

  setConsentPreferences(preferences);
  closePreferencesModal();
  closeBanner();
  loadOptionalScripts(preferences);
}

/**
 * Open preferences modal
 */
function openPreferencesModal() {
  const modal = querySelector(`#${PREFERENCES_MODAL_ID}`);
  if (modal) {
    addClass(modal, "active");
    // Disable body scroll
    document.body.style.overflow = "hidden";
  }
}

/**
 * Close preferences modal
 */
function closePreferencesModal() {
  const modal = querySelector(`#${PREFERENCES_MODAL_ID}`);
  if (modal) {
    removeClass(modal, "active");
    // Restore body scroll
    document.body.style.overflow = "";
  }
}

/**
 * Close consent banner
 */
function closeBanner() {
  const banner = querySelector(`#${CONSENT_BANNER_ID}`);
  if (banner) {
    addClass(banner, "closing");
    setTimeout(() => {
      banner.style.display = "none";
    }, 300);
  }
}

/**
 * Load optional tracking scripts based on consent
 * @param {Object} preferences - Consent preferences
 */
function loadOptionalScripts(preferences) {
  // Google Analytics example
  if (preferences.analytics && window.gtag) {
    console.log("Analytics consent granted");
    // Enable GA tracking
    gtag("consent", "update", {
      analytics_storage: "granted",
    });
  }

  // Marketing/Ads
  if (preferences.marketing && window.gtag) {
    console.log("Marketing consent granted");
    gtag("consent", "update", {
      ad_storage: "granted",
    });
  }

  // Custom preferences (e.g., personalization)
  if (preferences.preferences && window.gtag) {
    console.log("Preferences consent granted");
    gtag("consent", "update", {
      personalization_storage: "granted",
    });
  }

  // Trigger custom event for plugins/external scripts
  document.dispatchEvent(
    new CustomEvent("cookieConsent", { detail: preferences }),
  );
}

/**
 * Track analytics event with consent check
 * @param {string} eventName - Event name
 * @param {Object} eventData - Event data
 */
export function trackEvent(eventName, eventData = {}) {
  if (!hasConsent("analytics")) {
    return false;
  }

  if (window.gtag) {
    gtag("event", eventName, eventData);
    return true;
  }

  return false;
}

/**
 * Get current consent preferences
 * @returns {Object} - Current preferences or null
 */
export function getCurrentConsent() {
  return getConsentPreferences();
}
