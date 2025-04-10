<?php 
// ================= DATABASE CONNECTION CONFIGURATION =================
// Connect to MySQL database using PDO

// Database connection parameters
$dsn = "mysql:host=localhost;dbname=school;charset=utf8mb4";
$user = "root";
$pass = "";

try {
    // Create new PDO connection object
    $conn = new PDO($dsn, $user, $pass);
    
    // Set error mode to throw exceptions for better error handling
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $error) {
    // Handle connection failures
    die("Database connection failed: " . $error->getMessage());
}

    
     

?>