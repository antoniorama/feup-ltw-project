<?php
declare(strict_types=1);

class Ticket {
    public int $id;
    public string $title;
    public string $description;
    public int $client_id;
    public int $agent_id;
    public int $department_id;
    public int $status_id; 
    public int $priority;

    public function __construct(int $id, string $title, string $description, int $client_id, ?int $agent_id, int $department_id, ?int $status_id, int $priority) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->client_id = $client_id;
        $this->agent_id = $agent_id ?? 0;
        $this->department_id = $department_id;
        $this->status_id = $status_id ?? 0;
        $this->priority = $priority;
    }

    // Gets tickets from database passing them to class objects.
    // Uses an $id that can be assigned to ? in a $query if $ignoreId is false (default)
    static function getTickets(PDO $db, int $id , string $query, ?bool $ignoreId = false) {

        $stmt = $db->prepare($query);

        if (!$ignoreId) {
            $stmt->execute([$id]);
        } else {
            $stmt->execute();
        }
        $tickets = array();

        while($ticket = $stmt->fetch()) {
            $tickets[] = new Ticket(
                $ticket['id'],
                $ticket['title'],
                $ticket['description'],
                $ticket['client_id'],
                $ticket['agent_id'],
                $ticket['department_id'],
                $ticket['status_id'],
                $ticket['priority'],
            );
        }

        return $tickets;
    }
}
?>