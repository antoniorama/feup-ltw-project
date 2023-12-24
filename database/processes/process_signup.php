<?php
declare(strict_types=1);

require_once(__DIR__ . '/../../database/connection_db.php');
require_once(__DIR__ . '/../../utils/function_utils.php');
require_once(__DIR__ . '/../../utils/validate_inputs.php');

$email = $_POST['email'];
validateEmail($email);

$name = $_POST['name'];
validateName($name);

$username = $_POST['username'];
validateUsername($username);

$password = $_POST['password'];
validatePassword($password);

if ($_POST['confirm-password'] !== $password) {
    die('Passwords must match');
}

// Hashing the password - bcrypt
// The password_hash() function in PHP uses a salt by default when generating a hash.
// The salt is randomly generated and included in the resulting hash,
// making it more secure against various types of attacks, 
// such as dictionary attacks and rainbow table attacks.
$password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Inserting the values into database
$db = databaseConnection();

//Checking if username or email already exists
$email = $_POST['email'];
$username = $_POST['username'];

$stmt = $db->prepare('SELECT COUNT(*) FROM User WHERE email = ? OR username = ?;');
$stmt->execute([$email, $username]);

if ($stmt->fetchColumn() > 0) {
    die('Email or username already exists');
}

$query = 'INSERT INTO User(name, username, password, email)
          VALUES (?, ?, ?, ?);';

$stmt = $db->prepare($query);
$stmt->bindParam(1, $_POST['name']);
$stmt->bindParam(2, $_POST['username']);
$stmt->bindParam(3, $password_hash);
$stmt->bindParam(4, $_POST['email']);

if (!$stmt->execute()) {
    die('Database error');
}

$id_user = $db->lastInsertId();

// By default it creates a new Client
$query = 'INSERT INTO Client(user_id) VALUES (?);';

$stmt = $db->prepare($query);

$stmt->bindParam(1, $id_user, PDO::PARAM_INT);

if (!$stmt->execute()) {
    die('Database error');
}

header('Location: /../pages/signup_successful.php');
?>