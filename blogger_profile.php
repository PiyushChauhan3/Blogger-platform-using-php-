<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Blogger') {
    header("Location: login.php");
    exit;
}

include 'db.php'; // Include the database connection

$user = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogger Profile</title>
    <link rel="stylesheet" href="style.css?v=0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/profile.css?v=1">
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <div class="profile-container">
            <h1>Welcome, <?php echo htmlspecialchars($row['username']); ?></h1>
            <hr>

            <h2>Profile of <?php echo htmlspecialchars($row['username']); ?></h2>
            <hr>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
            <p><strong>Role:</strong> <?php echo htmlspecialchars($row['role']); ?></p>
            <p><strong>Joined on:</strong> <?php echo htmlspecialchars($row['created_at']); ?></p>
            <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
            <hr>

            <h2>Your Posts</h2>
            <hr>
            <a href="create_post.php" class="btn btn-success mb-3">Create New Post</a>
            <?php
            $stmt = $conn->prepare("SELECT * FROM posts WHERE author = ? ORDER BY created_at DESC");
            $stmt->bind_param("s", $row['username']);
            $stmt->execute();
            $posts_result = $stmt->get_result();

            while ($post = $posts_result->fetch_assoc()) {
                echo '<div class="card mb-3">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . htmlspecialchars($post['title']) . '</h5>';
                echo '<p class="card-text">' . substr($post['content'], 0, 80) . '...</p>';
                echo '<a href="post.php?id=' . $post['id'] . '" class="btn btn-primary">Read More</a> ';
                echo '<a href="edit_post.php?id=' . $post['id'] . '" class="btn btn-warning">Edit</a> ';
                echo '<a href="delete_post.php?id=' . $post['id'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this post?\')">Delete</a>'; // Add the Delete button
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>


    </div>
    <?php include 'foot.php'; ?>
</body>

</html>