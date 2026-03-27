/**
 * Jest Setup File
 *
 * Configures Jest environment, global mocks, and test utilities
 * Runs before each test suite
 */

// Mock WooCommerce global
global.wc = {
  store: {
    select: jest.fn(),
    dispatch: jest.fn(),
    subscribe: jest.fn(),
  },
  productEditor: {
    BlockEditor: jest.fn(),
  },
};

// Mock WordPress global
global.wp = {
  data: {
    select: jest.fn(),
    dispatch: jest.fn(),
    subscribe: jest.fn(),
  },
  blocks: {
    registerBlockType: jest.fn(),
  },
  element: {
    createElement: jest.fn((type, props, ...children) => ({
      type,
      props,
      children,
    })),
    Fragment: Symbol.for("react.fragment"),
  },
  i18n: {
    __: (text) => text,
    _x: (text) => text,
    _n: (singular, plural, count) => (count === 1 ? singular : plural),
  },
  hooks: {
    addFilter: jest.fn(),
    applyFilters: jest.fn((hook, value) => value),
    addAction: jest.fn(),
    doAction: jest.fn(),
  },
  url: {
    addQueryArgs: jest.fn((url, args) => {
      const params = new URLSearchParams(args);
      return `${url}?${params.toString()}`;
    }),
  },
};

// Mock Timber/Twig if needed
global.Timber = {
  compile: jest.fn(),
};

// Mock fetch API
global.fetch = jest.fn();

// Mock localStorage
global.localStorage = (() => {
  let store = {};
  return {
    getItem: (key) => store[key] || null,
    setItem: (key, value) => {
      store[key] = String(value);
    },
    removeItem: (key) => {
      delete store[key];
    },
    clear: () => {
      store = {};
    },
  };
})();

// Mock sessionStorage
global.sessionStorage = (() => {
  let store = {};
  return {
    getItem: (key) => store[key] || null,
    setItem: (key, value) => {
      store[key] = String(value);
    },
    removeItem: (key) => {
      delete store[key];
    },
    clear: () => {
      store = {};
    },
  };
})();

// Suppress console errors during tests (can be overridden per test)
const originalError = console.error;
beforeAll(() => {
  console.error = (...args) => {
    if (
      typeof args[0] === "string" &&
      (args[0].includes("Not implemented: HTMLFormElement.prototype.submit") ||
        args[0].includes("Not implemented: HTMLFormElement.prototype.reset"))
    ) {
      return;
    }
    originalError.call(console, ...args);
  };
});

afterAll(() => {
  console.error = originalError;
});
