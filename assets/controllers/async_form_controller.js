// assets/controllers/async_form_controller.js
import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
    static targets = ["form", "feedback"];

    connect() {
        console.log("Async form controller connected");
    }

    async submitForm(event) {
        event.preventDefault(); // Prevent page reload on form submission

        const form = this.formTarget;
        const url = form.action;

        try {
            const formData = new FormData(form);
            const response = await fetch(url, {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            });

            const json = await response.json();

            if (json.success) {
                // Redirect or show success message if submission was successful
                window.location.href = json.redirectUrl;
            } else {
                // Display validation errors
                this.showErrors(json.errors);
            }
        } catch (error) {
            console.error("Error submitting form:", error);
        }
    }

    showErrors(errors) {
        // Clear previous errors
        this.clearErrors();

        for (const [field, message] of Object.entries(errors)) {
            // Dynamic name selection for different forms (e.g., user_info, payment)
            const inputName = this.formTarget.getAttribute('name');
            const input = this.formTarget.querySelector(`[name="${inputName}[${field}]"]`);

            if (input) {
                input.classList.add("is-invalid");

                // Locate the feedback element to show error
                const feedback = input.nextElementSibling;
                if (feedback && feedback.classList.contains("invalid-feedback")) {
                    feedback.textContent = message;
                }
            }
        }
    }

    clearErrors() {
        // Clear error styling and messages
        this.formTarget.querySelectorAll(".is-invalid").forEach(input => {
            input.classList.remove("is-invalid");
            const feedback = input.nextElementSibling;
            if (feedback && feedback.classList.contains("invalid-feedback")) {
                feedback.textContent = "";
            }
        });
    }
}
