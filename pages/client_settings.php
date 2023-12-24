<?php
require_once(__DIR__ . '/../templates/common_tpl.php');
require_once(__DIR__ . '/../utils/function_utils.php');

drawHeader("client_settings");

session_start();

// Check if user is allowed to access this page
handleUser(false, true, true, true);
?>

<body id="client_settings">
    <?php drawCompany() ?>
    <div class="container-client-settings">
        <div class="your_profile">
            <a href="<?php echo getMainPage($_SESSION['user_id'])?>"> <img src="/../images/go_back.png" alt="~"> </a>
            <div class="text_your_profile">Your profile</div>
        </div>
        <form action="/../database/processes/process_change_user_info.php" method="post" class="form-client-settings">
            <div class="form-group">
                <div class="name">°°
                    <p class="text_name">Name</p>
                    <input type="text" name="name" value="<?php echo getName($_SESSION['user_id']) ?>" class="type">
                </div>
                <div class="username">
                    <p class="text_username">Username</p>
                    <input type="text" name="username" value="<?php echo getUsername($_SESSION['user_id']) ?>"
                        class="type">
                </div>
            </div>
            <div class="email">
                <p class="text_email">Email</p>
                <input type="email" name="email" value="<?php echo getEmail($_SESSION['user_id']) ?>" class="type">
            </div>
            <div class="password">
                <p class="text_password">Password</p>
                <button class="button-password-client-settings">Change password</button>
            </div>
            <div class="group_buttons">
                <button type="submit" class="button_submit">Save</button>
                <div class="button-cancel-client-settings"
                    onclick="window.location.href='<?php echo getMainPage($_SESSION['user_id']) ?>'">Cancel</div>
            </div>
        </form>
    </div>
</body>