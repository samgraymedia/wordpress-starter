/**
 * Accordion Block JavaScript
 * Handles accordion expand/collapse functionality
 */

import Logger from "./utils/logger.js";
console.log("[SITE] Accordion module loaded");

(function () {
  "use strict";

  class Accordion {
    constructor(element) {
      this.element = element;
      this.triggers = this.element.querySelectorAll(".accordion__trigger");
      this.panels = this.element.querySelectorAll(".accordion__panel");
      this.items = this.element.querySelectorAll(".accordion__item");
      Logger.debug("[Accordion] Initializing", {
        id: element.dataset.accordionId,
        itemCount: this.items.length,
      });
      this.init();
    }

    init() {
      this.triggers.forEach((trigger, index) => {
        trigger.id = `accordion-trigger-${this.element.dataset.accordionId}-${index}`;
        trigger.addEventListener("click", (e) => this.toggle(e, index));
        trigger.addEventListener("keydown", (e) =>
          this.handleKeyboard(e, index),
        );
      });
      Logger.debug("[Accordion] Initialized successfully", {
        id: this.element.dataset.accordionId,
      });
    }

    toggle(event, index) {
      event.preventDefault();
      const item = this.items[index];
      const isOpen = item.classList.contains("is-open");

      if (isOpen) {
        this.close(index);
      } else {
        this.open(index);
      }
    }

    open(index) {
      const item = this.items[index];
      const trigger = this.triggers[index];
      const panel = this.panels[index];

      item.classList.add("is-open");
      trigger.setAttribute("aria-expanded", "true");
      panel.removeAttribute("hidden");
      panel.setAttribute("aria-hidden", "false");
    }

    close(index) {
      const item = this.items[index];
      const trigger = this.triggers[index];
      const panel = this.panels[index];

      item.classList.remove("is-open");
      trigger.setAttribute("aria-expanded", "false");
      panel.setAttribute("hidden", "");
      panel.setAttribute("aria-hidden", "true");
    }

    handleKeyboard(event, index) {
      switch (event.key) {
        case "Enter":
        case " ":
          event.preventDefault();
          this.toggle(event, index);
          break;
        case "ArrowDown":
          event.preventDefault();
          this.focusNext(index);
          break;
        case "ArrowUp":
          event.preventDefault();
          this.focusPrev(index);
          break;
        case "Home":
          event.preventDefault();
          this.triggers[0].focus();
          break;
        case "End":
          event.preventDefault();
          this.triggers[this.triggers.length - 1].focus();
          break;
      }
    }

    focusNext(index) {
      if (index < this.triggers.length - 1) {
        this.triggers[index + 1].focus();
      }
    }

    focusPrev(index) {
      if (index > 0) {
        this.triggers[index - 1].focus();
      }
    }
  }

  // Initialize all accordions on the page
  function initAccordions() {
    const accordionContainers = document.querySelectorAll(".accordion__items");
    accordionContainers.forEach((container) => {
      new Accordion(container);
    });
  }

  // Initialize when DOM is ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initAccordions);
  } else {
    initAccordions();
  }
})();
