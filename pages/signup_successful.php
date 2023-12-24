<?php
require_once(__DIR__ . '/../templates/common_tpl.php');
require_once(__DIR__ . '/../utils/function_utils.php');

drawHeader("signup_successful");

// Check if user is allowed to access this page
handleUser(true, false, false, false);
?>

<body id="signup_successful">
    <div class="container-signup-successful">
        <div class="text_signup">Sign Up</div>
        <div class="text_signup_successful">Signup successful. You can now <a href="loginpage.php">log in</a>.</div>
    </div>
</body>