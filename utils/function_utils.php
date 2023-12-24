<?php
// ██╗░░░██╗████████╗██╗██╗░░░░░░██████╗
// ██║░░░██║╚══██╔══╝██║██║░░░░░██╔════╝
// ██║░░░██║░░░██║░░░██║██║░░░░░╚█████╗░
// ██║░░░██║░░░██║░░░██║██║░░░░░░╚═══██╗
// ╚██████╔╝░░░██║░░░██║███████╗██████╔╝
// ░╚═════╝░░░░╚═╝░░░╚═╝╚══════╝╚═════╝░

// This is a big file with all the auxiliar functions that
// we used in our website.
// This file is a little messy but the idea is to have as many
// functions as possible to help in some tasks, avoiding SQL queries
// or loops

declare(strict_types=1);
require_once(__DIR__ . '/../database/connection_db.php');

// Checks if a user is a client. Returns client_id if is client, otherwise null
function getClientId($user_id): ?int
{
    $db = databaseConnection();

    $query = 'SELECT id FROM Client WHERE user_id = :user_id';
    $statement = $db->prepare($query);
    $statement->bindParam(':user_id', $user_id);

    if ($statement->execute()) {
        $client_id = $statement->fetchColumn();
        if ($client_id !== false) {
            return $client_id;
        }
    }

    return null;
}

// Checks if a user is an agent. Returns agent_id if is agent, otherwise null
function getAgentId($user_id): ?int
{
    $db = databaseConnection();

    $query = 'SELECT id FROM Agent WHERE user_id = :user_id';
    $statement = $db->prepare($query);
    $statement->bindParam(':user_id', $user_id);

    if ($statement->execute()) {
        $agent_id = $statement->fetchColumn();
        if ($agent_id !== false) {
            return $agent_id;
        }
    }

    return null;
}

// Checks if a user is an admin. Returns agent_id if is client, otherwise null
function getAdminId($user_id): ?int
{
    $db = databaseConnection();

    $query = 'SELECT id FROM Admin WHERE user_id = :user_id';
    $statement = $db->prepare($query);
    $statement->bindParam(':user_id', $user_id);

    if ($statement->execute()) {
        $admin_id = $statement->fetchColumn();
        if ($admin_id !== false) {
            return $admin_id;
        }
    }

    return null;
}

// Gets username from user_id. Returns username if user_id is valid, otherwise null
function getUsername($user_id): ?string
{
    $db = databaseConnection();

    $query = 'SELECT username FROM User WHERE id = :id';
    $statement = $db->prepare($query);
    $statement->bindParam(':id', $user_id);

    if ($statement->execute()) {
        $username = $statement->fetchColumn();
        if ($username !== false) {
            return $username;
        }
    }

    return null;
}

// Gets name from user_id. Returns name if user_id is valid, otherwise null
function getName($user_id): ?string
{
    $db = databaseConnection();

    $query = 'SELECT name FROM User WHERE id = :id';
    $statement = $db->prepare($query);
    $statement->bindParam(':id', $user_id);

    if ($statement->execute()) {
        $name = $statement->fetchColumn();
        if ($name !== false) {
            return $name;
        }
    }

    return null;
}

// Gets email from user_id. Returns email if user_id is valid, otherwise null
function getEmail($user_id): ?string
{
    $db = databaseConnection();

    $query = 'SELECT email FROM User WHERE id = :id';
    $statement = $db->prepare($query);
    $statement->bindParam(':id', $user_id);

    if ($statement->execute()) {
        $email = $statement->fetchColumn();
        if ($email !== false) {
            return $email;
        }
    }

    return null;
}

// Gets user type (client, agent or admin) in a string
function getUserType($user_id): string
{

    if (getAdminId($user_id))
        return 'Admin';

    if (getAgentId($user_id))
        return 'Agent';
    else
        return 'Client';
}

