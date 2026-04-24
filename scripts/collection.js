document.addEventListener('DOMContentLoaded', () => {
  const collection = document.querySelector('.collection');
  if (!collection) return;

  const evenItems = collection.querySelectorAll('.collection__item--container:nth-child(even)');
  if (evenItems.length === 0) return;

  let isInView = false;
  let collectionTop = 0;
  let collectionHeight = 0;

  // Intersection Observer to detect when collection is in view
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        isInView = entry.isIntersecting;
        if (isInView) {
          collectionTop = entry.boundingClientRect.top + window.scrollY;
          collectionHeight = entry.boundingClientRect.height;
        }
      });
    },
    { threshold: 0 }
  );

  observer.observe(collection);

  // Scroll handler
  window.addEventListener('scroll', () => {
    if (!isInView) return;

    const scrollY = window.scrollY;
    const collectionBottom = collectionTop + collectionHeight;
    const viewportCenter = window.innerHeight / 2;

    // Calculate how far through the viewport the collection is
    // When collection top enters viewport: progress = 0
    // When collection exits: progress = 1
    const distanceFromTop = scrollY + viewportCenter - collectionTop;
    const progress = Math.max(0, Math.min(1, distanceFromTop / (window.innerHeight + collectionHeight)));

    // Translate from 50% towards 0 based on scroll progress
    const translateValue = 50 * (1 - progress);

    evenItems.forEach((item) => {
      item.style.transform = `translate3d(0px, ${translateValue}%, 0px)`;
    });
  });
});
