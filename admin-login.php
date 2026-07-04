<?php
// admin-login.php

// Start session to keep track of login status
session_start();

// Database connection
$servername = "localhost"; // Your database host
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "healthbloom"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the submitted username and password
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $login_type = $_POST['login_type'] ?? '';

    // Query the database for the credentials
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Set session for user
            $_SESSION['user_logged_in'] = true;
            $_SESSION['username'] = $username;
            if ($login_type == 'user') {
                header("Location: Welcome-page.html"); // Redirect to user dashboard
            } 
            exit();
        } else {
            $error_message = 'Invalid credentials';
        }
    } else {
        $error_message = 'User not found';
    }
}

// Close the database connection
$conn->close();
?>