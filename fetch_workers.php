<?php
$servername = "localhost";
$username = "root";
$password = "1913";
$database = "gigconnect";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get worker IDs from the form submission
$worker_ids = $_POST['worker_ids'];
$worker_ids_array = array_map('trim', explode(',', $worker_ids));

// Sanitize input to prevent SQL injection
$worker_ids_array = array_map([$conn, 'real_escape_string'], $worker_ids_array);
$worker_ids_list = implode(',', array_map(function($id) { return "'$id'"; }, $worker_ids_array));

// SQL query to fetch workers based on IDs
$sql = "SELECT id, name, email, phone, location, work_preference, skills FROM gig_workers WHERE id IN ($worker_ids_list)";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workers List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            overflow-x: auto;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        h1 {
            color: #007BFF;
            font-size: 24px;
            text-align: center;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        @media (max-width: 768px) {
            table {
                display: block;
                width: 100%;
                overflow-x: auto;
                white-space: nowrap;
            }
            th, td {
                display: block;
                text-align: right;
                padding: 10px;
                box-sizing: border-box;
            }
            th {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            td {
                position: relative;
                padding-left: 50%;
                text-align: left;
            }
            td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 45%;
                padding-left: 10px;
                font-weight: bold;
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Workers List</h1>
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
                <?php
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td data-label='ID'>{$row['id']}</td>
                                <td data-label='Name'>{$row['name']}</td>
                                <td data-label='Email'>{$row['email']}</td>
                                <td data-label='Phone'>{$row['phone']}</td>
                                <td data-label='Location'>{$row['location']}</td>
                                <td data-label='Work Preference'>{$row['work_preference']}</td>
                                <td data-label='Skills'>{$row['skills']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php $conn->close(); ?>
</body>
</html>
