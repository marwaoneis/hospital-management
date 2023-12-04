<?php
// Database connection parameters
$host = 'localhost';
$db_name = 'hospital_management';
$username = 'root';
$password = null;

// Create connection
try{
$conn = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    echo 'Connection failed: ' . $exception->getMessage();
    die();
}
?>
