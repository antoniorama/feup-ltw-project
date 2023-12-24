<?php
declare(strict_types=1);

require_once(__DIR__ . '/../../database/connection_db.php');
require_once(__DIR__ . '/../../utils/function_utils.php');
require_once(__DIR__ . '/../../utils/validate_inputs.php');

session_start();

$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$confirm_new_password = $_POST['confirm_new_password'];

// Confirm current password
$db = databaseConnection();

$query = 'SELECT * FROM User WHERE id = ?';
$statement = $db->prepare($query);

if (!$statement->execute([$_SESSION['user_id']])) {
    // Handle database error
    die('Database error');
}

$user = $statement->fetch(PDO::FETCH_ASSOC);

if (!password_verify($current_password, $user['password'])) {
    die('Current password is wrong!');
}


validatePassword($new_password);

if ($confirm_new_password !== $new_password) {
    die('Passwords must match');
}

// Hashing the password - bcrypt
// The password_hash() function in PHP uses a salt by default when generating a hash.
// The salt is randomly generated and included in the resulting hash,
// making it more secure against various types of attacks, 
// such as dictionary attacks and rainbow table attacks.
$password_hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

// Updating user's values
$query = "UPDATE User SET password = ? WHERE id = ?;";

$stmt = $db->prepare($query);

if (!$stmt->execute([$password_hash, $_SESSION['user_id']])) {
    die('Database error');
}

header('Location: /../pages/client_settings.php');