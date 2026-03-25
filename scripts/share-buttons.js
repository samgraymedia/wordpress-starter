/**
 * Share Buttons Block
 * Handles social sharing functionality
 */

import Logger from "./utils/logger.js";
console.log("[SITE] Share Buttons module loaded");

function initShareButtons() {
  const shareButtons = document.querySelectorAll(".share-buttons__button");

  Logger.debug("[Share Buttons] Found buttons", { count: shareButtons.length });

  shareButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      // Email links should use default behavior
      if (this.getAttribute("data-platform") === "email") {
        return;
      }

      e.preventDefault();

      const url = this.getAttribute("href");
      const platform = this.getAttribute("data-platform");
      const width = 600;
      const height = 400;
      const left = (window.innerWidth - width) / 2;
      const top = (window.innerHeight - height) / 2;

      Logger.info("[Share Buttons] Sharing", { platform });

      // Open share dialog in a popup window
      window.open(
        url,
        `share-${platform}`,
        `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=yes`,
      );

      // Track the share action (optional - for analytics)
      trackShare(platform);
    });
  });
}

/**
 * Track share events (optional analytics tracking)
 * @param {string} platform - The social platform being shared to
 */
function trackShare(platform) {
  Logger.debug("[Share Buttons] Tracking share event", { platform });

  // If using Google Analytics, you can track the event
  if (typeof gtag !== "undefined") {
    gtag("event", "share", {
      method: platform,
      content_type:
        document.querySelector("body").getAttribute("data-post-type") || "page",
      content_id:
        document.querySelector("body").getAttribute("data-post-id") || "",
    });
  }

  // Optional: Send to server for custom analytics
  if (navigator.sendBeacon) {
    navigator.sendBeacon(
      "/wp-admin/admin-ajax.php",
      new FormData(
        Object.assign(document.createElement("form"), {
          method: "POST",
          innerHTML: `
          <input type="hidden" name="action" value="track_share">
          <input type="hidden" name="platform" value="${platform}">
          <input type="hidden" name="nonce" value="${
            window.shareButtonsNonce || ""
          }">
        `,
        }),
      ),
    );
  }
}

// Initialize when DOM is ready or immediately if already loaded
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initShareButtons);
} else {
  initShareButtons();
}
