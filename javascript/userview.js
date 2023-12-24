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

// Display new ticket popup content
const newTicketButton = document.querySelector(".new-ticket-button");
const newTicketPopup = document.getElementById("new-ticket-popup");
newTicketPopup.style.display = "none";

newTicketButton.addEventListener("click", function () {
  console.log("a");
  if (newTicketPopup.style.display == "none")
    newTicketPopup.style.display = "flex";
  else newTicketPopup.style.display = "none";
});

// Stop displaying the popup content when close button is pressed
const closeButtons = document.querySelectorAll(
  ".filter_user_popup_content_close"
);

console.log(closeButtons.length);

for (let i = 0; i < closeButtons.length; i++) {
  closeButtons[i].addEventListener("click", function () {
    newTicketPopup.style.display = "none";
  });
}

const cancelButton = document.getElementsByClassName("button_cancel");

cancelButton.addEventListener("click", function (event) {
  event.preventDefault(); // prevent the form from being submitted
  window.location.replace("/../pages/client_view/tickets.php?selected=open");
});
