/**
 * Stats Block JavaScript
 * Adds scroll-in animation and count-up effect to stats block
 */

import Logger from "./utils/logger.js";
console.log("[SITE] Stats module loaded");

(function () {
  "use strict";

  class StatsAnimation {
    constructor() {
      this.blocks = document.querySelectorAll(".stats");
      Logger.debug("[Stats] Initializing animation", {
        count: this.blocks.length,
      });
      this.init();
    }

    init() {
      if (!this.blocks.length) {
        Logger.debug("[Stats] No stats blocks found");
        return;
      }

      this.prepareAnimations();

      if (!("IntersectionObserver" in window)) {
        Logger.warn(
          "[Stats] IntersectionObserver not supported, showing items immediately",
        );
        this.blocks.forEach((block) => {
          this.revealBlock(block);
        });
        return;
      }

      this.createObserver();
      this.observeBlocks();
    }

    prepareAnimations() {
      this.blocks.forEach((block) => {
        const title = block.querySelector(".stats__title");
        const items = block.querySelectorAll(".stats__item");

        if (title) {
          title.classList.add("stats-animate", "stats-animate--up");
        }

        items.forEach((item, index) => {
          item.classList.add("stats-animate", "stats-animate--up");
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

            Logger.debug("[Stats] Block entered viewport");
            this.revealBlock(entry.target);
            this.observer.unobserve(entry.target);
          });
        },
        {
          threshold: 0.25,
          rootMargin: "0px 0px -10% 0px",
        },
      );
    }

    observeBlocks() {
      this.blocks.forEach((block) => {
        this.observer.observe(block);
      });
    }

    revealBlock(block) {
      block.classList.add("stats-in-view");

      const numbers = block.querySelectorAll(".stat__number");
      numbers.forEach((number, index) => {
        setTimeout(() => {
          this.animateNumber(number);
        }, index * 150);
      });
    }

    animateNumber(element) {
      const originalText = element.textContent.trim();
      const parsed = this.parseNumber(originalText);

      if (!parsed) {
        Logger.warn("[Stats] Could not parse number", {
          value: originalText,
        });
        return;
      }

      const start = 0;
      const end = parsed.value;
      const duration = 1800;
      const startTime = performance.now();

      const updateNumber = (currentTime) => {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const easedProgress = 1 - Math.pow(1 - progress, 3);
        const currentValue = start + (end - start) * easedProgress;

        element.textContent = this.formatNumber(currentValue, parsed, progress);

        if (progress < 1) {
          window.requestAnimationFrame(updateNumber);
        } else {
          element.textContent = parsed.original;
        }
      };

      window.requestAnimationFrame(updateNumber);
    }

    parseNumber(text) {
      const cleaned = text.replace(/,/g, "").trim();
      const match = cleaned.match(/^([^0-9.-]*)(-?\d*\.?\d+)([^0-9]*)$/);

      if (!match) {
        return null;
      }

      return {
        prefix: match[1] || "",
        value: parseFloat(match[2]),
        suffix: match[3] || "",
        decimals: this.getDecimalPlaces(match[2]),
        original: text,
      };
    }

    getDecimalPlaces(value) {
      if (value.indexOf(".") === -1) {
        return 0;
      }

      return value.split(".")[1].length;
    }

    formatNumber(value, parsed, progress) {
      const factor = Math.pow(10, parsed.decimals);
      const roundedValue = Math.round(value * factor) / factor;

      let formattedNumber = "";

      if (parsed.decimals > 0) {
        formattedNumber = roundedValue.toFixed(parsed.decimals);
      } else {
        formattedNumber = Math.round(roundedValue).toLocaleString();
      }

      if (progress < 1 && parsed.decimals === 0) {
        formattedNumber = Math.round(roundedValue).toLocaleString();
      }

      return `${parsed.prefix}${formattedNumber}${parsed.suffix}`;
    }
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
      new StatsAnimation();
    });
  } else {
    new StatsAnimation();
  }
})();