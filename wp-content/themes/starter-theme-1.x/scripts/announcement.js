/**
 * Announcement Bar Block JavaScript
 * Handles dismiss functionality with localStorage persistence
 */

(function () {
  "use strict";

  class AnnouncementBar {
    constructor(element) {
      this.element = element;
      this.id = element.dataset.announcementId;
      this.closeButton = element.querySelector(".announcement__close");
      this.init();
    }

    init() {
      // Check if this announcement has been dismissed
      if (this.isDismissed()) {
        this.element.classList.add("is-hidden");
        return;
      }

      // Attach close button listener
      if (this.closeButton) {
        this.closeButton.addEventListener("click", (e) => {
          e.preventDefault();
          this.dismiss();
        });

        // Keyboard: Enter or Space to close
        this.closeButton.addEventListener("keydown", (e) => {
          if (e.key === "Enter" || e.key === " ") {
            e.preventDefault();
            this.dismiss();
          }
        });
      }
    }

    dismiss() {
      // Store dismissal in localStorage
      this.setDismissed(true);

      // Animate out
      this.element.style.animation = "slideUp 0.3s ease-out forwards";

      // Remove after animation
      setTimeout(() => {
        this.element.classList.add("is-hidden");
      }, 300);
    }

    isDismissed() {
      const storage = window.localStorage.getItem(this.getStorageKey());
      return storage === "dismissed";
    }

    setDismissed(value) {
      if (value) {
        window.localStorage.setItem(this.getStorageKey(), "dismissed");
      } else {
        window.localStorage.removeItem(this.getStorageKey());
      }
    }

    getStorageKey() {
      return `announcement_${this.id}`;
    }
  }

  // Initialize all announcement bars on the page
  function initAnnouncementBars() {
    const announcements = document.querySelectorAll(".announcement");
    announcements.forEach((announcement) => {
      new AnnouncementBar(announcement);
    });
  }

  // Initialize when DOM is ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initAnnouncementBars);
  } else {
    initAnnouncementBars();
  }
})();
