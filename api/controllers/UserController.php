<?php

include_once '../config/database.php';
include_once '../models/User.php';

class UserController {
    private $conn;
    private $userModel;

    public function __construct() {
        $this->conn = $GLOBALS['conn'];
        $this->userModel = new User($this->conn);
    }


    public function signIn($username, $password) {
        // Validate input
        if (empty($username) || empty($password)) {
            return false;
        }

        // Check if the user exists
        $query = 'SELECT * FROM users WHERE username = ? LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false; // User not found
        }

        // Verify password
        if (password_verify($password, $user['password'])) {
            return $user; // Password is correct
        }

        return false; // Incorrect password
    }

    public function signUp($username, $password, $role) {
        // Validate input
        if (empty($username) || empty($password) || empty($role)) {
            return false;
        }

        // Check if the username is already taken
        $query = 'SELECT * FROM users WHERE username = ? LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$username]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            return false; // Username is taken
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $query = 'INSERT INTO users (username, password, role) VALUES (?, ?, ?)';
        $stmt = $this->conn->prepare($query);
        $success = $stmt->execute([$username, $hashedPassword, $role]);

        return $success;
    }
}
?>