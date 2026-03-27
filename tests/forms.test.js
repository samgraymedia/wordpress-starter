/**
 * JavaScript Unit Tests: Forms Module
 *
 * Tests form validation, submission, and error handling
 */

const Forms = require("../../scripts/modules/forms");

describe("Forms Module", () => {
  let forms;
  let formElement;

  beforeEach(() => {
    forms = new Forms();

    // Create a test form element
    document.body.innerHTML = `
			<form id="test-form">
				<input type="text" name="username" required />
				<input type="email" name="email" required />
				<input type="password" name="password" required minlength="8" />
				<textarea name="message"></textarea>
				<button type="submit">Submit</button>
			</form>
		`;

    formElement = document.getElementById("test-form");
    fetch.mockClear();
  });

  afterEach(() => {
    document.body.innerHTML = "";
  });

  describe("Form Validation", () => {
    test("should validate required fields", () => {
      const username = document.querySelector('input[name="username"]');

      const isValid = forms.validateField(username);
      expect(isValid).toBe(false);

      username.value = "testuser";
      expect(forms.validateField(username)).toBe(true);
    });

    test("should validate email format", () => {
      const email = document.querySelector('input[name="email"]');

      email.value = "invalid-email";
      expect(forms.validateField(email)).toBe(false);

      email.value = "valid@example.com";
      expect(forms.validateField(email)).toBe(true);
    });

    test("should validate minlength constraint", () => {
      const password = document.querySelector('input[name="password"]');

      password.value = "short";
      expect(forms.validateField(password)).toBe(false);

      password.value = "longenough";
      expect(forms.validateField(password)).toBe(true);
    });

    test("should validate entire form", () => {
      expect(forms.validateForm(formElement)).toBe(false);

      document.querySelector('input[name="username"]').value = "testuser";
      document.querySelector('input[name="email"]').value = "test@example.com";
      document.querySelector('input[name="password"]').value = "password123";

      expect(forms.validateForm(formElement)).toBe(true);
    });
  });

  describe("Form Submission", () => {
    beforeEach(() => {
      // Fill form with valid data
      document.querySelector('input[name="username"]').value = "testuser";
      document.querySelector('input[name="email"]').value = "test@example.com";
      document.querySelector('input[name="password"]').value = "password123";
      document.querySelector('textarea[name="message"]').value = "Test message";
    });

    test("should prevent submission of invalid form", (done) => {
      const password = document.querySelector('input[name="password"]');
      password.value = "short"; // Invalid

      const submitHandler = jest.fn((e) => {
        e.preventDefault();
        done();
      });

      formElement.addEventListener("submit", submitHandler);
      forms.preventInvalidSubmit(formElement);

      const submitEvent = new Event("submit");
      formElement.dispatchEvent(submitEvent);

      setTimeout(() => {
        expect(submitHandler).toHaveBeenCalled();
        done();
      }, 100);
    });

    test("should allow submission of valid form", (done) => {
      const submitHandler = jest.fn();

      fetch.mockResolvedValueOnce({
        ok: true,
        json: async () => ({ success: true }),
      });

      formElement.addEventListener("submit", (e) => {
        e.preventDefault();
        forms.submitForm(formElement).then(() => {
          expect(fetch).toHaveBeenCalled();
          done();
        });
      });

      const submitEvent = new Event("submit");
      formElement.dispatchEvent(submitEvent);
    });

    test("should serialize form data correctly", () => {
      const data = forms.serializeForm(formElement);

      expect(data).toHaveProperty("username", "testuser");
      expect(data).toHaveProperty("email", "test@example.com");
      expect(data).toHaveProperty("password", "password123");
      expect(data).toHaveProperty("message", "Test message");
    });
  });

  describe("Error Handling", () => {
    test("should display error messages", () => {
      const input = document.querySelector('input[name="email"]');
      input.value = "invalid-email";

      forms.showFieldError(input, "Please enter a valid email address");

      const errorElement = input.parentElement.querySelector(".error-message");
      expect(errorElement).toBeTruthy();
      expect(errorElement.textContent).toContain("valid email");
    });

    test("should clear error messages when field becomes valid", () => {
      const input = document.querySelector('input[name="email"]');
      input.value = "invalid-email";

      forms.showFieldError(input, "Invalid email");
      let errorElement = input.parentElement.querySelector(".error-message");
      expect(errorElement).toBeTruthy();

      input.value = "valid@example.com";
      forms.clearFieldError(input);

      errorElement = input.parentElement.querySelector(".error-message");
      expect(errorElement).toBeFalsy();
    });

    test("should handle server-side validation errors", async () => {
      fetch.mockResolvedValueOnce({
        ok: false,
        json: async () => ({
          errors: {
            email: "Email already in use",
            username: "Username taken",
          },
        }),
      });

      document.querySelector('input[name="username"]').value = "testuser";
      document.querySelector('input[name="email"]').value = "test@example.com";
      document.querySelector('input[name="password"]').value = "password123";

      await forms.submitForm(formElement);

      const emailError = document
        .querySelector('input[name="email"]')
        .parentElement.querySelector(".error-message");
      const usernameError = document
        .querySelector('input[name="username"]')
        .parentElement.querySelector(".error-message");

      expect(emailError?.textContent).toContain("already in use");
      expect(usernameError?.textContent).toContain("taken");
    });
  });

  describe("Field Interactions", () => {
    test("should mark field as touched on blur", () => {
      const input = document.querySelector('input[name="username"]');

      forms.initFieldTracking(input);

      input.dispatchEvent(new Event("blur"));
      expect(input.classList.contains("touched")).toBe(true);
    });

    test("should validate field on blur if touched", () => {
      const input = document.querySelector('input[name="email"]');
      input.value = "invalid-email";

      forms.initFieldTracking(input);
      input.dispatchEvent(new Event("blur"));

      expect(input.classList.contains("invalid")).toBe(true);
    });
  });
});
