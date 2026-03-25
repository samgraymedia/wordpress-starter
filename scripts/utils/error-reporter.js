/**
 * Error Reporter Utility
 *
 * Handles server-side error reporting for production error tracking.
 * Batches errors before sending to reduce network overhead and implements
 * retry logic for failed transmissions.
 *
 * @module ErrorReporter
 *
 * @example
 * import ErrorReporter from './utils/error-reporter.js';
 * import Logger from './utils/logger.js';
 *
 * const errorReporter = new ErrorReporter({
 *   endpoint: '/wp-json/theme/v1/errors',
 *   batchSize: 10,
 *   flushInterval: 30000
 * });
 *
 * Logger.setErrorReporter(errorReporter);
 */

/**
 * ErrorReporter class
 * Handles batching and transmission of errors to server
 * @class
 */
class ErrorReporter {
  constructor(config = {}) {
    const themeConfig = window.themeConfig || {};

    this.config = {
      endpoint: config.endpoint || "/wp-json/theme/v1/errors",
      batchSize: config.batchSize || 10,
      flushInterval: config.flushInterval || 30000, // 30 seconds
      maxRetries: config.maxRetries || 3,
      retryDelay: config.retryDelay || 1000, // 1 second
      nonce: themeConfig.nonce || "",
      debug: themeConfig.debug || false,
    };

    this.errorBatch = [];
    this.flushTimer = null;
    this.isOnline = navigator.onLine;
    this.retryQueue = [];

    this._setupEventListeners();
    this._startFlushTimer();
  }

  /**
   * Set up event listeners for online/offline detection
   * @private
   */
  _setupEventListeners() {
    window.addEventListener("online", () => {
      this.isOnline = true;
      if (this.config.debug) {
        console.log("[ErrorReporter] Going online, flushing queued errors");
      }
      this._flushRetryQueue();
    });

    window.addEventListener("offline", () => {
      this.isOnline = false;
      if (this.config.debug) {
        console.log("[ErrorReporter] Going offline");
      }
    });

    // Flush errors before page unload
    window.addEventListener("beforeunload", () => {
      this._flush();
    });
  }

  /**
   * Start the automatic flush timer
   * @private
   */
  _startFlushTimer() {
    this.flushTimer = setInterval(() => {
      if (this.errorBatch.length > 0) {
        this._flush();
      }
    }, this.config.flushInterval);
  }

  /**
   * Stop the flush timer
   * @private
   */
  _stopFlushTimer() {
    if (this.flushTimer) {
      clearInterval(this.flushTimer);
      this.flushTimer = null;
    }
  }

  /**
   * Add an error to the batch
   * @param {Object} errorData - Error information object
   */
  report(errorData) {
    if (!errorData) {
      return;
    }

    this.errorBatch.push({
      ...errorData,
      id: `${Date.now()}-${Math.random().toString(36).substr(2, 9)}`,
    });

    if (this.config.debug) {
      console.log("[ErrorReporter] Error batched:", errorData);
    }

    // Auto-flush if batch size reached
    if (this.errorBatch.length >= this.config.batchSize) {
      this._flush();
    }
  }

  /**
   * Flush all batched errors to server
   * @private
   */
  _flush() {
    if (this.errorBatch.length === 0 || !this.isOnline) {
      return;
    }

    const errorsToSend = [...this.errorBatch];
    this.errorBatch = [];

    this._sendErrors(errorsToSend);
  }

  /**
   * Send errors to server endpoint
   * @private
   * @param {Array} errors - Array of error objects
   */
  _sendErrors(errors) {
    const payload = {
      errors,
      timestamp: new Date().toISOString(),
      url: window.location.href,
    };

    fetch(this.config.endpoint, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-WP-Nonce": this.config.nonce,
      },
      body: JSON.stringify(payload),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
      })
      .then((data) => {
        if (this.config.debug) {
          console.log("[ErrorReporter] Errors sent successfully:", data);
        }
      })
      .catch((error) => {
        if (this.config.debug) {
          console.error("[ErrorReporter] Failed to send errors:", error);
        }
        // Add to retry queue
        this._addToRetryQueue(errors);
      });
  }

  /**
   * Add errors to retry queue with exponential backoff
   * @private
   * @param {Array} errors - Errors to retry
   * @param {number} attempt - Current attempt number
   */
  _addToRetryQueue(errors, attempt = 1) {
    if (attempt > this.config.maxRetries) {
      if (this.config.debug) {
        console.warn("[ErrorReporter] Max retries exceeded for error batch");
      }
      return;
    }

    const delay = this.config.retryDelay * Math.pow(2, attempt - 1); // Exponential backoff

    setTimeout(() => {
      if (this.isOnline) {
        this._sendErrors(errors);
      } else {
        // If still offline, re-queue
        this._addToRetryQueue(errors, attempt + 1);
      }
    }, delay);
  }

  /**
   * Flush retry queue when coming back online
   * @private
   */
  _flushRetryQueue() {
    // Implementation hook for retry queue processing
    // Future enhancement: maintain persistent queue in localStorage
  }

  /**
   * Manually flush all pending errors
   * Useful before page unload or for testing
   */
  flush() {
    this._flush();
  }

  /**
   * Destroy reporter and clean up resources
   */
  destroy() {
    this._stopFlushTimer();
    this._flush();
  }
}

// Create and export singleton instance
const errorReporterInstance = new ErrorReporter();

export default errorReporterInstance;
