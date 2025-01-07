<?php
include 'db.php';

$registrationMessage = ""; // Variable to store the code or error message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Server-side email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $registrationMessage = "<span style='color: red;'>Invalid email format. Please enter a valid email address.</span>";
    } else {
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        $stmt = $conn->prepare("INSERT INTO users (email, code) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $code);

        if ($stmt->execute()) {
            // Display the generated code below the form
            $registrationMessage = "<span style='color: green;'>Registration successful! Your login code is: <strong>$code</strong></span>";
        } else {
            // Handle duplicate email error
            $registrationMessage = "<span style='color: red;'>This email is already registered. Try logging in.</span>";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 0; }
        .container { width: 30%; margin: 50px auto; padding: 20px; background: #fff; box-shadow: 0px 4px 6px rgba(0,0,0,0.1); }
        h1 { text-align: center; }
        form { display: flex; flex-direction: column; }
        input { margin: 10px 0; padding: 10px; font-size: 16px; }
        button { padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
        .message { margin-top: 20px; font-size: 16px; text-align: center; }
        .login-button { margin-top: 20px; text-align: center; }
        .login-button a { padding: 10px 20px; background: #28a745; color: white; text-decoration: none; font-size: 16px; border-radius: 5px; }
        .login-button a:hover { background: #218838; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        <form method="POST">
            <input type="email" name="email" placeholder="Enter your email" required 
                   pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" 
                   title="Please enter a valid email address">
            <button type="submit">Register</button>
        </form>
        <!-- Display the registration message -->
        <div class="message">
            <?php echo $registrationMessage; ?>
        </div>
        <!-- Add a button to navigate to the login page -->
        <div class="login-button">
            <a href="login.php">Go to Login</a>
        </div>
    </div>
</body>
</html>
