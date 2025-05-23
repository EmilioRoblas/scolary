document.addEventListener("DOMContentLoaded", function() {
  const rolSelect = document.getElementById("rol");
  const avatarField = document.getElementById("campoAvatar");

  rolSelect.addEventListener("change", function() {
    if (this.value === "profesor") {
      avatarField.classList.remove("d-none");
    } else {
      avatarField.classList.add("d-none");
    }
  });
});