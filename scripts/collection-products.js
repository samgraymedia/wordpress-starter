(function () {
  "use strict";

  function initCollectionProductsScroll() {
    const scrollItems = document.querySelectorAll(".js-scroll-translate");

    if (!scrollItems.length) {
      console.log("[Collection Products] No items found, returning");
      return;
    }

    let ticking = false;
    let isAnimating = false;

    function handleScroll() {
      if (!isAnimating) return;

      const scrollY =
        scrollContainer === window
          ? window.scrollY || window.pageYOffset
          : scrollContainer.scrollTop;
      const maxScroll =
        scrollContainer === window
          ? document.documentElement.scrollHeight - window.innerHeight
          : scrollContainer.scrollHeight - scrollContainer.clientHeight;
      const scrollPercent = maxScroll > 0 ? scrollY / maxScroll : 0;

      scrollItems.forEach((item, i) => {
        const index = Number(item.getAttribute("data-index")) || i + 1;
        const moveY = Math.min(
          scrollPercent * index * 100 * speedMultiplier,
          100,
        );
        item.style.transform = `translate(0%, ${50 - moveY}%)`;
      });

      ticking = false;
    }

    function onScroll() {
      if (ticking) return;
      ticking = true;
      window.requestAnimationFrame(handleScroll);
    }

    // Detect scrollable container
    const scrollContainer = (() => {
      // First, check for the site's main scrollable container
      const contentWrapper = document.getElementById("content");
      if (
        contentWrapper &&
        contentWrapper.scrollHeight > contentWrapper.clientHeight
      ) {
        return contentWrapper;
      }

      // Check if document.documentElement is scrollable
      if (document.documentElement.scrollHeight > window.innerHeight) {
        return window;
      }

      // Check if body is scrollable
      if (document.body.scrollHeight > window.innerHeight) {
        return window;
      }

      // Find parent container with overflow
      let parent = scrollItems[0]?.parentElement;
      while (parent) {
        const overflow = window.getComputedStyle(parent).overflow;
        if (
          overflow === "auto" ||
          overflow === "scroll" ||
          overflow === "scroll-y"
        ) {
          return parent;
        }
        parent = parent.parentElement;
      }

      return window;
    })();

    function handleScrollEvent() {
      if (ticking) return;
      ticking = true;
      window.requestAnimationFrame(handleScroll);
    }

    // Find the collection products block
    const block = document.querySelector(".collection-products");
    if (!block) {
      return;
    }

    // Get speed multiplier from data attribute (default 1 = normal speed)
    const speedMultiplier = parseFloat(block.getAttribute("data-speed")) || 1;

    // Use Intersection Observer to detect when block enters viewport
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            isAnimating = true;
            scrollContainer.addEventListener("scroll", handleScrollEvent, {
              passive: true,
            });
            window.addEventListener("resize", handleScrollEvent);
            handleScroll(); // Initial call
          } else {
            isAnimating = false;
            scrollContainer.removeEventListener("scroll", handleScrollEvent);
            window.removeEventListener("resize", handleScrollEvent);
          }
        });
      },
      { threshold: 0.1 },
    );

    observer.observe(block);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initCollectionProductsScroll);
  } else {
    initCollectionProductsScroll();
  }
})();
