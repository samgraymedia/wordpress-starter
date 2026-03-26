/**
 * Product Gallery Lightbox JavaScript
 * Adds lightbox functionality to WooCommerce product gallery images
 * Works in conjunction with data-lightbox="product-gallery" attributes
 */

(function () {
  "use strict";

  class ProductGalleryLightbox {
    constructor() {
      this.currentIndex = 0;
      this.images = [];
      this.lightboxElement = null;
      console.log("[SITE] Product Gallery module loaded");
      this.init();
    }

    init() {
      // Find all product gallery links with data-lightbox attribute
      const galleries = document.querySelectorAll(
        '[data-lightbox="product-gallery"]',
      );

      if (galleries.length === 0) {
        console.debug("[Product Gallery] No product galleries found");
        return;
      }

      console.debug("[Product Gallery] Found product gallery links", {
        count: galleries.length,
      });

      // Get all gallery image URLs from the links
      galleries.forEach((link, index) => {
        link.style.cursor = "pointer";
        link.addEventListener("click", (e) => {
          e.preventDefault();
          console.debug("[Product Gallery] Image clicked for lightbox");
          this.openLightbox(index);
        });
      });

      // Extract all image URLs
      this.images = Array.from(galleries).map((link) => ({
        src: link.href,
        alt: link.querySelector("img")?.alt || "Product image",
        title: link.querySelector("img")?.title || "",
      }));
    }

    openLightbox(index) {
      this.currentIndex = index;

      if (!this.lightboxElement) {
        this.createLightbox();
      }

      this.showImage(index);
      this.lightboxElement.style.display = "flex";

      // Prevent body scroll
      document.body.style.overflow = "hidden";
    }

    createLightbox() {
      const lightbox = document.createElement("div");
      lightbox.className = "gallery-lightbox";
      lightbox.style.display = "none";
      lightbox.innerHTML = `
        <div class="gallery-lightbox__overlay"></div>
        <div class="gallery-lightbox__container">
          <button class="gallery-lightbox__close" aria-label="Close lightbox">&times;</button>
          <img class="gallery-lightbox__image" src="" alt="">
          <div class="gallery-lightbox__caption"></div>
          ${
            this.images.length > 1
              ? '<button class="gallery-lightbox__prev" aria-label="Previous image">‹</button>'
              : ""
          }
          ${
            this.images.length > 1
              ? '<button class="gallery-lightbox__next" aria-label="Next image">›</button>'
              : ""
          }
          ${
            this.images.length > 1
              ? '<div class="gallery-lightbox__counter"></div>'
              : ""
          }
        </div>
      `;

      document.body.appendChild(lightbox);
      this.lightboxElement = lightbox;

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

      if (this.images.length > 1) {
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
      }

      // Keyboard navigation
      document.addEventListener("keydown", (e) => {
        if (
          !this.lightboxElement ||
          this.lightboxElement.style.display === "none"
        ) {
          return;
        }

        if (e.key === "ArrowLeft" && this.images.length > 1) this.prevImage();
        if (e.key === "ArrowRight" && this.images.length > 1) this.nextImage();
        if (e.key === "Escape") this.closeLightbox();
      });
    }

    showImage(index) {
      if (!this.lightboxElement) return;

      const image = this.images[index];
      const lightboxImg = this.lightboxElement.querySelector(
        ".gallery-lightbox__image",
      );
      const caption = this.lightboxElement.querySelector(
        ".gallery-lightbox__caption",
      );
      const counter = this.lightboxElement.querySelector(
        ".gallery-lightbox__counter",
      );

      lightboxImg.src = image.src;
      lightboxImg.alt = image.alt;
      if (caption) {
        caption.textContent = image.title || "";
      }

      // Update counter
      if (counter && this.images.length > 1) {
        counter.textContent = `${index + 1} / ${this.images.length}`;
      }
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
      if (this.lightboxElement) {
        this.lightboxElement.style.display = "none";
      }

      // Restore body scroll
      document.body.style.overflow = "";
    }
  }

  // Initialize on DOM ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
      new ProductGalleryLightbox();
    });
  } else {
    new ProductGalleryLightbox();
  }
})();
