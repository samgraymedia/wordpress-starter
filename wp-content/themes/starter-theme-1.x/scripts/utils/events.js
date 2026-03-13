/**
 * Event & Timing Utility Functions
 *
 * Helper functions for event handling, debouncing, throttling, and timing.
 *
 * @module utils/events
 */

/**
 * Debounce function - delay execution until events stop firing
 * Useful for: window resize, scroll, input typing
 *
 * @example
 * const handleResize = debounce(() => {
 *     console.log('Resized!');
 * }, 300);
 * window.addEventListener('resize', handleResize);
 *
 * @param {Function} callback - Function to debounce
 * @param {number} wait - Wait time in milliseconds
 * @param {boolean} immediate - Call on leading edge (default: false)
 * @returns {Function}
 */
export function debounce(callback, wait = 300, immediate = false) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      if (!immediate) callback.apply(this, args);
    };
    const callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) callback.apply(this, args);
  };
}

/**
 * Throttle function - limit execution rate
 * Useful for: scroll events, mousemove tracking
 *
 * @example
 * const handleScroll = throttle(() => {
 *     console.log('Scrolling!');
 * }, 250);
 * window.addEventListener('scroll', handleScroll);
 *
 * @param {Function} callback - Function to throttle
 * @param {number} wait - Minimum time between calls in milliseconds
 * @param {Object} options - Options object
 * @returns {Function}
 */
export function throttle(callback, wait = 300, options = {}) {
  let timeout = null;
  let previous = 0;

  const throttled = function (...args) {
    const now = Date.now();
    if (!previous && options.leading === false) previous = now;

    const remaining = wait - (now - previous);
    const context = this;

    if (remaining <= 0 || remaining > wait) {
      if (timeout) {
        clearTimeout(timeout);
        timeout = null;
      }
      previous = now;
      callback.apply(context, args);
    } else if (!timeout && options.trailing !== false) {
      timeout = setTimeout(() => {
        previous = options.leading === false ? 0 : Date.now();
        timeout = null;
        callback.apply(context, args);
      }, remaining);
    }
  };

  throttled.cancel = function () {
    clearTimeout(timeout);
    previous = 0;
    timeout = null;
  };

  return throttled;
}

/**
 * Request animation frame with fallback
 *
 * @param {Function} callback - Function to execute on next frame
 * @returns {number}
 */
export function raf(callback) {
  return window.requestAnimationFrame(callback);
}

/**
 * Cancel animation frame
 *
 * @param {number} id - Animation frame ID
 */
export function cancel(id) {
  window.cancelAnimationFrame(id);
}

/**
 * Delay execution
 * Returns a promise that resolves after specified delay
 *
 * @example
 * await delay(1000);
 * console.log('1 second later');
 *
 * @param {number} ms - Milliseconds to delay
 * @returns {Promise<void>}
 */
export function delay(ms = 0) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}

/**
 * Execute callback once, then ignore subsequent calls
 *
 * @example
 * const init = once(() => {
 *     console.log('Initialized once');
 * });
 * init(); // Logs
 * init(); // Nothing
 * init(); // Nothing
 *
 * @param {Function} callback - Function to execute once
 * @returns {Function}
 */
export function once(callback) {
  let executed = false;
  let result;
  return function (...args) {
    if (!executed) {
      result = callback.apply(this, args);
      executed = true;
    }
    return result;
  };
}

/**
 * Add event listener with automatic cleanup
 * Returns cleanup function
 *
 * @example
 * const cleanup = on(button, 'click', () => console.log('Clicked'));
 * cleanup(); // Remove listener
 *
 * @param {Element} element - Target element
 * @param {string} event - Event name
 * @param {Function} callback - Event handler
 * @param {Object} options - addEventListener options
 * @returns {Function}
 */
export function on(element, event, callback, options = {}) {
  if (!element) return;
  element.addEventListener(event, callback, options);
  return () => element.removeEventListener(event, callback, options);
}

/**
 * Add one-time event listener
 *
 * @param {Element} element - Target element
 * @param {string} event - Event name
 * @param {Function} callback - Event handler
 * @returns {Function}
 */
export function onOnce(element, event, callback) {
  if (!element) return;
  const cleanup = () => {
    element.removeEventListener(event, handler);
  };
  const handler = (...args) => {
    callback(...args);
    cleanup();
  };
  element.addEventListener(event, handler);
  return cleanup;
}

/**
 * Delegate event listener
 * Useful for dynamically added elements
 *
 * @example
 * delegate(document, 'button', 'click', (e) => {
 *     console.log('Button clicked:', e.target);
 * });
 *
 * @param {Element} element - Parent element to listen on
 * @param {string} selector - Selector to match
 * @param {string} event - Event name
 * @param {Function} callback - Event handler
 * @returns {Function}
 */
export function delegate(element, selector, event, callback) {
  if (!element) return;

  const handler = (e) => {
    if (e.target.matches(selector)) {
      callback.call(e.target, e);
    }
  };

  element.addEventListener(event, handler);
  return () => element.removeEventListener(event, handler);
}

/**
 * Trigger custom event
 *
 * @param {Element} element - Target element
 * @param {string} eventName - Event name
 * @param {Object} detail - Event detail data
 */
export function trigger(element, eventName, detail = {}) {
  if (!element) return;
  const event = new CustomEvent(eventName, { detail });
  element.dispatchEvent(event);
}

/**
 * Wait for DOM element to appear
 *
 * @example
 * const element = await waitForElement('.my-element');
 *
 * @param {string} selector - CSS selector
 * @param {number} timeout - Max wait time in ms (0 = infinite)
 * @returns {Promise<Element>}
 */
export function waitForElement(selector, timeout = 0) {
  return new Promise((resolve, reject) => {
    const element = document.querySelector(selector);
    if (element) {
      resolve(element);
      return;
    }

    const observer = new MutationObserver(() => {
      const element = document.querySelector(selector);
      if (element) {
        observer.disconnect();
        clearTimeout(timeoutId);
        resolve(element);
      }
    });

    observer.observe(document.documentElement, {
      childList: true,
      subtree: true,
    });

    let timeoutId;
    if (timeout > 0) {
      timeoutId = setTimeout(() => {
        observer.disconnect();
        reject(new Error(`Element ${selector} not found within ${timeout}ms`));
      }, timeout);
    }
  });
}

/**
 * Intersection Observer helper
 *
 * @example
 * const cleanup = observeElement('.lazy', (entry) => {
 *     if (entry.isIntersecting) {
 *         loadLazyImage(entry.target);
 *     }
 * });
 *
 * @param {string|Element} target - Selector or element
 * @param {Function} callback - Called when visible
 * @param {Object} options - IntersectionObserver options
 * @returns {Function}
 */
export function observeElement(target, callback, options = {}) {
  const elements =
    typeof target === "string" ? document.querySelectorAll(target) : [target];

  if (elements.length === 0) return;

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        callback(entry);
      });
    },
    {
      threshold: 0.1,
      ...options,
    },
  );

  elements.forEach((el) => observer.observe(el));

  return () => observer.disconnect();
}
