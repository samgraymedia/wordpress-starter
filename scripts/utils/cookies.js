/**
 * Cookie Utilities
 *
 * Helper functions for reading, writing, and managing cookies and consent preferences.
 *
 * @module utils/cookies
 */

/**
 * Set a cookie
 * @param {string} name - Cookie name
 * @param {string} value - Cookie value
 * @param {number} days - Days until expiration (default: 365)
 * @param {Object} options - Additional options (path, domain, secure)
 */
export function setCookie(name, value, days = 365, options = {}) {
  let cookieString = `${encodeURIComponent(name)}=${encodeURIComponent(value)}`;

  if (days) {
    const date = new Date();
    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
    cookieString += `; expires=${date.toUTCString()}`;
  }

  cookieString += `; path=${options.path || "/"}`;

  if (options.domain) {
    cookieString += `; domain=${options.domain}`;
  }

  if (options.secure || location.protocol === "https:") {
    cookieString += "; secure";
  }

  cookieString += "; SameSite=Strict";

  document.cookie = cookieString;
}

/**
 * Get a cookie value
 * @param {string} name - Cookie name
 * @returns {string|null} - Cookie value or null if not found
 */
export function getCookie(name) {
  const nameEQ = `${encodeURIComponent(name)}=`;
  const cookies = document.cookie.split(";");

  for (let i = 0; i < cookies.length; i++) {
    let cookie = cookies[i].trim();
    if (cookie.indexOf(nameEQ) === 0) {
      return decodeURIComponent(cookie.substring(nameEQ.length));
    }
  }

  return null;
}

/**
 * Delete a cookie
 * @param {string} name - Cookie name
 */
export function deleteCookie(name) {
  setCookie(name, "", -1);
}

/**
 * Get consent preferences from localStorage
 * @returns {Object} - Consent object with cookie categories
 */
export function getConsentPreferences() {
  const stored = localStorage.getItem("cookie-consent");

  if (!stored) {
    return null;
  }

  try {
    return JSON.parse(stored);
  } catch (e) {
    console.error("Failed to parse consent preferences:", e);
    return null;
  }
}

/**
 * Save consent preferences to localStorage
 * @param {Object} preferences - Consent preferences { necessary, analytics, marketing, preferences }
 */
export function setConsentPreferences(preferences) {
  localStorage.setItem("cookie-consent", JSON.stringify(preferences));
}

/**
 * Check if user has consented to a specific category
 * @param {string} category - Category name (necessary, analytics, marketing, preferences)
 * @returns {boolean} - Whether category is consented to
 */
export function hasConsent(category) {
  const prefs = getConsentPreferences();

  if (!prefs) {
    return false;
  }

  return prefs[category] === true;
}

/**
 * Clear all consent data
 */
export function clearConsentData() {
  localStorage.removeItem("cookie-consent");
  deleteCookie("cookie-consent");
}
