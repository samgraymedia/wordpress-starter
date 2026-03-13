/**
 * Slider / Carousel Block JavaScript
 * Handles carousel navigation and autoplay functionality
 */

(function () {
  "use strict";

  class Slider {
    constructor(element) {
      this.slider = element;
      this.track = element.querySelector(".slider__track");
      this.slides = element.querySelectorAll(".slider__slide");
      this.prevBtn = element.querySelector(".slider__nav--prev");
      this.nextBtn = element.querySelector(".slider__nav--next");
      this.dots = element.querySelectorAll(".slider__dot");

      this.currentIndex = 0;
      this.totalSlides = this.slides.length;
      this.autoplay =
        element.dataset.autoplay === "true" || element.dataset.autoplay === "1";
      this.interval = parseInt(element.dataset.interval) || 5000;
      this.autoplayInterval = null;
      this.touchStartX = null;

      this.init();
    }

    init() {
      // Event listeners
      if (this.prevBtn) {
        this.prevBtn.addEventListener("click", () => this.prevSlide());
      }

      if (this.nextBtn) {
        this.nextBtn.addEventListener("click", () => this.nextSlide());
      }

      this.dots.forEach((dot) => {
        dot.addEventListener("click", (e) => {
          this.goToSlide(parseInt(e.target.dataset.index));
        });
      });

      // Touch support
      this.track.addEventListener(
        "touchstart",
        (e) => this.handleTouchStart(e),
        false,
      );
      this.track.addEventListener(
        "touchend",
        (e) => this.handleTouchEnd(e),
        false,
      );

      // Keyboard navigation
      document.addEventListener("keydown", (e) => {
        if (e.key === "ArrowLeft") this.prevSlide();
        if (e.key === "ArrowRight") this.nextSlide();
      });

      // Pause autoplay on hover
      this.slider.addEventListener("mouseenter", () => this.pauseAutoplay());
      this.slider.addEventListener("mouseleave", () => this.resumeAutoplay());

      // Start autoplay
      if (this.autoplay) {
        this.startAutoplay();
      }

      // Update on initial load
      this.updateSlider();
    }

    handleTouchStart(e) {
      this.touchStartX = e.touches[0].clientX;
      this.pauseAutoplay();
    }

    handleTouchEnd(e) {
      const touchEndX = e.changedTouches[0].clientX;
      const diff = this.touchStartX - touchEndX;

      if (diff > 50) {
        this.nextSlide();
      } else if (diff < -50) {
        this.prevSlide();
      }

      this.resumeAutoplay();
    }

    nextSlide() {
      this.currentIndex = (this.currentIndex + 1) % this.totalSlides;
      this.updateSlider();
      this.resetAutoplay();
    }

    prevSlide() {
      this.currentIndex =
        (this.currentIndex - 1 + this.totalSlides) % this.totalSlides;
      this.updateSlider();
      this.resetAutoplay();
    }

    goToSlide(index) {
      if (index >= 0 && index < this.totalSlides) {
        this.currentIndex = index;
        this.updateSlider();
        this.resetAutoplay();
      }
    }

    updateSlider() {
      // Update track position
      const offset = -this.currentIndex * 100;
      this.track.style.transform = `translateX(${offset}%)`;

      // Update active dot
      this.dots.forEach((dot, index) => {
        dot.classList.toggle("active", index === this.currentIndex);
      });
    }

    startAutoplay() {
      if (this.totalSlides > 1) {
        this.autoplayInterval = setInterval(() => {
          this.nextSlide();
        }, this.interval);
      }
    }

    pauseAutoplay() {
      if (this.autoplayInterval) {
        clearInterval(this.autoplayInterval);
        this.autoplayInterval = null;
      }
    }

    resumeAutoplay() {
      if (this.autoplay && !this.autoplayInterval) {
        this.startAutoplay();
      }
    }

    resetAutoplay() {
      this.pauseAutoplay();
      if (this.autoplay) {
        this.startAutoplay();
      }
    }
  }

  // Initialize all sliders on the page
  function initSliders() {
    const sliders = document.querySelectorAll(".slider");
    sliders.forEach((slider) => {
      new Slider(slider);
    });
  }

  // Initialize when DOM is ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initSliders);
  } else {
    initSliders();
  }
})();
