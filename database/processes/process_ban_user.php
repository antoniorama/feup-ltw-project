<?php
declare(strict_types=1);

require_once(__DIR__ . '/../../database/connection_db.php');

$db = databaseConnection();

// Note: When someone is banned from user table he is also banned from
// the corresponding user type table because of database triggers.
$query = "DELETE FROM User WHERE id = ?";

$stmt = $db->prepare($query);

$stmt->execute([$_POST['user_id']]);

header('Location: /../pages/admin_view/users.php');