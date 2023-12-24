<?php
declare(strict_types=1);

require_once(__DIR__ . '/../../database/connection_db.php');
require_once(__DIR__ . '/../../utils/function_utils.php');
require_once(__DIR__ . '/../../utils/change_ticket.php');
require_once(__DIR__ . '/../../database/classes/chat_message_class.php');

session_start();

$ticket_id = (int) $_POST["ticket_id"];

// XSS protection
$message_text = htmlspecialchars($_POST['message-input']);

$sender_id = $_SESSION['user_id'];

$message = new ChatMessage(0, $sender_id, $ticket_id, $message_text, " ");

if (!empty($message_text)) {

    $db = databaseConnection();

    ChatMessage::insertChatMessage($db, $message);

    // If message is from client, change status to "Open - Waiting for Answer"
    if (getUserType($sender_id) === "Client") {
        changeTicketStatus($ticket_id, 1); // passing the hardcoded id here is fine
        // as the first 4 status are supposed to be fixed!
    }

    // If message is from agent (or admin), change status to "Open - Waiting for Answer"
    if (getUserType($sender_id) !== "Client") {
        changeTicketStatus($ticket_id, 2); // passing the hardcoded id here is fine
        // as the first 4 status are supposed to be fixed!
    }
}

header("Location: /pages/ticketpage.php?id=" . $ticket_id);
?>