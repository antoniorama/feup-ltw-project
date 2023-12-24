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
try {
  // Display change agent popup content
  const newTicketButton = document.querySelector(
    ".ticket-info-new-ticket-button"
  );
  const newTicketPopup = document.getElementById("new-ticket-popup");
  newTicketPopup.style.display = "none";

  newTicketButton.addEventListener("click", function () {
    console.log("a");
    if (newTicketPopup.style.display == "none")
      newTicketPopup.style.display = "flex";
    else newTicketPopup.style.display = "none";
  });
} catch (error) {
  console.log(error);
}

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

// Stop displaying the popup content when close button is pressed
const closeButtons = document.querySelectorAll(
  ".filter_user_popup_content_close"
);

console.log(closeButtons.length);

for (let i = 0; i < closeButtons.length; i++) {
  closeButtons[i].addEventListener("click", function () {
    document.querySelector("#po4Fg").style.display = "none";
    document.querySelector("#xF45g").style.display = "none";
    document.querySelector("#lO0df").style.display = "none";
    document.querySelector("#new-ticket-popup").style.display = "none";
    document.querySelector("#choose-faq-popup").style.display = "none";
  });
}

// Display change department popup content
const departmentButton = document.getElementById("info2");
const changeDepartmentPopup = document.getElementById("xF45g");
changeDepartmentPopup.style.display = "none";

departmentButton.addEventListener("click", function () {
  if (changeDepartmentPopup.style.display == "none")
    changeDepartmentPopup.style.display = "flex";
  else changeDepartmentPopup.style.display = "none";
});

// Display change status popup content
const statusButton = document.getElementById("info3");
const changeStatusPopup = document.getElementById("lO0df");
changeStatusPopup.style.display = "none";

statusButton.addEventListener("click", function () {
  if (changeStatusPopup.style.display == "none")
    changeStatusPopup.style.display = "flex";
  else changeStatusPopup.style.display = "none";
});

// Display change agent popup content
const agentButton = document.getElementById("info1");
const changeAgentPopup = document.getElementById("po4Fg");
changeAgentPopup.style.display = "none";

agentButton.addEventListener("click", function () {
  if (changeAgentPopup.style.display == "none")
    changeAgentPopup.style.display = "flex";
  else changeAgentPopup.style.display = "none";
});

const chooseFaqButton = document.getElementById("message-choose-faq-button");
const chooseFaqPopup = document.getElementById("choose-faq-popup");
chooseFaqPopup.style.display = "none";

chooseFaqButton.addEventListener("click", function () {
  if (chooseFaqPopup.style.display == "none")
    chooseFaqPopup.style.display = "flex";
  else chooseFaqPopup.style.display = "none";
});

