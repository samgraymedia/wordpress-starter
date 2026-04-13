(function () {
  "use strict";

  function initCTAScroll() {
    const ctaBlock = document.querySelector(".block.cta");
    if (!ctaBlock) {
      return;
    }

    const lead = ctaBlock.querySelector(".cta__lead");
    const title = ctaBlock.querySelector(".cta__title");
    const button = ctaBlock.querySelector(".btn");

    if (!lead || !title || !button) {
      return;
    }

    let ticking = false;
    let isBlockInView = false;

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

      // Get block position relative to scroll container
      const blockRect = ctaBlock.getBoundingClientRect();
      const scrollContainerHeight =
        scrollContainer === window
          ? window.innerHeight
          : scrollContainer.clientHeight;

      // Calculate how far through the block we are (0 = top of viewport, 1 = bottom of viewport)
      const blockProgress = Math.max(
        0,
        Math.min(1, 1 - blockRect.top / scrollContainerHeight),
      );

      // Stagger animations for each element
      const leadDelay = 0;
      const titleDelay = 0.1;
      const buttonDelay = 0.2;

      const elements = [
        { el: lead, delay: leadDelay },
        { el: title, delay: titleDelay },
        { el: button, delay: buttonDelay },
      ];

      elements.forEach(({ el, delay }) => {
        // Calculate progress for this element with delay
        const elementProgress = Math.max(0, blockProgress - delay);
        const opacity = Math.min(1, elementProgress * 2); // 0 to 1 over first 50%
        const translateY = Math.max(0, (1 - elementProgress) * 30); // 30px down to 0

        el.style.opacity = opacity;
        el.style.transform = `translateY(${translateY}px)`;
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

    observer.observe(ctaBlock);
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initCTAScroll);
  } else {
    initCTAScroll();
  }
})();
