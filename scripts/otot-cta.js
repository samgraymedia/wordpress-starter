/**
 * OTOT CTA Block
 * Animates image transforms based on scroll speed when block enters viewport
 */

export function initOTOTCTA() {
  const ctaBlocks = document.querySelectorAll('.otot-cta');

  if (!ctaBlocks.length) return;

  ctaBlocks.forEach((block) => {
    const images = block.querySelectorAll('.otot-cta__image');
    const contents = block.querySelectorAll('.otot-cta__content');
    let isInView = false;

    // Intersection Observer to detect when block enters viewport
    const observer = new IntersectionObserver(
      ([entry]) => {
        isInView = entry.isIntersecting;
      },
      {
        threshold: 0.1,
      }
    );

    observer.observe(block);

    // Get initial transform values
    const getInitialTransform = (el) => {
      const computedStyle = window.getComputedStyle(el);
      const transform = computedStyle.transform;
      const matrix = transform.match(/matrix\(([^)]+)\)/);
      if (matrix) {
        const values = matrix[1].split(', ');
        return parseFloat(values[4]); // translateX value
      }
      return 0;
    };

    const initialImageTransforms = Array.from(images).map(getInitialTransform);
    const initialContentTransforms = Array.from(contents).map(getInitialTransform);

    // Scroll listener
    const handleScroll = () => {
      if (!isInView) return;

      const blockRect = block.getBoundingClientRect();
      const windowHeight = window.innerHeight;

      // Calculate progress: 0 when block enters, 1 when block center reaches middle of screen
      const totalDistance = windowHeight / 2 + blockRect.height / 2;
      const progress = Math.max(0, Math.min(1, (windowHeight - blockRect.top) / totalDistance));

      images.forEach((img, index) => {
        const initialX = initialImageTransforms[index];
        // Lerp from initial transform to 0
        const currentX = initialX + (0 - initialX) * progress;
        img.style.transform = `translate(${currentX}px, 0px)`;
      });

      contents.forEach((content, index) => {
        const initialX = initialContentTransforms[index];
        // Content lerps from initial transform to 0
        const currentX = initialX + (0 - initialX) * progress;
        content.style.transform = `translate(${currentX}px, 0px)`;
      });
    };

    window.addEventListener('scroll', handleScroll, { passive: true });
  });
}
