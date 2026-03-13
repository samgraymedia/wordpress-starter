/**
 * DOM Utility Functions
 *
 * Helper functions for common DOM manipulation tasks.
 * Provides a lightweight abstraction over native DOM methods.
 *
 * @module utils/dom
 */

/**
 * Query one element
 * @param {string} selector - CSS selector
 * @param {Element} context - Parent element (default: document)
 * @returns {Element|null}
 */
export function querySelector(selector, context = document) {
  return context.querySelector(selector);
}

/**
 * Query multiple elements
 * @param {string} selector - CSS selector
 * @param {Element} context - Parent element (default: document)
 * @returns {NodeList}
 */
export function querySelectorAll(selector, context = document) {
  return context.querySelectorAll(selector);
}

/**
 * Get element by ID
 * @param {string} id - Element ID
 * @returns {Element|null}
 */
export function getElementById(id) {
  return document.getElementById(id);
}

/**
 * Add class(es) to element
 * @param {Element} element - Target element
 * @param {...string} classes - Class names to add
 */
export function addClass(element, ...classes) {
  if (!element) return;
  element.classList.add(...classes);
}

/**
 * Remove class(es) from element
 * @param {Element} element - Target element
 * @param {...string} classes - Class names to remove
 */
export function removeClass(element, ...classes) {
  if (!element) return;
  element.classList.remove(...classes);
}

/**
 * Toggle class on element
 * @param {Element} element - Target element
 * @param {string} className - Class name to toggle
 * @param {boolean} force - Force add/remove
 */
export function toggleClass(element, className, force) {
  if (!element) return;
  if (force === undefined) {
    element.classList.toggle(className);
  } else {
    element.classList.toggle(className, force);
  }
}

/**
 * Check if element has class
 * @param {Element} element - Target element
 * @param {string} className - Class name to check
 * @returns {boolean}
 */
export function hasClass(element, className) {
  if (!element) return false;
  return element.classList.contains(className);
}

/**
 * Set inline styles on element
 * @param {Element} element - Target element
 * @param {Object} styles - Style object (camelCase keys)
 */
export function setStyles(element, styles) {
  if (!element) return;
  Object.assign(element.style, styles);
}

/**
 * Get computed style value
 * @param {Element} element - Target element
 * @param {string} property - CSS property name
 * @returns {string}
 */
export function getStyle(element, property) {
  if (!element) return "";
  return window.getComputedStyle(element).getPropertyValue(property);
}

/**
 * Set attribute on element
 * @param {Element} element - Target element
 * @param {string} name - Attribute name
 * @param {string} value - Attribute value
 */
export function setAttribute(element, name, value) {
  if (!element) return;
  element.setAttribute(name, value);
}

/**
 * Get attribute from element
 * @param {Element} element - Target element
 * @param {string} name - Attribute name
 * @returns {string|null}
 */
export function getAttribute(element, name) {
  if (!element) return null;
  return element.getAttribute(name);
}

/**
 * Remove attribute from element
 * @param {Element} element - Target element
 * @param {string} name - Attribute name
 */
export function removeAttribute(element, name) {
  if (!element) return;
  element.removeAttribute(name);
}

/**
 * Set inner HTML (use with caution - XSS risk)
 * @param {Element} element - Target element
 * @param {string} html - HTML content
 */
export function setHTML(element, html) {
  if (!element) return;
  element.innerHTML = html;
}

/**
 * Set text content
 * @param {Element} element - Target element
 * @param {string} text - Text content
 */
export function setText(element, text) {
  if (!element) return;
  element.textContent = text;
}

/**
 * Get text content
 * @param {Element} element - Target element
 * @returns {string}
 */
export function getText(element) {
  if (!element) return "";
  return element.textContent;
}

/**
 * Show element by setting display
 * @param {Element} element - Target element
 * @param {string} display - Display value (default: 'block')
 */
export function show(element, display = "block") {
  if (!element) return;
  element.style.display = display;
}

/**
 * Hide element by setting display: none
 * @param {Element} element - Target element
 */
export function hide(element) {
  if (!element) return;
  element.style.display = "none";
}

/**
 * Check if element is visible in viewport
 * @param {Element} element - Target element
 * @returns {boolean}
 */
export function isInViewport(element) {
  if (!element) return false;
  const rect = element.getBoundingClientRect();
  return (
    rect.top >= 0 &&
    rect.left >= 0 &&
    rect.bottom <=
      (window.innerHeight || document.documentElement.clientHeight) &&
    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
  );
}

/**
 * Get element position and size
 * @param {Element} element - Target element
 * @returns {DOMRect}
 */
export function getRect(element) {
  if (!element) return null;
  return element.getBoundingClientRect();
}

/**
 * Remove element from DOM
 * @param {Element} element - Element to remove
 */
export function remove(element) {
  if (element) {
    element.remove();
  }
}

/**
 * Clone element
 * @param {Element} element - Element to clone
 * @param {boolean} deep - Deep clone (default: true)
 * @returns {Element}
 */
export function clone(element, deep = true) {
  if (!element) return null;
  return element.cloneNode(deep);
}

/**
 * Create new element
 * @param {string} tag - HTML tag name
 * @param {Object} attributes - Element attributes
 * @param {string} content - Text content (optional)
 * @returns {Element}
 */
export function createElement(tag, attributes = {}, content = "") {
  const element = document.createElement(tag);
  Object.entries(attributes).forEach(([key, value]) => {
    if (key === "class") {
      element.className = value;
    } else {
      element.setAttribute(key, value);
    }
  });
  if (content) {
    element.textContent = content;
  }
  return element;
}
