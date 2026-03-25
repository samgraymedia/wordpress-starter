/**
 * Logger Utility Tests
 *
 * Tests for the Logger utility covering:
 * - Log level filtering and configuration
 * - Message formatting and timestamp behavior
 * - Stack trace parsing
 * - Environment detection (dev vs production)
 * - Error reporting integration
 *
 * @module test/logger.test
 */

import Logger from "../scripts/utils/logger.js";
import ErrorReporter from "../scripts/utils/error-reporter.js";

/**
 * Test configuration
 */
const TEST_CONFIG = {
  originalThemeConfig: window.themeConfig,
};

/**
 * Mock error reporter for testing
 */
class MockErrorReporter {
  constructor() {
    this.reports = [];
  }

  report(errorData) {
    this.reports.push(errorData);
  }

  getReports() {
    return this.reports;
  }

  clear() {
    this.reports = [];
  }
}

/**
 * Setup test environment
 */
function setupTest() {
  // Reset theme config
  window.themeConfig = { debug: true, nonce: "test-nonce" };

  // Create a fresh logger instance
  Logger.configure({ debug: true, minLevel: "debug" });
}

/**
 * Cleanup after tests
 */
function cleanupTest() {
  window.themeConfig = TEST_CONFIG.originalThemeConfig;
}

/**
 * Test Suite: Logger Instantiation
 */
describe("Logger Instantiation", () => {
  beforeEach(setupTest);
  afterEach(cleanupTest);

  test("Logger should be initialized", () => {
    expect(Logger.isInitialized()).toBe(true);
  });

  test("Logger should read debug flag from window.themeConfig", () => {
    window.themeConfig = { debug: true };
    Logger.configure({ debug: window.themeConfig.debug });
    expect(Logger.config.debug).toBe(true);
  });

  test("Logger should default to production mode when debug is false", () => {
    window.themeConfig = { debug: false };
    Logger.configure({ debug: false });
    expect(Logger.config.debug).toBe(false);
  });
});

/**
 * Test Suite: Log Levels
 */
describe("Log Levels", () => {
  beforeEach(setupTest);
  afterEach(cleanupTest);

  test("Logger should support debug, info, warn, and error methods", () => {
    expect(typeof Logger.debug).toBe("function");
    expect(typeof Logger.info).toBe("function");
    expect(typeof Logger.warn).toBe("function");
    expect(typeof Logger.error).toBe("function");
  });

  test("Debug method should log in development mode", () => {
    const consoleSpy = jest.spyOn(console, "log").mockImplementation(() => {});
    Logger.configure({ debug: true, minLevel: "debug" });
    Logger.debug("Test debug message");
    expect(consoleSpy).toHaveBeenCalled();
    consoleSpy.mockRestore();
  });

  test("Debug method should not log in production mode", () => {
    const consoleSpy = jest.spyOn(console, "log").mockImplementation(() => {});
    Logger.configure({ debug: false, minLevel: "warn" });
    Logger.debug("Test debug message");
    expect(consoleSpy).not.toHaveBeenCalled();
    consoleSpy.mockRestore();
  });

  test("Error method should always log regardless of environment", () => {
    const consoleSpy = jest
      .spyOn(console, "error")
      .mockImplementation(() => {});
    Logger.configure({ debug: false, minLevel: "warn" });
    Logger.error("Test error message");
    expect(consoleSpy).toHaveBeenCalled();
    consoleSpy.mockRestore();
  });
});

/**
 * Test Suite: Message Formatting
 */
describe("Message Formatting", () => {
  beforeEach(setupTest);
  afterEach(cleanupTest);

  test("Logger should format messages with level prefix", () => {
    const consoleSpy = jest.spyOn(console, "log").mockImplementation(() => {});
    Logger.configure({
      debug: true,
      enableTimestamp: false,
      enableColors: false,
    });
    Logger.info("test message");

    // Check that the formatted message includes the prefix
    const callArgs = consoleSpy.mock.calls[0];
    expect(callArgs[0]).toContain("[INFO]");
    expect(callArgs[0]).toContain("test message");

    consoleSpy.mockRestore();
  });

  test("Logger should include timestamp when enabled", () => {
    const consoleSpy = jest.spyOn(console, "log").mockImplementation(() => {});
    Logger.configure({
      debug: true,
      enableTimestamp: true,
      enableColors: false,
    });
    Logger.info("test message");

    const callArgs = consoleSpy.mock.calls[0];
    expect(callArgs[0]).toMatch(/\d{1,2}:\d{2}:\d{2}/); // Time format check

    consoleSpy.mockRestore();
  });

  test("Logger should exclude timestamp when disabled", () => {
    const consoleSpy = jest.spyOn(console, "log").mockImplementation(() => {});
    Logger.configure({
      debug: true,
      enableTimestamp: false,
      enableColors: false,
    });
    Logger.info("test message");

    const callArgs = consoleSpy.mock.calls[0];
    expect(callArgs[0]).toMatch(/^\[INFO\]/); // Should start with level, not timestamp

    consoleSpy.mockRestore();
  });
});

