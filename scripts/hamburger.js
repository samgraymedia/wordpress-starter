/**
 * Hamburger Menu JavaScript
 * Handles mobile navigation toggle
 */

import Logger from "./utils/logger.js";
console.log("[SITE] Hamburger module loaded");

function initHamburger() {
  const hamburger = document.querySelector(".hamburger");
  const navMenu = document.querySelector(".nav-menu");

  if (!hamburger || !navMenu) {
    Logger.debug("[Hamburger] Menu elements not found");
    return;
  }

  Logger.debug("[Hamburger] Initializing...");

  // Toggle menu on hamburger click
  hamburger.addEventListener("click", function () {
    hamburger.classList.toggle("is-active");
    navMenu.classList.toggle("is-active");
    Logger.debug("[Hamburger] Menu toggled", {
      isOpen: hamburger.classList.contains("is-active"),
    });
  });

  // Close menu when a link is clicked
  const menuLinks = navMenu.querySelectorAll("a");
  menuLinks.forEach((link) => {
    link.addEventListener("click", function () {
      hamburger.classList.remove("is-active");
      navMenu.classList.remove("is-active");
      Logger.debug("[Hamburger] Menu closed by link click");
    });
  });

  // Close menu when clicking outside
  document.addEventListener("click", function (event) {
    if (!event.target.closest("header")) {
      hamburger.classList.remove("is-active");
      navMenu.classList.remove("is-active");
    }
  });
}

// Initialize when DOM is ready or immediately if already loaded
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initHamburger);
} else {
  initHamburger();
}
