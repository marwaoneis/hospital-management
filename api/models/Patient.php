<?php
class Patient {
    private $conn;
    private $table_name = "patients";

    public $id;
    public $name;
    public $dob;
    public $gender;
    public $contact_number;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a new patient
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (name, dob, gender, contact_number) VALUES (:name, :dob, :gender, :contact_number)";
        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->dob = htmlspecialchars(strip_tags($this->dob));
        $this->gender = htmlspecialchars(strip_tags($this->gender));
        $this->contact_number = htmlspecialchars(strip_tags($this->contact_number));

        // Bind parameters
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":dob", $this->dob);
        $stmt->bindParam(":gender", $this->gender);
        $stmt->bindParam(":contact_number", $this->contact_number);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Read all patients
    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Update a patient's information
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET name = :name, dob = :dob, gender = :gender, contact_number = :contact_number WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Sanitize input
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->dob = htmlspecialchars(strip_tags($this->dob));
        $this->gender = htmlspecialchars(strip_tags($this->gender));
        $this->contact_number = htmlspecialchars(strip_tags($this->contact_number));

        // Bind parameters
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":dob", $this->dob);
        $stmt->bindParam(":gender", $this->gender);
        $stmt->bindParam(":contact_number", $this->contact_number);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Delete a patient
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
