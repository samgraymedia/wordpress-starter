export function initializeSocialInteraction() {
  const cards = document.querySelectorAll('.callout-socials-card-w');
  
  if (cards.length === 0) return;

  const baseConfigs = [
    { scale: 0.7756, top: '15%', left: 0 },
    { scale: 0.8785, top: '10%', left: 1 },
    { scale: 0.9522, top: '5%', left: 2 },
    { scale: 1, top: '0%', left: 3 },
    { scale: 0.9522, top: '5%', left: 4 },
    { scale: 0.8785, top: '10%', left: 5 },
    { scale: 0.7756, top: '15%', left: 6 }
  ];

  // Staggered entrance animation
  cards.forEach((card, index) => {
    card.style.animationDelay = `${index * 0.1}s`;
  });

  const container = document.querySelector('.social__images');
  if (!container) return;

  let mouseX = 0;
  let mouseY = 0;

  document.addEventListener('mousemove', (e) => {
    mouseX = e.clientX;
    mouseY = e.clientY;

    cards.forEach((card, index) => {
      const rect = card.getBoundingClientRect();
      const cardCenterX = rect.left + rect.width / 2;
      const cardCenterY = rect.top + rect.height / 2;

      const distX = mouseX - cardCenterX;
      const distY = mouseY - cardCenterY;
      const distance = Math.sqrt(distX * distX + distY * distY);

      // Magnetic pull effect - only if within 300px
      if (distance < 300) {
        const strength = (1 - distance / 300) * 15; // Max 15 degree tilt
        const rotateY = (distX / distance) * strength;
        const rotateX = -(distY / distance) * strength;

        card.style.setProperty('--rotate-x', `${rotateX}deg`);
        card.style.setProperty('--rotate-y', `${rotateY}deg`);
        card.classList.add('magnetic-active');
      } else {
        card.style.setProperty('--rotate-x', '0deg');
        card.style.setProperty('--rotate-y', '0deg');
        card.classList.remove('magnetic-active');
      }
    });
  });

  cards.forEach((card, index) => {
    card.addEventListener('mouseenter', () => {
      handleCardHover(card, index, cards, baseConfigs);
    });

    card.addEventListener('mouseleave', () => {
      handleCardLeave(card, index, cards, baseConfigs);
    });
  });
}

function handleCardHover(hoveredCard, hoveredIndex, cards, baseConfigs) {
  const hoveredConfig = baseConfigs[hoveredIndex];

  cards.forEach((card, index) => {
    if (index === hoveredIndex) {
      // Scale up by 8% and move up
      const newScale = hoveredConfig.scale * 1.08;
      const newTop = parseFloat(hoveredConfig.top) - 8;
      card.style.transform = getTransform(index, newScale);
      card.style.top = newTop + '%';
    } else {
      // Move other cards away
      const config = baseConfigs[index];
      const direction = index < hoveredIndex ? -1 : 1; // -1 for left, 1 for right
      const moveAmount = 15 * direction;
      
      const newTranslateX = direction * 10; // pixels to move
      card.style.transform = getTransformWithSeparation(index, config, moveAmount, newTranslateX);
    }
  });
}

function handleCardLeave(card, index, cards, baseConfigs) {
  const config = baseConfigs[index];

  cards.forEach((c, i) => {
    const baseConfig = baseConfigs[i];
    c.style.transform = getTransform(i, baseConfig.scale);
    c.style.top = baseConfig.top;
    c.style.opacity = '1';
  });
}

function getTransform(index, scale) {
  const transforms = [
    `translateX(-20%) rotate(-21deg) scale(${scale})`,
    `translateX(-30%) rotate(-14deg) scale(${scale})`,
    `translateX(-40%) rotate(-7deg) scale(${scale})`,
    `translateX(-50%) rotate(0deg) scale(${scale})`,
    `translateX(-60%) rotate(7deg) scale(${scale})`,
    `translateX(-70%) rotate(14deg) scale(${scale})`,
    `translateX(-80%) rotate(21deg) scale(${scale})`
  ];
  return transforms[index] || transforms[0];
}

function getTransformWithSeparation(index, config, moveAmount, extraTranslateX) {
  const baseTransforms = [
    `translateX(-20%) rotate(-21deg)`,
    `translateX(-30%) rotate(-14deg)`,
    `translateX(-40%) rotate(-7deg)`,
    `translateX(-50%) rotate(0deg)`,
    `translateX(-60%) rotate(7deg)`,
    `translateX(-70%) rotate(14deg)`,
    `translateX(-80%) rotate(21deg)`
  ];
  
  const base = baseTransforms[index] || baseTransforms[0];
  return `${base} scale(${config.scale * 0.9}) translateX(${extraTranslateX}px)`;
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initializeSocialInteraction);
} else {
  initializeSocialInteraction();
}
