<?php
session_start();

// Check if user is logged in
// if (!isset($_SESSION['user_id'])) {
//     header('Location: login.php'); // Redirect to login page if not logged in
//     exit();
// }

// Database connection
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

// Fetch user data
// $user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM gig_workers WHERE id = '5'";
$result = $conn->query($sql);

// Check if user data exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    die("User not found.");
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - GigConnect</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: #f0f8ff;
            color: #333;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Header */
        header {
            background-color: #003366;
            padding: 1rem 0;
            color: white;
            text-align: center;
        }

        header h1 {
            margin: 0;
        }

        /* Navigation */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        nav .logo h1 {
            font-size: 2rem;
            color: white;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 1.5rem;
        }

        nav ul li a {
            color: white;
            font-size: 1.1rem;
        }

        nav ul li a.btn {
            background-color: #ffcc00;
            padding: 0.5rem 1.5rem;
            border-radius: 5px;
            color: #003366;
            font-weight: bold;
        }

        /* Dashboard Container */
        .dashboard-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .dashboard-container h2 {
            text-align: center;
            font-size: 2rem;
            color: #003366;
            margin-bottom: 1.5rem;
        }

        .dashboard-details {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .dashboard-details div {
            margin-bottom: 1rem;
            font-size: 1.1rem;
            color: #555;
        }

        .dashboard-details span {
            font-weight: bold;
            color: #003366;
        }

        /* Footer */
        footer {
            background-color: #003366;
            color: white;
            text-align: center;
            padding: 1.5rem 0;
            margin-top: 2rem;
        }

        footer p {
            margin: 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-container {
                width: 95%;
                padding: 1.5rem;
            }

            nav ul {
                flex-direction: column;
                gap: 0.5rem;
            }

            nav ul li a {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <nav>
            <div class="logo">
                <h1>GigConnect</h1>
            </div>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="about.html">About Us</a></li>
                <li><a href="features.html">Features</a></li>
                <li><a href="contact.html">Contact Us</a></li>
                <li><a href="logout.php" class="btn">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Dashboard -->
    <div class="dashboard-container">
        <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h2>
        <div class="dashboard-details">
            <div><span>Email:</span> <?php echo htmlspecialchars($user['email']); ?></div>
            <div><span>Phone:</span> <?php echo htmlspecialchars($user['phone']); ?></div>
            <div><span>Location:</span> <?php echo htmlspecialchars($user['location']); ?></div>
            <div><span>Work Preference:</span> <?php echo htmlspecialchars($user['work_preference']); ?></div>
            <div><span>Skills:</span> <?php echo nl2br(htmlspecialchars($user['skills'])); ?></div>
            <!-- Add more user details here -->
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 GigConnect. All rights reserved.</p>
    </footer>

</body>
</html>
