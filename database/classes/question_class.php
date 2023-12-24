<?php
declare(strict_types=1);

class Question {

    public int $id;
    public string $question;
    public string $answer;

    public function __construct(int $id, string $question, string $answer) {
        $this->id = $id;
        $this->question = $question;
        $this->answer = $answer;
    }

    static function getQuestions(PDO $db) {
        $query = "SELECT * FROM Question;";
        $stmt = $db->prepare($query);

        $stmt->execute();

        $questions = array();
        
        while($question = $stmt->fetch()) {
            $questions[] = new Question(
                $question['id'],
                $question['question'],
                $question['answer'],
            );
        }
        return $questions;
    }

}