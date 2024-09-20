<?php
session_start();

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

// Fetch worker ID from URL query parameter
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $worker_id = intval($_GET['id']); // Sanitize input
} else {
    die("Worker ID is missing.");
}

// Prepare and execute the SQL query to fetch worker details
$sql = "SELECT * FROM gig_workers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $worker_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if user data exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    die("User not found.");
}

// Prepare and execute the SQL query to fetch industries demanding similar skills
$skills = $user['skills'];
$industry_sql = "SELECT industry_name, location, contact_email, contact_phone FROM industry WHERE skills LIKE ?";
$like_param = '%' . $skills . '%';
$industry_stmt = $conn->prepare($industry_sql);
$industry_stmt->bind_param("s", $like_param);
$industry_stmt->execute();
$industries_result = $industry_stmt->get_result();

// Close connection
$stmt->close();
$industry_stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GigConnect - Gig Worker Support Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        /* CSS for the dashboard */
        .dashboard-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #ddd;
        }

        .dashboard-container h2 {
            text-align: center;
            font-size: 2rem;
            color: #003366;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #003366;
            padding-bottom: 0.5rem;
        }

        .dashboard-details, .industries-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .detail-item, .industry-item {
            padding: 1rem;
            background-color: #f9f9f9;
            border-radius: 6px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .detail-item span, .industry-item span {
            font-weight: bold;
            color: #003366;
        }

        .industry-item:nth-child(even) {
            background-color: #f1f1f1;
        }

        /* Footer styles */
        footer {
            background-color: #003366;
            color: white;
            padding: 1.5rem 0;
            text-align: center;
        }

        .footer-content {
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .footer-content h4 {
            margin-bottom: 1rem;
        }

        .footer-content ul {
            list-style-type: none;
            padding: 0;
            margin-bottom: 1rem;
        }

        .footer-content ul li {
            display: inline;
            margin: 0 1rem;
        }

        .footer-content a {
            color: #66ccff;
            text-decoration: none;
        }

        .footer-content a:hover {
            text-decoration: underline;
        }

        .contact-btn {
            display: inline-block;
            margin: 0.5rem;
            padding: 0.5rem 1rem;
            background-color: #66ccff;
            color: #003366;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
        }

        .contact-btn:hover {
            background-color: #3399ff;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .footer-content ul {
                margin-bottom: 1rem;
            }

            .footer-content ul li {
                display: block;
                margin: 0.5rem 0;
            }

            .contact-btn {
                display: block;
                margin: 0.5rem auto;
            }
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <div id="logo"><img src="images/logo.png" alt="GigConnect"></div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse show" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.html">Home</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Dashboard -->
<div class="dashboard-container">
    <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h2>
    <div class="dashboard-details">
        <div class="detail-item"><span>Email:</span> <?php echo htmlspecialchars($user['email']); ?></div>
        <div class="detail-item"><span>Phone:</span> <?php echo htmlspecialchars($user['phone']); ?></div>
        <div class="detail-item"><span>Location:</span> <?php echo htmlspecialchars($user['location']); ?></div>
        <div class="detail-item"><span>Work Preference:</span> <?php echo htmlspecialchars($user['work_preference']); ?></div>
        <div class="detail-item"><span>Skills:</span> <?php echo nl2br(htmlspecialchars($user['skills'])); ?></div>
    </div>

    <h3>Industries Demanding Your Skills:</h3>
    <div class="industries-list">
        <?php if ($industries_result->num_rows > 0) {
            while ($industry = $industries_result->fetch_assoc()) { ?>
                <div class="industry-item">
                    <span>Industry Name:</span> <?php echo htmlspecialchars($industry['industry_name']); ?><br>
                    <span>Location:</span> <?php echo htmlspecialchars($industry['location']); ?><br>
                    <span>Contact Email:</span> <a href="mailto:<?php echo htmlspecialchars($industry['contact_email']); ?>"><?php echo htmlspecialchars($industry['contact_email']); ?></a><br>
                    <span>Contact Phone:</span> 
                    <?php if (!empty($industry['contact_phone'])): ?>
                        <a href="tel:<?php echo htmlspecialchars($industry['contact_phone']); ?>"><?php echo htmlspecialchars($industry['contact_phone']); ?></a>
                    <?php else: ?>
                        Not provided
                    <?php endif; ?>
                </div>
        <?php } } else { ?>
            <div class="industry-item">
                No industries currently demanding your skills.
            </div>
        <?php } ?>
    </div>
</div>

<!-- Footer -->
<footer>
    <div class="footer-content">
        <div id="logo" class="footer-logo"><img src="images/logo.png" alt="GigConnect"></div>
        <ul>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms of Service</a></li>
            <li><a href="mailto:ankitgour19168893@gmail.com">Contact Us</a></li>
            <li><a href="tel:+919399484597">+91 9399484597</a></li>
        </ul>
        <div class="footer-social">
            <a href="https://www.linkedin.com/in/ankit-gour-0a00ab259/" target="_blank">LinkedIn</a>
        </div>
        <p>© 2024 GigConnect. All rights reserved.</p>
    </div>

    <div id="google_translate_element"></div>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
