<?php

declare(strict_types=1);
require_once(__DIR__ . '/../../utils/function_utils.php');

session_start(); // start the session

// This class is used in agent panel so that agents are able to sort tickets
class TicketFilters
{
    public string $sort; // Recent message date, Old message date
    public array $assigned_agents = []; // agent names
    public array $status = []; // status names
    public array $priority = []; // priorities names
    public array $hashtags = []; // hashtag names

    public function __construct($sort = "Recent message date", $assigned_agents = [], $status = [], $priority = [], $hashtags = [])
    {
        $this->sort = $sort;
        $this->assigned_agents = $assigned_agents;
        $this->status = $status;
        $this->priority = $priority;
        $this->hashtags = $hashtags;
    }

    // Retuns the query to use for assigned agents in applyFilters()
    function getAssignedAgentsFilterQuery() : string {
        $assigned_agents_query = "";

        // Initial value of query
        if ($this->assigned_agents !== []) {
            $assigned_agents_query = "AND (t.agent_id = ";
        }

        // Apply assigned_agents filter
        foreach ($this->assigned_agents as $agent_name) {
            $agent_id = getAgentId(getUserIdFromUsername($agent_name));
            $assigned_agents_query .= $agent_id . " OR t.agent_id = ";
        }

        // rtrim only removes from the end
        if ($this->assigned_agents !== []) {
            $assigned_agents_query = rtrim($assigned_agents_query, "OR t.agent_id = ");
            $assigned_agents_query .= ")";
        }

        return $assigned_agents_query;
    }

    // Retuns the query to use for status in applyFilters()
    function getStatusFilterQuery() : string {
        $status_query = "";

        if ($this->status !== []) {
            $status_query = " AND (t.status_id = ";
        }

        // Apply status filter
        foreach ($this->status as $status_name) {
            $status_id = getStatsIdFromName($status_name);
            $status_query .= $status_id . " OR t.status_id = "; 
        }

        // rtrim only removes from the end
        if ($this->status !== []) {
            $status_query = rtrim($status_query, "OR t.status_id = ");
            $status_query .= ")";
        }

        return $status_query;
    }

    function getPriorityFilterQuery() : string {
        $priority_query = "";

        if ($this->priority !== []) {
            $priority_query = " AND (t.priority = ";
        }

        // Apply priority filter
        foreach ($this->priority as $priority_name) {
            $priority_id = priorityTextToInt($priority_name);
            $priority_query .= $priority_id . " OR t.priority = "; 
        }

        // rtrim only removes from the end
        if ($this->priority !== []) {
            $priority_query = rtrim($priority_query, "OR t.priority = ");
            $priority_query .= ")";
        }

        return $priority_query;
    }

    // Returns the query to use for hashtags in applyFilters()
    // This is a little different from others because a ticket can have 
    // multiple hashtags
    function getHashtagsFilterQuery() : string {

        $hashtags_query = "";

        if ($this->hashtags !== []) {
            $hashtags_query = " AND EXISTS (
                SELECT 1
                FROM TicketHashtag th
                WHERE th.ticket_id = t.id
                  AND (th.hashtag_id = ";
        }

        // Apply hashtags filter
        foreach ($this->hashtags as $hashtag_name) {
            $hashtag_id = getTagIdFromName($hashtag_name);
            $hashtags_query .= $hashtag_id . " OR th.hashtag_id = "; 
        }

        // rtrim only removes from the end
        if ($this->hashtags !== []) {
            $hashtags_query = rtrim($hashtags_query, "OR th.hashtag_id = ");
            $hashtags_query .= "))";
        }

        return $hashtags_query;
    }

    // Sets the session's 'ticket_filters_query' parameter
    // This parameter is used to append to the already exinting query in
    // every of the ticket pages (unassigned, assigned_to_me, all_tickets ...)
    function applyFilters()
    {

        $query = "";

        $assigned_agents_query = $this->getAssignedAgentsFilterQuery();
        $status_query = $this->getStatusFilterQuery();
        $priority_query = $this->getPriorityFilterQuery();
        $hashtags_query = $this->getHashtagsFilterQuery();

        $query .= $assigned_agents_query;
        $query .= $status_query;
        $query .= $priority_query;
        $query .= $hashtags_query;

        $_SESSION['ticket_filters_query'] = $query;
    }

    // Returns string to append at the end of the query to sort
    function applySort() {

        $session_variable = "";

        // var_dump($this->sort);
        // die();

        if ($this->sort === "Recent message date") $session_variable = "DESC";
        else if ($this->sort === "Old message date") $session_variable = "ASC";
        else $session_variable = "";

        $_SESSION['sort_by'] = $session_variable;
    }
}