<?php
declare(strict_types=1);

require_once(__DIR__ . '/../../database/connection_db.php');
require_once(__DIR__ . '/../../utils/function_utils.php');

$db = databaseConnection();

// POST parameters to variables
$user_id = $_POST['user_id'];

$current_agent_departments = getDepartmentsFromAgent($user_id);

// If no department is selected delete all departments from user
if (!isset($_POST['department'])) {
    foreach ($current_agent_departments as $department) {
        deleteDepartmentFromAgent($department, $user_id);
    }

    header('Location: /../pages/admin_view/users.php');
    exit();
}

$departments = $_POST['department'];

// Get the departments that we need to add to the agent
$departments_to_add = [];

foreach ($departments as $department) {
    if (!in_array((string) $department, $current_agent_departments)) {
        $departments_to_add[] = $department;
    }
}

// Get the departments that we need to delete from the agent
$departments_to_remove = [];

foreach ($current_agent_departments as $department) {
    if (!in_array($department, $departments)) {
        $departments_to_remove[] = $department;
    }
}

// for deparment in $departments_to_add : addDepartmentToAgent
foreach ($departments_to_add as $department) {
    addDepartmentToAgent($department, $user_id);
}

// for deparment in $departments_to_remove : deleteDepartmentFromAgent
foreach ($departments_to_remove as $department) {
    deleteDepartmentFromAgent($department, $user_id);
}

// Go back to users page
header('Location: /../pages/admin_view/users.php');