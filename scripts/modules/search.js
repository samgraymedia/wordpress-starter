/**
 * Search Module
 *
 * Handles search form and live search functionality.
 *
 * @module modules/search
 */

import {
  querySelector,
  querySelectorAll,
  setHTML,
  show,
  hide,
} from "../utils/dom.js";
import { debounce } from "../utils/events.js";
import { ajax } from "../utils/http.js";
import Logger from "../utils/logger.js";

/**
 * Initialize search functionality
 */
export function initSearch() {
  Logger.debug("[Search] Initializing...");

  const searchForm = querySelector(".search-form");
  if (!searchForm) {
    return;
  }

  const searchInput = querySelector(".search-form__input", searchForm);
  if (searchInput) {
    // Setup live search with debounce
    searchInput.addEventListener("input", debounce(handleSearchInput, 300));
  }

  // Setup form submission
  searchForm.addEventListener("submit", handleSearchSubmit);
}

/**
 * Handle search input for live search
 * @param {Event} e
 */
async function handleSearchInput(e) {
  const input = e.target;
  const query = input.value.trim();

  if (query.length < 2) {
    hideSuggestions();
    return;
  }

  try {
    // Get search suggestions via AJAX
    const results = await ajax("search", {
      q: query,
      limit: 5,
    });

    if (results.length > 0) {
      displaySuggestions(results);
    } else {
      displayNoResults();
    }
  } catch (error) {
    Logger.error("[Search] Error fetching suggestions", error, {
      context: "live-search",
      query,
    });
  }
}

/**
 * Handle search form submission
 * @param {Event} e
 */
function handleSearchSubmit(e) {
  const input = querySelector(".search-form__input", e.target);
  if (!input.value.trim()) {
    e.preventDefault();
  }
}

/**
 * Display search suggestions
 * @param {Array} results
 */
function displaySuggestions(results) {
  const form = querySelector(".search-form");
  let dropdown = querySelector(".search-suggestions", form);

  if (!dropdown) {
    dropdown = document.createElement("div");
    dropdown.className = "search-suggestions";
    form.appendChild(dropdown);
  }

  const html = results
    .map(
      (result) => `
        <a href="${result.link}" class="search-suggestion">
            <span class="search-suggestion__title">${result.title}</span>
            <span class="search-suggestion__type">${result.type}</span>
        </a>
    `,
    )
    .join("");

  setHTML(dropdown, html);
  show(dropdown);

  // Close on item click or outside click
  querySelectorAll(".search-suggestion", dropdown).forEach((item) => {
    item.addEventListener("click", hideSuggestions);
  });

  document.addEventListener("click", (e) => {
    if (!form.contains(e.target)) {
      hideSuggestions();
    }
  });
}

/**
 * Display no results message
 */
function displayNoResults() {
  const form = querySelector(".search-form");
  let dropdown = querySelector(".search-suggestions", form);

  if (!dropdown) {
    dropdown = document.createElement("div");
    dropdown.className = "search-suggestions";
    form.appendChild(dropdown);
  }

  setHTML(dropdown, '<div class="search-no-results">No results found</div>');
  show(dropdown);
}

/**
 * Hide search suggestions dropdown
 */
function hideSuggestions() {
  const dropdown = querySelector(".search-suggestions");
  if (dropdown) {
    hide(dropdown);
  }
}

/**
 * Perform a search programmatically
 * @param {string} query
 * @returns {Promise<Array>}
 */
export async function search(query) {
  try {
    return await ajax("search", {
      q: query,
    });
  } catch (error) {
    console.error("[Search] Error:", error);
    return [];
  }
}
