/**
 * Logger Utility
 *
 * Provides a centralized, environment-aware logging system for development and production.
 * Supports multiple log levels (debug, info, warn, error) with stack trace capture
 * and automatic filtering based on environment settings.
 *
 * @module Logger
 *
 * @example
 * import Logger from './utils/logger.js';
 *
 * // Development environment - all logs appear
 * Logger.debug('Debugging info'); // [DEBUG] Debugging info
 * Logger.info('Information');      // [INFO] Information
 * Logger.warn('Warning message');  // [WARN] Warning message
 * Logger.error('Error occurred');  // [ERROR] Error occurred
 *
 * // Production environment - only errors and warnings go to server
 * Logger.error('Critical error', { context: 'checkout' });
 */

const LOG_LEVELS = {
  DEBUG: "debug",
  INFO: "info",
  WARN: "warn",
  ERROR: "error",
};

// Map log levels to numeric values for filtering
const LOG_LEVEL_VALUES = {
  debug: 0,
  info: 1,
  warn: 2,
  error: 3,
};

/**
 * Logger class - Primary logging interface
 * @class
 */
class Logger {
  constructor(config = {}) {
    // Get configuration from window.themeConfig if available
    // Note: themeConfig is set by WordPress wp_localize_script and may not be available at module load time
    const themeConfig =
      (typeof window !== "undefined" && window.themeConfig) || {};

    this.config = {
      debug: themeConfig.debug !== undefined ? themeConfig.debug : false,
      minLevel:
        config.minLevel ||
        (themeConfig.debug ? LOG_LEVELS.DEBUG : LOG_LEVELS.WARN),
      colors: {
        debug: "#7f8c8d",
        info: "#3498db",
        warn: "#f39c12",
        error: "#e74c3c",
      },
      enableColors: config.enableColors !== false,
      enableTimestamp: config.enableTimestamp !== false,
      enableStackTrace: config.enableStackTrace !== false,
      stackTraceDepth: config.stackTraceDepth || 5,
      errorReporter: null, // Will be set by error-reporter.js
    };

    this._initialized = false;
  }

  /**
   * Check if console colors are supported
   * @private
   * @return {boolean}
   */
  _canUseColors() {
    // Colors work in modern browsers, but not all environments
    return !this._isProduction();
  }

  /**
   * Check if running in production environment
   * @private
   * @return {boolean}
   */
  _isProduction() {
    return !this.config.debug;
  }

  /**
   * Check if a log level should be logged based on current configuration
   * @private
   * @param {string} level - Log level (debug, info, warn, error)
   * @return {boolean}
   */
  _shouldLog(level) {
    const currentLevelValue =
      LOG_LEVEL_VALUES[this.config.minLevel] || LOG_LEVEL_VALUES.warn;
    const messageLevelValue = LOG_LEVEL_VALUES[level] || 0;
    return messageLevelValue >= currentLevelValue;
  }

  /**
   * Get current timestamp formatted for logging
   * @private
   * @return {string}
   */
  _getTimestamp() {
    if (!this.config.enableTimestamp) return "";
    const now = new Date();
    return now.toLocaleTimeString();
  }

  /**
   * Parse stack trace and extract useful information
   * @private
   * @return {Array}
   */
  _getStackTrace() {
    if (!this.config.enableStackTrace) return [];

    const stack = new Error().stack || "";
    const lines = stack.split("\n").slice(3); // Skip Error, _getStackTrace, and calling function

    return lines
      .slice(0, this.config.stackTraceDepth)
      .map((line) => line.trim())
      .filter((line) => line.length > 0);
  }

  /**
   * Format a log message with optional colors
   * @private
   * @param {string} level - Log level
   * @param {string} message - Log message
   * @param {string} timestamp - Formatted timestamp
   * @return {Object} Formatted output with style
   */
  _formatMessage(level, message, timestamp) {
    const prefix = `[${level.toUpperCase()}]`;
    const time = timestamp ? `${timestamp} ` : "";
    const fullMessage = `${time}${prefix} ${message}`;

    if (!this.config.enableColors) {
      return { message: fullMessage, style: "" };
    }

    const color = this.config.colors[level] || "#000000";
    const style = `color: ${color}; font-weight: bold;`;

    return { message: fullMessage, style };
  }

