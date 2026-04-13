console.log("[Image Pair] Module loaded");

(function () {
  "use strict";

  console.log("[Image Pair] IIFE executing immediately");

  function initImagePairScroll() {
    console.log("[Image Pair] initImagePairScroll() called");

    const imagePairBlocks = document.querySelectorAll(".image-pair");
    console.log(
      "[Image Pair] Found " + imagePairBlocks.length + " image pair blocks"
    );

    if (!imagePairBlocks.length) {
      console.log("[Image Pair] No image pair blocks found, returning");
      return;
    }

    let ticking = false;

    // Detect scrollable container (same as collection-products.js)
    const scrollContainer = (() => {
      const contentWrapper = document.getElementById("content");
      if (
        contentWrapper &&
        contentWrapper.scrollHeight > contentWrapper.clientHeight
      ) {
        console.log("[Image Pair] Using #content container for scroll");
        return contentWrapper;
      }

      if (document.documentElement.scrollHeight > window.innerHeight) {
        console.log("[Image Pair] Using window for scroll");
        return window;
      }

      return window;
    })();

    function handleScroll() {
      imagePairBlocks.forEach((block) => {
        const image2 = block.querySelector(".image-pair__image-1");
        if (!image2) return;

        // Check if block is in viewport
        const rect = block.getBoundingClientRect();
        const isInView =
          rect.top < window.innerHeight && rect.bottom > 0;

        if (!isInView) {
          return;
        }

        // Calculate scroll percentage based on block position
        const scrollY =
          scrollContainer === window
            ? window.scrollY || window.pageYOffset
            : scrollContainer.scrollTop;
        const blockTop = block.offsetTop;
        const windowHeight = window.innerHeight;

        // Distance from top of viewport to block top
        const distanceFromViewport = blockTop - scrollY;
        
        // Calculate parallax amount (move more as you scroll past)
        let translateAmount = 0;
        if (distanceFromViewport < windowHeight) {
          translateAmount = (windowHeight - distanceFromViewport) * 0.15;
        }

        console.log(
          "[Image Pair] scrollY: " + scrollY +
          ", blockTop: " + blockTop +
          ", distanceFromViewport: " + distanceFromViewport +
          ", translateAmount: " + translateAmount.toFixed(2) + "px"
        );

        image2.style.transform =
          "translate3d(0, " + translateAmount.toFixed(2) + "px, 0)";
      });

      ticking = false;
    }

    function onScroll() {
      console.log("[Image Pair] onScroll triggered!");
      if (ticking) return;
      ticking = true;
      window.requestAnimationFrame(handleScroll);
    }

    // Add scroll listener to the CORRECT container
    scrollContainer.addEventListener("scroll", onScroll, { passive: true });
    window.addEventListener("resize", onScroll);

    // Initial call
    handleScroll();

    console.log("[Image Pair] Scroll listener attached to scrollContainer");
  }

  // Initialize when DOM is ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initImagePairScroll);
  } else {
    // DOM already loaded, use a small delay to ensure all content rendered
    setTimeout(initImagePairScroll, 100);
  }
})();
