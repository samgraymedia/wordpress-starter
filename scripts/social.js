/**
 * Social Block JavaScript
 * Sets parent height to match first child's height after images load
 */

import Logger from "./utils/logger.js";
console.log("[SITE] Social block module loaded");

(function () {
	"use strict";

	function initSocialBlock() {
		const parent = document.querySelector('.social__images');
		const child = document.querySelector('.callout-socials-card-w');

		if (!parent || !child) {
			Logger.debug("[Social] Parent or child element not found");
			return;
		}

		const images = parent.querySelectorAll('img');
		let loadedCount = 0;

		const setHeight = () => {
			parent.style.height = child.offsetHeight + 'px';
			Logger.debug("[Social] Height set to", {
				height: child.offsetHeight,
				elementId: parent.closest('[id^="social-"]')?.id
			});
		};

		if (images.length === 0) {
			setHeight();
			return;
		}

		images.forEach(img => {
			if (img.complete) {
				loadedCount++;
				if (loadedCount === images.length) {
					setHeight();
				}
			} else {
				img.addEventListener('load', () => {
					loadedCount++;
					if (loadedCount === images.length) {
						setHeight();
					}
				});
				img.addEventListener('error', () => {
					loadedCount++;
					if (loadedCount === images.length) {
						setHeight();
					}
				});
			}
		});
	}

	// Initialize on DOM ready
	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", initSocialBlock);
	} else {
		initSocialBlock();
	}
})();
