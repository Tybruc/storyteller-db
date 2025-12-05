function showForm(formId) {
    // Hide all form boxes and show the requested one
    document.querySelectorAll(".form-box").forEach(form => form.classList.remove("active"));
    document.getElementById(formId).classList.add("active");
}

