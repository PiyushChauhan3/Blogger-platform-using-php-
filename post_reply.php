<?php
session_start();
include 'db.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['username'])) {
    $reply_content = $_POST['reply'];
    $username = $_SESSION['username'];
    $parent_comment_id = $_POST['parent_comment_id'];
    $post_id = $_POST['post_id'];

    // Insert reply into the database
    $reply_stmt = $conn->prepare("INSERT INTO comments (post_id, author, content, parent_comment_id) VALUES (?, ?, ?, ?)");
    $reply_stmt->bind_param("issi", $post_id, $username, $reply_content, $parent_comment_id);
    $reply_stmt->execute();

    // Fetch the newly added reply
    $reply_id = $reply_stmt->insert_id;
    $reply = $conn->query("SELECT * FROM comments WHERE id = $reply_id")->fetch_assoc();

    // Return the reply HTML to be added
    echo '<div class="card mb-3">';
    echo '<div class="card-body">';
    echo '<h5 class="card-title">' . htmlspecialchars($reply['author']) . '</h5>';
    echo '<p class="card-text">' . htmlspecialchars($reply['content']) . '</p>';
    echo '<p class="card-text"><small class="text-body-secondary">' . htmlspecialchars($reply['created_at']) . '</small></p>';
    echo '</div>';
    echo '</div>';
}
?>
