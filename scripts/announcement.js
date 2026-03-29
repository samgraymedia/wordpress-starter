/**
 * Announcement Bar Block JavaScript
 * Handles auto-play slider functionality for multiple announcements
 */

import Logger from "./utils/logger.js";
console.log("[SITE] Announcement module loaded");

(function () {
  "use strict";

  class AnnouncementSlider {
    constructor(element) {
      this.element = element;
      this.id = element.dataset.announcementId;
      this.totalItems = parseInt(element.dataset.totalItems, 10);
      this.currentIndex = 0;
      this.autoPlayInterval = null;
      this.autoPlayDelay = 5000; // 5 seconds

      Logger.debug("[Announcement] Initializing slider", {
        id: this.id,
        totalItems: this.totalItems,
      });

      this.init();
    }

    getSlides() {
      return this.element.querySelectorAll(".announcement__slide");
    }

    init() {
      // Only initialize slider if there are multiple items
      if (this.totalItems <= 1) {
        Logger.debug("[Announcement] Single item, no slider needed", {
          id: this.id,
        });
        return;
      }

      // Show first slide
      this.updateSlide();

      // Start auto-play
      this.startAutoPlay();
      Logger.debug("[Announcement] Slider initialized with auto-play", {
        id: this.id,
        slides: this.totalItems,
        delay: this.autoPlayDelay,
      });
    }

    startAutoPlay() {
      if (this.autoPlayInterval) clearInterval(this.autoPlayInterval);

      this.autoPlayInterval = setInterval(() => {
        this.nextSlide();
      }, this.autoPlayDelay);

      // Reset timer on element hover
      this.element.addEventListener("mouseenter", () => {
        this.pauseAutoPlay();
      });

      this.element.addEventListener("mouseleave", () => {
        this.startAutoPlay();
      });
    }

    pauseAutoPlay() {
      if (this.autoPlayInterval) {
        clearInterval(this.autoPlayInterval);
        this.autoPlayInterval = null;
      }
    }

    nextSlide() {
      this.currentIndex = (this.currentIndex + 1) % this.totalItems;
      this.updateSlide();
      Logger.debug("[Announcement] Auto-slide to next", {
        id: this.id,
        index: this.currentIndex,
      });
    }

    updateSlide() {
      const slides = this.getSlides();

      // Hide all slides
      slides.forEach((slide) => {
        slide.classList.remove("is-active");
      });

      // Show current slide
      if (slides[this.currentIndex]) {
        slides[this.currentIndex].classList.add("is-active");
        Logger.debug("[Announcement] Updated slide", {
          id: this.id,
          currentIndex: this.currentIndex,
          totalSlides: slides.length,
        });
      }
    }
  }

  // Initialize all announcement sliders on the page
  function initAnnouncementSliders() {
    const announcements = document.querySelectorAll(".announcement--slider");
    announcements.forEach((announcement) => {
      new AnnouncementSlider(announcement);
    });
  }

  // Initialize when DOM is ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initAnnouncementSliders);
  } else {
    initAnnouncementSliders();
  }
})();
