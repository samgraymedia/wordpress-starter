/**
 * AnimatedArrow Component
 * Handles animated SVG arrow hover effects for buttons
 *
 * Usage: Just include this in your main script bundle
 * The component automatically initializes on DOMContentLoaded
 */

export function initAnimatedArrows() {
  document.querySelectorAll(".btn .animated-arrow").forEach((arrow) => {
    const btn = arrow.closest(".btn");
    const path = arrow.querySelector("path");
    const rect = arrow.querySelector("rect");

    btn.addEventListener("mouseenter", () => {
      path.style.strokeDashoffset = "0.001";
      path.style.strokeDasharray = "0px, 999999px";
      rect.style.strokeDashoffset = "-11.1163";
      rect.style.strokeDasharray = "22.2426px, 0.105px";
    });

    btn.addEventListener("mouseleave", () => {
      path.style.strokeDashoffset = "0";
      path.style.strokeDasharray = "18.5px, 0.1px";
      rect.style.strokeDashoffset = "0";
      rect.style.strokeDasharray = "11.1213px, 11.2213px";
    });
  });
}

// Auto-initialize on DOMContentLoaded
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initAnimatedArrows);
} else {
  initAnimatedArrows();
}
