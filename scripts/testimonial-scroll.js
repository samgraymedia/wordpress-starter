console.log("[Testimonial Carousel] Module loaded");

(function () {
  "use strict";

  function initTestimonialCarousel() {
    console.log("[Testimonial Carousel] initTestimonialCarousel() called");

    const slider = document.querySelector(".testimonial-slider");
    if (!slider) {
      console.log("[Testimonial Carousel] Slider not found");
      return;
    }

    const slides = slider.querySelectorAll(".testimonial-slide");
    const block = slider.closest(".block");
    const prevBtn = block.querySelector("[data-carousel-prev]");
    const nextBtn = block.querySelector("[data-carousel-next]");
    const counter = block.querySelector("[data-counter] .current");

    if (!slides.length || !prevBtn || !nextBtn) {
      console.log("[Testimonial Carousel] Controls not found");
      return;
    }

    console.log("[Testimonial Carousel] Found " + slides.length + " slides");

    let current = 0;
    let isAnimating = false;

    function show(idx) {
      if (isAnimating) return;
      isAnimating = true;

      console.log("[Testimonial Carousel] Transitioning to slide " + idx);

      // Fade out current slide
      slides[current].classList.remove("is-active");

      // Update counter
      if (counter) {
        counter.textContent = String(idx + 1).padStart(2, "0");
      }

      // Fade in next slide after transition completes
      setTimeout(() => {
        current = idx;
        slides[current].classList.add("is-active");
        isAnimating = false;
      }, 200);
    }

    // Initialize first slide
    slides[0].classList.add("is-active");

    // Set all other slides to hidden
    for (let i = 1; i < slides.length; i++) {
      slides[i].classList.remove("is-active");
    }

    // Navigation handlers
    prevBtn.addEventListener("click", () => {
      const nextIdx = (current - 1 + slides.length) % slides.length;
      show(nextIdx);
    });

    nextBtn.addEventListener("click", () => {
      const nextIdx = (current + 1) % slides.length;
      show(nextIdx);
    });

    console.log(
      "[Testimonial Carousel] Carousel initialized with smooth transitions",
    );
  }

  if (document.readyState === "loading") {
    console.log(
      "[Testimonial Carousel] DOM not ready, waiting for DOMContentLoaded",
    );
    document.addEventListener("DOMContentLoaded", initTestimonialCarousel);
  } else {
    console.log("[Testimonial Carousel] DOM ready, initializing now");
    initTestimonialCarousel();
  }
})();
