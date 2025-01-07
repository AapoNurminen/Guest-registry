<?php
$host = 'localhost'; // Adjust to your host
$user = 'root'; // Default user
$password = ''; // Set password if any
$db_name = 'guest';

$conn = new mysqli($host, $user, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