// Returns user Id , from username
// If no user was found with $username, returns null
function getUserIdFromUsername(string $username)
{
    $db = databaseConnection();

    $query = "SELECT id FROM User WHERE username = ?";

    $stmt = $db->prepare($query);
    $stmt->execute([$username]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        return $result['id'];
    }

    return null; // If no user found with the given username
}

function getMainPage($user_id): string
{
    if (getAdminId($user_id) != null)
        return '/../pages/admin_view/departments.php';
    else if (getAgentId($user_id) != null)
        return '/../pages/agent_view/overview.php';
    else if (getClientId($user_id) != null)
        return '/../pages/client_view/tickets.php?selected=open';
    else
        return '';
}

// Gets the total amount of client tickets
function getClientTicketsAmount($user_id): int
{
    $db = databaseConnection();

    $query = "SELECT COUNT(*) AS ticket_count
    FROM Ticket
    WHERE client_id = (
        SELECT id
        FROM Client
        WHERE user_id = ?
    );
    ";

    $stmt = $db->prepare($query);
    $stmt->execute([$user_id]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $ticket_count = $result['ticket_count'];

    return (int) $ticket_count;
}

// Returns a list of deparments names from $user_id
function getDepartmentsFromAgent($user_id)
{
    $db = databaseConnection();

    // If user not agent or admin return empty list
    if (getUserType($user_id) == "Client")
        return [];

    $agent_id = getAgentId($user_id);

    $query = "SELECT Department.*
    FROM Department
    INNER JOIN AgentDepartment ON Department.id = AgentDepartment.department_id
    WHERE AgentDepartment.agent_id = $agent_id;";

    $result = $db->query($query);
    $departments = [];

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $departments[] = $row['id'];
    }

    return $departments;
}

// True -> Agent with $user_id is in department with $department_id
// False -> Agent with $user_id is NOT in department with $department_id
function isAgentInDepartment($department_id, $user_id): bool
{
    $departments = getDepartmentsFromAgent($user_id);

    if (in_array($department_id, $departments))
        return true;
    return false;
}

// Adds department with $department_id to agent with $user_id
// Returns 1 on success and 0 on fail
function addDepartmentToAgent($department_id, $user_id): void
{

    $db = databaseConnection();

    // If user not agent or admin return 0
    if (getUserType($user_id) == "Client")
        ;

    // If agent is already in department, return 0
    if (isAgentInDepartment($department_id, $user_id))
        ;

    $agent_id = getAgentId($user_id);

    $query = "INSERT INTO AgentDepartment(agent_id, department_id) VALUES (?, ?);";
    $stmt = $db->prepare($query);
    $stmt->execute([$agent_id, $department_id]);
}

// Removes department with $department_id from agent with $user_id
// Returns 1 on success and 0 on fail
function deleteDepartmentFromAgent($department_id, $user_id): void
{
    $db = databaseConnection();

    // If user not agent or admin return 0
    if (getUserType($user_id) == "Client")
        return;

    // If agent isn't in department, return 0
    if (!isAgentInDepartment($department_id, $user_id))
        return;

    $agent_id = getAgentId($user_id);

    $query = "DELETE FROM AgentDepartment WHERE agent_id = ? AND department_id = ?;";
    $stmt = $db->prepare($query);
    $stmt->execute([$agent_id, $department_id]);
}

// Checks which types of users are allowed in the page and acts accordingly
function handleUser(bool $not_logged_allowed, bool $client_allowed, bool $agent_allowed, bool $admin_allowed): void
{
    // If not logged in and is not allowed to access if is not logged in
    if (!isset($_SESSION['user_id']) && !$not_logged_allowed)
        redirectNotAllowed();

    if (!isset($_SESSION['user_id']) && $not_logged_allowed)
        return;

    // If user is client and client users are not allowed
    if ($_SESSION['client_id'] != 0 && $_SESSION['agent_id'] == 0 && !$client_allowed)
        redirectNotAllowed();
    // If user is agent and agent users are not allowed
    if ($_SESSION['agent_id'] != 0 && $_SESSION['admin_id'] == 0 && !$agent_allowed)
        redirectNotAllowed();
    // If user is admin and admin users are not allowed
    if ($_SESSION['admin_id'] != 0 && !$admin_allowed)
        redirectNotAllowed();
}

