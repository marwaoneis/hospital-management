<?php
class Admin {
    private $conn;
    private $table_name = "admins";

    public $id;
    public $name;  // You can extend this based on admin-specific properties

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new admin
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (name) VALUES (:name)";
        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->name = htmlspecialchars(strip_tags($this->name));

        // Bind parameters
        $stmt->bindParam(":name", $this->name);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Read all admins
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Update an admin's information
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET name = :name WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->name = htmlspecialchars(strip_tags($this->name));

        // Bind parameters
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $this->name);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Delete an admin
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind parameter
        $stmt->bindParam(":id", $this->id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
