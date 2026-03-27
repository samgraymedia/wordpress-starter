# Testing Framework Setup Guide

## Overview

This document provides comprehensive guidance for testing the Starter Theme, including PHP unit tests, JavaScript integration tests, and code coverage reporting.

**Status:** Testing framework initialized and ready for expansion
**Framework Version:** PHPUnit 9.x + Jest 29.x
**Estimated Coverage Target:** 70%+

---

## Table of Contents

1. [Quick Start](#quick-start)
2. [Testing Architecture](#testing-architecture)
3. [Running Tests](#running-tests)
4. [Writing PHP Tests](#writing-php-tests)
5. [Writing JavaScript Tests](#writing-javascript-tests)
6. [Code Coverage](#code-coverage)
7. [Test Fixtures and Mocks](#test-fixtures-and-mocks)
8. [Continuous Integration](#continuous-integration)
9. [Troubleshooting](#troubleshooting)

---

## Quick Start

### Installation

```bash
# Install PHP dependencies (requires Composer)
composer install

# Install JavaScript dependencies
npm install

# Verify installations
npm run test:php -- --version
npm test -- --version
```

### Run All Tests

```bash
# Run JavaScript tests
npm test

# Run PHP tests
npm run test:php

# Run both with coverage
npm run test:coverage && npm run test:php:coverage
```

### Run Specific Test Suite

```bash
# Run only unit tests
npm run test:php -- --testsuite unit

# Run only integration tests
npm run test:php -- --testsuite integration

# Run specific test file
npm test -- tests/cart.test.js
```

---

## Testing Architecture

### Directory Structure

```
tests/
├── bootstrap.php              # WordPress test environment setup
├── jest.setup.js              # Jest configuration and global mocks
├── unit/                      # Unit tests for isolated functionality
│   ├── test-validation-helper.php
│   ├── test-sanitization-helper.php
│   └── ...
├── integration/               # Integration tests for component interactions
│   ├── test-woocommerce-integration.php
│   ├── test-checkout-flow.php
│   └── ...
├── functional/                # End-to-end tests
│   ├── test-order-completion.php
│   └── ...
├── fixtures/                  # Test data and fixtures
│   ├── sample-products.json
│   ├── sample-orders.php
│   └── ...
├── mocks/                     # Mock objects and utilities
│   ├── MockCart.js
│   ├── MockCheckout.js
│   └── ...
├── cart.test.js              # JavaScript test for Cart module
├── forms.test.js             # JavaScript test for Forms module
└── logger.test.js            # JavaScript test for Logger utility (existing)
```

### Test Types

**Unit Tests (70% of test coverage)**

- Test individual functions and methods in isolation
- Use mocks and stubs for dependencies
- Fast execution, focused assertions
- Location: `tests/unit/` (PHP) and `tests/*.test.js` (JS)

**Integration Tests (20% of test coverage)**

- Test components working together
- Test real WordPress hooks and data
- Use fixtures for realistic data
- Location: `tests/integration/`
- Examples: WooCommerce cart + checkout flow, email template rendering

**Functional Tests (10% of test coverage)**

- Test complete user workflows
- Test end-to-end scenarios
- Slower but most realistic
- Location: `tests/functional/`
- Examples: Order completion, customer registration, product purchase

---

## Running Tests

### JavaScript Tests

```bash
# Run all JavaScript tests
npm test

# Run tests in watch mode (re-run on file changes)
npm run test:watch

# Run tests with coverage report
npm run test:coverage

# Run specific test file
npm test -- tests/cart.test.js

# Run tests matching pattern
npm test -- --testNamePattern="Cart Module"

# Update snapshots (if using snapshot testing)
npm test -- --updateSnapshot
```

### PHP Tests

```bash
# Run all PHP tests
npm run test:php

# Run specific test suite
npm run test:php -- --testsuite unit

# Run specific test file
npm run test:php -- tests/unit/test-validation-helper.php

# Run tests with coverage
npm run test:php:coverage

# Run tests with verbose output
npm run test:php -- --verbose

# Stop on first failure
npm run test:php -- --stop-on-failure
```

### Combined Test Run

```bash
# Create custom npm script combination
npm run test && npm run test:php
```

---

## Writing PHP Tests

### Basic Structure

```php
<?php
/**
 * Unit Tests: Example Helper
 */

class Test_Example_Helper extends WP_UnitTestCase {

	/**
	 * Test that the function exists
	 */
	public function test_function_exists() {
		$this->assertTrue( function_exists( 'theme_example_function' ) );
	}

	/**
	 * Test basic functionality
	 */
	public function test_basic_functionality() {
		$result = theme_example_function( 'input' );

		$this->assertIsString( $result );
		$this->assertEquals( 'expected output', $result );
	}

	/**
	 * Test with different inputs (data provider)
	 *
	 * @dataProvider provide_test_cases
	 */
	public function test_function_with_various_inputs( $input, $expected ) {
		$result = theme_example_function( $input );
		$this->assertEquals( $expected, $result );
	}

	/**
	 * Data provider for parameterized tests
	 */
	public function provide_test_cases() {
		return [
			[ 'input1', 'expected1' ],
			[ 'input2', 'expected2' ],
		];
	}

	/**
	 * Test error conditions
	 */
	public function test_handles_invalid_input() {
		$this->expectException( InvalidArgumentException::class );
		theme_example_function( [] ); // Invalid input
	}

	/**
	 * Test with WooCommerce hooks
	 */
	public function test_woocommerce_integration() {
		// Assuming WooCommerce is active
		if ( ! class_exists( 'WooCommerce' ) ) {
			$this->markTestSkipped( 'WooCommerce not active' );
		}

		// Test WooCommerce-specific functionality
		$this->assertTrue( true );
	}
}
```

### Common Assertions

```php
// Equality
$this->assertEquals( $expected, $actual, $message );
$this->assertNotEquals( $expected, $actual );

// Type checks
$this->assertIsString( $value );
$this->assertIsArray( $value );
$this->assertIsInt( $value );
$this->assertTrue( $value );
$this->assertFalse( $value );

// Null checks
$this->assertNull( $value );
$this->assertNotNull( $value );

// Array checks
$this->assertArrayHasKey( 'key', $array );
$this->assertContains( $item, $array );
$this->assertCount( $expectedCount, $array );

// String checks
$this->assertStringContainsString( 'substring', $string );
$this->assertStringNotContainsString( 'substring', $string );
$this->assertStringStartsWith( 'prefix', $string );

// Instance checks
$this->assertInstanceOf( ClassName::class, $object );

// Exception handling
$this->expectException( ExceptionClass::class );
$this->expectExceptionMessage( 'message' );
```

### Testing WordPress-Specific Code

```php
<?php
/**
 * Testing with WordPress Hooks
 */

class Test_Theme_Hooks extends WP_UnitTestCase {

	/**
	 * Test filter hook
	 */
	public function test_filter_hook() {
		$value = 'original';

		// Apply filter
		$filtered = apply_filters( 'theme_filter_hook', $value );

		// Assert filtered value
		$this->assertEquals( 'modified', $filtered );
	}

	/**
	 * Test action hook
	 */
	public function test_action_hook() {
		$callback_called = false;

		add_action( 'theme_action_hook', function() use ( &$callback_called ) {
			$callback_called = true;
		});

		do_action( 'theme_action_hook' );

		$this->assertTrue( $callback_called );
	}

	/**
	 * Test with mock objects
	 */
	public function test_with_mock_post() {
		$post_id = $this->factory->post->create( [
			'post_title' => 'Test Post',
			'post_type' => 'post',
		]);

		$post = get_post( $post_id );

		$this->assertNotNull( $post );
		$this->assertEquals( 'Test Post', $post->post_title );
	}

	/**
	 * Test with created WooCommerce product
	 */
	public function test_with_product() {
		if ( ! class_exists( 'WC_Product' ) ) {
			$this->markTestSkipped( 'WooCommerce not available' );
		}

		$product = new WC_Product();
		$product->set_name( 'Test Product' );
		$product->set_price( 29.99 );
		$product_id = $product->save();

		$loaded = wc_get_product( $product_id );

		$this->assertEquals( 'Test Product', $loaded->get_name() );
		$this->assertEquals( 29.99, $loaded->get_price() );
	}
}
```

---

## Writing JavaScript Tests

### Basic Structure

```javascript
/**
 * Unit Test: Example Module
 */

const ExampleModule = require("../../scripts/modules/example");

describe("Example Module", () => {
  let module;

  beforeEach(() => {
    // Setup before each test
    module = new ExampleModule();
    fetch.mockClear();
  });

  afterEach(() => {
    // Cleanup after each test
    jest.clearAllMocks();
  });

  describe("Initialization", () => {
    test("should initialize with default values", () => {
      expect(module).toBeDefined();
      expect(module.state).toEqual({});
    });
  });

  describe("Functionality", () => {
    test("should perform action", () => {
      const result = module.performAction("input");

      expect(result).toBe("expected output");
    });

    test("should handle errors", () => {
      expect(() => {
        module.performAction(null);
      }).toThrow();
    });
  });

  describe("API Integration", () => {
    test("should fetch data from API", async () => {
      fetch.mockResolvedValueOnce({
        ok: true,
        json: async () => ({ data: "test" }),
      });

      const result = await module.fetchData();

      expect(fetch).toHaveBeenCalled();
      expect(result.data).toBe("test");
    });
  });
});
```

### Common Jest Matchers

```javascript
// Equality
expect(value).toBe(expected); // Strict equality (===)
expect(value).toEqual(expected); // Deep equality
expect(value).not.toBe(expected);

// Type checks
expect(value).toBeDefined();
expect(value).toBeUndefined();
expect(value).toBeNull();
expect(value).toBeTruthy();
expect(value).toBeFalsy();
expect(typeof value).toBe("string");

// String checks
expect(value).toMatch(/pattern/);
expect(value).toContain("substring");
expect(value).toHaveLength(5);

// Number checks
expect(value).toBeGreaterThan(0);
expect(value).toBeGreaterThanOrEqual(0);
expect(value).toBeLessThan(10);
expect(value).toBeCloseTo(3.14, 2);

// Array checks
expect(array).toContain(item);
expect(array).toHaveLength(3);

// Object checks
expect(object).toHaveProperty("key");
expect(object).toEqual({ key: "value" });

// Exception handling
expect(() => {
  fn();
}).toThrow();
expect(() => {
  fn();
}).toThrow(ErrorType);
expect(() => {
  fn();
}).toThrow("message");

// Mocks and spies
expect(mockFn).toHaveBeenCalled();
expect(mockFn).toHaveBeenCalledWith(arg);
expect(mockFn).toHaveBeenCalledTimes(2);
```

### Testing Async Code

```javascript
describe("Async Code", () => {
  test("should handle promises", () => {
    return module.asyncAction().then((result) => {
      expect(result).toBe("expected");
    });
  });

  test("should handle async/await", async () => {
    const result = await module.asyncAction();
    expect(result).toBe("expected");
  });

  test("should handle mock fetch", async () => {
    fetch.mockResolvedValueOnce({
      ok: true,
      json: async () => ({ data: "test" }),
    });

    const result = await module.fetchData();
    expect(result.data).toBe("test");
  });
});
```

---

## Code Coverage

### Generating Coverage Reports

```bash
# Generate JavaScript coverage
npm run test:coverage

# Generate PHP coverage
npm run test:php:coverage

# View coverage reports
open coverage/html/index.html              # JavaScript (macOS)
open coverage/html/index.html              # PHP (macOS)
```

### Coverage Thresholds (phpunit.xml)

The PHPUnit configuration is set to generate coverage reports in three formats:

- **Clover XML** (`coverage/clover.xml`) - For CI/CD integration
- **HTML Report** (`coverage/html/`) - For visual inspection
- **Text Report** (`coverage/coverage.txt`) - For terminal review

### Coverage Goals

| Category                | Target | Status               |
| ----------------------- | ------ | -------------------- |
| Overall                 | 70%    | 🔴 ~5% (in progress) |
| Helpers                 | 80%    | 🔴 0%                |
| Modules                 | 75%    | 🔴 0%                |
| Utils                   | 80%    | 🟡 5% (logger only)  |
| WooCommerce Integration | 60%    | 🔴 0%                |

### Improving Coverage

1. **Identify untested code:**

   ```bash
   npm run test:coverage
   # Review coverage/html/index.html for red (uncovered) lines
   ```

2. **Write tests for critical paths:**

   - Focus on helper functions first (highest impact)
   - Then test module interactions
   - Then test utility functions

3. **Run coverage regularly:**
   - Add to CI/CD pipeline
   - Report coverage on pull requests
   - Enforce minimum coverage thresholds

---

## Test Fixtures and Mocks

### Using Fixtures

Fixtures provide realistic test data:

```javascript
// tests/fixtures/sample-products.json
[
  {
    id: 1,
    name: "Test Product",
    price: 29.99,
    stock: 100,
  },
];
```

```javascript
// In test file
const sampleProducts = require("../fixtures/sample-products.json");

test("should process products", () => {
  const result = processProducts(sampleProducts);
  expect(result).toHaveLength(1);
});
```

### Creating Mocks

```javascript
// tests/mocks/MockCart.js
class MockCart {
  constructor() {
    this.items = [];
  }

  addItem(item) {
    this.items.push(item);
  }

  getItems() {
    return this.items;
  }

  clear() {
    this.items = [];
  }
}

module.exports = MockCart;
```

### Mocking WordPress & WooCommerce

```javascript
// Already configured in tests/jest.setup.js
// Global mocks for wp (WordPress) and wc (WooCommerce)

test("should integrate with WordPress", () => {
  wp.hooks.addFilter("my_filter", "my_namespace", (value) => {
    return value + "_modified";
  });

  const filtered = wp.hooks.applyFilters("my_filter", "test");
  expect(filtered).toBe("test_modified");
});
```

---

## Continuous Integration

### GitHub Actions Example

```yaml
# .github/workflows/test.yml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest

    steps:
      - uses: actions/checkout@v3

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 7.4
          tools: composer, phpunit

      - name: Setup Node.js
        uses: actions/setup-node@v3
        with:
          node-version: 18

      - name: Install dependencies
        run: |
          composer install
          npm install

      - name: Run PHP tests
        run: npm run test:php:coverage

      - name: Run JavaScript tests
        run: npm run test:coverage

      - name: Upload coverage
        uses: codecov/codecov-action@v3
        with:
          files: ./coverage/clover.xml
```

---

## Troubleshooting

### Common Issues

**Issue: "PHPUnit not found"**

```bash
# Solution: Ensure composer dependencies are installed
composer install
composer require --dev phpunit/phpunit:^9.0
```

**Issue: "Jest not found"**

```bash
# Solution: Install npm dependencies
npm install
npm install --save-dev jest babel-jest
```

**Issue: "WordPress test library not found"**

```bash
# Solution: Required by bootstrap.php
# Usually set up via wp-cli or composer
# See bin/install-wp-tests.sh
bash bin/install-wp-tests.sh
```

**Issue: "Tests fail with 'Illegal string offset' error"**

```bash
# Solution: PHP version issue (7.4+ required)
php --version

# If using MAMP, ensure correct PHP version:
/Applications/MAMP/bin/php/php7.4.21/bin/php --version
```

**Issue: "Module not found in Jest"**

```bash
# Solution: Check jest.config.js moduleNameMapper configuration
# Ensure module paths match actual file locations
# Update jest.config.js if needed
```

**Issue: "fetch is not defined in Node.js tests"**

```bash
# Solution: Already mocked in jest.setup.js
# If custom modules need fetch, ensure jest.setup.js is loaded
# Check package.json jest configuration
```

### Getting Help

1. Check test output for specific error messages
2. Run in verbose mode:
   ```bash
   npm run test:php -- --verbose
   npm test -- --verbose
   ```
3. Review generated coverage reports for untested code
4. Consult WordPress testing documentation:
   - https://developer.wordpress.org/plugins/testing/
5. Consult Jest documentation:
   - https://jestjs.io/docs/getting-started

---

## Next Steps

### Phase 2: Expand Test Coverage

**Priority 1 (Week 1-2):**

- [ ] Write unit tests for all `/src/helpers/` functions
- [ ] Write unit tests for all `/scripts/utils/` functions
- [ ] Reach 50% overall coverage

**Priority 2 (Week 3-4):**

- [ ] Write integration tests for WooCommerce features
- [ ] Write integration tests for checkout flow
- [ ] Reach 65% overall coverage

**Priority 3 (Week 5-6):**

- [ ] Write functional tests for complete workflows
- [ ] Set up code coverage badges
- [ ] Reach 70%+ coverage goal

### Resources

- [PHPUnit Manual](https://docs.phpunit.de/en/9.5/)
- [Jest Documentation](https://jestjs.io/)
- [WordPress Testing Handbook](https://developer.wordpress.org/plugins/testing/)
- [WooCommerce Testing](https://github.com/woocommerce/woocommerce/wiki/Running-Unit-Tests)

---

## Summary

The testing framework is now initialized with:

- ✅ PHPUnit 9.x configured for PHP tests
- ✅ Jest 29.x configured for JavaScript tests
- ✅ Code coverage reporting (HTML, Clover XML, text)
- ✅ npm test scripts for easy execution
- ✅ Sample tests demonstrating patterns
- ✅ Global mocks for WordPress and WooCommerce
- ✅ Test fixtures and utilities for advanced testing

**Ready to expand test coverage to 70%+ in Phase 2.**
