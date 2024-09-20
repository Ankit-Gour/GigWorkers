<?php
// Database credentials
$servername = "localhost";  
$username = "root";         
$password = "1913";             
$dbname = "gigconnect";    

// Create a connection to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to create the 'industry' table if it doesn't already exist
$sql = "CREATE TABLE IF NOT EXISTS industry (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    industry_name VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    domain VARCHAR(255) NOT NULL,
    work_type VARCHAR(255) NOT NULL,
    skills TEXT NOT NULL,
    contact_email VARCHAR(255) NULL,
    contact_phone VARCHAR(20) NULL,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

$conn->query($sql);

// Check if form data has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $industry_name = $conn->real_escape_string($_POST['industry_name']);
    $location = $conn->real_escape_string($_POST['location']);
    $domain = $conn->real_escape_string($_POST['domain']);
    $work_type = $conn->real_escape_string($_POST['work_type']);
    $skills = $conn->real_escape_string($_POST['skills']);
    $contact_email = $conn->real_escape_string($_POST['contact_email']);
    $contact_phone = $conn->real_escape_string($_POST['contact_phone']);

    // Validate email format
    if (!filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format');</script>";
    } else {
        // SQL query to insert form data into the 'industry' table
        $sql = "INSERT INTO industry (industry_name, location, domain, work_type, skills, contact_email, contact_phone)
                VALUES ('$industry_name', '$location', '$domain', '$work_type', '$skills', '$contact_email', '$contact_phone')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('New industry registered successfully');</script>";
        } else {
            echo "<script>alert('Error: " . $sql . "\\n" . $conn->error . "');</script>";
        }
    }
}

// Fetch all registered industries
$sql = "SELECT * FROM industry";
$result = $conn->query($sql);

// Start HTML Output
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Industry Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: scale(1.02);
        }
        .card-header {
            font-weight: bold;
            color: #007BFF;
        }
        .card-content {
            margin-top: 10px;
        }
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            .card {
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Registered Industries</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card">
                <div class="card-header">Industry ID: <?= $row['id'] ?></div>
                <div class="card-content">
    <strong>Industry Name:</strong> <?= htmlspecialchars($row['industry_name']) ?><br>
    <strong>Location:</strong> <?= htmlspecialchars($row['location']) ?><br>
    <strong>Domain:</strong> <?= htmlspecialchars($row['domain']) ?><br>
    <strong>Type of Work:</strong> <?= htmlspecialchars($row['work_type']) ?><br>
    <strong>Skills:</strong> <?= htmlspecialchars($row['skills']) ?><br>
    <strong>Contact Email:</strong> <?= htmlspecialchars($row['contact_email']) ?><br>
    <strong>Contact Phone:</strong> <?= !empty($row['contact_phone']) ? htmlspecialchars($row['contact_phone']) : 'Not provided' ?><br>
    <strong>Registration Date:</strong> <?= htmlspecialchars($row['reg_date']) ?><br>
</div>

            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No industries registered yet.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
