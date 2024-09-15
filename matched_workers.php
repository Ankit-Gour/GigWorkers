<?php
// Function to execute the Python script and get worker IDs
function getWorkerIds() {
    // Run the Python script and capture the JSON output
    $command = escapeshellcmd('C:\xampp\htdocs\gigworker\search.py');
    $output = shell_exec($command);
    
    // Decode the JSON output
    $data = json_decode($output, true);
    
    // Return worker IDs from the Python script
    if (isset($data['worker_ids'])) {
        return $data['worker_ids'];
    } else {
        return [];
    }
}

// Function to get worker details from the database
function getWorkerDetails($worker_ids) {
    $worker_details = [];
    
    // Connect to the MySQL database
    $conn = new mysqli('localhost', 'root', '1913', 'gigconnect');
    
    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Prepare the SQL query to get worker details
    foreach ($worker_ids as $id) {
        $sql = "SELECT * FROM gig_workers WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Fetch worker details and add them to the array
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $worker_details[] = $row;
            }
        }
    }
    
    // Close the connection
    $conn->close();
    
    return $worker_details;
}

// Get the worker IDs from the Python script
$worker_ids = getWorkerIds();

// Get the worker details from the database
if (!empty($worker_ids)) {
    $workers = getWorkerDetails($worker_ids);
} else {
    $workers = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matching Workers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        h1 {
            color: #007BFF;
            font-size: 24px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            border: 1px solid #ddd;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #007BFF;
            color: white;
        }
        @media (max-width: 768px) {
            table {
                border: 0;
            }
            table thead {
                display: none;
            }
            table tr {
                display: block;
                margin-bottom: 10px;
            }
            table td {
                display: block;
                text-align: right;
                padding-left: 50%;
                position: relative;
                border-bottom: 1px solid #ddd;
            }
            table td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 45%;
                padding-left: 15px;
                font-weight: bold;
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Matching Workers:</h1>
        <?php if (!empty($workers)): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Location</th>
                        <th>Work Preference</th>
                        <th>Skills</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($workers as $worker): ?>
                        <tr>
                            <td data-label="ID"><?php echo htmlspecialchars($worker['id']); ?></td>
                            <td data-label="Name"><?php echo htmlspecialchars($worker['name']); ?></td>
                            <td data-label="Email"><?php echo htmlspecialchars($worker['email']); ?></td>
                            <td data-label="Phone"><?php echo htmlspecialchars($worker['phone']); ?></td>
                            <td data-label="Location"><?php echo htmlspecialchars($worker['location']); ?></td>
                            <td data-label="Work Preference"><?php echo htmlspecialchars($worker['work_preference']); ?></td>
                            <td data-label="Skills"><?php echo htmlspecialchars($worker['skills']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No matching workers found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
