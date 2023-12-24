<?php
declare(strict_types=1);

class ChatMessage {
    public int $id;
    public int $sender_id;
    public int $ticket_id;
    public string $message;
    public string $timestamp;


    public function __construct(int $id, int $sender_id, int $ticket_id, string $message, string $timestamp) {
        $this->id = $id;
        $this->sender_id = $sender_id;
        $this->ticket_id = $ticket_id;
        $this->message = $message;
        $this->timestamp = $timestamp;
    
    }

    static function getChatMessage(PDO $db, int $id , string $query, ?bool $ignoreId = false) {

        $stmt = $db->prepare($query);

        if (!$ignoreId) {
            $stmt->execute([$id]);
        } else {
            $stmt->execute();
        }
        $messages = array();

        while($message = $stmt->fetch()) {
            $messages[] = new chatMessage(
                $message['id'],
                $message['sender_id'],
                $message['ticket_id'],
                $message['message'],
                $message['timestamp']
            );
        }

        return $messages;
    }
    static function insertChatMessage(PDO $db, ChatMessage $message) {
        $query = "INSERT INTO ChatMessage(ticket_id, sender_id, message) VALUES
                (?, ?, ?);";
        $stmt = $db->prepare($query);
        $stmt->execute([$message->ticket_id, $message->sender_id,$message->message]);
    }
}

?>