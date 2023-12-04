<?php
class Doctor {
    private $conn;
    private $table_name = "doctors";

    public $id;
    public $name;
    public $specialization;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new doctor
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (name, specialization) VALUES (:name, :specialization)";
        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->specialization = htmlspecialchars(strip_tags($this->specialization));

        // Bind parameters
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":specialization", $this->specialization);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Read all doctors
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Update a doctor's information
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET name = :name, specialization = :specialization WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->specialization = htmlspecialchars(strip_tags($this->specialization));

        // Bind parameters
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":specialization", $this->specialization);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Delete a doctor
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
