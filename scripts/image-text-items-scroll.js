(function () {
  "use strict";

  function initImageTextItemsScroll() {
    const items = document.querySelectorAll(".image-text-item");
    if (items.length === 0) {
      return;
    }

    let ticking = false;
    let isBlockInView = false;
    const lastTransforms = new Map(); // Cache last transform values

    // Detect scrollable container
    const scrollContainer = (() => {
      const contentWrapper = document.getElementById("content");
      if (
        contentWrapper &&
        contentWrapper.scrollHeight > contentWrapper.clientHeight
      ) {
        return contentWrapper;
      }

      if (document.documentElement.scrollHeight > window.innerHeight) {
        return window;
      }

      return window;
    })();

    function handleScroll() {
      if (!isBlockInView) return;

      const navHeight = 100; // Fixed nav height

      items.forEach((item) => {
        const leftDiv = item.querySelector(".left");
        if (!leftDiv) return;

        const itemRect = item.getBoundingClientRect();
        const itemHeight = item.offsetHeight;
        const leftDivHeight = leftDiv.offsetHeight;

        // Calculate how far the item has scrolled past the nav
        const scrollProgress = Math.max(0, (-itemRect.top + navHeight) / itemHeight);

        // Calculate max translation (stop when left div reaches bottom of item)
        const maxTranslate = itemHeight - leftDivHeight;
        const translateY = Math.min(maxTranslate, scrollProgress * itemHeight);

        // Only update if change > 0.5px to reduce jitter
        const lastTransform = lastTransforms.get(leftDiv) ?? 0;
        if (Math.abs(translateY - lastTransform) > 0.5) {
          leftDiv.style.transform = `translateY(${Math.round(translateY * 10) / 10}px)`;
          lastTransforms.set(leftDiv, translateY);
        }
      });

      ticking = false;
    }

    function onScroll() {
      if (ticking) return;
      ticking = true;
      window.requestAnimationFrame(handleScroll);
    }

    // Use Intersection Observer to detect when block enters viewport
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            isBlockInView = true;
            scrollContainer.addEventListener("scroll", onScroll, {
              passive: true,
            });
            window.addEventListener("resize", onScroll);
            handleScroll(); // Initial call
          } else {
            isBlockInView = false;
            scrollContainer.removeEventListener("scroll", onScroll);
            window.removeEventListener("resize", onScroll);
          }
        });
      },
      { threshold: 0.1 },
    );

    // Watch the first item's container
    const firstItem = items[0];
    const container = firstItem.closest(".image-text-items");
    if (container) {
      observer.observe(container);
    }
  }

  // Initialize when DOM ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initImageTextItemsScroll);
  } else {
    initImageTextItemsScroll();
  }
})();
