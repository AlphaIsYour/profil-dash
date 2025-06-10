// Dropdown functionality
document.addEventListener("DOMContentLoaded", function () {
  // Find all elements with the dropdown-toggle class
  const dropdownToggles = document.querySelectorAll(".dropdown-toggle");

  dropdownToggles.forEach((toggle) => {
    toggle.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();

      // Find the dropdown content
      const dropdownContent = this.nextElementSibling;

      // Close all other dropdowns
      document
        .querySelectorAll(".dropdown-content.show")
        .forEach((dropdown) => {
          if (dropdown !== dropdownContent) {
            dropdown.classList.remove("show");
          }
        });

      // Toggle the current dropdown
      dropdownContent.classList.toggle("show");
    });
  });

  // Close dropdowns when clicking outside
  document.addEventListener("click", function (e) {
    if (!e.target.matches(".dropdown-toggle")) {
      document
        .querySelectorAll(".dropdown-content.show")
        .forEach((dropdown) => {
          dropdown.classList.remove("show");
        });
    }
  });
});
