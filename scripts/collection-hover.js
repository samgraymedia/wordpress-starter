document.addEventListener('DOMContentLoaded', () => {
  const collectionItems = document.querySelectorAll('.collection__item');

  collectionItems.forEach((item) => {
    item.addEventListener('mouseleave', () => {
      item.classList.add('is-leaving');
      
      // Remove the class after animations complete
      setTimeout(() => {
        item.classList.remove('is-leaving');
      }, 600);
    });
  });
});
