<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Workers</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"] {
            padding: 8px;
            width: 300px;
        }
        input[type="submit"] {
            padding: 8px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Search Workers by ID</h1>
    <form action="fetch_workers.php" method="post">
        <label for="worker_ids">Enter Worker IDs (comma-separated):</label><br>
        <input type="text" id="worker_ids" name="worker_ids" required>
        <input type="submit" value="Search">
    </form>
</body>
</html>
