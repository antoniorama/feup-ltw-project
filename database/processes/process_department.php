<?php

declare(strict_types=1);

require_once(__DIR__ . '/../../database/connection_db.php');

// Choose what function to process based on form that was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create_name'])) { // create form
        createDepartment();
    }
    if (isset($_POST['delete_id'])) { // delete form
        deleteDepartment();
    }
    if (isset($_POST['edit_id'])) { // edit form
        editDepartment();
    }
}

function createDepartment()
{

    $db = databaseConnection();

    $query = 'INSERT INTO Department(name) VALUES(?);';
    $stmt = $db->prepare($query);

    $stmt->execute([$_POST['create_name']]);

    header('Location: /../pages/admin_view/departments.php');
    exit;
}

function deleteDepartment()
{

    $db = databaseConnection();

    $query = 'DELETE FROM Department WHERE id = ?';
    $stmt = $db->prepare($query);

    $stmt->execute([$_POST['delete_id']]);

    header('Location: /../pages/admin_view/departments.php');
    exit;
}

function editDepartment()
{

    $db = databaseConnection();

    $query = 'UPDATE Department SET name = ? WHERE id = ?';
    $stmt = $db->prepare($query);

    $stmt->execute([$_POST['edit_name'], $_POST['edit_id']]);

    header('Location: /../pages/admin_view/departments.php');
    exit;
}