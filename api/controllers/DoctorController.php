<?php
include_once '../models/Doctor.php';

class DoctorController {
    private $conn;
    private $doctorModel;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->doctorModel = new DoctorModel($this->conn);
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

    public function getDoctorProfile($doctorId) {
        authenticate();

        try {
            $stmt = $this->conn->prepare("SELECT * FROM doctors WHERE id = ?");
            $stmt->execute([$doctorId]);
            $doctor = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($doctor) {
                echo json_encode($doctor);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Doctor not found"));
            }

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(array("message" => "Internal Server Error", "error" => $e->getMessage()));
        }
    }

    // Implement other CRUD operations for Doctors
}
?>