// If a user if not allowed in a page, redirect him accordingly
function redirectNotAllowed(): void
{

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        // Redirect the user to the login page
        header("Location: /../../pages/loginpage.php");
        exit;
    } else {

        // Redirect the admin to his main page
        if ($_SESSION['admin_id'] != 0) {
            header("Location: /../../pages/admin_view/departments.php");
            exit;
        }

        // Redirect the agent to his main page
        if ($_SESSION['agent_id'] != 0) {
            header("Location: /../../pages/agent_view/overview.php");
            exit;
        }

        // Redirect the client to his main page
        if ($_SESSION['client_id'] != 0) {
            header("Location: /../../pages/client_view/tickets.php?selected=open");
            exit;
        }
    }
}
function getTicket($ticket_id)
{

    $db = databaseConnection();

    $query = "SELECT id, title, description, client_id, agent_id, department_id, status_id, priority
                   FROM Ticket
                   WHERE id = ?
                   GROUP BY id;";


    $result = Ticket::getTickets($db, $ticket_id, $query);

    return $result[0];
}

function getChat($ticket_id, $orderDesc = false)
{

    $db = databaseConnection();
    if ($orderDesc === false) {
        $query = "SELECT *
              FROM ChatMessage
              WHERE ticket_id = ?
              GROUP BY id
              ORDER BY timestamp";
    } else {
        $query = "SELECT *
            FROM ChatMessage
            WHERE ticket_id = ?
            GROUP BY id
            ORDER BY timestamp DESC";
    }

    $result = ChatMessage::getChatMessage($db, $ticket_id, $query);

    return $result;
}

function getDepartmentName($department_id)
{
    $db = databaseConnection();

    $query = 'SELECT name FROM Department WHERE id = :id';
    $statement = $db->prepare($query);
    $statement->bindParam(':id', $department_id);

    if ($statement->execute()) {
        $name = $statement->fetchColumn();
        if ($name !== false) {
            return $name;
        }
    }

    return null;
}

function getStatusName($status_id)
{
    $db = databaseConnection();

    $query = 'SELECT name FROM Status WHERE id = :id';
    $statement = $db->prepare($query);
    $statement->bindParam(':id', $status_id);

    if ($statement->execute()) {
        $name = $statement->fetchColumn();
        if ($name !== false) {
            return $name;
        }
    }

    return null;
}

function getLastActivity($ticket_id)
{

    $messages = getChat($ticket_id, true);
    if (count($messages) === 0)
        return;
    return $messages[0]->timestamp;
}

function getCreatedDate($ticket_id)
{

    $messages = getChat($ticket_id);
    if (count($messages) === 0)
        return;
    return $messages[0]->timestamp;
}

// 1 -> Low
// 2 -> Medium
// 3 -> Urgent
// TO DO -> should be named $ticket_priority, not $ticket_status
function priorityIntToText(int $ticket_status): string
{
    if ($ticket_status == 1)
        return "Low";
    else if ($ticket_status == 2)
        return "Medium";
    else if ($ticket_status == 3)
        return "Urgent";
    else
        return "Not a valid priority";
}

// Low -> 1
// Medium -> 2
// High -> 3
function priorityTextToInt(string $priority_name): int
{
    if ($priority_name === "Low")
        return 1;
    else if ($priority_name === "Medium")
        return 2;
    else if ($priority_name === "High")
        return 3;
    else
        return 0;
}

