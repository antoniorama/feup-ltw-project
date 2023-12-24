<?php

require_once(__DIR__ . '/../templates/common_tpl.php');
require_once(__DIR__ . '/../templates/ticket_tpl.php');
require_once(__DIR__ . '/../utils/function_utils.php');
require_once(__DIR__ . '/../database/classes/ticket_class.php');
require_once(__DIR__ . '/../database/classes/chat_message_class.php');
require_once(__DIR__ . '/../database/classes/user_class.php');

session_start();

drawHeader("ticketpage");

$ticket_id = (int) $_GET['id'];
$ticket = getTicket($ticket_id);
$user_type = getUserType($_SESSION['user_id']);
$user_id = $_SESSION['user_id']
    ?>

<body>

    <?php
    handleUserTicket($ticket_id, $user_id);
    drawUserTab();

    if ($user_type === "Client") {
        drawClientSideTab(2); ?>
    <?php } else if ($user_type === "Agent") { ?>

            <div class="side_tab">
            <?php drawCompany() ?>
                <div class="line"></div>
                <div class="button_list">
                    <a href="./agent_view/overview.php">
                        <div class="button_departments">
                            <p class="main-tab-text">Overview</p>
                            <img class="side-bar-imagFe-1" src="/../images/overview.png" alt="O">
                        </div>
                    </a>
                    <a href="./agent_view/tickets.php?selected=unassigned">
                        <div class="button_users">
                            <p class="text_users">Tickets</p>
                            <img class="side-bar-image-2" src="/../../images/tickets.png" alt="O">
                            <div class="rectangle"></div>
                        </div>
                    </a>
                </div>
            </div>

    <?php } ?>
    <?php drawNewTicketPopup();
    drawChooseFAQPopup($ticket_id); ?>

    <div class="main-tab"> <!-- ticket -->
        <div class="upper-tab">
            <?php drawTicketTitle($ticket_id); ?>
            <div class="separator"></div>

        </div>
        <?php drawTicketText($ticket_id, $user_id); ?>
    </div>
    <div class="ticket-info-box">
        <?php drawTicketInfo($ticket_id); ?>
    </div>

    <!-- Popups  : Edit ticket info -->

    <!-- Change ticket agent -->
    <div class="filter_user_popup" id="po4Fg">
        <div class="filter_user_popup_content">
            <img class="filter_user_popup_content_close" src="/images/close.png" alt="close">
            <p class="filter_user_popup_content_text">Assign ticket to agent</p>
            <form class="filter_user_form" action="/database/processes/process_ticket.php" method="POST">
                <input type="hidden" name="ticket_id" value="<?php echo $ticket_id ?>">
                <div class="form-row">
                    <label for="order1">Agent:</label>
                    <select id="order1" name="agent_id">
                        <?php $ticket_department_id = getTicketDepartmentId($ticket_id);
                        $agents = getAgentsFromDepartment($ticket_department_id);
                        foreach ($agents as $agent) {
                            $user_id = $agent['user_id'];
                            ?>
                            <option value="<?php echo $agent['id'] ?>" <?php if ($ticket->agent_id === $agent['id'])
                                   echo "selected"; ?>><?php echo getUsername($user_id); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="submit-row">
                    <input type="submit" name="submit" value="Apply">
                </div>
            </form>
        </div>
    </div>

    <!-- Change ticket department -->
    <div class="filter_user_popup" id="xF45g">
        <div class="filter_user_popup_content">
            <img class="filter_user_popup_content_close" src="/images/close.png" alt="close">
            <p class="filter_user_popup_content_text">Change ticket department</p>
            <form class="filter_user_form" action="/database/processes/process_ticket.php" method="POST">
                <input type="hidden" name="ticket_id" value="<?php echo $ticket_id ?>">
                <div class="form-row">
                    <label for="order2">Department:</label>
                    <select id="order2" name="department_id">
                        <?php $departments = getAllDepartments();
                        foreach ($departments as $department) {
                            ?>
                            <option value="<?php echo $department['id'] ?>" <?php if ($ticket->department_id === $department['id'])
                                   echo "selected"; ?>><?php echo $department['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="submit-row">
                    <input type="submit" name="submit" value="Apply">
                </div>
            </form>
        </div>
    </div>

    <!-- Change ticket status -->
    <div class="filter_user_popup" id="lO0df">
        <div class="filter_user_popup_content">
            <img class="filter_user_popup_content_close" src="/images/close.png" alt="close">
            <p class="filter_user_popup_content_text">Change ticket status</p>
            <form class="filter_user_form" action="/database/processes/process_ticket.php" method="POST">
                <input type="hidden" name="ticket_id" value="<?php echo $ticket_id ?>">
                <div class="form-row">
                    <label for="order3">Status:</label>
                    <select id="order3" name="status_id">
                        <?php $status = getAllStatus();
                        foreach ($status as $st) {
                            ?>
                            <option value="<?php echo $st['id'] ?>" <?php if ($ticket->status_id === $st['id'])
                                   echo "selected"; ?>><?php echo $st['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="submit-row">
                    <input type="submit" name="submit" value="Apply">
                </div>
            </form>
        </div>
    </div>
</body>