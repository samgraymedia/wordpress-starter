/**
 * Theme Toggle Module
 * Manages light/dark mode switching with localStorage persistence
 * and system preference detection
 */

const STORAGE_KEY = "theme-preference";
const ATTR_NAME = "data-theme";

/**
 * Get current theme from DOM
 * @returns {string} 'light', 'dark', or 'system'
 */
function getCurrent() {
  const theme = document.documentElement.getAttribute(ATTR_NAME);
  if (theme === "light" || theme === "dark") {
    return theme;
  }
  return "system";
}

/**
 * Get reflected preference (what's actually active)
 * Accounts for system preference when set to 'system'
 * @returns {string} 'light' or 'dark'
 */
function getReflected() {
  const current = getCurrent();

  if (current === "system") {
    return window.matchMedia("(prefers-color-scheme: dark)").matches
      ? "dark"
      : "light";
  }

  return current;
}

/**
 * Apply theme to DOM
 * @param {string} theme - 'light', 'dark', or 'system'
 * @param {boolean} persist - save to localStorage
 */
function setTheme(theme, persist = true) {
  if (theme === "system") {
    document.documentElement.removeAttribute(ATTR_NAME);
  } else {
    document.documentElement.setAttribute(ATTR_NAME, theme);
  }

  if (persist) {
    localStorage.setItem(STORAGE_KEY, theme);
  }

  // Dispatch custom event so other modules can react to theme change
  window.dispatchEvent(
    new CustomEvent("theme-change", {
      detail: { theme, reflected: getReflected() },
    }),
  );
}

/**
 * Toggle theme between light and dark
 * Respects current preference and cycles through options
 */
function toggle() {
  const current = getCurrent();

  let next;
  if (current === "light") {
    next = "dark";
  } else if (current === "dark") {
    next = "system";
  } else {
    next = "light";
  }

  setTheme(next);
}

/**
 * Initialize theme on page load
 * Reads from localStorage, then falls back to system preference
 */
function init() {
  // 1. Read stored preference from localStorage
  const stored = localStorage.getItem(STORAGE_KEY);

  // 2. Validate stored preference
  if (stored === "light" || stored === "dark" || stored === "system") {
    setTheme(stored, false);
  } else {
    // 3. Fall back to system preference (default behavior)
    setTheme("system", false);
  }

  // 4. Listen for system preference changes and update if currently on 'system'
  const darkModeQuery = window.matchMedia("(prefers-color-scheme: dark)");

  darkModeQuery.addListener(() => {
    if (getCurrent() === "system") {
      // Dispatch event even though we don't change the DOM attribute
      window.dispatchEvent(
        new CustomEvent("theme-change", {
          detail: { theme: "system", reflected: getReflected() },
        }),
      );
    }
  });

  // Modern API (Safari 13.1+, Chrome 88+, Firefox 78+)
  if (darkModeQuery.addEventListener) {
    darkModeQuery.addEventListener("change", () => {
      if (getCurrent() === "system") {
        window.dispatchEvent(
          new CustomEvent("theme-change", {
            detail: { theme: "system", reflected: getReflected() },
          }),
        );
      }
    });
  }
}

/**
 * Get WordPress theme setting (if using WordPress options)
 * Falls back to localStorage if no WordPress setting exists
 * @returns {Promise<string>} 'light', 'dark', or 'system'
 */
async function getWordPressPreference() {
  try {
    const response = await fetch("/wp-json/starter-theme/v1/theme-preference");
    if (response.ok) {
      const data = await response.json();
      return data.preference || getCurrent();
    }
  } catch (error) {
    console.debug("Dark mode: Could not fetch WordPress preference", error);
  }
  return getCurrent();
}

/**
 * Set WordPress theme setting (if user is logged in as admin)
 * Also updates localStorage
 * @param {string} theme - 'light', 'dark', or 'system'
 * @returns {Promise<boolean>} success
 */
async function setWordPressPreference(theme) {
  try {
    const response = await fetch("/wp-json/starter-theme/v1/theme-preference", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-WP-Nonce": window.themeToggleNonce || "",
      },
      body: JSON.stringify({ preference: theme }),
    });

    if (response.ok) {
      setTheme(theme, true);
      return true;
    }
  } catch (error) {
    console.debug("Dark mode: Could not save WordPress preference", error);
    // Fall back to localStorage
    setTheme(theme, true);
  }
  return false;
}

/**
 * Public API
 */
export default {
  init,
  toggle,
  setTheme,
  getCurrent,
  getReflected,
  getWordPressPreference,
  setWordPressPreference,
};
