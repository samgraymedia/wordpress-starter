/**
 * Checkout Module
 *
 * Handles WooCommerce checkout functionality including:
 * - Guest vs account login
 * - Account creation with password validation
 * - Address validation
 * - Shipping method selection
 * - Payment gateway integration
 *
 * @module modules/checkout
 */

import {
  querySelector,
  querySelectorAll,
  addClass,
  removeClass,
} from "../utils/dom.js";
import Logger from "../utils/logger.js";

/**
 * Initialize checkout functionality
 */
export function initCheckout() {
  if (typeof wc === "undefined") {
    Logger.debug("[Checkout] WooCommerce not active");
    return;
  }

  Logger.debug("[Checkout] Initializing...");

  // Setup login/guest tabs
  setupLoginTabs();

  // Setup account creation
  setupAccountCreation();

  // Setup address validation
  setupAddressValidation();

  // Setup shipping method handling
  setupShippingMethods();

  // Setup payment method handling
  setupPaymentMethods();

  // Setup form submission
  setupCheckoutForm();
}

/**
 * Setup login/guest checkout tabs
 */
function setupLoginTabs() {
  const tabs = querySelectorAll(".checkout-page__login-tab");
  if (tabs.length === 0) return;

  tabs.forEach((tab) => {
    tab.addEventListener("click", handleTabClick);
  });
}

/**
 * Handle tab click for login/guest
 * @param {Event} e
 */
function handleTabClick(e) {
  const tab = e.currentTarget;
  const tabName = tab.dataset.tab;

  // Update active tab
  querySelectorAll(".checkout-page__login-tab").forEach((t) => {
    removeClass(t, "checkout-page__login-tab--active");
  });
  addClass(tab, "checkout-page__login-tab--active");

  // Update active content
  querySelectorAll(".checkout-page__login-tab-content").forEach((content) => {
    removeClass(content, "checkout-page__login-tab-content--active");
  });

  const activeContent = querySelector(`#${tabName}-tab`);
  if (activeContent) {
    addClass(activeContent, "checkout-page__login-tab-content--active");
  }

  // Handle login form submission
  if (tabName === "login") {
    const loginBtn = querySelector("#checkout-login-btn");
    if (loginBtn) {
      loginBtn.addEventListener("click", handleCheckoutLogin);
    }
  }
}

/**
 * Handle checkout login for existing customers
 * @param {Event} e
 */
async function handleCheckoutLogin(e) {
  e.preventDefault();

  const username = querySelector("#login_username")?.value || "";
  const password = querySelector("#login_password")?.value || "";

  if (!username || !password) {
    alert("Please enter both username and password");
    return;
  }

  const btn = e.currentTarget;
  btn.disabled = true;
  const originalText = btn.textContent;
  btn.textContent = "Signing in...";

  try {
    const formData = new FormData();
    formData.append("action", "checkout_login");
    formData.append("username", username);
    formData.append("password", password);
    formData.append(
      "nonce",
      document.querySelector('input[name="_wpnonce"]')?.value || "",
    );

    const response = await fetch(window.ajaxurl || "/wp-admin/admin-ajax.php", {
      method: "POST",
      body: formData,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    });

    const data = await response.json();

    if (data.success) {
      // Reload page to show logged-in user checkout
      window.location.reload();
    } else {
      alert(data.data?.message || "Login failed. Please try again.");
      btn.disabled = false;
      btn.textContent = originalText;
    }
  } catch (error) {
    Logger.error("[Checkout] Login error", error);
    alert("An error occurred. Please try again.");
    btn.disabled = false;
    btn.textContent = originalText;
  }
}

/**
 * Setup account creation checkbox and password fields
 */
function setupAccountCreation() {
  const checkbox = querySelector("#create_account");
  if (!checkbox) return;

  checkbox.addEventListener("change", handleAccountCreationToggle);

  // Setup password strength indicator
  const passwordInput = querySelector("#account_password");
  if (passwordInput) {
    passwordInput.addEventListener("input", handlePasswordInput);
  }
}

/**
 * Handle create account checkbox toggle
 * @param {Event} e
 */
function handleAccountCreationToggle(e) {
  const isChecked = e.currentTarget.checked;
  const passwordFields = querySelector("#account-password-fields");

  if (passwordFields) {
    passwordFields.style.display = isChecked ? "block" : "none";

    // Update required attributes
    querySelectorAll("[data-required-if='create_account']").forEach((field) => {
      if (isChecked) {
        field.setAttribute("required", "required");
      } else {
        field.removeAttribute("required");
      }
    });
  }
}

