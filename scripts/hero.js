/**
 * Hero Block JavaScript
 * Runs animation on load (no scroll trigger)
 */

import Logger from "./utils/logger.js";
console.log("[SITE] Hero module loaded");

(function () {
  "use strict";

  class HeroAnimation {
    constructor() {
      this.blocks = document.querySelectorAll(".hero");
      Logger.debug("[Hero] Initializing animation", {
        count: this.blocks.length,
      });
      this.init();
    }

    init() {
      if (!this.blocks.length) {
        return;
      }

      this.prepareAnimations();
      this.runAnimation();
    }

    prepareAnimations() {
      this.blocks.forEach((block) => {
        const overlay = block.querySelector(".hero__overlay");
        const content = block.querySelector(".hero__content");
        const title = block.querySelector(".hero__title");
        const styled = title ? title.querySelector("span") : null;
        const video = block.querySelector(".hero__video");

        if (overlay) {
          overlay.classList.add("hero-animate", "hero-animate--fade");
        }

        if (content) {
          content.classList.add("hero-animate", "hero-animate--up");
        }

        if (title) {
          title.classList.add("hero-animate", "hero-animate--up", "hero-animate--delay-1");
        }

        if (styled) {
          styled.classList.add("hero-animate", "hero-animate--up", "hero-animate--delay-2");
        }

        if (video) {
          video.classList.add("hero-animate", "hero-animate--zoom");
        }
      });
    }

    runAnimation() {
      // slight delay so it doesn’t feel instant
      setTimeout(() => {
        this.blocks.forEach((block) => {
          block.classList.add("hero-in-view");
        });
        Logger.debug("[Hero] Animation triggered");
      }, 100);
    }
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
      new HeroAnimation();
    });
  } else {
    new HeroAnimation();
  }
})();