/**
 * Test Suite: Stack Trace
 */
describe("Stack Trace Handling", () => {
  beforeEach(setupTest);
  afterEach(cleanupTest);

  test("Logger should capture stack trace in development mode", () => {
    const consoleSpy = jest.spyOn(console, "log").mockImplementation(() => {});
    Logger.configure({
      debug: true,
      enableStackTrace: true,
      enableColors: false,
      stackTraceDepth: 3,
    });
    Logger.error("test error");

    // Stack trace logging should include multiple console calls
    expect(consoleSpy.mock.calls.length).toBeGreaterThan(1);

    consoleSpy.mockRestore();
  });

  test("Logger should not capture stack trace when disabled", () => {
    const consoleSpy = jest.spyOn(console, "log").mockImplementation(() => {});
    Logger.configure({
      debug: true,
      enableStackTrace: false,
      enableColors: false,
    });
    Logger.error("test error");

    // Should only have one console call (the message itself)
    expect(consoleSpy.mock.calls.length).toBe(1);

    consoleSpy.mockRestore();
  });
});

/**
 * Test Suite: Error Reporting Integration
 */
describe("Error Reporting Integration", () => {
  beforeEach(setupTest);
  afterEach(cleanupTest);

  test("Logger should accept error reporter instance", () => {
    const mockReporter = new MockErrorReporter();
    Logger.setErrorReporter(mockReporter);
    expect(Logger.config.errorReporter).toBe(mockReporter);
  });

  test("Production errors should be reported to server", () => {
    const mockReporter = new MockErrorReporter();
    Logger.setErrorReporter(mockReporter);
    Logger.configure({ debug: false, minLevel: "warn" });

    Logger.error("Server error", { code: 500 });

    expect(mockReporter.getReports().length).toBe(1);
    expect(mockReporter.getReports()[0].message).toBe("Server error");
  });

  test("Development warnings should be reported in development mode", () => {
    const mockReporter = new MockErrorReporter();
    Logger.setErrorReporter(mockReporter);
    Logger.configure({ debug: true, minLevel: "debug" });

    Logger.warn("Development warning");

    expect(mockReporter.getReports().length).toBe(1);
  });

  test("Error report should include metadata", () => {
    const mockReporter = new MockErrorReporter();
    Logger.setErrorReporter(mockReporter);
    Logger.configure({ debug: true });

    Logger.error("Error with context", undefined, {
      context: "checkout",
      userId: 123,
    });

    const report = mockReporter.getReports()[0];
    expect(report.context).toBe("checkout");
    expect(report.userId).toBe(123);
  });
});

/**
 * Test Suite: Configuration
 */
describe("Logger Configuration", () => {
  beforeEach(setupTest);
  afterEach(cleanupTest);

  test("Logger should update configuration on demand", () => {
    const originalDebug = Logger.config.debug;
    Logger.configure({ debug: !originalDebug });
    expect(Logger.config.debug).toBe(!originalDebug);
  });

  test("Logger should respect color configuration", () => {
    Logger.configure({ enableColors: false });
    expect(Logger.config.enableColors).toBe(false);

    Logger.configure({ enableColors: true });
    expect(Logger.config.enableColors).toBe(true);
  });

  test("Logger should respect minimum log level", () => {
    const consoleSpy = jest.spyOn(console, "log").mockImplementation(() => {});
    Logger.configure({ debug: true, minLevel: "error" });

    Logger.debug("debug message");
    Logger.info("info message");
    Logger.warn("warn message");
    Logger.error("error message");

    // Only error should be logged
    expect(consoleSpy).toHaveBeenCalledTimes(1);

    consoleSpy.mockRestore();
  });
});

/**
 * Test Suite: Data Logging
 */
describe("Data Logging", () => {
  beforeEach(setupTest);
  afterEach(cleanupTest);

  test("Logger should log additional data alongside message", () => {
    const consoleSpy = jest.spyOn(console, "log").mockImplementation(() => {});
    Logger.configure({ debug: true, enableColors: false });

    const testData = { user: "john", action: "login" };
    Logger.info("User action", testData);

    // Data should be passed as second argument to console.log
    const callArgs = consoleSpy.mock.calls[0];
    expect(callArgs[1]).toEqual(testData);

    consoleSpy.mockRestore();
  });

  test("Logger should handle undefined data gracefully", () => {
    const consoleSpy = jest.spyOn(console, "log").mockImplementation(() => {});
    Logger.configure({ debug: true, enableColors: false });

    Logger.info("Message without data");
    expect(consoleSpy).toHaveBeenCalled();

    consoleSpy.mockRestore();
  });
});

/**
 * Export test summary
 */
export { setupTest, cleanupTest, MockErrorReporter };
