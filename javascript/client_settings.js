const changePasswordButton = document.querySelector(
  ".button-password-client-settings"
);

changePasswordButton.addEventListener("click", function (event) {
  event.preventDefault(); // prevent the form from being submitted
  window.location.replace("/../pages/change_password.php");
});
