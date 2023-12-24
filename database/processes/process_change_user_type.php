<?php
declare(strict_types=1);

require_once(__DIR__ . '/../../database/connection_db.php');
require_once(__DIR__ . '/../../utils/function_utils.php');

$db = databaseConnection();

$user_id = $_POST['user_id'];
$client_id = getClientId($user_id);
$agent_id = getAgentId($user_id);
// Current user type
$current_user_type = getUserType($user_id);
// User type to change to
$user_type = $_POST['user_type'];
$query = "";

// If user is changing to Client (only possible from Agent)
if ($user_type == "Client") {
    $query = "DELETE FROM Agent WHERE user_id = ?;";
    $stmt = $db->prepare($query);
    $stmt->execute([$user_id]);
}

// If user is changing to Agent from Client
if ($user_type == "Agent" && $current_user_type == "Client") {
    $query = "INSERT INTO Agent(user_id, client_id) VALUES(?, ?);";
    $stmt = $db->prepare($query);
    $stmt->execute([$user_id, $client_id]);
}

// If user is changing to Agent from Admin
if ($user_type == "Agent" && $current_user_type == "Admin") {
    $query = "DELETE FROM Admin WHERE user_id = ?;";
    $stmt = $db->prepare($query);
    $stmt->execute([$user_id]);
}

// If user is changing to Admin (only possible from Agent)
if ($user_type == "Admin") {
    $query = "INSERT INTO Admin(user_id, agent_id) VALUES(?, ?);";
    $stmt = $db->prepare($query);
    $stmt->execute([$user_id, $agent_id]);
}

header('Location: /../pages/admin_view/users.php');