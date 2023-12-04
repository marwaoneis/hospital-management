<?php

include_once '../config/jwt.php';
include_once '../config/database.php'; // Assuming you have a Database class for database connections

class AuthController
{
    public static function login($username, $password)
    {
        // Validate user credentials against the database
        $user = self::validateUserCredentials($username, $password);

        if ($user !== null) {
            $userData = array(
                "id" => $user['id'],
                "username" => $user['username'],
                "role" => $user['role'],
            );

            $expiry = time() + (60 * 60); // Token expires in 1 hour

            $token = JWTUtil::generateToken($userData, $expiry);

            return $token;
        }

        http_response_code(401);
        die(json_encode(array("message" => "Invalid username or password")));
    }

    private static function validateUserCredentials($username, $password)
    {
        // Use a secure method (e.g., password hashing) to compare the password
        $hashedPassword = hash('sha256', $password);

        // Replace this with your actual database connection logic
        $db = new Database();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("SELECT id, username, role FROM users WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $hashedPassword);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }

        return null;
    }
}
