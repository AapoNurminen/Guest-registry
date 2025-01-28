<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

include 'db.php'; // Include the database connection

// Fetch all spaces from the database
$query = "SELECT id, space_name FROM spaces";
$result = $conn->query($query);
$spaces = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $spaces[] = $row;
    }
}

// Check if a space is selected
$selected_space = null;
if (isset($_GET['id'])) {
    $space_id = $_GET['id'];
    foreach ($spaces as $space) {
        if ($space['id'] == $space_id) {
            $selected_space = $space['space_name'];
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>QR Code Reader</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 0; }
        .container { width: 60%; margin: 50px auto; padding: 20px; background: #fff; box-shadow: 0px 4px 6px rgba(0,0,0,0.1); }
        h1 { text-align: center; }
        ul { list-style-type: none; padding: 0; }
        li { margin: 10px 0; }
        a { text-decoration: none; color: #007bff; font-size: 18px; }
        a:hover { color: #0056b3; }
        .logout-button { margin-top: 20px; text-align: center; }
        .logout-button a { padding: 10px 20px; background: #dc3545; color: white; text-decoration: none; font-size: 16px; border-radius: 5px; }
        .logout-button a:hover { background: #c82333; }
        .selected-space { margin-top: 20px; padding: 10px; background: #e9ecef; border: 1px solid #ddd; }
        .qr-scanner { margin-top: 20px; text-align: center; }
        .qr-result { margin-top: 20px; padding: 10px; background: #e9ecef; border: 1px solid #ddd; }
        video { width: 100%; max-width: 500px; height: auto; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to the QR Code Reader</h1>
        <p>Hello, <?php echo htmlspecialchars($_SESSION['user']['email']); ?>!</p>
        <h2>Available Spaces</h2>
        <ul>
            <?php foreach ($spaces as $space): ?>
                <li>
                    <a href="index.php?id=<?php echo $space['id']; ?>">
                        <?php echo htmlspecialchars($space['space_name']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        
        <?php if ($selected_space): ?>
            <div class="selected-space">
                <p>You have selected: <strong><?php echo htmlspecialchars($selected_space); ?></strong></p>
            </div>
        <?php endif; ?>

        <!-- QR Code Scanner Section -->
        <div class="qr-scanner">
            <h2>Scan a QR Code</h2>
            <video id="preview"></video>
            <div class="qr-result">
                <p>Scanned Result: <span id="qr-result-text">None</span></p>
            </div>
        </div>

        <div class="logout-button">
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <!-- Include Instascan Library -->
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script>
        // Initialize the QR code scanner
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview'), mirror: false });

        // Add a listener for when a QR code is scanned
        scanner.addListener('scan', function (content) {
            // Display the scanned content
            document.getElementById('qr-result-text').textContent = content;

            // Optionally, you can send the scanned data to the server for further processing
            fetch('process_qr.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ qr_content: content }),
            })
            .then(response => response.json())
            .then(data => {
                console.log('QR code processed:', data);
            })
            .catch(error => {
                console.error('Error processing QR code:', error);
            });
        });

        // Start the scanner
        function startScanner() {
            Instascan.Camera.getCameras()
                .then(function (cameras) {
                    if (cameras.length > 0) {
                        // Use the back camera by default (cameras[1] is usually the back camera)
                        const selectedCamera = cameras[cameras.length > 1 ? 1 : 0];
                        scanner.start(selectedCamera)
                            .then(() => {
                                console.log('Scanner started successfully.');
                            })
                            .catch((error) => {
                                console.error('Error starting scanner:', error);
                                alert('Error starting the camera. Please ensure you have granted camera permissions.');
                            });
                    } else {
                        console.error('No cameras found.');
                        alert('No cameras found. Please ensure your device has a camera.');
                    }
                })
                .catch(function (e) {
                    console.error('Camera access error:', e);
                    alert('Error accessing the camera. Please ensure you have granted camera permissions.');
                });
        }

        // Start the scanner when the page loads
        document.addEventListener('DOMContentLoaded', startScanner);
    </script>
</body>
</html>