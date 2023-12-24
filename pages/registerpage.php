<?php
require_once(__DIR__ . '/../templates/common_tpl.php');
require_once(__DIR__ . '/../utils/function_utils.php');
drawHeader("registerpage");

// Check if user is allowed to access this page
handleUser(true, false, false, false);
?>

<body id="registerpage">
  <div class="container-registerpage">
    <div class="signup_text">Sign Up</div>
    <form action="/../database/processes/process_signup.php" method="post" class="register">
      <input type="email" name="email" placeholder="Enter email" class="type">
      <input type="text" name="name" placeholder="Enter name" class="type">
      <input type="text" name="username" placeholder="Create username" class="type">
      <input type="password" name="password" placeholder="Password" class="type">
      <input type="password" name="confirm-password" placeholder="Confirm password" class="type">
      <div class="text-to-other-page">Already have an account? <a href="/pages/loginpage.php"><span>Login
            here.</span></a></div>
      <button type="submit">Register</button>
    </form>
  </div>
</body>