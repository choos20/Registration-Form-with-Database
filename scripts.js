document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("registrationForm");
  const countrySelect = document.getElementById("country");
  const contactInput = document.getElementById("contact");
  const contactError = document.getElementById("contact-error");

  // ✅ Load countries into dropdown
  fetch("https://restcountries.com/v3.1/all")
    .then(res => res.json())
    .then(data => {
      countrySelect.innerHTML = '<option value="">Select Country</option>';
      const countries = data.map((c) => c.name.common).sort();
      countries.forEach((name) => {
        const option = document.createElement("option");
        option.value = name;
        option.textContent = name;
        countrySelect.appendChild(option);
      });
    })
    .catch(() => {
      countrySelect.innerHTML = '<option value="">Could not load countries</option>';
    });

  // ✅ Phone number validation
  let timeout = null;
  contactInput.addEventListener("input", () => {
    const number = contactInput.value.trim();
    clearTimeout(timeout);

    if (number.length < 10) {
      contactError.textContent = "";
      return;
    }

    contactError.textContent = "Checking...";
    contactError.style.color = "gray";

    timeout = setTimeout(() => {
      const phoneRegex = /^(\+?\d{1,3}[- ]?)?\(?\d{1,4}\)?[- ]?\d{1,4}[- ]?\d{1,4}$/;

      if (!phoneRegex.test(number)) {
        contactError.textContent = "✘ Invalid phone number format";
        contactError.style.color = "red";
        return;
      }

      contactError.textContent = "✔ Valid phone number";
      contactError.style.color = "green";
    }, 600);
  });

  // ✅ Form submission
  form.addEventListener("submit", (e) => {
    // Perform additional validation if necessary
    const contactValue = contactInput.value.trim();
    if (contactValue.length >= 10 && !contactError.textContent.includes("✔ Valid")) {
      e.preventDefault(); // Prevent form submission if invalid
      alert("Please fix errors before submitting.");
    } else {
      alert("Form submitted successfully!");
    }
  });
});
