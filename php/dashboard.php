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

// Prepare and execute the SQL query
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

// Close connection
$stmt->close();
$conn->close();
?>



<html lang="en"><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GigConnect - Gig Worker Support Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
   <style>/* dashboard */

* Dashboard Container */
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

.dashboard-details {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.detail-item {
    padding: 1rem;
    background-color: #f9f9f9;
    border-radius: 6px;
    border: 1px solid #ddd;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.detail-item span {
    font-weight: bold;
    color: #003366;
}

.detail-item:nth-child(even) {
    background-color: #f1f1f1;
}

/* Responsive Design */
@media (max-width: 768px) {
    .dashboard-container {
        width: 95%;
        padding: 1.5rem;
    }
}</style>

</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">GigConnect</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="true" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="navbar-collapse collapse show" id="navbarNav" style="">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="index.html">Home</a>
              </li>
            
            
            </ul>
          </div>
        </div>
      </nav>


<!-- dashboard -->


  <!-- Dashboard -->

<div class="dashboard-container">
    <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h2>
    <div class="dashboard-details">
        <div class="detail-item"><span>Email:</span> <?php echo htmlspecialchars($user['email']); ?></div>
        <div class="detail-item"><span>Phone:</span> <?php echo htmlspecialchars($user['phone']); ?></div>
        <div class="detail-item"><span>Location:</span> <?php echo htmlspecialchars($user['location']); ?></div>
        <div class="detail-item"><span>Work Preference:</span> <?php echo htmlspecialchars($user['work_preference']); ?></div>
        <div class="detail-item"><span>Skills:</span> <?php echo nl2br(htmlspecialchars($user['skills'])); ?></div>
        <!-- Add more user details here -->
    </div>
</div>



    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <h4>GigConnect</h4>
            <ul>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Service</a></li>
            </ul>
            <p>© 2024 GigConnect. All rights reserved.</p>
        </div>
    </footer>

    <!-- Script -->
    <script>
    toggle();
    function toggle(){
    window.addEventListener('load', function() {
        // Select the element with the class 'navbar-toggler'
        var toggler = document.getElementsByClassName('navbar-toggler')[0];
        
        // Check if the element exists before attempting to click
        if (toggler) {
            toggler.click();
        }
    });}
        // Carousel functionality
        const carousel = document.getElementById('hero-carousel');
        const indicators = document.querySelectorAll('#carousel-indicators span');
        const slides = document.querySelectorAll('.hero-slide');
        const prev = document.getElementById('prev');
        const next = document.getElementById('next');
        let currentSlide = 0;

        function updateCarousel() {
            carousel.style.transform = `translateX(-${currentSlide * 100}%)`;
            indicators.forEach((indicator, index) => {
                indicator.classList.toggle('active', index === currentSlide);
            });
        }

        next.addEventListener('click', () => {
            currentSlide = (currentSlide + 1) % slides.length;
            updateCarousel();
        });

        prev.addEventListener('click', () => {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            updateCarousel();
        });

        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                currentSlide = index;
                updateCarousel();
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>


</body></html>

