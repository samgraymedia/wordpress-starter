/**
 * Tabs Block JavaScript
 * Handles tab switching with keyboard navigation support
 */

import Logger from "./utils/logger.js";
console.log("[SITE] Tabs module loaded");

function initTabs() {
  const tabsBlocks = document.querySelectorAll(".tabs-block");

  Logger.debug("[Tabs] Found", { tabCount: tabsBlocks.length });

  tabsBlocks.forEach(function (tabsBlock) {
    const buttons = tabsBlock.querySelectorAll(".tabs-block__button");
    const panels = tabsBlock.querySelectorAll(".tabs-block__panel");

    Logger.debug("[Tabs] Initializing tabs block", {
      tabCount: buttons.length,
    });

    // Click handler for tab buttons
    buttons.forEach((button, index) => {
      button.addEventListener("click", function () {
        Logger.debug("[Tabs] Tab clicked", { index });
        activateTab(index, buttons, panels);
      });

      // Keyboard navigation
      button.addEventListener("keydown", function (event) {
        let targetIndex = null;

        switch (event.key) {
          case "ArrowRight":
          case "ArrowDown":
            event.preventDefault();
            targetIndex = index === buttons.length - 1 ? 0 : index + 1;
            break;
          case "ArrowLeft":
          case "ArrowUp":
            event.preventDefault();
            targetIndex = index === 0 ? buttons.length - 1 : index - 1;
            break;
          case "Home":
            event.preventDefault();
            targetIndex = 0;
            break;
          case "End":
            event.preventDefault();
            targetIndex = buttons.length - 1;
            break;
        }

        if (targetIndex !== null) {
          activateTab(targetIndex, buttons, panels);
          buttons[targetIndex].focus();
        }
      });
    });
  });

  /**
   * Activate a specific tab
   */
  function activateTab(index, buttons, panels) {
    // Remove active class from all buttons and panels
    buttons.forEach((btn) => btn.classList.remove("is-active"));
    panels.forEach((panel) => panel.classList.remove("is-active"));

    // Update tabindex for keyboard navigation
    buttons.forEach((btn, i) => {
      btn.setAttribute("tabindex", i === index ? "0" : "-1");
      btn.setAttribute("aria-selected", i === index ? "true" : "false");
    });

    // Hide all panels and show the active one
    panels.forEach((panel, i) => {
      if (i === index) {
        panel.removeAttribute("hidden");
        panel.classList.add("is-active");
      } else {
        panel.setAttribute("hidden", "");
      }
    });

    // Add active class to button and panel
    buttons[index].classList.add("is-active");
    panels[index].classList.add("is-active");
  }
}

// Initialize when DOM is ready or immediately if already loaded
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initTabs);
} else {
  initTabs();
}

// Initialize when DOM is ready or immediately if already loaded
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initTabs);
} else {
  initTabs();
}
