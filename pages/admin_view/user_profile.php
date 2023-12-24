<?php
declare(strict_types=1);

session_start();

require_once(__DIR__ . '/../../templates/common_tpl.php');
require_once(__DIR__ . '/../../templates/department_tpl.php');
require_once(__DIR__ . '/../../utils/function_utils.php');

// Check if user is allowed to access this page
handleUser(false, false, false, true);


$user_id = $_GET['user_id'];
$user_type = getUserType($user_id);

drawHeader("admin_view_user_profile");
?>

<body id="admin-view-user-profile">
    <?php drawCompany(); ?>
    <div class="admin_view_client_profile">
        <div class="group_arrow_username_usertype">
            <div class="group_arrow_username">
                <a href="users.php"><img class="icon_back-RJT" src="/../../images/go_back.png" alt="Back"></a>
                <p class="username-K8w">
                    <?php echo getUsername($user_id) ?>
                </p>
                <form id="user-type-form" action="/../../database/processes/process_change_user_type.php" method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                    <select class="user-type-9OJ" name="user_type" onchange="submitForm()">
                        <?php if ($user_type != "Admin") { ?>
                            <option value="Client" <?php echo ($user_type == "Client") ? 'selected' : ''; ?>>Client</option>
                        <?php } ?>
                        <option value="Agent" <?php echo ($user_type == "Agent") ? 'selected' : ''; ?>>Agent</option>
                        <?php if ($user_type != "Client") { ?>
                            <option value="Admin" <?php echo ($user_type == "Admin") ? 'selected' : ''; ?>>Admin</option>
                        <?php } ?>
                    </select>
                </form>
                <?php if (getUserType($user_id) != "Client") { ?>
                    <div class="wrapper">
                        <form id="department-form" action="/../../database/processes/process_agent_departments.php"
                            method="POST">
                            <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                            <div class="select-wrap">
                                <ul class="default-option">
                                    <li>
                                        <div class="option">
                                            <p>
                                                <span class="count">0</span>
                                                <span class="text">Select departments</span>
                                            </p>
                                            <span class="icon">
                                                <img class="icon-dropdown" src="/../../images/arrow_drop.png" alt="↓">
                                            </span>
                                        </div>
                                    </li>
                                </ul>
                                <ul class="select-ul">
                                    <?php drawDepartmentWithCheckbox($user_id); ?>
                                    <li class="button-li"><button type="submit">Submit</button></li>
                                </ul>
                            </div>
                        </form>
                    </div>

                <?php } ?>
            </div>
        </div>
        <div class="ticket-info">
            <div class="info-tickets-0PI">
                <p class="opened-tickets-text">Opened Tickets</p>
                <p class="opened-tickets-amount">
                    <?php echo 'to do' ?>
                </p>
            </div>
            <div class="info-tickets-0PI">
                <p class="opened-tickets-text">Closed Tickets</p>
                <p class="opened-tickets-amount">
                    <?php echo 'to do' ?>
                </p>
            </div>
            <div class="info-tickets-0PI">
                <p class="opened-tickets-text">All Tickets</p>
                <p class="opened-tickets-amount">
                    <?php echo getClientTicketsAmount($user_id) ?>
                </p>
            </div>
        </div>
        <div class="group-user-details-ban">
            <div class="user-details-09L">
                <p class="user-details-09L-text">User details</p>
                <div class="user-details-09L-field">
                    <p class="user-details-09L-userinfo">Username:
                        <?php echo getUsername($user_id) ?>
                    </p>
                    <p class="user-details-09L-userinfo">Name:
                        <?php echo getName($user_id) ?>
                    </p>
                    <p class="user-details-09L-userinfo">Email:
                        <?php echo getEmail($user_id) ?>
                    </p>
                </div>
            </div>
            <form action="/../../database/processes/process_ban_user.php" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                <input type="submit" class="ban-user" value="Ban">
            </form>
        </div>
    </div>
</body>