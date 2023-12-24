<?php
require_once(__DIR__ . '/../../templates/common_tpl.php');
require_once(__DIR__ . '/../../templates/department_tpl.php');
require_once(__DIR__ . '/../../utils/function_utils.php');


drawHeader("admin_view_departments");

session_start();

// Check if user is allowed to access this page
handleUser(false, false, false, true);

?>

<body>
    <div class="side_tab">
        <?php drawCompany() ?>
        <div class="line"></div>
        <div class="button_list">
            <div class="button_departments">
                <p class="main-tab-text">Departments</p>
                <img class="image_departments" src="/../images/department.png" alt="O">
                <div class="rectangle"></div>
            </div>
            <a href="users.php">
                <div class="button_users">
                    <p class="text_users">Users</p>
                    <img class="image_users" src="/../../images/users.png" alt="O">
                </div>
            </a>
        </div>
    </div>
    <?php drawUserTab() ?>
    <div class="main-tab">
        <div class="upper_tab">
            <p class="main-tab-text">Departments</p>
            <div class="upper-tab-button">
                <img class="img-pencil" src="/../../images/pencil.png" alt="edit">
                <p class="text_department">New Department</p>
            </div>
        </div>
        <?php drawDepartment(); ?>
    </div>
    <div class="upper-tab-button_popup">
        <div class="popup-content">
            <img class="popup-content-close" src="/../../images/close.png" alt="close">
            <p class="popup-content-text">Create department</p>
            <form name="createDepartmentForm" class="popup-content-form"
                action="/../../database/processes/process_department.php" method="POST">
                <input type="text" name="create_name" placeholder="Department name">
                <button type="submit" class="popup-content-submit">Create</button>
            </form>
        </div>
    </div>
    <?php drawDepartmentPopup(); ?>
</body>