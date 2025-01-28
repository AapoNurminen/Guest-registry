<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

include 'db.php'; // Include the database connection

// Get the raw POST data
$data = json_decode(file_get_contents('php://input'), true);
$qr_content = $data['qr_content'] ?? null;
$space_id = $data['space_id'] ?? null;

if ($qr_content && $space_id) {
    // Process the QR code content (e.g., save to the database)
    $query = "INSERT INTO qr_scans (user_id, qr_content, space_id, scanned_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isi", $_SESSION['user']['id'], $qr_content, $space_id);
    $stmt->execute();

    // Return a success response
    echo json_encode(['status' => 'success', 'message' => 'QR code processed successfully.']);
} else {
    // Return an error response
    echo json_encode(['status' => 'error', 'message' => 'No QR code data or space ID received.']);
}
?>