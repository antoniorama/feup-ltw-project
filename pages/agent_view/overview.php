<?php
declare(strict_types=1);

require_once(__DIR__ . '/../../templates/common_tpl.php');
require_once(__DIR__ . '/../../templates/faq_tpl.php');
drawHeader("agent_view_overview");

session_start();

// Check if user is allowed to access this page
handleUser(false, false, true, true);
?>

<body>
    <?php drawUserTab();
        createFaqPopup();
    ?>
    <div class="side_tab">
        <?php drawCompany(); ?>
        <div class="line"></div>
        <div class="button_list">
            <div class="button_departments">
                <p class="main-tab-text">Overview</p>
                <img class="side-bar-image-1" src="/../images/overview.png" alt="O">
                <div class="rectangle"></div>
            </div>
            <a href="tickets.php?selected=unassigned">
                <div class="button_users">
                    <p class="text_users">Tickets</p>
                    <img class="side-bar-image-2" src="/../../images/tickets.png" alt="O">
                </div>
            </a>
        </div>
    
    </div>
    <div class="main-tab">
        <div class="upper-tab">
            <div class="main-tab-text"> Frequently Asked Questions</div>
            <div class="separator"> </div>
        </div>
        <button id ="new-faq-button" class = "new-ticket-button">New FAQ</button>
        <?php drawQuestions(); ?>
    </div>
</body>