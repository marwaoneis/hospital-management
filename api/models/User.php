<?php

include_once '../config/database.php';

class UserModel {
    private $conn;

    public function __construct() {
        $this->conn = $GLOBALS['conn'];
    }

    public function getUserByUsername($username) {
        $query = 'SELECT * FROM users WHERE username = ? LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($username, $password, $role) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = 'INSERT INTO users (username, password, role) VALUES (?, ?, ?)';
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$username, $hashedPassword, $role]);
    }
}

?>