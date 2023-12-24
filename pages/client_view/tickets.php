<?php
require_once(__DIR__ . '/../../templates/common_tpl.php');
require_once(__DIR__ . '/../../templates/ticket_tpl.php');
require_once(__DIR__ . '/../../utils/function_utils.php');

drawHeader("userview");

session_start();

// Check if user is allowed to access this page
handleUser(false, true, true, true);

$open_query = "SELECT t.id, t.title, t.description, t.client_id, t.agent_id, t.department_id, t.status_id, t.priority
FROM Ticket t
WHERE t.client_id = ?
AND t.status_id IN (
    SELECT s.id
    FROM Status s
    WHERE s.name LIKE '%Open%'
);";


$archive_query = "SELECT t.id, t.title, t.description, t.client_id, t.agent_id, t.department_id, t.status_id, t.priority
FROM Ticket t
WHERE t.client_id = ?
AND status_id IN (
    SELECT id
    FROM Status
    WHERE name LIKE 'Closed%'
);";


?>

<body id="userview">
  <?php drawNewTicketPopup();
  drawClientSideTab(2);
  drawCompany(); ?>
  <div class="container-userview">
    <div class="userview-upper-bar">
      <div class="text_yourtickets">Your tickets</div>
      <button class="new-ticket-button">New Ticket</button>
    </div>
    <div class="navigation">
      <div class="navigation-group">
        <a href="./tickets.php?selected=open"><div class="navigation-click">Open Tickets</div></a>
        <?php if ($_GET['selected'] === "open") { ?>
          <div class="navigation-downbar"></div>
        <?php } ?>
      </div>
      <div class="navigation-group">
        <a href="./tickets.php?selected=archive"><div class="navigation-click">Archive</div></a>
        <?php if ($_GET['selected'] === "archive") { ?>
          <div class="navigation-downbar"></div>
        <?php } ?>
      </div>
    </div>
    <?php if ($_GET['selected'] === "open") {
      drawTicketsClient($open_query);
    } else if ($_GET['selected'] === "archive") {
      drawTicketsClient($archive_query);
    } ?>
  </div>
  <?php drawUserTab() ?>
</body>