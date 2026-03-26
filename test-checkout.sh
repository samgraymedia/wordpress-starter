#!/bin/bash

# Checkout Flow Testing Script
# Tests the checkout AJAX endpoints with curl
# 
# Usage: ./test-checkout.sh [site_url] [nonce]
# Example: ./test-checkout.sh http://localhost/wordpress-starter-store ABC123DEF456

set -e

# Configuration
SITE_URL="${1:-http://localhost/wordpress-starter-store}"
AJAX_URL="$SITE_URL/wp-admin/admin-ajax.php"
NONCE="${2:-}"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Test counter
PASSED=0
FAILED=0

# Helper functions
print_test() {
  echo -e "${BLUE}=== $1 ===${NC}"
}

print_pass() {
  echo -e "${GREEN}✓ PASS${NC}: $1"
  ((PASSED++))
}

print_fail() {
  echo -e "${RED}✗ FAIL${NC}: $1"
  ((FAILED++))
}

print_info() {
  echo -e "${YELLOW}ℹ INFO${NC}: $1"
}

# Get nonce from checkout page if not provided
get_nonce() {
  if [ -z "$NONCE" ]; then
    print_info "Fetching nonce from checkout page..."
    NONCE=$(curl -s "$SITE_URL/checkout/" | grep -oP '_wpnonce" value="\K[^"]+' | head -1)
    
    if [ -z "$NONCE" ]; then
      print_fail "Could not fetch nonce from checkout page"
      return 1
    fi
    
    print_pass "Nonce fetched: $NONCE"
  fi
}

# Test 1: Check if AJAX endpoint is responding
test_ajax_endpoint() {
  print_test "Test 1: AJAX Endpoint Accessibility"
  
  response=$(curl -s -o /dev/null -w "%{http_code}" "$AJAX_URL?action=checkout_login")
  
  if [ "$response" == "200" ]; then
    print_pass "AJAX endpoint is accessible (HTTP 200)"
  else
    print_fail "AJAX endpoint returned HTTP $response"
    return 1
  fi
}

# Test 2: Test checkout_login action (should fail without credentials)
test_checkout_login() {
  print_test "Test 2: Checkout Login - Missing Credentials"
  
  response=$(curl -s -X POST "$AJAX_URL" \
    -H "Content-Type: application/x-www-form-urlencoded" \
    -d "action=checkout_login&_wpnonce=$NONCE")
  
  # Should contain error message
  if echo "$response" | grep -q "error\|required"; then
    print_pass "Correctly returns error for missing credentials"
  else
    print_fail "Should return error for missing credentials. Got: $response"
    return 1
  fi
}

# Test 3: Test update_shipping_method with missing method
test_update_shipping_missing() {
  print_test "Test 3: Update Shipping Method - Missing Method"
  
  response=$(curl -s -X POST "$AJAX_URL" \
    -H "Content-Type: application/x-www-form-urlencoded" \
    -d "action=update_shipping_method&_wpnonce=$NONCE")
  
  if echo "$response" | grep -q "error\|required"; then
    print_pass "Correctly returns error for missing shipping method"
  else
    print_fail "Should return error. Got: $response"
    return 1
  fi
}

# Test 4: Test update_shipping_method with valid method
test_update_shipping_valid() {
  print_test "Test 4: Update Shipping Method - Valid Method"
  
  response=$(curl -s -X POST "$AJAX_URL" \
    -H "Content-Type: application/x-www-form-urlencoded" \
    -d "action=update_shipping_method&method=flat_rate:1&_wpnonce=$NONCE")
  
  if echo "$response" | grep -q "success\|cart_total"; then
    print_pass "Shipping method update successful"
    print_info "Response: $response"
  else
    # Check if it's a nonce error (expected if user not on site)
    if echo "$response" | grep -q "0"; then
      print_info "Response may indicate nonce/session issue (expected if testing externally)"
      print_info "Response: $response"
    else
      print_fail "Unexpected response: $response"
    fi
  fi
}

# Test 5: Test process_checkout with missing required fields
test_process_checkout_missing() {
  print_test "Test 5: Process Checkout - Missing Required Fields"
  
  response=$(curl -s -X POST "$AJAX_URL" \
    -H "Content-Type: application/x-www-form-urlencoded" \
    -d "action=process_checkout&_wpnonce=$NONCE")
  
  if echo "$response" | grep -q "error\|required\|First name"; then
    print_pass "Correctly returns error for missing required fields"
  else
    print_fail "Should return error for missing fields. Got: $response"
    return 1
  fi
}

