<?php

declare(strict_types=1);
require_once(__DIR__ . '/../database/connection_db.php');
require_once(__DIR__ . '/../utils/function_utils.php');

// Changes deparment id from ticket with $ticket_id to $department_id
function changeTicketDepartment($ticket_id, $department_id): void
{
    $db = databaseConnection();

    $query = "UPDATE Ticket SET department_id = ? WHERE id = ?;";

    $stmt = $db->prepare($query);
    $stmt->execute([$department_id, $ticket_id]);
}

// Changes status id from ticket with $ticket_id to $status_id
function changeTicketStatus($ticket_id, $status_id): void
{
    $db = databaseConnection();

    $query = "UPDATE Ticket SET status_id = ? WHERE id = ?;";

    $stmt = $db->prepare($query);
    $stmt->execute([$status_id, $ticket_id]);
}

// Changes agent id from ticket with $ticket_id to $agent_id
function changeTicketAgent($ticket_id, $agent_id): void
{
    $db = databaseConnection();

    $query = "UPDATE Ticket SET agent_id = ? WHERE id = ?;";

    $stmt = $db->prepare($query);
    $stmt->execute([$agent_id, $ticket_id]);
}

// Deletes tag with $tag_id from ticket with $ticket_id
function deleteTicketTag($ticket_id, $tag_id): void
{
    $db = databaseConnection();

    $query = "DELETE FROM TicketHashtag WHERE ticket_id = ? AND hashtag_id = ?;";

    $stmt = $db->prepare($query);
    $stmt->execute([$ticket_id, $tag_id]);
}

// Adds a tag with $tag_name to ticket with $ticket_id
// If the tag does't exist yet, creates a new tag
// Tickets can't have more than 3 hashtags
function addTicketTag($ticket_id, $tag_name)
{
    $db = databaseConnection();

    // Tickets can't have more than 3 hashtags
    $tags_in_ticket = getTagsFromTicket($ticket_id);
    if (count($tags_in_ticket) >= 3)
        die("Tickets can't have more than 3 hashtags");

    $all_tags = getAllTags();
    $all_tags_names = [];

    // If tag doesn't exist, create it!
    if (!in_array($tag_name, $all_tags_names)) {
        $query2 = "INSERT INTO Hashtag (name) VALUES (?)";

        $stmt = $db->prepare($query2);
        $stmt->execute([$tag_name]);
    }

    foreach ($all_tags as $tag) {
        $all_tags_names[] = $tag['name'];
    }

    $tag = getHashtagFromName($tag_name);
    $tag_id = $tag['id'];

    // Verify if tag is already in ticket
    if (in_array($tag, $tags_in_ticket))
        die('Tag already in ticket');

    $query = "INSERT INTO TicketHashtag (ticket_id, hashtag_id)
    VALUES (?, ?);";
    $query2 = "";

    $stmt = $db->prepare($query);
    $stmt->execute([$ticket_id, $tag_id]);
}