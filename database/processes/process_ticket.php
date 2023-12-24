<?php
declare(strict_types=1);

require_once(__DIR__ . '/../../utils/change_ticket.php');

// Choose what function to run based on what form was submitted
if (isset($_POST['department_id'])) {
    changeDepartment();
} else if (isset($_POST['status_id'])) {
    changeStatus();
} else if (isset($_POST['agent_id'])) {
    changeAgent();
} else if (isset($_POST['tag_id'])) {
    deleteTag();
} else if (isset($_POST['tag_name'])) {
    addTag();
}

// Changes department from ticket
function changeDepartment(): void
{
    $ticket_id = $_POST['ticket_id'];
    $department_id = $_POST['department_id'];

    changeTicketDepartment($ticket_id, $department_id);

    header("Location: /pages/agent_view/tickets.php?selected=unassigned");
    exit;
}

// Changes status from ticket
function changeStatus(): void
{
    $ticket_id = $_POST['ticket_id'];
    $status_id = $_POST['status_id'];

    changeTicketStatus($ticket_id, $status_id);

    header("Location: /pages/agent_view/tickets.php?selected=unassigned");
    exit;
}

function changeAgent(): void
{
    $ticket_id = $_POST['ticket_id'];
    $agent_id = $_POST['agent_id'];

    changeTicketAgent($ticket_id, $agent_id);

    header("Location: /pages/agent_view/tickets.php?selected=unassigned");
    exit;
}

// Deletes tag from ticket
function deleteTag(): void
{

    $ticket_id = $_POST['ticket_id'];
    $tag_id = $_POST['tag_id'];
    $selected = $_POST['selected'];

    deleteTicketTag($ticket_id, $tag_id);

    header("Location: /pages/agent_view/tickets.php?selected=$selected");
    exit;
}

// Adds tag to ticket
function addTag(): void
{
    $ticket_id = $_POST['ticket_id'];
    $tag_name = $_POST['tag_name'];

    addTicketTag($ticket_id, $tag_name);

    header("Location: /pages/agent_view/tickets.php?selected=unassigned");
    exit;
}