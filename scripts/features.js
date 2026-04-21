/**
 * Features Block JavaScript
 * Adds scroll-in animation to features block elements
 */

import Logger from "./utils/logger.js";
console.log("[SITE] Features module loaded");

(function () {
  "use strict";

  class FeaturesAnimation {
    constructor() {
      this.blocks = document.querySelectorAll(".features");
      Logger.debug("[Features] Initializing animation", {
        count: this.blocks.length,
      });
      this.init();
    }

    init() {
      if (!this.blocks.length) {
        Logger.debug("[Features] No features blocks found");
        return;
      }

      this.prepareAnimations();

      if (!("IntersectionObserver" in window)) {
        Logger.warn(
          "[Features] IntersectionObserver not supported, showing items immediately",
        );
        this.blocks.forEach((block) => {
          block.classList.add("features-in-view");
          block
            .querySelectorAll(".features__item")
            .forEach((item) => item.classList.add("features-item-in-view"));
        });
        return;
      }

      this.createObserver();
      this.observeBlocks();
    }

    prepareAnimations() {
      this.blocks.forEach((block) => {
        const title = block.querySelector(".features__title");
        const image = block.querySelector(".features__image");
        const description = block.querySelector(".features__description");
        const buttons = block.querySelector(".features__buttons");
        const items = block.querySelectorAll(".features__item");

        if (title) {
          title.classList.add("features-animate", "features-animate--up");
        }

        if (image) {
          image.classList.add("features-animate", "features-animate--right");
        }

        if (description) {
          description.classList.add(
            "features-animate",
            "features-animate--up",
            "features-animate--delay-1",
          );
        }

        if (buttons) {
          buttons.classList.add(
            "features-animate",
            "features-animate--up",
            "features-animate--delay-2",
          );
        }

        items.forEach((item, index) => {
          item.classList.add("features-item-animate");
          item.style.transitionDelay = `${index * 0.12}s`;
        });
      });
    }

    createObserver() {
      this.observer = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            if (!entry.isIntersecting) {
              return;
            }

            const block = entry.target;
            Logger.debug("[Features] Block entered viewport");

            block.classList.add("features-in-view");

            const items = block.querySelectorAll(".features__item");
            items.forEach((item, index) => {
              setTimeout(() => {
                item.classList.add("features-item-in-view");
              }, index * 120);
            });

            this.observer.unobserve(block);
          });
        },
        {
          threshold: 0.2,
          rootMargin: "0px 0px -10% 0px",
        },
      );
    }

    observeBlocks() {
      this.blocks.forEach((block) => {
        this.observer.observe(block);
      });
    }
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
      new FeaturesAnimation();
    });
  } else {
    new FeaturesAnimation();
  }
})();