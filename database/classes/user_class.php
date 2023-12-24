<?php
declare(strict_types=1);

class User {
    public int $id;
    public string $name;
    public string $username;
    public string $password;
    public string $email;

    public function __construct($id, $name, $username, $password, $email) {
        $this->id = $id;
        $this->name = $name;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
    }

    static function getUser(PDO $db, string $query) {
        
        $stmt = $db->prepare($query);

        $stmt->execute();

        $users = array();

        while($user = $stmt->fetch()) {
            $users[] = new User(
                $user['id'],
                $user['name'],
                $user['username'],
                $user['password'],
                $user['email']
            );
        }

        return $users;
    }
}