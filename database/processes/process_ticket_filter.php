<?php
declare(strict_types=1);

require_once(__DIR__ . '/../../database/classes/ticket_filters_class.php');

// Decide what to do depending on what was clicked
if (isset($_GET['clear-filters'])) {
    clearFilters();
} else if (isset($_GET['submit'])) {
    submitFilters();
}

function clearFilters()
{
    // Create the TicketFilters class (empty for default filters)
    $ticket_filters = new TicketFilters();

    // Apply the filters
    $ticket_filters->applySort();
    $ticket_filters->applyFilters();

    $selected = $_GET['selected'];

    header("Location: /pages/agent_view/tickets.php?selected=$selected");
    exit;
}

function submitFilters()
{
    $selected = $_GET['selected'];

    // Check if GET parameters are set, otherwise pass an empty list to class constructor
    $assignedAgents = isset($_GET['assigned-agents']) ? $_GET['assigned-agents'] : [];
    $status = isset($_GET['status']) ? $_GET['status'] : [];
    $priorities = isset($_GET['priorities']) ? $_GET['priorities'] : [];
    $hashtags = isset($_GET['hashtags']) ? $_GET['hashtags'] : [];
    $sort = isset($_GET['order1']) ? $_GET['order1'] : "Recent message date";

    // Create the TicketFilters class
    $ticket_filters = new TicketFilters($sort, $assignedAgents, $status, $priorities, $hashtags);

    // Apply the filters
    $ticket_filters->applySort();
    $ticket_filters->applyFilters();

    $url = "/../pages/agent_view/tickets.php?selected=$selected&filter=";

    $url .= 'sort:' . $sort;

    if ($ticket_filters->assigned_agents !== []) {
        $url = $url . 'Agents:';
    }

    foreach ($ticket_filters->assigned_agents as $agent_name) {
        $url = $url . $agent_name . ' ';
    }

    if ($ticket_filters->status !== []) {
        $url = $url . 'Status:';
    }

    foreach ($ticket_filters->status as $status_name) {
        $url = $url . $status_name . ' ';
    }

    if ($ticket_filters->priority !== []) {
        $url = $url . 'Priority:';
    }

    foreach ($ticket_filters->priority as $priority_name) {
        $url = $url . $priority_name . ' ';
    }

    if (!empty($ticket_filters->hashtags)) {
        $url = $url . 'Tags:';
    }

    foreach ($ticket_filters->hashtags as $hashtag_name) {
        // Clean # from tags before passing it 
        $hashtag_name = ltrim($hashtag_name, '#');
        $url = $url . $hashtag_name . ' ';
    }

    header("Location: $url");
    exit;
}