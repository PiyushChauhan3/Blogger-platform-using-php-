<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Admin') {
    header("Location: login.php");
    exit;
}

include 'db.php'; // Include the database connection

// Check if the user ID to delete is provided
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Prevent admin from deleting their own account
    if ($user_id == $_SESSION['user_id']) {
        echo "You cannot delete your own account.";
        exit();
    }

    // Fetch the user to delete
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists, proceed to delete
        $delete_sql = "DELETE FROM users WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $user_id);

        if ($delete_stmt->execute()) {
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "Error deleting user: " . $delete_stmt->error; // Show the error message
        }
    } else {
        echo "User not found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}
?>
