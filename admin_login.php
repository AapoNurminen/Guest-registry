<?php
session_start();

// Hardcoded admin credentials
$admin_email = "admin@admin";
$admin_password = "admin"; // Change this to your desired password

// Admin login logic
$loginMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email === $admin_email && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_dashboard.php"); // Redirect to admin dashboard
        exit;
    } else {
        $loginMessage = "<span style='color: red;'>Invalid email or password.</span>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 0; }
        .container { width: 30%; margin: 50px auto; padding: 20px; background: #fff; box-shadow: 0px 4px 6px rgba(0,0,0,0.1); }
        h1 { text-align: center; }
        form { display: flex; flex-direction: column; }
        input { margin: 10px 0; padding: 10px; font-size: 16px; }
        button { padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
        .message { margin-top: 20px; font-size: 16px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Login</h1>
        <form method="POST">
            <input type="email" name="email" placeholder="Enter admin email" required>
            <input type="password" name="password" placeholder="Enter admin password" required>
            <button type="submit">Login</button>
        </form>
        <!-- Display login message -->
        <div class="message">
            <?php echo $loginMessage; ?>
        </div>
    </div>
</body>
</html>
    