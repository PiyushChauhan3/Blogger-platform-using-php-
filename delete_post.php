<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Blogger') {
    header("Location: login.php");
    exit;
}

include 'db.php'; // Include the database connection

$post_id = $_GET['id'];
$username = $_SESSION['username'];

// Check if the post belongs to the logged-in user
$sql = "SELECT * FROM posts WHERE id = ? AND author = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $post_id, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Delete the post
    $delete_sql = "DELETE FROM posts WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $post_id);
    $delete_stmt->execute();
    
    header("Location: blogger_profile.php");
    exit();
} else {
    echo "Post not found or you do not have permission to delete this post.";
    exit();
}
?>
