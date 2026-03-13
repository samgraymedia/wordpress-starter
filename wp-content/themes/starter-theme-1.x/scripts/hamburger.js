/**
 * Hamburger Menu JavaScript
 * Handles mobile navigation toggle
 */

document.addEventListener("DOMContentLoaded", function () {
  const hamburger = document.querySelector(".hamburger");
  const navMenu = document.querySelector(".nav-menu");

  if (!hamburger || !navMenu) return;

  // Toggle menu on hamburger click
  hamburger.addEventListener("click", function () {
    hamburger.classList.toggle("is-active");
    navMenu.classList.toggle("is-active");
  });

  // Close menu when a link is clicked
  const menuLinks = navMenu.querySelectorAll("a");
  menuLinks.forEach((link) => {
    link.addEventListener("click", function () {
      hamburger.classList.remove("is-active");
      navMenu.classList.remove("is-active");
    });
  });

  // Close menu when clicking outside
  document.addEventListener("click", function (event) {
    if (!event.target.closest("header")) {
      hamburger.classList.remove("is-active");
      navMenu.classList.remove("is-active");
    }
  });
});