// Returns total messages from $ticket
function getTicketNumberMessages(Ticket $ticket): int
{

    $db = databaseConnection();

    $query = "SELECT COUNT(*) AS chatMessageCount
    FROM ChatMessage
    WHERE ticket_id = ?;
    ";

    $stmt = $db->prepare($query);

    $stmt->execute([$ticket->id]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $chatMessageCount = $result['chatMessageCount'];

    return $chatMessageCount;
}

// Returns all existing status
// Returns array[array]
function getAllStatus(): array
{
    $db = databaseConnection();

    $query = "SELECT * FROM Status";

    $stmt = $db->prepare($query);
    $stmt->execute();

    $status = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $status;
}

// Returns all existing tags
// Returns array[array]
function getAllTags(): array
{
    $db = databaseConnection();

    $query = "SELECT * FROM Hashtag";

    $stmt = $db->prepare($query);
    $stmt->execute();

    $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $tags;
}

// Returns tags from ticket_id
// Returns array[array]
function getTagsFromTicket($ticket_id)
{
    $db = databaseConnection();

    $query = "SELECT h.id, h.name
    FROM Ticket t
    JOIN TicketHashtag th ON t.id = th.ticket_id
    JOIN Hashtag h ON th.hashtag_id = h.id
    WHERE t.id = ?;
    ";

    $stmt = $db->prepare($query);
    $stmt->execute([$ticket_id]);

    $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $tags;
}

// Returns status name from status id 
function getStatusFromStatusId($status_id): string
{
    $db = databaseConnection();

    $query = "SELECT name FROM Status WHERE id = ?";

    $stmt = $db->prepare($query);
    $stmt->execute([$status_id]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $statusName = $result['name'];

    return $statusName;
}

function getStatsIdFromName(string $status_name)
{
    $db = databaseConnection();

    $query = "SELECT id FROM Status WHERE name = ?";

    $stmt = $db->prepare($query);
    $stmt->execute([$status_name]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $statusId = $result['id'];

    return $statusId;
}

// Returns hashtag name from hashtag id 
function getTagFromTagId($hashtag_id): string
{
    $db = databaseConnection();

    $query = "SELECT name FROM Hashtag WHERE id = ?";

    $stmt = $db->prepare($query);
    $stmt->execute([$hashtag_id]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $hashtagName = $result['name'];

    return $hashtagName;
}

// Returns hashtag id from hashtag name 
function getTagIdFromName($hashtag_name)
{
    $db = databaseConnection();

    $query = "SELECT id FROM Hashtag WHERE name = ?";

    $stmt = $db->prepare($query);
    $stmt->execute([$hashtag_name]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $hashtagId = $result['id'];

    return $hashtagId;
}

// Returns department name from department id 

function getDepartmentFromDepartmentId($department_id)
{
    if ($department_id === 0)
        return "No Department";
    $db = databaseConnection();

    $query = "SELECT name FROM Department WHERE id = ?";

    $stmt = $db->prepare($query);
    $stmt->execute([$department_id]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $departmentName = $result['name'];

    return $departmentName;
}

// Returns the time since the most recent message for ticket with $ticket_id
// If there are no chat messages in ticket, returns null
function getTicketLastMessageTime($ticket_id)
{
    $db = databaseConnection();

    $query = "SELECT
        CASE
            WHEN total_hours >= 24 THEN 
                (total_hours / 24) || ' d ' || (total_hours % 24) || ' h'
            ELSE
                (total_hours % 24) || ' h'
        END AS timeElapsed
    FROM
        (SELECT
            (strftime('%s', 'now') - strftime('%s', cm.timestamp)) / 3600 AS total_hours
        FROM
            ChatMessage AS cm
        WHERE
            cm.ticket_id = ?
        ORDER BY
            cm.timestamp DESC
        LIMIT 1) AS t";

    $stmt = $db->prepare($query);
    $stmt->execute([$ticket_id]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result === false || $result === true) {
        return null; // Return null if there are no chat messages in ticket
    }

    $timeElapsed = $result['timeElapsed'];

    return $timeElapsed;
}

// Returns all agents from department with $department_id
function getAgentsFromDepartment($department_id)
{
    $db = databaseConnection();

    $query = "SELECT A.*
    FROM Agent A
    JOIN AgentDepartment AD ON A.id = AD.agent_id
    WHERE AD.department_id = ?
    ";

    $stmt = $db->prepare($query);
    $stmt->execute([$department_id]);

    $agents = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $agents;
}

// Gets all the agents (their user_id) that belong to the same departments as agent with $user_id
function getColleages($user_id)
{

    if (getUserType($user_id) === 'Client')
        return;

    $departments = getDepartmentsFromAgent($user_id);

    $colleages = [];
    foreach ($departments as $department) {
        $agents = getAgentsFromDepartment($department);
        foreach ($agents as $agent) {
            $colleages[] = $agent['user_id'];
        }
    }

    $colleages = array_unique($colleages); // clears duplicates

    return $colleages;
}

// Returns all departments
function getAllDepartments(): array
{
    $db = databaseConnection();

    $query = "SELECT * FROM Department";

    $stmt = $db->prepare($query);
    $stmt->execute();

    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $departments;
}

// Returns status id from ticket with $ticket_id
function getTicketStatus($ticket_id)
{
    $db = databaseConnection();

    $query = "SELECT status_id FROM Ticket WHERE id = ?;";

    $stmt = $db->prepare($query);
    $stmt->execute([$ticket_id]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $status_id = $result['status_id'];

    return $status_id;
}

// Returns department id from ticket with $ticket_id
function getTicketDepartmentId($ticket_id)
{
    $db = databaseConnection();

    $query = "SELECT department_id FROM Ticket WHERE id = ?;";

    $stmt = $db->prepare($query);
    $stmt->execute([$ticket_id]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['department_id'];
}

function getUserIdFromAgentId($agent_id)
{
    $db = databaseConnection();

    $query = "SELECT user_id FROM Agent WHERE id = ?;";

    $stmt = $db->prepare($query);
    $stmt->execute([$agent_id]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result !== false) {
        return $result['user_id'];
    }
    return null;
}

// Gets hashtag(array) from $hashtag_name
function getHashtagFromName($hashtag_name): array
{
    $db = databaseConnection();

    $query = "SELECT id, name
                   FROM Hashtag
                   WHERE name = ?";


    $stmt = $db->prepare($query);
    $stmt->execute([$hashtag_name]);

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result[0];
}

// Checks if users are allowed to access tickets
// Clients -> Only allowed to access tickets of their ownership
// Agents -> Only allowed to access tickets of their departments (and write only on their own) 
// Admins -> Able to access all tickets (and write on all ticket)
function handleUserTicket($ticketId, $userId)
{
    $userType = getUserType($userId);
    $ticket = getTicket($ticketId);
    $ticketOwner = $ticket->client_id;
    $ticketDepartmentId = $ticket->department_id;

    // Handle client
    if ($userType === "Client" && $userId !== $ticketOwner) {
        redirectNotAllowed();
    }

    // Handle agent
    if ($userType === "Agent") {
        $departmentsIds = getDepartmentsFromAgent($userId);

        if (!in_array($ticketDepartmentId, $departmentsIds) && $ticketDepartmentId !== 0) {
            redirectNotAllowed();
        }
    }
}

function getAssignedAgent($ticket_id)
{
    $ticket = getTicket($ticket_id);
    $agent_id = $ticket->agent_id;
    return $agent_id;

}

function isTicketAgent($ticket_id, $user_id)
{
    $ticket = getTicket($ticket_id);
    $user_type = getUserType($user_id);

    if ($user_type == "Agent" & $user_id == $ticket->agent_id)
        return true;
    return false;
}

function canWriteTicket($ticket_id, $user_id)
{
    $user_type = getUserType($user_id);
    $ticket = getTicket($ticket_id);


    if (!str_starts_with(getStatusName(getTicketStatus($ticket_id)), "Open")) {
        return false;
    } else if ($user_type == "Agent" & $user_id != $ticket->agent_id) {
        return false;
    }

    return true;
}

function getAllQuestions()
{
    $db = databaseConnection();
    $questions = Question::getQuestions($db);

    return $questions;

}