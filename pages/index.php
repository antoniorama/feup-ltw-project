<?php
require_once(__DIR__ . '/../templates/common_tpl.php');
require_once(__DIR__ . '/../utils/function_utils.php');

session_start();

drawHeader("mainpage");

// Check if user is allowed to access this page
handleUser(true, false, false, false);
?>

<body id="mainpage">
  <div class="container-mainpage">
    <div class="welcome_text">Welcome</div>
    <a href="/pages/loginpage.php"><div class="button-main-page">Login</div></a>
    <a href="/pages/registerpage.php"><div class="button-main-page">Register</div></a>
  </div>
</body>