/**
 * HTTP/AJAX Utility Functions
 *
 * Functions for making AJAX requests and API calls.
 * Integrates with WordPress admin-ajax.php and wp_create_nonce().
 *
 * @module utils/http
 */

/**
 * Make AJAX request to WordPress admin-ajax.php
 * Requires themeConfig.nonce to be set in page via PHP
 *
 * @example
 * // PHP in functions.php sets this:
 * wp_localize_script('starter-theme-js', 'themeConfig', [
 *     'ajaxUrl' => admin_url('admin-ajax.php'),
 *     'nonce' => wp_create_nonce('theme-nonce')
 * ]);
 *
 * // Then use in JS:
 * ajax('my_action', { data: 'value' }).then(response => {
 *     console.log(response);
 * });
 *
 * @param {string} action - WordPress AJAX action name
 * @param {Object} data - Data to send (automatic merge with nonce)
 * @param {Object} options - Fetch options
 * @returns {Promise<Object>}
 */
export async function ajax(action, data = {}, options = {}) {
  // Get AJAX URL and nonce from global config (set by PHP)
  const ajaxUrl = window.themeConfig?.ajaxUrl || "/wp-admin/admin-ajax.php";
  const nonce = window.themeConfig?.nonce || null;

  // Prepare form data
  const formData = new FormData();
  formData.append("action", action);

  if (nonce) {
    formData.append("nonce", nonce);
  }

  // Add custom data
  Object.entries(data).forEach(([key, value]) => {
    if (typeof value === "object") {
      formData.append(key, JSON.stringify(value));
    } else {
      formData.append(key, value);
    }
  });

  try {
    const response = await fetch(ajaxUrl, {
      method: "POST",
      body: formData,
      credentials: "same-origin",
      ...options,
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const json = await response.json();

    if (!json.success) {
      throw new Error(json.data?.message || "Request failed");
    }

    return json.data || json;
  } catch (error) {
    console.error("[AJAX Error]", action, error);
    throw error;
  }
}

/**
 * Make standard fetch request with CSRF token
 *
 * @param {string} url - Request URL
 * @param {Object} options - Fetch options
 * @returns {Promise<Object>}
 */
export async function request(url, options = {}) {
  try {
    const response = await fetch(url, {
      credentials: "same-origin",
      headers: {
        "Content-Type": options.body ? "application/json" : "text/plain",
        ...options.headers,
      },
      ...options,
    });

    if (!response.ok) {
      throw new Error(`HTTP ${response.status}`);
    }

    // Try to parse as JSON, fallback to text
    const contentType = response.headers.get("content-type");
    if (contentType?.includes("application/json")) {
      return await response.json();
    }
    return await response.text();
  } catch (error) {
    console.error("[Request Error]", url, error);
    throw error;
  }
}

/**
 * GET request
 *
 * @param {string} url - Request URL
 * @returns {Promise<Object>}
 */
export async function get(url) {
  return request(url, {
    method: "GET",
  });
}

/**
 * POST request with JSON body
 *
 * @param {string} url - Request URL
 * @param {Object} data - Request data
 * @returns {Promise<Object>}
 */
export async function post(url, data = {}) {
  return request(url, {
    method: "POST",
    body: JSON.stringify(data),
  });
}

/**
 * PUT request with JSON body
 *
 * @param {string} url - Request URL
 * @param {Object} data - Request data
 * @returns {Promise<Object>}
 */
export async function put(url, data = {}) {
  return request(url, {
    method: "PUT",
    body: JSON.stringify(data),
  });
}

/**
 * DELETE request
 *
 * @param {string} url - Request URL
 * @returns {Promise<Object>}
 */
export async function del(url) {
  return request(url, {
    method: "DELETE",
  });
}

/**
 * Check if online
 *
 * @returns {Promise<boolean>}
 */
export async function isOnline() {
  try {
    const response = await fetch("/", {
      method: "HEAD",
      mode: "no-cors",
    });
    return response.ok || response.type === "opaque";
  } catch (error) {
    return false;
  }
}
