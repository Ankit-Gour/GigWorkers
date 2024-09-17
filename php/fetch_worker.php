<?php
// Connect to your database
$conn = new mysqli('localhost', 'root', '1913', 'gigconnect'); // Update with your credentials

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get all unique skills
$sql = "SELECT DISTINCT skills FROM gig_workers";
$result = $conn->query($sql);

$skills = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $skills[] = htmlspecialchars($row['skills']);
    }
}

// Close database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skills List</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden; /* Ensure content doesn't overflow */
        }
        .skills-container {
            text-align: center;
        }
        .skills-container h1 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 20px;
            animation: slideIn 0.5s ease-in-out;
        }
        .search-box {
            margin-bottom: 20px;
            margin-right: 20px;
        }
        .search-box input {
            width: 100%;
            max-width: 400px;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .search-box input:focus {
            border-color: #4CAF50;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            outline: none;
        }
        .skills-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            padding: 0;
            list-style-type: none;
            margin: 0;
        }
        .skill-item {
            background-color: #4CAF50;
            color: #fff;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            font-size: 1rem;
            text-align: center;
            text-decoration: none; /* Remove underline from links */
            max-width: 220px;
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
            animation: fadeIn 0.5s ease-in-out;
        }
        .skill-item:hover {
            background-color: #45a049;
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
            color: #e0e0e0;
        }
        .skills-container p {
            font-size: 1.125rem;
            color: #777;
            margin-top: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .search-box input {
                max-width: 100%;
            }
            .skills-list {
                flex-direction: column;
                align-items: center;
            }
            .skill-item {
                width: 100%;
                max-width: 300px;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="skills-container">
            <h1>Available Skills</h1>
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search for skills...">
            </div>
            <div id="skillsList" class="skills-list">
                <?php foreach ($skills as $skill): ?>
                    <a href="fetch_workers.php?skill=<?php echo urlencode($skill); ?>" class="skill-item"><?php echo $skill; ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            var input = this.value.toLowerCase();
            var skillItems = document.querySelectorAll('.skill-item');

            skillItems.forEach(function(item) {
                var skill = item.textContent.toLowerCase();
                if (skill.includes(input)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
