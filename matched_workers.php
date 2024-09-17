<?php
// Connect to your database
$conn = new mysqli('localhost', 'root', '1913', 'gigconnect'); // Update with your credentials

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the skill from the query parameter
$skill = isset($_GET['skill']) ? trim($_GET['skill']) : '';

// Escape skill to prevent SQL injection
$skill = $conn->real_escape_string($skill);

// Query to search for workers with the given skill
$sql = "SELECT * FROM gig_workers WHERE skills LIKE '%$skill%'";
$result = $conn->query($sql);

// Check if query execution was successful
if ($result === false) {
    die("Error executing query: " . $conn->error);
}

// Start output buffer to handle results
ob_start();

echo '<div class="workers-container">';
echo '<h1>Workers with Skill: ' . htmlspecialchars($skill) . '</h1>';

if ($result->num_rows > 0) {
    echo '<div class="workers-list">';
    while ($row = $result->fetch_assoc()) {
        echo '<div class="worker-item">';
        echo '<h2>Worker ID: ' . htmlspecialchars($row['id']) . '</h2>';
        echo '<p><strong>Name:</strong> ' . htmlspecialchars($row['name']) . '</p>';
        echo '<p><strong>Skills:</strong> ' . htmlspecialchars($row['skills']) . '</p>';
        echo '<p><strong>Location:</strong> ' . htmlspecialchars($row['location']) . '</p>';
        echo '<p><strong>Contact:</strong> ' . htmlspecialchars($row['phone']) . '</p>';
        echo '<div class="action-buttons">';
        echo '<a href="#" class="btn profile-btn" onclick="openModal(' . htmlspecialchars($row['id']) . ')">View Profile</a>';
        echo '</div>';
        // Hidden modal content for each worker
        echo '<div id="modal-' . htmlspecialchars($row['id']) . '" class="modal">';
        echo '<div class="modal-content">';
        echo '<span class="close" onclick="closeModal(' . htmlspecialchars($row['id']) . ')">&times;</span>';
        echo '<h2>Worker Profile: ' . htmlspecialchars($row['name']) . '</h2>';
        echo '<p><strong>Worker ID:</strong> ' . htmlspecialchars($row['id']) . '</p>';
        echo '<p><strong>Name:</strong> ' . htmlspecialchars($row['name']) . '</p>';
        echo '<p><strong>Skills:</strong> ' . htmlspecialchars($row['skills']) . '</p>';
        echo '<p><strong>Location:</strong> ' . htmlspecialchars($row['location']) . '</p>';
        echo '<p><strong>Contact:</strong> ' . htmlspecialchars($row['phone']) . '</p>';
        echo '<p><strong>About:</strong> ' . htmlspecialchars($row['work_preference']) . '</p>';
        // Email button
        echo '<p><a href="mailto:' . htmlspecialchars($row['email']) . '" class="btn contact-btn">Email Worker</a></p>';
        // Phone button
        echo '<p><a href="tel:' . htmlspecialchars($row['phone']) . '" class="btn contact-btn">Call Worker</a></p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
} else {
    echo '<p class="no-results">No workers found with the skill "' . htmlspecialchars($skill) . '".</p>';
}

// Get and clean output buffer content
$output = ob_get_clean();

// Close database connection
$conn->close();

// Output the result
echo $output;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workers with Skill</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .workers-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h1 {
            font-size: 28px;
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .workers-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .worker-item {
            flex: 1 1 calc(33.333% - 20px);
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            text-align: center;
            position: relative;
        }
        .worker-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .worker-item h2 {
            font-size: 22px;
            color: #4CAF50;
            margin-bottom: 15px;
        }
        .worker-item p {
            font-size: 16px;
            color: #555;
            margin: 10px 0;
        }
        .action-buttons {
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
        }
        .btn:hover {
            background-color: #45a049;
            transform: translateY(-3px);
        }
        .no-results {
            text-align: center;
            font-size: 18px;
            color: #888;
        }
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1001;  /* Ensure modal is above overlay */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;  /* Ensure content scrolls if too large */
        }
        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 60%;
            position: relative;
        }
        .close {
            color: #aaa;
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: #000;
        }
        .contact-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #008CBA;
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
            margin: 10px 0;
        }
        .contact-btn:hover {
            background-color: #007bb5;
            transform: translateY(-3px);
        }
        /* Overlay */
        .modal-overlay {
            display: none;
            position: fixed;
            z-index: 1000; /* Behind modal */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        /* Disable background scrolling */
        .no-scroll {
            overflow: hidden;
        }
        /* Media Queries */
        @media (max-width: 768px) {
            .worker-item {
                flex: 1 1 calc(50% - 20px);
            }
            .modal-content {
                width: 80%;
            }
        }
        @media (max-width: 480px) {
            .worker-item {
                flex: 1 1 100%;
            }
            .modal-content {
                width: 90%;
            }
        }
    </style>
</head>
<body>

<!-- Modal Overlay -->
<div class="modal-overlay"></div>

<script>
    // Function to open the modal
    function openModal(workerId) {
        document.getElementById('modal-' + workerId).style.display = 'block';
        document.querySelector('.modal-overlay').style.display = 'block';  // Show overlay
        document.body.classList.add('no-scroll');  // Disable scrolling
    }

    // Function to close the modal
    function closeModal(workerId) {
        document.getElementById('modal-' + workerId).style.display = 'none';
        document.querySelector('.modal-overlay').style.display = 'none';  // Hide overlay
        document.body.classList.remove('no-scroll');  // Re-enable scrolling
    }
</script>
</body>
</html>
