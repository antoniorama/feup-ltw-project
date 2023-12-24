<?php
declare(strict_types=1);

session_start(); // start the session

// This class is used in admin panel so that admins are able to sort users
class UserFilters {
    public string $user_type;  // All, Client, Agent or Admin
    public string $sort;   // Id_asc, Id_desc, Username_asc, Username_desc

    public function __construct($user_type = 'All', $sort = 'Id - Ascending') {
        $this->user_type = $user_type;
        $this->sort = $sort;
    }

    // Sets the session's 'user_filters_query' parameter
    function applyFilters() {

        $query = '';

        // Apply user_type filter
        if ($this->user_type == 'All') {
            $query = 'SELECT * FROM User U';
        } 
        
        else {
            $query = "SELECT u.id, u.username, u.name, u.password, u.email
            FROM User U
            JOIN $this->user_type C ON U.id = C.user_id
            ";
        }

        // Apply sort filter
        if ($this->sort == 'Id_asc') {
            $query = $query . ' ORDER BY u.id ASC';
        } 
        
        else if ($this->sort == 'Id_desc') {
            $query = $query . ' ORDER BY u.id DESC';
        }

        else if ($this->sort == 'Username_asc') {
            $query = $query . ' ORDER BY u.username ASC';
        }

        else if ($this->sort == 'Username_desc') {
            $query = $query . ' ORDER BY u.username DESC';
        }

        $query = $query . ';';

        $_SESSION['user_filters_query'] = $query;
    }
}