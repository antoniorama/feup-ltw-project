<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/connection_db.php');

// Validates email input
function validateEmail(string $email): void
{

    // Remove leading and trailing whitespace
    $email = trim($email);

    // Apply XSS protection
    $email = htmlspecialchars($email, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    // Needs to be in email format, and not empty
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Email is not valid");
    }
}

// Validates name input
function validateName(string $name): void
{
    // Remove leading and trailing whitespace
    $name = trim($name);

    // Apply XSS protection
    $name = htmlspecialchars($name, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    // Cannot be empty 
    if (empty($name)) {
        die('Name is required');
    }

    // Cannot be > 16 chars
    if (strlen($name) > 16) {
        die("Name can't have more than 16 characters");
    }
}

// Validates username input
function validateUsername(string $username): void
{
    // Remove leading and trailing whitespace
    $username = trim($username);

    // Apply XSS protection
    $username = htmlspecialchars($username, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    // Cannot be > 16 chars
    if (strlen($username) > 16) {
        die("Username can't have more than 16 characters");
    }

    // Cannot be empty
    if (empty($username)) {
        die('Username is required');
    }

    // Cannot contain spaces
    if (preg_match('/\s/', $username)) {
        die('Username cannot contain spaces');
    }

    // Cannot contain capital letters
    if (preg_match('/[A-Z]/', $username)) {
        die('Username cannot contain capital letters');
    }
}

// Validates password input
function validatePassword(string $password): void
{
    // Apply XSS protection
    $password = htmlspecialchars($password, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    // Cannot be < 8 chars
    if (strlen($password) < 8) {
        die('Password must be at least 8 characters');
    }

    // Must contain one letter
    if (!preg_match("/[a-z]/i", $password)) {
        die('Password must contain at least one letter');
    }

    // Must contain one number
    if (!preg_match('/[0-9]/', $password)) {
        die('Password must contain at least one number');
    }
}