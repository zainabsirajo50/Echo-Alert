<?php
// login.php
session_start();
require 'app/database/connection.php'; // Ensure the path is correct and it establishes $conn

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get email and password from POST request
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Prepare a statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Fetch the user data
        $user = $result->fetch_assoc();
        // Verify the password
        if ($password == $user['password']) {
            // Login successful
            echo "Login successful!";
            // Set session variables, redirect, etc.
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            echo "Success!";
            header('Location: /~' . $pathname . '/Eco-Alert/user-homepage.php'); // Redirect to a homepage or dashboard
            exit();
        } else {
            echo "Invalid email or password.";
        }
    } else {
        echo "Error";
    }

    $stmt->close();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/css/LoginForm.css">
    <title>Login</title>
</head>

<body>
    <div class="login-form">
        <h2>Login</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="submit-button">Login</button>
        </form>
        <p class="signup-link">Don't have an account? <a href="/~zsirajo1/src/SignupForm.php">Sign up here</a></p>
    </div>
</body>

</html>