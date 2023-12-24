<?php
declare(strict_types=1);

session_start(); // Start the session

require_once(__DIR__ . '/../../database/classes/user_filters_class.php');

$user_filters = new UserFilters($_GET['user_type'], $_GET['order1']);

$user_filters->applyFilters();

header('Location: /../pages/admin_view/users.php');