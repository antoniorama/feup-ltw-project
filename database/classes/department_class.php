<?php
declare(strict_types=1);

class Department {

    public int $id;
    public string $name;

    public function __construct(int $id, string $name) {
        $this->id = $id;
        $this->name = $name;
    }

    static function getDepartment(PDO $db, string $query) {
        
        $stmt = $db->prepare($query);

        $stmt->execute();

        $departments = array();

        while($department = $stmt->fetch()) {
            $departments[] = new Department(
                $department['id'],
                $department['name'],
            );
        }

        return $departments;
    }
}