/**
 * Forms Module
 *
 * Handles form validation, submission, and error handling.
 * Works with WordPress forms and Contact Form 7.
 *
 * @module modules/forms
 */

import {
  querySelector,
  querySelectorAll,
  addClass,
  removeClass,
  setText,
} from "../utils/dom.js";

/**
 * Initialize form functionality
 */
export function initForms() {
  console.log("[Forms] Initializing...");

  // Handle Contact Form 7 if present
  if (typeof wpcf7 !== "undefined") {
    setupContactForm7();
  }

  // Setup general form validation
  setupFormValidation();
}

/**
 * Setup Contact Form 7 specific handlers
 */
function setupContactForm7() {
  document.addEventListener("wpcf7mailsent", (e) => {
    console.log("[Forms] CF7 form submitted successfully");
    // Handle successful submission
  });

  document.addEventListener("wpcf7mailfailed", (e) => {
    console.log("[Forms] CF7 form submission failed");
    // Handle failed submission
  });

  document.addEventListener("wpcf7invalid", (e) => {
    console.log("[Forms] CF7 form has invalid fields");
    // Handle validation errors
  });
}

/**
 * Setup HTML5 form validation
 */
function setupFormValidation() {
  querySelectorAll("form").forEach((form) => {
    form.addEventListener("submit", handleFormSubmit);
  });

  // Real-time validation
  querySelectorAll(
    "input[required], textarea[required], select[required]",
  ).forEach((field) => {
    field.addEventListener("blur", validateField);
    field.addEventListener("input", validateField);
  });
}

/**
 * Handle form submission
 * @param {Event} e
 */
function handleFormSubmit(e) {
  const form = e.target;

  // Check HTML5 validation
  if (!form.checkValidity()) {
    e.preventDefault();
    validateForm(form);
    return;
  }

  // Show loading state
  const submitBtn = querySelector('[type="submit"]', form);
  if (submitBtn) {
    submitBtn.disabled = true;
    submitBtn.dataset.originalText = submitBtn.textContent;
    submitBtn.textContent = "Submitting...";
  }
}

/**
 * Validate a single field
 * @param {Event} e
 */
function validateField(e) {
  const field = e.target;

  if (!field.checkValidity()) {
    addClass(field, "is-invalid");
    showFieldError(field);
  } else {
    removeClass(field, "is-invalid");
    clearFieldError(field);
  }
}

/**
 * Validate entire form
 * @param {Element} form
 * @returns {boolean}
 */
function validateForm(form) {
  let isValid = true;

  querySelectorAll("input, textarea, select", form).forEach((field) => {
    if (!field.checkValidity()) {
      addClass(field, "is-invalid");
      showFieldError(field);
      isValid = false;
    } else {
      removeClass(field, "is-invalid");
      clearFieldError(field);
    }
  });

  return isValid;
}

/**
 * Show field error message
 * @param {Element} field
 */
function showFieldError(field) {
  let error = querySelector(".field-error", field.parentElement);

  if (!error) {
    error = document.createElement("div");
    error.className = "field-error";
    field.parentElement.appendChild(error);
  }

  // Get validation message
  let message = field.validationMessage;

  // Custom messages
  if (field.type === "email" && field.validity.typeMismatch) {
    message = "Please enter a valid email address";
  } else if (field.validity.valueMissing) {
    message = `${field.name || "This field"} is required`;
  } else if (field.validity.patternMismatch) {
    message = field.title || "Please enter a valid value";
  }

  setText(error, message);
}

/**
 * Clear field error message
 * @param {Element} field
 */
function clearFieldError(field) {
  const error = querySelector(".field-error", field.parentElement);
  if (error) {
    error.remove();
  }
}

/**
 * Reset form and clear errors
 * @param {Element|string} form - Form element or selector
 */
export function resetForm(form) {
  if (typeof form === "string") {
    form = querySelector(form);
  }

  if (!form) return;

  form.reset();

  // Clear error states
  querySelectorAll(".is-invalid", form).forEach((field) => {
    removeClass(field, "is-invalid");
  });

  querySelectorAll(".field-error", form).forEach((error) => {
    error.remove();
  });

  // Reset submit button
  const submitBtn = querySelector('[type="submit"]', form);
  if (submitBtn && submitBtn.dataset.originalText) {
    submitBtn.disabled = false;
    submitBtn.textContent = submitBtn.dataset.originalText;
  }
}

/**
 * Disable form submission temporarily
 * @param {Element} form
 * @param {boolean} disabled
 */
export function setFormDisabled(form, disabled = true) {
  if (disabled) {
    addClass(form, "is-disabled");
    querySelectorAll("input, textarea, select, button", form).forEach(
      (field) => {
        field.disabled = true;
      },
    );
  } else {
    removeClass(form, "is-disabled");
    querySelectorAll("input, textarea, select, button", form).forEach(
      (field) => {
        field.disabled = false;
      },
    );
  }
}
