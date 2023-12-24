<?php
require_once(__DIR__ . '/../../templates/common_tpl.php');
require_once(__DIR__ . '/../../templates/user_tpl.php');
require_once(__DIR__ . '/../../utils/function_utils.php');


drawHeader("admin_view_users");

session_start();

// Check if user is allowed to access this page
handleUser(false, false, false, true);

if (!isset($_SESSION['user_filters_query'])) {
    $_SESSION['user_filters_query'] = 'SELECT * FROM USER u;';
}
?>

<body>
    <div class="side_tab">
        <?php drawCompany() ?>
        <div class="line"></div>
        <div class="button_list">
            <a href="departments.php">
                <div class="button_departments">
                    <p class="main-tab-text">Departments</p>
                    <img class="image_departments" src="/../images/department.png" alt="O">
                </div>
            </a>
            <div class="button_users">
                <p class="text_users">Users</p>
                <img class="image_users" src="/../../images/users.png" alt="O">
                <div class="rectangle"></div>
            </div>
        </div>
    </div>
    <?php drawUserTab() ?>
    <div class="users_tab">
        <div class="upper_tab">
            <p class="main-tab-text">Users</p>
            <div class="filter_user">
                <img class="img_filter" src="/../../images/filter.png" alt="filter">
                <p class="text_filter_user">Filter</p>
            </div>
        </div>
        <?php drawUser(); ?>
    </div>
    <div class="filter_user_popup">
        <div class="filter_user_popup_content" id="fup-admin">
            <img class="filter_user_popup_content_close" src="/../../images/close.png" alt="close">
            <p class="filter_user_popup_content_text">Choose your filters</p>
            <form class="filter_user_form" action="/database/processes/process_user_filter.php">
                <div class="form-row">
                    <label for="user_type">User Type:</label>
                    <select id="user_type" name="user_type">
                        <option value="All">All</option>
                        <option value="Client" <?php echo (str_contains($_SESSION['user_filters_query'], 'Client')) ? 'selected' : ''; ?>>Client</option>
                        <option value="Agent" <?php echo (str_contains($_SESSION['user_filters_query'], 'Agent')) ? 'selected' : ''; ?>>
                            Agent</option>
                        <option value="Admin" <?php echo (str_contains($_SESSION['user_filters_query'], 'Admin')) ? 'selected' : ''; ?>>
                            Admin</option>
                    </select>
                </div>

                <div class="form-row">
                    <label for="order4">Order by:</label>
                    <select id="order4" name="order1">
                        <option value="Id_asc" <?php echo (str_contains($_SESSION['user_filters_query'], 'id ASC')) ? 'selected' : ''; ?>>Id - Ascending</option>
                        <option value="Id_desc" <?php echo (str_contains($_SESSION['user_filters_query'], 'id DESC')) ? 'selected' : ''; ?>>Id - Descending</option>
                        <option value="Username_asc" <?php echo (str_contains($_SESSION['user_filters_query'], 'username ASC')) ? 'selected' : ''; ?>>Username - Ascending</option>
                        <option value="Username_desc" <?php echo (str_contains($_SESSION['user_filters_query'], 'username DESC')) ? 'selected' : ''; ?>>Username - Descending</option>
                    </select>
                </div>

                <div class="submit-row">
                    <input type="submit" name="submit" value="Filter">
                </div>

            </form>

        </div>
    </div>
</body>