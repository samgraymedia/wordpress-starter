/**
 * Image Block
 * Parallax scroll effect - translateY and scale increase as user scrolls
 */

document.addEventListener('DOMContentLoaded', () => {
  const imageBlocks = document.querySelectorAll('.image');
  if (!imageBlocks.length) return;

  imageBlocks.forEach((block) => {
    const img = block.querySelector('.image__img');
    if (!img) return;

    let isInView = false;

    // Intersection Observer to detect when block enters viewport
    const observer = new IntersectionObserver(
      ([entry]) => {
        isInView = entry.isIntersecting;
      },
      { threshold: 0 }
    );

    observer.observe(block);

    // Scroll handler
    window.addEventListener('scroll', () => {
      if (!isInView) return;

      const blockRect = block.getBoundingClientRect();
      const windowHeight = window.innerHeight;

      // Calculate progress without upper limit - keeps going as user scrolls through
      const progress = Math.max(0, (windowHeight - blockRect.top) / windowHeight);

      // Translate Y increases upward (negative values move up)
      const translateY = -50 * progress; // Goes from 0 to -30px and beyond
      
      // Scale increases from 1 to 1.05 and beyond
      const scale = 1 + 0.03 * progress;

      img.style.transform = `translate3d(0, ${translateY}px, 0) scale(${scale})`;
    });
  });
});
