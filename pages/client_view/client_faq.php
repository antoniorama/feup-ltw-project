<?php
declare(strict_types=1);

require_once(__DIR__ . '/../../templates/common_tpl.php');
require_once(__DIR__ . '/../../templates/faq_tpl.php');

session_start();

drawHeader("client_faq");

// Check if user is allowed to access this page
handleUser(false, true, true, true);
?>

<body>
    <?php
    drawCompany();
    drawClientSideTab(1);
    drawUserTab();
    ?>
    <div class="main-tab">
        <div class="upper-tab">
            <div class="main-tab-text"> Frequently Asked Questions</div>
            <div class="separator"> </div>
        </div>
        <?php drawQuestions(); ?>
    </div>
</body>