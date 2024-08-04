<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blogpoint";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$role = $_POST['select-role'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
$confirm_password = $_POST['confirm-password'];

// Check if passwords match
if (password_verify($confirm_password, $password)) {
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (role, username, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $role, $username, $email, $password);

    // Execute the query
    if ($stmt->execute()) {
        echo "Signup successful!";
        // Redirect to login page or another page
        header("Location: login.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Passwords do not match!";
}

$conn->close();

