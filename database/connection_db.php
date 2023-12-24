<?php
declare(strict_types=1);

function databaseConnection(): PDO
{
    $db = new PDO('sqlite:' . __DIR__ . '/../database/database.db');

    return $db;
}
?>