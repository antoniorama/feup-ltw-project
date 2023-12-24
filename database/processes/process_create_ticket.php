<?php

declare(strict_types=1);

require_once(__DIR__ . '/../../database/connection_db.php');
require_once(__DIR__ . '/../../utils/function_utils.php');

session_start();

$title = $_POST['title'];
$description = $_POST['description'];
$department = $_POST['department'];
$priority = priorityTextToInt($_POST['priority']);
$client_id = $_SESSION['user_id'];

$db = databaseConnection();
$query = "INSERT INTO TICKET(title, description, client_id, department_id, status_id, priority)
             VALUES (?, ?, ?, ?, ?, ?);";

$stmt = $db->prepare($query);
$stmt->execute([$title, $description, $client_id, $department, 1, $priority]);

$ticket_id = $db->lastInsertId();

$query = "INSERT INTO ChatMessage(ticket_id, sender_id, message)
              VALUES(?, ?, ?)";
$stmt = $db->prepare($query);
$stmt->execute([$ticket_id, $client_id, $description]);

header('Location: /../pages/ticketpage.php?id=' . $ticket_id);
exit;