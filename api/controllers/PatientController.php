<?php
include_once '../models/Patient.php';

class PatientController {
    private $conn;
    private $patientModel;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->patientModel = new PatientModel($this->conn);
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

    public function getPatientProfile($patientId) {
        authenticate();

        try {
            $stmt = $this->conn->prepare("SELECT * FROM patients WHERE id = ?");
            $stmt->execute([$patientId]);
            $patient = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($patient) {
                echo json_encode($patient);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Patient not found"));
            }

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(array("message" => "Internal Server Error", "error" => $e->getMessage()));
        }
    }

    public function getMedicalHistory($patientId) {
        authenticate();

        try {
            $stmt = $this->conn->prepare("SELECT * FROM medical_history WHERE patient_id = ?");
            $stmt->execute([$patientId]);
            $medicalHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($medicalHistory);

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(array("message" => "Internal Server Error", "error" => $e->getMessage()));
        }
    }

    public function getAppointments($patientId) {
        authenticate();

        try {
            $stmt = $this->conn->prepare("SELECT * FROM appointments WHERE patient_id = ?");
            $stmt->execute([$patientId]);
            $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($appointments);

        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(array("message" => "Internal Server Error", "error" => $e->getMessage()));
        }
    }

    public function manageAppointments($patientId) {
        authenticate();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Book a new appointment
            $data = json_decode(file_get_contents("php://input"));

            if (!empty($data->doctor_id) && !empty($data->appointment_date)) {
                try {
                    $stmt = $this->conn->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date) VALUES (?, ?, ?)");
                    $stmt->execute([$patientId, $data->doctor_id, $data->appointment_date]);

                    http_response_code(201);
                    echo json_encode(array("message" => "Appointment booked successfully"));

                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(array("message" => "Internal Server Error", "error" => $e->getMessage()));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Incomplete data. Please provide doctor_id and appointment_date."));
            }

        } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            // Cancel an existing appointment
            $data = json_decode(file_get_contents("php://input"));

            if (!empty($data->appointment_id)) {
                try {
                    $stmt = $this->conn->prepare("DELETE FROM appointments WHERE id = ? AND patient_id = ?");
                    $stmt->execute([$data->appointment_id, $patientId]);

                    http_response_code(200);
                    echo json_encode(array("message" => "Appointment canceled successfully"));

                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(array("message" => "Internal Server Error", "error" => $e->getMessage()));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Incomplete data. Please provide appointment_id."));
            }
        }
    }
}
?>
