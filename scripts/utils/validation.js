/**
 * Form Validation Utilities
 *
 * Reusable validation helpers for forms and form blocks
 * Provides standardized validation patterns and error messages
 */

/**
 * Validate email address format
 * @param {string} email - Email address to validate
 * @returns {object} { isValid: boolean, error: string|null }
 */
export const validateEmail = (email) => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (!email || email.trim() === "") {
    return { isValid: false, error: "Email is required" };
  }

  if (!emailRegex.test(email)) {
    return { isValid: false, error: "Please enter a valid email address" };
  }

  return { isValid: true, error: null };
};

/**
 * Validate phone number format (basic: digits, spaces, dashes, parentheses)
 * @param {string} phone - Phone number to validate
 * @returns {object} { isValid: boolean, error: string|null }
 */
export const validatePhone = (phone) => {
  const phoneRegex = /^[\d\s\-\+\(\)]{7,}$/;

  if (!phone || phone.trim() === "") {
    return { isValid: false, error: "Phone number is required" };
  }

  if (!phoneRegex.test(phone)) {
    return { isValid: false, error: "Please enter a valid phone number" };
  }

  return { isValid: true, error: null };
};

/**
 * Validate required field (non-empty string)
 * @param {string} value - Value to validate
 * @param {string} fieldName - Name of field for error message
 * @returns {object} { isValid: boolean, error: string|null }
 */
export const validateRequired = (value, fieldName = "This field") => {
  if (value === null || value === undefined || value.toString().trim() === "") {
    return { isValid: false, error: `${fieldName} is required` };
  }

  return { isValid: true, error: null };
};

/**
 * Validate value against a regex pattern
 * @param {string} value - Value to validate
 * @param {RegExp} pattern - Regex pattern to test against
 * @param {string} errorMessage - Custom error message
 * @returns {object} { isValid: boolean, error: string|null }
 */
export const validatePattern = (
  value,
  pattern,
  errorMessage = "Invalid format",
) => {
  if (!value || value.trim() === "") {
    return { isValid: false, error: "This field is required" };
  }

  if (!pattern.test(value)) {
    return { isValid: false, error: errorMessage };
  }

  return { isValid: true, error: null };
};

/**
 * Validate minimum string length
 * @param {string} value - Value to validate
 * @param {number} minLength - Minimum required length
 * @param {string} fieldName - Name of field for error message
 * @returns {object} { isValid: boolean, error: string|null }
 */
export const validateMinLength = (
  value,
  minLength,
  fieldName = "This field",
) => {
  if (!value) {
    return { isValid: false, error: `${fieldName} is required` };
  }

  if (value.length < minLength) {
    return {
      isValid: false,
      error: `${fieldName} must be at least ${minLength} character${
        minLength !== 1 ? "s" : ""
      }`,
    };
  }

  return { isValid: true, error: null };
};

/**
 * Validate maximum string length
 * @param {string} value - Value to validate
 * @param {number} maxLength - Maximum allowed length
 * @param {string} fieldName - Name of field for error message
 * @returns {object} { isValid: boolean, error: string|null }
 */
export const validateMaxLength = (
  value,
  maxLength,
  fieldName = "This field",
) => {
  if (!value) {
    return { isValid: true, error: null };
  }

  if (value.length > maxLength) {
    return {
      isValid: false,
      error: `${fieldName} must not exceed ${maxLength} character${
        maxLength !== 1 ? "s" : ""
      }`,
    };
  }

  return { isValid: true, error: null };
};

/**
 * Validate two values match (password confirmation, etc.)
 * @param {string} value1 - First value
 * @param {string} value2 - Second value
 * @param {string} fieldName - Name of field for error message
 * @returns {object} { isValid: boolean, error: string|null }
 */
export const validateMatch = (value1, value2, fieldName = "Values") => {
  if (value1 !== value2) {
    return { isValid: false, error: `${fieldName} do not match` };
  }

  return { isValid: true, error: null };
};

/**
 * Validate URL format
 * @param {string} url - URL to validate
 * @returns {object} { isValid: boolean, error: string|null }
 */
export const validateUrl = (url) => {
  try {
    new URL(url);
    return { isValid: true, error: null };
  } catch {
    return { isValid: false, error: "Please enter a valid URL" };
  }
};

/**
 * Validate integer value
 * @param {any} value - Value to validate
 * @param {number} min - Minimum allowed value (optional)
 * @param {number} max - Maximum allowed value (optional)
 * @returns {object} { isValid: boolean, error: string|null }
 */
