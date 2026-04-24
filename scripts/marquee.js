/**
 * Marquee Block JavaScript
 * Animates images to slide off the page in a seamless infinite loop
 */

import Logger from "./utils/logger.js";
console.log("[SITE] Marquee module loaded");

(function () {
	"use strict";

	class MarqueeAnimation {
		constructor() {
			this.marquees = [];
			this.animationFrameIds = [];
			Logger.debug("[Marquee] Initializing marquee animations");
			
			// Wait for DOM and images to load before initializing
			if (document.readyState === "loading") {
				document.addEventListener("DOMContentLoaded", () => this.init());
			} else {
				this.init();
			}
		}

		init() {
			// Find all marquee blocks
			const marqueeElements = document.querySelectorAll("[id^='marquee-']");

			Logger.debug("[Marquee] Found marquee blocks", {
				count: marqueeElements.length,
			});

			marqueeElements.forEach((marqueeElement) => {
				const wrapper = marqueeElement.querySelector(".marquee__wrapper");
				const content = marqueeElement.querySelector(".marquee__content");

				if (!wrapper || !content) {
					Logger.warn("[Marquee] Marquee structure not found");
					return;
				}

				const allItems = content.querySelectorAll(".marquee__item");
				if (allItems.length === 0) {
					Logger.warn("[Marquee] No marquee items found");
					return;
				}

				// Count UNIQUE items (half of total, since duplicated)
				const uniqueItemCount = allItems.length / 2;

				const speed = this.getSpeedValue(marqueeElement);

				const marqueeData = {
					element: marqueeElement,
					wrapper,
					content,
					allItems,
					uniqueItemCount,
					itemWidth: 0,
					oneSetWidth: 0, // Width of ONE unique set (including gaps)
					speed,
					offset: 0,
					isAnimating: true,
				};

				this.marquees.push(marqueeData);
				
				// Wait for images to load, then measure and animate
				this.waitForImagesAndMeasure(marqueeData);
			});
		}

		waitForImagesAndMeasure(marqueeData) {
			// Get all images in the marquee
			const images = marqueeData.content.querySelectorAll("img");
			let imagesLoaded = 0;

			if (images.length === 0) {
				// No images, measure immediately
				this.measureAndStart(marqueeData);
				return;
			}

			const onImageLoad = () => {
				imagesLoaded++;
				if (imagesLoaded === images.length) {
					// All images loaded
					this.measureAndStart(marqueeData);
				}
			};

			// Listen for image load events
			images.forEach((img) => {
				if (img.complete) {
					// Already loaded (from cache)
					onImageLoad();
				} else {
					img.addEventListener("load", onImageLoad);
					img.addEventListener("error", onImageLoad); // Count errors too, don't wait forever
				}
			});

			// Fallback: start measuring after 2 seconds anyway
			setTimeout(() => {
				this.measureAndStart(marqueeData);
			}, 2000);
		}

		measureAndStart(marqueeData) {
			// Measure the actual width of one complete set of unique items
			this.calculateDimensions(marqueeData);
			
			// Set content width explicitly so items don't wrap
			const totalContentWidth = marqueeData.oneSetWidth * 2; // Two sets (original + duplicate)
			marqueeData.content.style.width = totalContentWidth + "px";

			Logger.debug("[Marquee] Starting animation", {
				id: marqueeData.element.id,
				oneSetWidth: marqueeData.oneSetWidth,
				totalContentWidth,
				uniqueItems: marqueeData.uniqueItemCount,
			});

			this.startAnimation(marqueeData);
		}

		getSpeedValue(marqueeElement) {
			const classes = marqueeElement.className;

			if (classes.includes("marquee--slow")) {
				return 0.025;
			} else if (classes.includes("marquee--fast")) {
				return 0.08;
			} else {
				return 0.05;
			}
		}

		calculateDimensions(marqueeData) {
			// Get the first N items (one unique set)
			const firstSetStart = 0;
			const firstSetEnd = marqueeData.uniqueItemCount;
			const firstSetItems = Array.from(marqueeData.allItems).slice(
				firstSetStart,
				firstSetEnd
			);

			if (firstSetItems.length === 0) {
				Logger.warn("[Marquee] Could not extract first set items");
				return;
			}

			// Measure from start of first item to start of its duplicate (in second set)
			// This accounts for ALL gaps between items, including the gap before the duplicate set
			const firstItem = firstSetItems[0];
			const firstDuplicateItem = marqueeData.allItems[marqueeData.uniqueItemCount];

			if (!firstDuplicateItem) {
				Logger.warn("[Marquee] Could not find duplicate set start");
				return;
			}

			// Reset point: when the first duplicate is at the same position the first item was
			// This is the distance from start of first item to start of its duplicate
			const oneSetWidth = firstDuplicateItem.offsetLeft - firstItem.offsetLeft;

			marqueeData.oneSetWidth = oneSetWidth;

			Logger.debug("[Marquee] Dimensions calculated", {
				uniqueItemCount: marqueeData.uniqueItemCount,
				oneSetWidth,
				firstItemLeft: firstItem.offsetLeft,
				firstDuplicateLeft: firstDuplicateItem.offsetLeft,
			});
		}

		startAnimation(marqueeData) {
			let lastTime = Date.now();

			const animate = () => {
				if (!marqueeData.isAnimating) {
					// Still continue the loop, just paused
					const frameId = requestAnimationFrame(animate);
					this.animationFrameIds.push(frameId);
					return;
				}

				const currentTime = Date.now();
				const deltaTime = currentTime - lastTime;
				lastTime = currentTime;

				// Continuously move left
				marqueeData.offset += marqueeData.speed * deltaTime;

				// Reset when one complete set has scrolled off
				// This puts the second set (duplicate) into view seamlessly
				if (marqueeData.offset >= marqueeData.oneSetWidth) {
					marqueeData.offset = 0;
				}

				// Apply transform to content
				marqueeData.content.style.transform = `translateX(-${marqueeData.offset}px)`;

				const frameId = requestAnimationFrame(animate);
				this.animationFrameIds.push(frameId);
			};

			animate();
		}

		destroy() {
			this.animationFrameIds.forEach((frameId) => {
				cancelAnimationFrame(frameId);
			});
			this.animationFrameIds = [];
			this.marquees = [];

			Logger.debug("[Marquee] All animations destroyed");
		}
	}

	// Initialize
	const marqueeAnimation = new MarqueeAnimation();

	// Cleanup
	window.addEventListener("beforeunload", () => {
		marqueeAnimation.destroy();
	});
})();
