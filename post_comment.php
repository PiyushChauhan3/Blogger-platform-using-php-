<?php
session_start();
include 'db.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['username'])) {
    $post_id = $_POST['post_id'];
    $comment_content = $_POST['comment'];
    $username = $_SESSION['username'];

    // Insert comment into the database
    $comment_stmt = $conn->prepare("INSERT INTO comments (post_id, author, content) VALUES (?, ?, ?)");
    $comment_stmt->bind_param("iss", $post_id, $username, $comment_content);
    $comment_stmt->execute();
    $comment_id = $comment_stmt->insert_id;

    // Insert notification for the blogger
    $user_id = 1; // Assuming the blogger's user_id is 1. Change this according to your database.
    $notification_message = "$username commented on your post.";
    $notif_stmt = $conn->prepare("INSERT INTO notifications (user_id, post_id, comment_id, message) VALUES (?, ?, ?, ?)");
    $notif_stmt->bind_param("iiis", $user_id, $post_id, $comment_id, $notification_message);
    $notif_stmt->execute();

    // Fetch the newly inserted comment
    $new_comment_stmt = $conn->prepare("SELECT * FROM comments WHERE id = ?");
    $new_comment_stmt->bind_param("i", $comment_id);
    $new_comment_stmt->execute();
    $new_comment = $new_comment_stmt->get_result()->fetch_assoc();
    ?>

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($new_comment['author']); ?></h5>
            <p class="card-text"><?php echo htmlspecialchars($new_comment['content']); ?></p>
            <p class="card-text"><small class="text-body-secondary"><?php echo htmlspecialchars($new_comment['created_at']); ?></small></p>
        </div>
    </div>

    <?php
}
?>
