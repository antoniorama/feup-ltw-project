<?php
require_once(__DIR__ . '/../templates/common_tpl.php');
require_once(__DIR__ . '/../utils/function_utils.php');

drawHeader("change_password");

session_start();

// Check if user is allowed to access this page
handleUser(false, true, true, true);
?>

<body id="change_password">
    <?php drawCompany() ?>
    <div class="container">
        <div class="change_password">
            <a href="client_settings.php"> <img src="/../images/go_back.png" alt="~"> </a>
            <div class="text_change_password">Change password</div>
        </div>
        <form action="/../database/processes/process_password_change.php" method="post" class="form-1">
            <div class="current_password">
                <p class="text_current_password">Current password</p>
                <div class="password_input_wrapper">
                    <input type="password" autocomplete="off" name="current_password" id="new_password_input"
                        placeholder="Enter current password" class="type">
                    <div class="show_password"></div>
                </div>
            </div>
            <div class="new_password">
                <p class="text_new_password">Enter new password</p>
                <div class="password_input_wrapper">
                    <input type="password" name="new_password" placeholder="Enter new password" class="type">
                    <div class="show_password"></div>
                </div>
            </div>
            <div class="confirm_new_password">
                <p class="text_confirm_new_password">Confirm new password</p>
                <div class="password_input_wrapper">
                    <input type="password" name="confirm_new_password" placeholder="Confirm new password" class="type">
                    <div class="show_password"></div>
                </div>
            </div>
            <div class="group_buttons">
                <button type="submit" class="button_submit">Save</button>
                <button type="button" class="button_cancel">Cancel</button>
            </div>
        </form>
    </div>
</body>