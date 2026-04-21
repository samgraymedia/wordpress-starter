/**
 * Expertise Block JavaScript
 * Adds scroll-in animation to expertise items
 */

import Logger from "./utils/logger.js";
console.log("[SITE] Expertise module loaded");

(function () {
  "use strict";

  class ExpertiseAnimation {
    constructor() {
      this.wrappers = document.querySelectorAll(".expertise__wrapper");
      Logger.debug("[Expertise] Initializing animation", {
        count: this.wrappers.length,
      });
      this.init();
    }

    init() {
      if (!this.wrappers.length) {
        Logger.debug("[Expertise] No expertise wrappers found");
        return;
      }

      if (!("IntersectionObserver" in window)) {
        Logger.warn(
          "[Expertise] IntersectionObserver not supported, showing items immediately",
        );
        this.wrappers.forEach((wrapper) => {
          wrapper.classList.add("expertise-in-view");
        });
        return;
      }

      this.createObserver();
      this.observeWrappers();
    }

    createObserver() {
      this.observer = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            if (!entry.isIntersecting) {
              return;
            }

            Logger.debug("[Expertise] Wrapper entered viewport");
            entry.target.classList.add("expertise-in-view");
            this.observer.unobserve(entry.target);
          });
        },
        {
          threshold: 0.2,
          rootMargin: "0px 0px -10% 0px",
        },
      );
    }

    observeWrappers() {
      this.wrappers.forEach((wrapper) => {
        this.observer.observe(wrapper);
      });
    }
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
      new ExpertiseAnimation();
    });
  } else {
    new ExpertiseAnimation();
  }
})();