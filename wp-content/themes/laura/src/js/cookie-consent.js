class CookieConsent {
  constructor() {
    this.cookieName = "laura_cookie_consent";
    this.cookieValue = "accepted";
    this.banner = document.querySelector(".cookie-consent");
    this.init();
  }

  init() {
    if (!this.hasConsent() && this.banner) {
      this.showBanner();
      this.attachEventListeners();
    }
  }

  hasConsent() {
    return document.cookie
      .split(";")
      .some((item) => item.trim().startsWith(`${this.cookieName}=`));
  }

  showBanner() {
    this.banner.classList.add("show");
  }

  hideBanner() {
    this.banner.classList.remove("show");
  }

  setConsent(accepted = true) {
    const value = accepted ? this.cookieValue : "declined";
    // Set cookie to expire in 1 year
    const expiryDate = new Date();
    expiryDate.setFullYear(expiryDate.getFullYear() + 1);

    document.cookie = `${
      this.cookieName
    }=${value};expires=${expiryDate.toUTCString()};path=/;SameSite=Lax`;
    this.hideBanner();
  }

  attachEventListeners() {
    const acceptButton = this.banner.querySelector(
      ".cookie-consent__button--accept"
    );
    const declineButton = this.banner.querySelector(
      ".cookie-consent__button--decline"
    );

    if (acceptButton) {
      acceptButton.addEventListener("click", () => this.setConsent(true));
    }

    if (declineButton) {
      declineButton.addEventListener("click", () => this.setConsent(false));
    }
  }
}

// Initialize cookie consent when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
  new CookieConsent();
});
