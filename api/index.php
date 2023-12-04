<?php
include_once './config/database.php';
include_once './config/jwt.php';
include_once './controllers/UserController.php';
include_once './controllers/AdminController.php';
include_once './controllers/DoctorController.php';
include_once './controllers/PatientController.php';

$userController = new UserController();
$adminController = new AdminController($conn);
$doctorController = new DoctorController($conn);
$patientController = new PatientController($conn);

// Main routing logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
        case 'signin':
            handleSignIn($userController);
            break;
        case 'signup':
            handleSignUp($userController);
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['resource'])) {
    $resource = $_GET['resource'];

    switch ($resource) {
        case 'doctors':
            $doctorController->getAllDoctors();
            break;
        case 'patients':
            $patientController->getAllPatients();
            break;
        // Add more resource-specific endpoints as needed
        default:
            // Handle invalid resource requests
            http_response_code(404);
            echo json_encode(array("error" => "Invalid resource"));
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['resource'])) {
    $resource = $_GET['resource'];

    switch ($resource) {
        case 'doctors':
            $doctorController->createDoctor();
            break;
        case 'patients':
            $patientController->createPatient();
            break;
        // Add more resource-specific endpoints as needed
        default:
            // Handle invalid resource requests
            http_response_code(404);
            echo json_encode(array("error" => "Invalid resource"));
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['resource'])) {
    $resource = $_GET['resource'];

    switch ($resource) {
        case 'doctors':
            $doctorController->updateDoctor();
            break;
        case 'patients':
            $patientController->updatePatient();
            break;
        // Add more resource-specific endpoints as needed
        default:
            // Handle invalid resource requests
            http_response_code(404);
            echo json_encode(array("error" => "Invalid resource"));
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['resource'])) {
    $resource = $_GET['resource'];

    switch ($resource) {
        case 'doctors':
            $doctorController->deleteDoctor();
            break;
        case 'patients':
            $patientController->deletePatient();
            break;
        // Add more resource-specific endpoints as needed
        default:
            // Handle invalid resource requests
            http_response_code(404);
            echo json_encode(array("error" => "Invalid resource"));
            break;
    }
} else {
    // Handle invalid requests
    http_response_code(404);
    echo json_encode(array("error" => "Invalid request"));
}

function handleSignIn($userController) {
    $data = json_decode(file_get_contents("php://input"));

    if ($user = $userController->signIn($data->username, $data->password)) {
        $response = array("user" => $user, "message" => "Sign in successful");
        echo json_encode($response);
    } else {
        $response = array("error" => "Invalid credentials");
        echo json_encode($response);
    }
}

function handleSignUp($userController) {
    $data = json_decode(file_get_contents("php://input"));

    if ($userController->signUp($data->username, $data->password, $data->role)) {
        $response = array("message" => "Sign up successful");
        echo json_encode($response);
    } else {
        $response = array("error" => "Username already taken or invalid input");
        echo json_encode($response);
    }
}
?>
