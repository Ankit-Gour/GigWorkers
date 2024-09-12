<?php
// login.php

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "1913";
$dbname = "gigconnect";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$email = $_POST['email'];
$password = $_POST['password'];

// Prepare and bind
$stmt = $conn->prepare("SELECT * FROM gig_workers WHERE email = ? AND password = ?");
$stmt->bind_param("ss", $email, $password);

// Execute the statement
$stmt->execute();

// Store result
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // If record found, redirect to another PHP script
    header("Location: dashboard.php");
    exit();
} else {
    // If record not found, show error message
    echo "<p>Invalid email or password.</p>";
}

$stmt->close();
$conn->close();
?>
