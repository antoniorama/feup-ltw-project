<?php

declare(strict_types=1);

require_once(__DIR__ . '/../../database/connection_db.php');
require_once(__DIR__ . '/../../utils/function_utils.php');

session_start();

$question = $_POST['question'];
$answer = $_POST['answer'];

$query = "INSERT INTO Question(question, answer)
          VALUES(?,?);";

$db = databaseConnection();
$stmt = $db->prepare($query);
$stmt->execute([$question, $answer]);

header('Location: /../pages/agent_view/overview.php');
exit;