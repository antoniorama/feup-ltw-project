// Cancel button cancels the form and goes back to client settings page
const cancelButton = document.querySelector(".button_cancel");

cancelButton.addEventListener("click", function (event) {
  event.preventDefault(); // prevent the form from being submitted
  window.location.replace("/../pages/client_settings.php");
});

// Eye icon toggles password
const showPasswordButtons = document.querySelectorAll(".show_password");
const passwordInputs = document.querySelectorAll(
  '.password_input_wrapper input[type="password"]'
);

showPasswordButtons.forEach((button, index) => {
  button.addEventListener("click", () => {
    const type =
      passwordInputs[index].getAttribute("type") === "password"
        ? "text"
        : "password";
    passwordInputs[index].setAttribute("type", type);
    button.classList.toggle("show_password_active");
  });
});
