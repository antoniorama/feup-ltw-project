function submitForm() {
  document.querySelector("#user-type-form").submit();
}

var default_option_item = document.querySelector(".default-option .option");

// To open the dropdown menu for department
default_option_item.addEventListener("click", function () {
  document.querySelector(".select-ul").classList.toggle("active");
});
