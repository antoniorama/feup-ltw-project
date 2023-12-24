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

// Display the popup when Filters button is pressed
const filtersButton = document.querySelector(".upper-tab-button");

filtersButton.addEventListener("click", function () {
  document.querySelector(".filter_user_popup").style.display = "flex";
});

// Stop displaying the popup content when close button is pressed
const closeButton = document.querySelector(".filter_user_popup_content_close");

closeButton.addEventListener("click", function () {
  document.querySelector(".filter_user_popup").style.display = "none";
});

// Collapse assigned agent
const dropdown = document.querySelector(".collapse-dropdown");
const dropButton = document.querySelector(".filter-popup-button");
dropdown.style.display = "none";

dropButton.addEventListener("click", function () {
  if (dropdown.style.display == "none") dropdown.style.display = "block";
  else dropdown.style.display = "none";
});

// Collapse status
const dropdownStatus = document.querySelector("#cd2");
const dropButtonStatus = document.querySelector("#fpb2");
dropdownStatus.style.display = "none";

dropButtonStatus.addEventListener("click", function () {
  if (dropdownStatus.style.display == "none")
    dropdownStatus.style.display = "block";
  else dropdownStatus.style.display = "none";
});

// Collapse priority
const dropdownPriority = document.querySelector("#cd3");
const dropButtonPriority = document.querySelector("#fpb3");
dropdownPriority.style.display = "none";

dropButtonPriority.addEventListener("click", function () {
  if (dropdownPriority.style.display == "none")
    dropdownPriority.style.display = "block";
  else dropdownPriority.style.display = "none";
});

// Collapse hashtags
const dropdownTag = document.querySelector("#cd4");
const dropButtonTag = document.querySelector("#fpb4");
dropdownTag.style.display = "none";

dropButtonTag.addEventListener("click", function () {
  if (dropdownTag.style.display == "none") dropdownTag.style.display = "block";
  else dropdownTag.style.display = "none";
});
