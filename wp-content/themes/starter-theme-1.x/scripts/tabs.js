/**
 * Tabs Block JavaScript
 * Handles tab switching with keyboard navigation support
 */

document.addEventListener("DOMContentLoaded", function () {
  const tabsBlocks = document.querySelectorAll(".tabs-block");

  tabsBlocks.forEach(function (tabsBlock) {
    const buttons = tabsBlock.querySelectorAll(".tabs-block__button");
    const panels = tabsBlock.querySelectorAll(".tabs-block__panel");

    // Click handler for tab buttons
    buttons.forEach((button, index) => {
      button.addEventListener("click", function () {
        activateTab(index, buttons, panels);
      });

      // Keyboard navigation
      button.addEventListener("keydown", function (event) {
        let targetIndex = null;

        switch (event.key) {
          case "ArrowRight":
          case "ArrowDown":
            event.preventDefault();
            targetIndex = index === buttons.length - 1 ? 0 : index + 1;
            break;
          case "ArrowLeft":
          case "ArrowUp":
            event.preventDefault();
            targetIndex = index === 0 ? buttons.length - 1 : index - 1;
            break;
          case "Home":
            event.preventDefault();
            targetIndex = 0;
            break;
          case "End":
            event.preventDefault();
            targetIndex = buttons.length - 1;
            break;
        }

        if (targetIndex !== null) {
          activateTab(targetIndex, buttons, panels);
          buttons[targetIndex].focus();
        }
      });
    });
  });

  /**
   * Activate a specific tab
   */
  function activateTab(index, buttons, panels) {
    // Remove active class from all buttons and panels
    buttons.forEach((btn) => btn.classList.remove("is-active"));
    panels.forEach((panel) => panel.classList.remove("is-active"));

    // Update tabindex for keyboard navigation
    buttons.forEach((btn, i) => {
      btn.setAttribute("tabindex", i === index ? "0" : "-1");
      btn.setAttribute("aria-selected", i === index ? "true" : "false");
    });

    // Hide all panels and show the active one
    panels.forEach((panel, i) => {
      if (i === index) {
        panel.removeAttribute("hidden");
        panel.classList.add("is-active");
      } else {
        panel.setAttribute("hidden", "");
      }
    });

    // Add active class to button and panel
    buttons[index].classList.add("is-active");
    panels[index].classList.add("is-active");
  }
});
