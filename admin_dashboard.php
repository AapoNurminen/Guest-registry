<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

$message = "";

// Handle form submission to add a new space
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $space_name = trim($_POST['space_name']);

    if (empty($space_name)) {
        $message = "<span style='color: red;'>Space name cannot be empty.</span>";
    } else {
        $stmt = $conn->prepare("INSERT INTO spaces (space_name) VALUES (?)");
        $stmt->bind_param("s", $space_name);

        if ($stmt->execute()) {
            $message = "<span style='color: green;'>Space added successfully!</span>";
        } else {
            $message = "<span style='color: red;'>Failed to add space. It might already exist.</span>";
        }

        $stmt->close();
    }
}

// Fetch all spaces from the database
$result = $conn->query("SELECT * FROM spaces");
$spaces = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 0; }
        .container { width: 80%; margin: 50px auto; padding: 20px; background: #fff; box-shadow: 0px 4px 6px rgba(0,0,0,0.1); }
        h1 { text-align: center; }
        form { display: flex; flex-direction: column; max-width: 400px; margin: 20px auto; }
        input, button { margin: 10px 0; padding: 10px; font-size: 16px; }
        button { background: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; }
        th { background-color: #007bff; color: white; }
        .message { margin: 20px auto; text-align: center; font-size: 16px; }
        a { display: inline-block; margin-top: 20px; text-decoration: none; color: #fff; background: #28a745; padding: 10px 20px; border-radius: 5px; text-align: center; }
        a:hover { background: #218838; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <form method="POST">
            <input type="text" name="space_name" placeholder="Enter space name (e.g., VIP, Dining Room)" required>
            <button type="submit">Add Space</button>
        </form>
        <!-- Display message -->
        <div class="message">
            <?php echo $message; ?>
        </div>
        <!-- Display list of spaces -->
        <h2>Spaces List</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Space Name</th>
            </tr>
            <?php foreach ($spaces as $space): ?>
                <tr>
                    <td><?php echo htmlspecialchars($space['id']); ?></td>
                    <td><?php echo htmlspecialchars($space['space_name']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
