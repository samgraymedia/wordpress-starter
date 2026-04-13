(function() {
	const cursorDiv = document.querySelector('.cursor');
	if (!cursorDiv) return;

	let mouseX = 0;
	let mouseY = 0;
	let isVisible = false;
	const offset = { x: 25, y: 20 }; // Offset to sit to bottom-right of cursor

	// Track mouse position
	document.addEventListener('mousemove', (e) => {
		mouseX = e.clientX;
		mouseY = e.clientY;

		// Update cursor position if visible
		if (isVisible) {
			updateCursorPosition();
		}
	});

	// Update the cursor div position
	function updateCursorPosition() {
		cursorDiv.style.transform = `translate3d(${mouseX + offset.x}px, ${mouseY + offset.y}px, 0)`;
	}

	// Show cursor on hover of .show-cursor elements
	document.addEventListener('mouseenter', (e) => {
		if (e.target.classList.contains('show-cursor')) {
			isVisible = true;
			cursorDiv.style.opacity = '1';
			cursorDiv.style.pointerEvents = 'none';
			updateCursorPosition();
		}
	}, true);

	// Hide cursor when leaving .show-cursor elements
	document.addEventListener('mouseleave', (e) => {
		if (e.target.classList.contains('show-cursor')) {
			isVisible = false;
			cursorDiv.style.opacity = '0';
		}
	}, true);

	// Start with cursor hidden
	cursorDiv.style.opacity = '0';
	cursorDiv.style.transition = 'opacity 0.3s ease';
	cursorDiv.style.pointerEvents = 'none';
	cursorDiv.style.position = 'fixed';
	cursorDiv.style.zIndex = '9999';
})();
