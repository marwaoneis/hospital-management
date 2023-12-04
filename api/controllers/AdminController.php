<?php
include_once '../models/Admin.php';

class AdminController {
    private $conn;
    private $adminModel;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->adminModel = new Admin($this->conn);
    }

    public function getAllDoctors() {
        authenticate();

        try {
            $stmt = $this->conn->query("SELECT * FROM doctors");
            $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($doctors);

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(array("message" => "Internal Server Error", "error" => $e->getMessage()));
        }
    }

    public function getAllPatients() {
        authenticate();

        try {
            $stmt = $this->conn->query("SELECT * FROM patients");
            $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($patients);

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(array("message" => "Internal Server Error", "error" => $e->getMessage()));
        }
    }

    public function manageDoctors() {
        authenticate();
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Create a new doctor
            $data = json_decode(file_get_contents("php://input"));
    
            if (!empty($data->name) && !empty($data->specialization)) {
                try {
                    $stmt = $this->conn->prepare("INSERT INTO doctors (name, specialization) VALUES (?, ?)");
                    $stmt->execute([$data->name, $data->specialization]);
    
                    http_response_code(201);
                    echo json_encode(array("message" => "Doctor created successfully"));
    
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(array("message" => "Internal Server Error", "error" => $e->getMessage()));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Incomplete data. Please provide name and specialization."));
            }
    
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Get the list of all doctors
            try {
                $stmt = $this->conn->query("SELECT * FROM doctors");
                $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($doctors);
    
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(array("message" => "Internal Server Error", "error" => $e->getMessage()));
            }
    
        } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            // Update an existing doctor
            $data = json_decode(file_get_contents("php://input"));
    
            if (!empty($data->id) && !empty($data->name) && !empty($data->specialization)) {
                try {
                    $stmt = $this->conn->prepare("UPDATE doctors SET name = ?, specialization = ? WHERE id = ?");
                    $stmt->execute([$data->name, $data->specialization, $data->id]);
    
                    http_response_code(200);
                    echo json_encode(array("message" => "Doctor updated successfully"));
    
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(array("message" => "Internal Server Error", "error" => $e->getMessage()));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Incomplete data. Please provide id, name, and specialization."));
            }
    
        } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            // Delete an existing doctor
            $data = json_decode(file_get_contents("php://input"));
    
            if (!empty($data->id)) {
                try {
                    $stmt = $this->conn->prepare("DELETE FROM doctors WHERE id = ?");
                    $stmt->execute([$data->id]);
    
                    http_response_code(200);
                    echo json_encode(array("message" => "Doctor deleted successfully"));
    
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(array("message" => "Internal Server Error", "error" => $e->getMessage()));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Incomplete data. Please provide id."));
            }
        }
    }

    public function managePatients() {
        authenticate();
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Create a new patient
            $data = json_decode(file_get_contents("php://input"));
    
            if (!empty($data->name) && !empty($data->dob) && !empty($data->gender) && !empty($data->contact_number)) {
                try {
                    $stmt = $this->conn->prepare("INSERT INTO patients (name, dob, gender, contact_number) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$data->name, $data->dob, $data->gender, $data->contact_number]);
    
                    http_response_code(201);
                    echo json_encode(array("message" => "Patient created successfully"));
    
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(array("message" => "Internal Server Error", "error" => $e->getMessage()));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Incomplete data. Please provide name, dob, gender, and contact_number."));
            }
    
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Get the list of all patients
            try {
                $stmt = $this->conn->query("SELECT * FROM patients");
                $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($patients);
    
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(array("message" => "Internal Server Error", "error" => $e->getMessage()));
            }
    
        } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            // Update an existing patient
            $data = json_decode(file_get_contents("php://input"));
    
            if (!empty($data->id) && !empty($data->name) && !empty($data->dob) && !empty($data->gender) && !empty($data->contact_number)) {
                try {
                    $stmt = $this->conn->prepare("UPDATE patients SET name = ?, dob = ?, gender = ?, contact_number = ? WHERE id = ?");
                    $stmt->execute([$data->name, $data->dob, $data->gender, $data->contact_number, $data->id]);
    
                    http_response_code(200);
                    echo json_encode(array("message" => "Patient updated successfully"));
    
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(array("message" => "Internal Server Error", "error" => $e->getMessage()));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Incomplete data. Please provide id, name, dob, gender, and contact_number."));
            }
    
        } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            // Delete an existing patient
            $data = json_decode(file_get_contents("php://input"));
    
            if (!empty($data->id)) {
                try {
                    $stmt = $this->conn->prepare("DELETE FROM patients WHERE id = ?");
                    $stmt->execute([$data->id]);
    
                    http_response_code(200);
                    echo json_encode(array("message" => "Patient deleted successfully"));
    
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(array("message" => "Internal Server Error", "error" => $e->getMessage()));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Incomplete data. Please provide id."));
            }
        }
    }
    
    public function assignPatientToRoom($patientId, $roomId) {
        authenticate();
    
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            // Assign a patient to a room
            $data = json_decode(file_get_contents("php://input"));
    
            if (!empty($data->room_id)) {
                try {
                    $stmt = $this->conn->prepare("UPDATE patients SET room_id = ? WHERE id = ?");
                    $stmt->execute([$data->room_id, $patientId]);
    
                    http_response_code(200);
                    echo json_encode(array("message" => "Patient assigned to room successfully"));
    
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(array("message" => "Internal Server Error", "error" => $e->getMessage()));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Incomplete data. Please provide room_id."));
            }
        }
    }    

}
?>