/**
 * Handle password input - show strength indicator
 * @param {Event} e
 */
function handlePasswordInput(e) {
  const password = e.currentTarget.value;
  const indicator = querySelector("#password-strength");

  if (!indicator) return;

  let strength = "weak";
  let strengthText = "Weak";
  let strengthColor = "#dc3545";

  // Check password strength
  if (password.length >= 8) {
    // Contains uppercase, lowercase, numbers, special chars
    const hasUpperCase = /[A-Z]/.test(password);
    const hasLowerCase = /[a-z]/.test(password);
    const hasNumbers = /\d/.test(password);
    const hasSpecialChars = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(
      password,
    );

    const charTypeCount = [
      hasUpperCase,
      hasLowerCase,
      hasNumbers,
      hasSpecialChars,
    ].filter(Boolean).length;

    if (password.length >= 12 && charTypeCount >= 3) {
      strength = "strong";
      strengthText = "Strong";
      strengthColor = "#28a745";
    } else if (password.length >= 10 && charTypeCount >= 2) {
      strength = "medium";
      strengthText = "Medium";
      strengthColor = "#ffc107";
    } else {
      strength = "fair";
      strengthText = "Fair";
      strengthColor = "#fd7e14";
    }
  }

  indicator.textContent = strengthText;
  indicator.style.color = strengthColor;
  indicator.className = `checkout-page__password-indicator checkout-page__password-indicator--${strength}`;
}

/**
 * Setup address field validation
 */
function setupAddressValidation() {
  const addressFields = querySelectorAll(
    '[name*="address"], [name*="city"], [name*="state"], [name*="postcode"], [name*="country"]',
  );

  addressFields.forEach((field) => {
    field.addEventListener("blur", validateAddressField);
  });
}

/**
 * Validate address field
 * @param {Event} e
 */
function validateAddressField(e) {
  const field = e.currentTarget;
  const value = field.value.trim();
  const fieldName = field.name;

  // Remove previous error state
  removeClass(field, "checkout-page__form-input--error");

  let isValid = true;

  // Basic validation rules
  if (fieldName.includes("first_name") || fieldName.includes("last_name")) {
    isValid = value.length > 0;
  } else if (fieldName.includes("address_1")) {
    isValid = value.length > 4;
  } else if (fieldName.includes("city")) {
    isValid = value.length > 2;
  } else if (fieldName.includes("postcode")) {
    isValid = /^[A-Z0-9\s\-]{3,10}$/i.test(value);
  } else if (fieldName.includes("state")) {
    isValid = value.length > 1;
  }

  if (!isValid && field.hasAttribute("required")) {
    addClass(field, "checkout-page__form-input--error");
  }

  return isValid;
}

/**
 * Setup shipping method changes
 */
function setupShippingMethods() {
  const shippingMethods = querySelectorAll('input[name="shipping_method"]');

  shippingMethods.forEach((method) => {
    method.addEventListener("change", handleShippingMethodChange);
  });
}

/**
 * Handle shipping method change
 * @param {Event} e
 */
async function handleShippingMethodChange(e) {
  const method = e.currentTarget.value;

  Logger.debug(`[Checkout] Shipping method changed: ${method}`);

  try {
    // Update shipping totals via AJAX
    const formData = new FormData();
    formData.append("action", "update_shipping_method");
    formData.append("method", method);
    formData.append(
      "nonce",
      document.querySelector('input[name="_wpnonce"]')?.value || "",
    );

    const response = await fetch(window.ajaxurl || "/wp-admin/admin-ajax.php", {
      method: "POST",
      body: formData,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    });

    const data = await response.json();

    if (data.success) {
      // Update totals display
      updateCheckoutTotals(data.data);
    }
  } catch (error) {
    Logger.error("[Checkout] Error updating shipping", error);
  }
}

/**
 * Update checkout totals display
 * @param {Object} data - Updated totals data
 */
function updateCheckoutTotals(data) {
  if (data.shipping_total !== undefined) {
    const shippingValue = querySelector(".checkout-page__summary-value");
    if (shippingValue) {
      // This would need proper currency formatting from the server
      // For now, just log that it should be updated
      Logger.debug("[Checkout] Totals updated", data);
    }
  }
}

/**
 * Setup payment method selection
 */
function setupPaymentMethods() {
  const paymentMethods = querySelectorAll('input[name="payment_method"]');

  paymentMethods.forEach((method) => {
    method.addEventListener("change", handlePaymentMethodChange);
  });
}