  /**
   * Core logging function used by all public methods
   * @private
   * @param {string} level - Log level
   * @param {string} message - Log message
   * @param {*} data - Optional additional data to log
   * @param {Object} metadata - Optional metadata for error tracking
   */
  _log(level, message, data, metadata = {}) {
    if (!this._shouldLog(level)) {
      return;
    }

    const timestamp = this._getTimestamp();
    const { message: formatted, style } = this._formatMessage(
      level,
      message,
      timestamp,
    );
    const stackTrace = this._getStackTrace();

    // Console output (development)
    if (
      !this._isProduction() ||
      level === LOG_LEVELS.WARN ||
      level === LOG_LEVELS.ERROR
    ) {
      if (style) {
        console.log(`%c${formatted}`, style, data || "");
      } else {
        console[level === LOG_LEVELS.ERROR ? "error" : "log"](
          formatted,
          data || "",
        );
      }

      if (stackTrace.length > 0 && this.config.debug) {
        console.log("%cStack Trace:", "color: #95a5a6; font-weight: bold;");
        stackTrace.forEach((line) => console.log(`  ${line}`));
      }
    }

    // Server-side error reporting (production + warnings/errors)
    if (
      (this._isProduction() &&
        (level === LOG_LEVELS.ERROR || level === LOG_LEVELS.WARN)) ||
      (!this._isProduction() && level === LOG_LEVELS.ERROR)
    ) {
      this._reportToServer(level, message, data, stackTrace, metadata);
    }
  }

  /**
   * Send error information to server endpoint
   * @private
   * @param {string} level - Log level
   * @param {string} message - Log message
   * @param {*} data - Additional data
   * @param {Array} stackTrace - Stack trace lines
   * @param {Object} metadata - Additional metadata
   */
  _reportToServer(level, message, data, stackTrace, metadata) {
    if (
      this.config.errorReporter &&
      typeof this.config.errorReporter.report === "function"
    ) {
      this.config.errorReporter.report({
        level,
        message,
        data,
        stackTrace,
        url: window.location.href,
        userAgent: navigator.userAgent,
        timestamp: new Date().toISOString(),
        ...metadata,
      });
    }
  }

  /**
   * Set the error reporter instance
   * @param {Object} errorReporter - Error reporter instance
   */
  setErrorReporter(errorReporter) {
    this.config.errorReporter = errorReporter;
  }

  /**
   * Update logger configuration
   * @param {Object} config - Configuration overrides
   */
  configure(config) {
    this.config = { ...this.config, ...config };
  }

  /**
   * Mark logger as initialized
   * @private
   */
  _markInitialized() {
    this._initialized = true;
  }

  /**
   * Check if logger is initialized
   * @return {boolean}
   */
  isInitialized() {
    return this._initialized;
  }

  // ============================================================
  // Public API Methods
  // ============================================================

  /**
   * Log a debug message (lowest priority)
   * @param {string} message - Log message
   * @param {*} data - Optional additional data
   * @param {Object} metadata - Optional metadata
   */
  debug(message, data, metadata) {
    this._log(LOG_LEVELS.DEBUG, message, data, metadata);
  }

  /**
   * Log an informational message
   * @param {string} message - Log message
   * @param {*} data - Optional additional data
   * @param {Object} metadata - Optional metadata
   */
  info(message, data, metadata) {
    this._log(LOG_LEVELS.INFO, message, data, metadata);
  }

  /**
   * Log a warning message
   * @param {string} message - Log message
   * @param {*} data - Optional additional data
   * @param {Object} metadata - Optional metadata
   */
  warn(message, data, metadata) {
    this._log(LOG_LEVELS.WARN, message, data, metadata);
  }

  /**
   * Log an error message (highest priority)
   * @param {string} message - Log message
   * @param {*} data - Optional additional data
   * @param {Object} metadata - Optional metadata
   */
  error(message, data, metadata) {
    this._log(LOG_LEVELS.ERROR, message, data, metadata);
  }
}

// Create and export singleton instance
const loggerInstance = new Logger();
loggerInstance._markInitialized();

export default loggerInstance;
