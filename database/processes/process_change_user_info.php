<?php
declare(strict_types=1);

require_once(__DIR__ . '/../../database/connection_db.php');
require_once(__DIR__ . '/../../utils/function_utils.php');
require_once(__DIR__ . '/../../utils/validate_inputs.php');

session_start();

$name = $_POST['name'];
validateName($name);

$username = $_POST['username'];
validateUsername($username);

$db = databaseConnection();

//Checking if username or email already exists (except for the current user)
$email = $_POST['email'];
$username = $_POST['username'];

$stmt = $db->prepare('SELECT COUNT(*) FROM User WHERE (email = ? OR username = ?) AND id <> ?;');
$stmt->execute([$email, $username, $_SESSION['user_id']]);

if ($stmt->fetchColumn() > 0) {
    die('Email or username already exists');
}

// Updating user's values
$query = "UPDATE User SET name = ?, email = ?, username = ? WHERE id = ?;";

$stmt = $db->prepare($query);
$stmt->bindParam(1, $_POST['name']);
$stmt->bindParam(2, $_POST['email']);
$stmt->bindParam(3, $_POST['username']);
$stmt->bindParam(4, $_SESSION['user_id']);

if (!$stmt->execute()) {
    die('Database error');
}

header('Location: ' . getMainPage($_SESSION['user_id']));