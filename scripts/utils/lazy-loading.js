/**
 * Image Lazy-Loading Utility
 *
 * Provides IntersectionObserver-based lazy loading for images with support for:
 * - Native loading="lazy" attribute fallback
 * - Blurred placeholder effect
 * - Multiple image formats (srcset, picture elements)
 * - Background images
 * - Error handling and retry logic
 *
 * Usage:
 *   - Add data-src to img tag for src attribute
 *   - Add data-srcset to img tag for srcset attribute
 *   - Use <picture> element with <source data-srcset>
 *   - Add loading="lazy" for progressive enhancement
 */

export class LazyLoader {
  constructor(options = {}) {
    this.options = {
      rootMargin: options.rootMargin || "50px",
      threshold: options.threshold || 0.01,
      retries: options.retries || 3,
      retryDelay: options.retryDelay || 1000,
      ...options,
    };

    this.retryMap = new Map();
    this.init();
  }

  init() {
    // Only initialize if IntersectionObserver is supported
    if (!("IntersectionObserver" in window)) {
      this.loadAllImmediately();
      return;
    }

    this.observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            this.loadImage(entry.target);
          }
        });
      },
      {
        rootMargin: this.options.rootMargin,
        threshold: this.options.threshold,
      },
    );

    // Observe all lazy images
    document.querySelectorAll("[data-src], [data-srcset]").forEach((img) => {
      this.observer.observe(img);
    });

    // Observe picture elements with lazy sources
    document.querySelectorAll("picture [data-srcset]").forEach((source) => {
      const picture = source.closest("picture");
      if (picture) {
        this.observer.observe(picture);
      }
    });

    // Observe background images
    document.querySelectorAll("[data-bg-image]").forEach((element) => {
      this.observer.observe(element);
    });
  }

  /**
   * Load a single image
   */
  loadImage(element) {
    // Handle picture elements
    if (element.tagName === "PICTURE") {
      const sources = element.querySelectorAll("source[data-srcset]");
      sources.forEach((source) => {
        source.srcset = source.dataset.srcset;
        source.removeAttribute("data-srcset");
      });
      const img = element.querySelector("img");
      if (img && img.dataset.src) {
        this.loadImageElement(img);
      }
      this.observer.unobserve(element);
      return;
    }

    // Handle regular img elements
    if (element.tagName === "IMG") {
      this.loadImageElement(element);
      return;
    }

    // Handle background images
    if (element.dataset.bgImage) {
      this.loadBackgroundImage(element);
      return;
    }
  }

  /**
   * Load a regular image element
   */
  loadImageElement(img) {
    // Skip if already loaded
    if (img.classList?.contains("lazy--loaded")) {
      return;
    }

    const src = img.dataset.src;
    const srcset = img.dataset.srcset;

    if (!src && !srcset) {
      this.observer?.unobserve(img);
      return;
    }

    // Mark as loading
    img.classList?.add("lazy--loading");

    // Set up load handler
    const onLoad = () => {
      img.classList?.remove("lazy--loading");
      img.classList?.add("lazy--loaded");
      img.removeAttribute("data-src");
      img.removeAttribute("data-srcset");
      this.observer?.unobserve(img);

      // Emit custom event
      img.dispatchEvent(new CustomEvent("image-lazy-loaded", { detail: img }));
    };

    const onError = () => {
      img.classList?.remove("lazy--loading");
      img.classList?.add("lazy--error");

      // Retry logic
      const retries = this.retryMap.get(img) || 0;
      if (retries < this.options.retries) {
        this.retryMap.set(img, retries + 1);
        setTimeout(() => {
          img.classList?.remove("lazy--error");
          this.loadImageElement(img);
        }, this.options.retryDelay);
      } else {
        img.dispatchEvent(
          new CustomEvent("image-lazy-failed", { detail: img }),
        );
        this.observer?.unobserve(img);
      }
    };

    img.addEventListener("load", onLoad, { once: true });
    img.addEventListener("error", onError, { once: true });

    // Load the image
    if (srcset) {
      img.srcset = srcset;
    }
    if (src) {
      img.src = src;
    }
  }

  /**
   * Load a background image
   */
  loadBackgroundImage(element) {
    const bgImage = element.dataset.bgImage;

    if (!bgImage) {
      return;
    }

    element.classList?.add("lazy--loading");

    // Preload the image
    const img = new Image();
    img.onload = () => {
      element.style.backgroundImage = `url('${bgImage}')`;
      element.classList?.remove("lazy--loading");
      element.classList?.add("lazy--loaded");
      element.removeAttribute("data-bg-image");
      this.observer?.unobserve(element);
    };

    img.onerror = () => {
      element.classList?.remove("lazy--loading");
      element.classList?.add("lazy--error");
      element.dispatchEvent(
        new CustomEvent("image-lazy-failed", { detail: element }),
      );
      this.observer?.unobserve(element);
    };

    img.src = bgImage;
  }

  /**
   * Load all images immediately (fallback for no IntersectionObserver support)
   */
  loadAllImmediately() {
    document.querySelectorAll("[data-src], [data-srcset]").forEach((img) => {
      this.loadImage(img);
    });

    document.querySelectorAll("picture [data-srcset]").forEach((source) => {
      const picture = source.closest("picture");
      if (picture) {
        this.loadImage(picture);
      }
    });

    document.querySelectorAll("[data-bg-image]").forEach((element) => {
      this.loadBackgroundImage(element);
    });
  }

  /**
   * Add observer to a dynamically added element
   */
  observe(element) {
    if (this.observer) {
      this.observer.observe(element);
    } else {
      // Fallback: load immediately if no observer
      this.loadImage(element);
    }
  }

  /**
   * Unobserve and clean up
   */
  destroy() {
    if (this.observer) {
      this.observer.disconnect();
    }
    this.retryMap.clear();
  }
}

/**
 * Initialize lazy loading when DOM is ready
 */
export function initLazyLoading(options = {}) {
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
      return new LazyLoader(options);
    });
  } else {
    return new LazyLoader(options);
  }
}

// Auto-initialize if not in a module context
if (typeof module === "undefined" && typeof Symbol === "undefined") {
  try {
    window.lazyLoader = initLazyLoading();
  } catch (e) {
    // Silently fail in non-browser contexts
  }
}
