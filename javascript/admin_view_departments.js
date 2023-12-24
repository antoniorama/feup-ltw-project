function openDropdown() {
  document.getElementById("userDropdown").classList.toggle("show");
}

// Close the dropdown menu if the user clicks outside of it
window.onclick = function (event) {
  if (!event.target.matches(".user, .arrow, .text_username")) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    for (var i = 0; i < dropdowns.length; i++) {
      var dropdown = dropdowns[i];
      if (dropdown.classList.contains("show")) {
        dropdown.classList.remove("show");
      }
    }
  }
};

// Logout button, when clicked runs process_logout.php script and sends a GET request
// to loginpage.php
document.getElementById("logout-btn").addEventListener("click", function () {
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      // handle successful logout
      window.location.replace("/../pages/loginpage.php"); // redirect to login page
    }
  };
  xhr.open("GET", "/../database/processes/process_logout.php", true); // run logout script
  xhr.send();
});

// Display the popup when New department button is pressed
const newDepartmentButton = document.querySelector(".upper-tab-button");

newDepartmentButton.addEventListener("click", function () {
  document.querySelector(".upper-tab-button_popup").style.display = "flex";
});

// Display the popup when a deparment is clicked from the table
const departments = document.getElementsByClassName("row1-d");

for (let i = 0; i < departments.length; i++) {
  departments[i].addEventListener("click", function () {
    console.log(parseInt(departments[i].id));
    document.querySelector(
      `#form${parseInt(departments[i].id)}`
    ).style.display = "flex";
  });
}

// Stop displaying the popup when X button is pressed
const closeButtons = document.getElementsByClassName("popup-content-close");

for (let i = 0; i < closeButtons.length; i++) {
  closeButtons[i].addEventListener("click", function () {
    document.querySelector(".upper-tab-button_popup").style.display = "none";
    const editPopups = document.querySelectorAll(".edit_department_popup");
    for (let j = 0; j < editPopups.length; j++) {
      editPopups[j].style.display = "none";
    }
  });
}
