<?php
// Database configuration
$servername = "localhost"; // Replace with your server name
$username = "root"; // Replace with your database username
$password = "1913"; // Replace with your database password
$dbname = "gigconnect"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect form data
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$location = $_POST['location'];
$work_preference = $_POST['work_preference'];
$skills = $_POST['skills'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password for security

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO gig_workers (name, email, phone, location, work_preference, skills, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $name, $email, $phone, $location, $work_preference, $skills, $password);

// Execute the statement
if ($stmt->execute()) {
    echo "Registration successful!";
} else {
    echo "Error: " . $stmt->error;
}

// Close connection
$stmt->close();
$conn->close();
?>
