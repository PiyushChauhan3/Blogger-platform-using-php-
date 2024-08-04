<?php
session_start();
include 'db.php'; // Include the database connection

// Fetch post by ID
$post_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

// Increment the view count
$update_stmt = $conn->prepare("UPDATE posts SET views = views + 1 WHERE id = ?");
$update_stmt->bind_param("i", $post_id);
$update_stmt->execute();
$update_stmt->close();

// Fetch comments for the post
$comments_stmt = $conn->prepare("SELECT * FROM comments WHERE post_id = ? ORDER BY created_at DESC");
$comments_stmt->bind_param("i", $post_id);
$comments_stmt->execute();
$comments = $comments_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Function to display comments and replies recursively
function displayComments($comments, $parent_id = null) {
    foreach ($comments as $comment) {
        if ($comment['parent_comment_id'] == $parent_id) {
            echo '<div class="card mb-3">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($comment['author']) . '</h5>';
            echo '<p class="card-text">' . $comment['content'] . '</p>'; // Output without htmlspecialchars
            echo '<p class="card-text"><small class="text-body-secondary">' . htmlspecialchars($comment['created_at']) . '</small></p>';

            // Show reply button
            if (isset($_SESSION['username'])) {
                echo '<button class="btn btn-secondary reply-btn">Reply</button>';
                echo '<form class="replyForm" style="display:none;">'; // Initially hide the form
                echo '<div class="mb-3">';
                echo '<label for="reply" class="form-label">Reply</label>';
                echo '<textarea class="form-control" id="reply" name="reply" rows="2" required></textarea>';
                echo '</div>';
                echo '<input type="hidden" name="parent_comment_id" value="' . $comment['id'] . '">';
                echo '<button type="submit" class="btn btn-secondary">Submit Reply</button>';
                echo '</form>';
            }

            // Display replies
            displayComments($comments, $comment['id']);

            echo '</div>';
            echo '</div>';
        }
    }
}

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['username'])) {
    if (isset($_POST['parent_comment_id'])) {
        // Handle reply submission
        $reply_content = $_POST['reply'];
        $username = $_SESSION['username'];
        $parent_comment_id = $_POST['parent_comment_id'];

        // Insert reply into the database
        $reply_stmt = $conn->prepare("INSERT INTO comments (post_id, author, content, parent_comment_id) VALUES (?, ?, ?, ?)");
        $reply_stmt->bind_param("issi", $post_id, $username, $reply_content, $parent_comment_id);
        $reply_stmt->execute();
        $reply_id = $reply_stmt->insert_id;

        // Fetch the newly added reply
        $reply = $conn->query("SELECT * FROM comments WHERE id = $reply_id")->fetch_assoc();

        // Return the reply HTML to be added
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . htmlspecialchars($reply['author']) . '</h5>';
        echo '<p class="card-text">' . $reply['content'] . '</p>'; // Output without htmlspecialchars
        echo '<p class="card-text"><small class="text-body-secondary">' . htmlspecialchars($reply['created_at']) . '</small></p>';
        echo '</div>';
        echo '</div>';
        exit; // Stop further execution after handling the reply submission
    } else {
        // Handle comment submission
        $comment_content = $_POST['comment'];
        $username = $_SESSION['username'];

        // Insert comment into the database
        $comment_stmt = $conn->prepare("INSERT INTO comments (post_id, author, content, parent_comment_id) VALUES (?, ?, ?, NULL)");
        $comment_stmt->bind_param("iss", $post_id, $username, $comment_content);
        $comment_stmt->execute();
        $comment_id = $comment_stmt->insert_id;

        // Fetch the newly added comment
        $comment = $conn->query("SELECT * FROM comments WHERE id = $comment_id")->fetch_assoc();

        // Return the comment HTML to be added
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . htmlspecialchars($comment['author']) . '</h5>';
        echo '<p class="card-text">' . $comment['content'] . '</p>'; // Output without htmlspecialchars
        echo '<p class="card-text"><small class="text-body-secondary">' . htmlspecialchars($comment['created_at']) . '</small></p>';
        echo '</div>';
        echo '</div>';
        exit; // Stop further execution after handling the comment submission
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <link rel="stylesheet" href="css/style.css?v=0">
    <link rel="stylesheet" href="css/post.css?v=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <span class="title-back">
            <i class="fa fa-arrow-circle-left" onclick="goBack()" style="cursor: pointer; font-size: 36px;"></i>
            <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        </span>
       
        <hr>
        <span class="image-post">
            <img src="<?php echo htmlspecialchars($post['image']); ?>" class="card-img-top" alt="Post Image">
        </span>
        <br>
        <h2>Content</h2>
        <p><?php echo $post['content']; ?></p> 
        <p>Views: <?php echo $post['views']; ?></p> 

        <h2>Comments</h2>
        <?php if (isset($_SESSION['username'])): ?>
            <form id="commentForm">
                <div class="mb-3">
                    <label for="comment" class="form-label">Add a comment</label>
                    <textarea class="form-control" id="comment" name="comment" rows="1" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        <?php else: ?>
            <p><a href="login.php">Login</a> to add a comment.</p>
        <?php endif; ?>

        <div id="commentsSection">
            <?php displayComments($comments); ?>
        </div>
    </div>

    <?php include 'foot.php'; ?>

    <script>
    function goBack() {
        window.history.back();
    }

    $(document).ready(function() {
        $('#commentForm').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: 'post.php?id=<?php echo $post_id; ?>',
                method: 'POST',
                data: $(this).serialize(),
                success: function(data) {
                    $('#commentsSection').prepend(data);
                    $('#comment').val('');
                }
            });
        });

        // Show reply form when "Reply" button is clicked
        $('#commentsSection').on('click', '.reply-btn', function() {
            $(this).next('.replyForm').toggle(); // Toggle the visibility of the reply form
        });

        $('#commentsSection').on('submit', '.replyForm', function(event) {
            event.preventDefault();
            var form = $(this); // Store a reference to the form

            $.ajax({
                url: 'post.php?id=<?php echo $post_id; ?>', // Ensure this file handles reply logic
                method: 'POST',
                data: form.serialize() + '&post_id=<?php echo $post_id; ?>',
                success: function(data) {
                    form.closest('.card').append(data); // Append the new reply under the comment
                    form.find('textarea').val(''); // Clear the reply textarea
                    form.hide(); // Hide the reply form after submission
                }
            });
        });
    });
    </script>
</body>

</html>
