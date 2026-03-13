/**
 * Gallery Block JavaScript
 * Adds lightbox functionality to gallery images
 */

(function () {
  "use strict";

  class GalleryLightbox {
    constructor() {
      this.currentIndex = 0;
      this.currentGallery = null;
      this.images = [];
      this.init();
    }

    init() {
      // Find all gallery blocks
      const galleries = document.querySelectorAll(".gallery__item");

      galleries.forEach((item) => {
        const img = item.querySelector(".gallery__image");
        if (img) {
          img.style.cursor = "pointer";
          img.addEventListener("click", (e) => this.openLightbox(e));
        }
      });
    }

    openLightbox(event) {
      const clickedImg = event.target;
      const gallerySection = clickedImg.closest(".gallery");

      if (!gallerySection) return;

      // Get all images in this gallery
      this.images = Array.from(
        gallerySection.querySelectorAll(".gallery__image"),
      );

      this.currentIndex = this.images.indexOf(clickedImg);
      this.currentGallery = gallerySection;

      this.createLightbox();
      this.showImage(this.currentIndex);
    }

    createLightbox() {
      // Remove existing lightbox if any
      const existing = document.querySelector(".gallery-lightbox");
      if (existing) existing.remove();

      const lightbox = document.createElement("div");
      lightbox.className = "gallery-lightbox";
      lightbox.innerHTML = `
        <div class="gallery-lightbox__overlay"></div>
        <div class="gallery-lightbox__container">
          <button class="gallery-lightbox__close" aria-label="Close lightbox">&times;</button>
          <img class="gallery-lightbox__image" src="" alt="">
          <div class="gallery-lightbox__caption"></div>
          <button class="gallery-lightbox__prev" aria-label="Previous image">‹</button>
          <button class="gallery-lightbox__next" aria-label="Next image">›</button>
          <div class="gallery-lightbox__counter"></div>
        </div>
      `;

      document.body.appendChild(lightbox);

      // Event listeners
      lightbox
        .querySelector(".gallery-lightbox__close")
        .addEventListener("click", () => {
          this.closeLightbox();
        });

      lightbox
        .querySelector(".gallery-lightbox__overlay")
        .addEventListener("click", () => {
          this.closeLightbox();
        });

      lightbox
        .querySelector(".gallery-lightbox__prev")
        .addEventListener("click", () => {
          this.prevImage();
        });

      lightbox
        .querySelector(".gallery-lightbox__next")
        .addEventListener("click", () => {
          this.nextImage();
        });

      // Keyboard navigation
      document.addEventListener("keydown", (e) => {
        if (!document.querySelector(".gallery-lightbox")) return;

        if (e.key === "ArrowLeft") this.prevImage();
        if (e.key === "ArrowRight") this.nextImage();
        if (e.key === "Escape") this.closeLightbox();
      });
    }

    showImage(index) {
      const lightbox = document.querySelector(".gallery-lightbox");
      if (!lightbox) return;

      const img = this.images[index];
      const lightboxImg = lightbox.querySelector(".gallery-lightbox__image");
      const caption = lightbox.querySelector(".gallery-lightbox__caption");
      const counter = lightbox.querySelector(".gallery-lightbox__counter");

      lightboxImg.src = img.src;
      lightboxImg.alt = img.alt;

      // Get caption from the gallery item
      const galleryItem = img.closest(".gallery__item");
      const captionText = galleryItem?.querySelector(".gallery__caption");
      caption.textContent = captionText ? captionText.textContent : "";

      // Update counter
      counter.textContent = `${index + 1} / ${this.images.length}`;

      // Show/hide navigation buttons
      lightbox.querySelector(".gallery-lightbox__prev").style.display =
        this.images.length > 1 ? "block" : "none";
      lightbox.querySelector(".gallery-lightbox__next").style.display =
        this.images.length > 1 ? "block" : "none";
    }

    prevImage() {
      this.currentIndex =
        (this.currentIndex - 1 + this.images.length) % this.images.length;
      this.showImage(this.currentIndex);
    }

    nextImage() {
      this.currentIndex = (this.currentIndex + 1) % this.images.length;
      this.showImage(this.currentIndex);
    }

    closeLightbox() {
      const lightbox = document.querySelector(".gallery-lightbox");
      if (lightbox) {
        lightbox.remove();
      }
      document.removeEventListener("keydown", null);
    }
  }

  // Initialize when DOM is ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
      new GalleryLightbox();
    });
  } else {
    new GalleryLightbox();
  }
})();