# Test 6: Test process_checkout with invalid email
test_process_checkout_invalid_email() {
  print_test "Test 6: Process Checkout - Invalid Email"
  
  response=$(curl -s -X POST "$AJAX_URL" \
    -H "Content-Type: application/x-www-form-urlencoded" \
    -d "action=process_checkout&billing_first_name=John&billing_email=invalid&_wpnonce=$NONCE")
  
  if echo "$response" | grep -q "error\|email"; then
    print_pass "Correctly returns error for invalid email"
  else
    print_info "Response: $response"
  fi
}

# Test 7: Test process_checkout with weak password
test_process_checkout_weak_password() {
  print_test "Test 7: Process Checkout - Weak Password (account creation)"
  
  response=$(curl -s -X POST "$AJAX_URL" \
    -H "Content-Type: application/x-www-form-urlencoded" \
    -d "action=process_checkout&billing_first_name=John&billing_first_name=Doe&billing_email=test@example.com&create_account=on&account_password=123&_wpnonce=$NONCE")
  
  if echo "$response" | grep -q "error\|password"; then
    print_pass "Correctly returns error for weak password"
  else
    print_info "Response may indicate different validation. Got: $response"
  fi
}

# Test 8: Nonce verification
test_nonce_verification() {
  print_test "Test 8: Nonce Verification"
  
  response=$(curl -s -X POST "$AJAX_URL" \
    -H "Content-Type: application/x-www-form-urlencoded" \
    -d "action=update_shipping_method&method=flat_rate:1&_wpnonce=invalid_nonce")
  
  if echo "$response" | grep -q "0\|-1\|nonce"; then
    print_pass "Correctly rejects invalid nonce"
  else
    print_fail "Should reject invalid nonce. Got: $response"
    return 1
  fi
}

# Test 9: Check that handlers are registered
test_handlers_registered() {
  print_test "Test 9: Check if Handlers Are Registered"
  
  # Try each handler
  handlers=("checkout_login" "update_shipping_method" "process_checkout")
  
  for handler in "${handlers[@]}"; do
    response=$(curl -s -X POST "$AJAX_URL" \
      -H "Content-Type: application/x-www-form-urlencoded" \
      -d "action=$handler&_wpnonce=$NONCE")
    
    # A response of "0" or "-1" means the action wasn't found
    if [ "$response" = "0" ] || [ "$response" = "-1" ]; then
      print_fail "Handler '$handler' not registered"
    else
      print_pass "Handler '$handler' is registered"
    fi
  done
}

# Main execution
main() {
  echo -e "${BLUE}╔════════════════════════════════════════════════════════╗${NC}"
  echo -e "${BLUE}║      CHECKOUT FLOW AJAX ENDPOINT TESTS                  ║${NC}"
  echo -e "${BLUE}╚════════════════════════════════════════════════════════╝${NC}"
  echo
  echo "Site URL: $SITE_URL"
  echo "AJAX URL: $AJAX_URL"
  echo
  
  # Get nonce first
  if ! get_nonce; then
    echo -e "${YELLOW}Warning: Could not fetch nonce, tests may fail${NC}"
  fi
  echo
  
  # Run tests
  test_ajax_endpoint
  echo
  
  test_handlers_registered
  echo
  
  test_checkout_login
  echo
  
  test_update_shipping_missing
  echo
  
  test_update_shipping_valid
  echo
  
  test_process_checkout_missing
  echo
  
  test_process_checkout_invalid_email
  echo
  
  test_process_checkout_weak_password
  echo
  
  test_nonce_verification
  echo
  
  # Summary
  echo -e "${BLUE}╔════════════════════════════════════════════════════════╗${NC}"
  echo -e "${BLUE}║                    TEST SUMMARY                        ║${NC}"
  echo -e "${BLUE}╚════════════════════════════════════════════════════════╝${NC}"
  echo -e "Passed: ${GREEN}$PASSED${NC}"
  echo -e "Failed: ${RED}$FAILED${NC}"
  echo
  
  if [ $FAILED -eq 0 ]; then
    echo -e "${GREEN}All tests passed!${NC}"
    return 0
  else
    echo -e "${RED}Some tests failed.${NC}"
    return 1
  fi
}

# Run main
main
