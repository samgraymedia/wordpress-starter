/**
 * Mobile Navigation JavaScript
 * Handles mobile menu toggle, accessibility, and interactions
 */

(function () {
  "use strict";

  // DOM elements
  const mobileNavToggle = document.querySelector(".mobile-nav-toggle");
  const mobileNav = document.querySelector("#mobile-nav");
  const mobileNavClose = document.querySelector(".mobile-nav__close");
  const body = document.body;

  // Debug logging
  console.log("Mobile Nav Debug:", {
    mobileNavToggle: mobileNavToggle,
    mobileNav: mobileNav,
    mobileNavClose: mobileNavClose,
  });

  // State
  let isMenuOpen = false;

  /**
   * Initialize mobile navigation
   */
  function init() {
    console.log("Initializing mobile navigation...");

    if (!mobileNavToggle || !mobileNav) {
      console.error("Mobile navigation elements not found:", {
        mobileNavToggle: !!mobileNavToggle,
        mobileNav: !!mobileNav,
      });
      return;
    }

    console.log(
      "Mobile navigation elements found, setting up event listeners..."
    );

    // Event listeners
    mobileNavToggle.addEventListener("click", toggleMenu);
    if (mobileNavClose) {
      mobileNavClose.addEventListener("click", closeMenu);
    }

    // Close menu when clicking outside
    mobileNav.addEventListener("click", handleOutsideClick);

    // Keyboard navigation
    document.addEventListener("keydown", handleKeyboard);

    // Handle window resize
    window.addEventListener("resize", handleResize);

    // Handle menu link clicks
    const menuLinks = mobileNav.querySelectorAll("a");
    menuLinks.forEach((link) => {
      link.addEventListener("click", handleMenuLinkClick);
    });

    console.log("Mobile navigation initialized successfully");
  }

  /**
   * Toggle mobile menu
   */
  function toggleMenu() {
    console.log("Toggle menu called, current state:", isMenuOpen);
    if (isMenuOpen) {
      closeMenu();
    } else {
      openMenu();
    }
  }

  /**
   * Open mobile menu
   */
  function openMenu() {
    console.log("Opening mobile menu...");
    isMenuOpen = true;

    // Update ARIA attributes
    mobileNavToggle.setAttribute("aria-expanded", "true");
    mobileNav.setAttribute("aria-hidden", "false");

    // Add classes
    body.classList.add("mobile-menu-open");
    mobileNav.classList.add("is-open");

    // Focus management
    const firstFocusableElement = mobileNav.querySelector("a, button");
    if (firstFocusableElement) {
      firstFocusableElement.focus();
    }

    // Trap focus within mobile menu
    trapFocus(mobileNav);

    console.log("Mobile menu opened");
  }

  /**
   * Close mobile menu
   */
  function closeMenu() {
    console.log("Closing mobile menu...");
    isMenuOpen = false;

    // Update ARIA attributes
    mobileNavToggle.setAttribute("aria-expanded", "false");
    mobileNav.setAttribute("aria-hidden", "true");

    // Remove classes
    body.classList.remove("mobile-menu-open");
    mobileNav.classList.remove("is-open");

    // Return focus to toggle button
    mobileNavToggle.focus();

    // Remove focus trap
    removeFocusTrap();

    console.log("Mobile menu closed");
  }

  /**
   * Handle clicks outside the mobile menu
   */
  function handleOutsideClick(event) {
    if (event.target === mobileNav) {
      closeMenu();
    }
  }

  /**
   * Handle keyboard navigation
   */
  function handleKeyboard(event) {
    if (!isMenuOpen) {
      return;
    }

    // Close menu on Escape key
    if (event.key === "Escape") {
      closeMenu();
    }

    // Handle arrow key navigation
    if (event.key === "ArrowDown" || event.key === "ArrowUp") {
      event.preventDefault();
      navigateMenu(event.key);
    }
  }

  /**
   * Navigate menu with arrow keys
   */
  function navigateMenu(key) {
    const focusableElements = mobileNav.querySelectorAll("a, button");
    const currentIndex = Array.from(focusableElements).indexOf(
      document.activeElement
    );

    let nextIndex;

    if (key === "ArrowDown") {
      nextIndex =
        currentIndex < focusableElements.length - 1 ? currentIndex + 1 : 0;
    } else {
      nextIndex =
        currentIndex > 0 ? currentIndex - 1 : focusableElements.length - 1;
    }

    focusableElements[nextIndex].focus();
  }

  /**
   * Handle menu link clicks
   */
  function handleMenuLinkClick(event) {
    // Close menu when clicking on a link (unless it's a submenu toggle)
    if (!event.target.closest(".has-submenu")) {
      closeMenu();
    }
  }

  /**
   * Handle window resize
   */
  function handleResize() {
    // Close menu if screen becomes larger than mobile breakpoint
    if (window.innerWidth > 768 && isMenuOpen) {
      closeMenu();
    }
  }

  /**
   * Trap focus within mobile menu
   */
  function trapFocus(container) {
    const focusableElements = container.querySelectorAll(
      'a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])'
    );
    const firstElement = focusableElements[0];
    const lastElement = focusableElements[focusableElements.length - 1];

    function handleTabKey(event) {
      if (event.key === "Tab") {
        if (event.shiftKey) {
          if (document.activeElement === firstElement) {
            event.preventDefault();
            lastElement.focus();
          }
        } else {
          if (document.activeElement === lastElement) {
            event.preventDefault();
            firstElement.focus();
          }
        }
      }
    }

    container.addEventListener("keydown", handleTabKey);
    container._focusTrapHandler = handleTabKey;
  }

  /**
   * Remove focus trap
   */
  function removeFocusTrap() {
    if (mobileNav._focusTrapHandler) {
      mobileNav.removeEventListener("keydown", mobileNav._focusTrapHandler);
      delete mobileNav._focusTrapHandler;
    }
  }

  /**
   * Add smooth scroll behavior for anchor links
   */
  function handleSmoothScroll() {
    const anchorLinks = document.querySelectorAll('a[href^="#"]');

    anchorLinks.forEach((link) => {
      link.addEventListener("click", function (event) {
        const href = this.getAttribute("href");

        // Skip if it's just a hash
        if (href === "#") {
          return;
        }

        const target = document.querySelector(href);

        if (target) {
          event.preventDefault();

          // Close mobile menu if open
          if (isMenuOpen) {
            closeMenu();
          }

          // Smooth scroll to target
          target.scrollIntoView({
            behavior: "smooth",
            block: "start",
          });
        }
      });
    });
  }

  /**
   * Add scroll effect to header
   */
  function handleHeaderScroll() {
    const header = document.querySelector(".header");
    let lastScrollTop = 0;

    window.addEventListener("scroll", function () {
      const scrollTop =
        window.pageYOffset || document.documentElement.scrollTop;

      if (scrollTop > 50) {
        header.classList.add("is-scrolled");
      } else {
        header.classList.remove("is-scrolled");
      }

      lastScrollTop = scrollTop;
    });
  }

  // Initialize when DOM is ready
  if (document.readyState === "loading") {
    console.log("DOM loading, waiting for DOMContentLoaded...");
    document.addEventListener("DOMContentLoaded", init);
  } else {
    console.log("DOM already loaded, initializing immediately...");
    init();
  }

  // Initialize additional features
  document.addEventListener("DOMContentLoaded", function () {
    handleSmoothScroll();
    handleHeaderScroll();
  });
})();
