<?php
session_start(); // Start the session to access session variables

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blogpoint";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the form data
$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['select-role']; // Include role selection

$stmt = $conn->prepare("SELECT password, role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);

$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($hashed_password, $user_role);
    $stmt->fetch();

    // Verify the password
    if (password_verify($password, $hashed_password)) {
        echo "Login successful!";
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $user_role; // Store the user's role in the session

        // Redirect based on the user's role
        switch ($user_role) {
            case 'Reader':
                header("Location: reader_profile.php");
                break;
            case 'Blogger':
                header("Location: blogger_profile.php");
                break;
            case 'Admin':
                header("Location: admin_profile.php");
                break;
            default:
                header("Location: index.php");
                break;
        }
        exit; // Ensure no further code runs after redirect
    } else {
        echo "Invalid password!";
    }
} else {
    echo "User not found!";
}

$stmt->close();
$conn->close();
?>