export const validateInteger = (value, min = null, max = null) => {
  const intValue = parseInt(value, 10);

  if (isNaN(intValue)) {
    return { isValid: false, error: "Please enter a valid number" };
  }

  if (min !== null && intValue < min) {
    return { isValid: false, error: `Value must be at least ${min}` };
  }

  if (max !== null && intValue > max) {
    return { isValid: false, error: `Value must not exceed ${max}` };
  }

  return { isValid: true, error: null };
};

/**
 * Validate checkbox is checked
 * @param {boolean} isChecked - Checkbox state
 * @param {string} fieldName - Name of field for error message
 * @returns {object} { isValid: boolean, error: string|null }
 */
export const validateCheckbox = (isChecked, fieldName = "This field") => {
  if (!isChecked) {
    return { isValid: false, error: `${fieldName} must be checked` };
  }

  return { isValid: true, error: null };
};

/**
 * Validate credit card number (Luhn algorithm)
 * @param {string} cardNumber - Credit card number
 * @returns {object} { isValid: boolean, error: string|null }
 */
export const validateCreditCard = (cardNumber) => {
  const cleaned = cardNumber.replace(/\D/g, "");

  if (cleaned.length < 13 || cleaned.length > 19) {
    return { isValid: false, error: "Invalid card number" };
  }

  // Luhn algorithm
  let sum = 0;
  let isEven = false;

  for (let i = cleaned.length - 1; i >= 0; i--) {
    let digit = parseInt(cleaned.charAt(i), 10);

    if (isEven) {
      digit *= 2;
      if (digit > 9) {
        digit -= 9;
      }
    }

    sum += digit;
    isEven = !isEven;
  }

  if (sum % 10 !== 0) {
    return { isValid: false, error: "Invalid card number" };
  }

  return { isValid: true, error: null };
};

/**
 * Validate file size
 * @param {File} file - File object
 * @param {number} maxSizeMB - Maximum size in megabytes
 * @returns {object} { isValid: boolean, error: string|null }
 */
export const validateFileSize = (file, maxSizeMB) => {
  if (!file) {
    return { isValid: false, error: "File is required" };
  }

  const maxSizeBytes = maxSizeMB * 1024 * 1024;

  if (file.size > maxSizeBytes) {
    return {
      isValid: false,
      error: `File size must not exceed ${maxSizeMB}MB`,
    };
  }

  return { isValid: true, error: null };
};

/**
 * Validate file type
 * @param {File} file - File object
 * @param {array} allowedTypes - Array of allowed MIME types
 * @returns {object} { isValid: boolean, error: string|null }
 */
export const validateFileType = (file, allowedTypes) => {
  if (!file) {
    return { isValid: false, error: "File is required" };
  }

  if (!allowedTypes.includes(file.type)) {
    return {
      isValid: false,
      error: `File type not allowed. Accepted types: ${allowedTypes.join(
        ", ",
      )}`,
    };
  }

  return { isValid: true, error: null };
};

/**
 * Run multiple validators on a single value
 * @param {string} value - Value to validate
 * @param {array} validators - Array of validator functions
 * @returns {object} { isValid: boolean, errors: array }
 */
export const validateMultiple = (value, validators) => {
  const errors = [];

  validators.forEach((validator) => {
    const result = validator(value);
    if (!result.isValid) {
      errors.push(result.error);
    }
  });

  return {
    isValid: errors.length === 0,
    errors: errors,
  };
};

/**
 * Validate entire form object
 * @param {object} formData - Form data object with field names as keys
 * @param {object} rules - Validation rules with field names as keys and validators as values
 * @returns {object} { isValid: boolean, fieldErrors: object }
 */
export const validateForm = (formData, rules) => {
  const fieldErrors = {};
  let isValid = true;

  Object.keys(rules).forEach((fieldName) => {
    const fieldValue = formData[fieldName];
    const fieldRules = rules[fieldName];

    // If rules is a function, call it
    if (typeof fieldRules === "function") {
      const result = fieldRules(fieldValue);
      if (!result.isValid) {
        fieldErrors[fieldName] = result.error;
        isValid = false;
      }
    }
    // If rules is an array of validators
    else if (Array.isArray(fieldRules)) {
      const errors = [];
      fieldRules.forEach((validator) => {
        const result = validator(fieldValue);
        if (!result.isValid) {
          errors.push(result.error);
        }
      });

      if (errors.length > 0) {
        fieldErrors[fieldName] = errors[0]; // Return first error
        isValid = false;
      }
    }
  });

  return { isValid, fieldErrors };
};

export default {
  validateEmail,
  validatePhone,
  validateRequired,
  validatePattern,
  validateMinLength,
  validateMaxLength,
  validateMatch,
  validateUrl,
  validateInteger,
  validateCheckbox,
  validateCreditCard,
  validateFileSize,
  validateFileType,
  validateMultiple,
  validateForm,
};