/**
 * Handle payment method change
 * @param {Event} e
 */
function handlePaymentMethodChange(e) {
  const method = e.currentTarget.value;

  // Hide all payment method details
  querySelectorAll(".checkout-page__payment-method-details").forEach(
    (detail) => {
      detail.style.display = "none";
    },
  );

  // Show selected payment method details
  const detailsElement = querySelector(`#${method}_fields`);
  if (detailsElement) {
    detailsElement.style.display = "block";
  }

  Logger.debug(`[Checkout] Payment method changed: ${method}`);
}

/**
 * Setup checkout form submission
 */
function setupCheckoutForm() {
  const form =
    querySelector("form.checkout-page__form") || querySelector("form");
  if (!form) return;

  form.addEventListener("submit", handleCheckoutSubmission);
}

/**
 * Handle checkout form submission with validation
 * @param {Event} e
 */
async function handleCheckoutSubmission(e) {
  e.preventDefault();

  // Validate all required fields
  const isValid = validateCheckoutForm();

  if (!isValid) {
    alert("Please fill in all required fields correctly.");
    return;
  }

  // Check if passwords match for account creation
  if (querySelector("#create_account")?.checked) {
    const password = querySelector("#account_password")?.value || "";
    const confirmPassword =
      querySelector("#account_password_confirm")?.value || "";

    if (password !== confirmPassword) {
      alert("Passwords do not match.");
      return;
    }

    if (password.length < 8) {
      alert("Password must be at least 8 characters long.");
      return;
    }
  }

  // Collect checkout data
  const checkoutData = new FormData(e.currentTarget);

  // Submit order
  await submitCheckoutOrder(checkoutData);
}

/**
 * Validate entire checkout form
 * @returns {boolean}
 */
function validateCheckoutForm() {
  let isValid = true;

  // Validate billing fields
  const billingFields = querySelectorAll(
    'input[name^="billing_"], select[name^="billing_"]',
  );
  billingFields.forEach((field) => {
    if (field.hasAttribute("required") && !field.value.trim()) {
      addClass(field, "checkout-page__form-input--error");
      isValid = false;
    } else {
      removeClass(field, "checkout-page__form-input--error");
    }
  });

  // Validate terms accepted
  const termsCheckbox = querySelector("#terms_conditions");
  if (termsCheckbox && !termsCheckbox.checked) {
    addClass(termsCheckbox, "checkout-page__form-checkbox--error");
    isValid = false;
  } else if (termsCheckbox) {
    removeClass(termsCheckbox, "checkout-page__form-checkbox--error");
  }

  return isValid;
}

/**
 * Submit checkout order
 * @param {FormData} formData - Checkout form data
 */
async function submitCheckoutOrder(formData) {
  const submitBtn = querySelector("#place_order");
  if (!submitBtn) return;

  submitBtn.disabled = true;
  const originalText = submitBtn.textContent;
  submitBtn.textContent = "Processing...";

  try {
    formData.append("action", "process_checkout");
    formData.append(
      "nonce",
      document.querySelector('input[name="_wpnonce"]')?.value || "",
    );

    const response = await fetch(window.ajaxurl || "/wp-admin/admin-ajax.php", {
      method: "POST",
      body: formData,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
      },
    });

    const data = await response.json();

    if (data.success) {
      // Redirect to order confirmation or payment processor
      if (data.data?.redirect_url) {
        window.location.href = data.data.redirect_url;
      } else {
        window.location.href = wc.checkout_url || "/checkout";
      }
    } else {
      alert(
        data.data?.message ||
          "An error occurred during checkout. Please try again.",
      );
      submitBtn.disabled = false;
      submitBtn.textContent = originalText;
    }
  } catch (error) {
    Logger.error("[Checkout] Submission error", error);
    alert("An error occurred. Please try again.");
    submitBtn.disabled = false;
    submitBtn.textContent = originalText;
  }
}

/**
 * Show/hide shipping address fields based on checkbox
 */
function setupShippingAddressToggle() {
  const checkbox = querySelector("#ship_to_different_address");
  if (!checkbox) return;

  checkbox.addEventListener("change", (e) => {
    const fields = querySelector("#shipping_address_fields");
    if (fields) {
      fields.style.display = e.currentTarget.checked ? "block" : "none";
    }
  });
}

/**
 * Export for testing
 */
export { validateCheckoutForm, validateAddressField };
