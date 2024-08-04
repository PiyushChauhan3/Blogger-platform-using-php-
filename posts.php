<?php
session_start();
include 'db.php'; // Include the database connection

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
// Fetch all posts
$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);

$posts = [];
if ($result->num_rows > 0) {
    $posts = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $posts = [];
}

// Fetch top posts (assuming you have a 'views' column to determine popularity)
$top_posts_result = $conn->query("SELECT * FROM posts ORDER BY views DESC LIMIT 5");
$top_posts = $top_posts_result->fetch_all(MYSQLI_ASSOC);

// Fetch recent posts
$recent_posts_result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 5");
$recent_posts = $recent_posts_result->fetch_all(MYSQLI_ASSOC);

// Fetch categories
$categories_result = $conn->query("SELECT * FROM categories");
$categories = $categories_result->fetch_all(MYSQLI_ASSOC);

$sql = "SELECT categories.id, categories.name, COUNT(posts.id) as post_count
        FROM categories
        LEFT JOIN posts ON categories.id = posts.category_id
        GROUP BY categories.id, categories.name";
$result = $conn->query($sql);
$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Posts</title>
    <link rel="stylesheet" href="css/style.css?v=1">
    <link rel="stylesheet" href="css/posts.css?v=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        .posts-section {
            flex: 3;
            padding-right: 20px;
        }

        .sidebar {
            flex: 1;
            padding-left: 20px;
            border-left: 2px solid #ddd;
            /* Vertical line */
        }

        .card {
            margin-bottom: 20px;
        }

        .list-group-item {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <div class="posts-section">
            <h1>All Posts</h1>
            <hr>

            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="card mb-3">
                        <img src="<?php echo htmlspecialchars($post['image']); ?>" class="card-img-top" alt="Post Image">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a
                                    href="post.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a>
                            </h5>
                            <p class="card-text"><?php echo htmlspecialchars(substr($post['content'], 0, 100)) . '...'; ?></p>
                            <p class="card-text"><small class="text-body-secondary">Last updated
                                    <?php echo htmlspecialchars($post['created_at']); ?></small></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No posts found.</p>
            <?php endif; ?>
        </div>

        <div class="sidebar">
            <h2>Top Posts</h2>
            <hr>
            <?php foreach ($top_posts as $top_post): ?>
                <div class="card mb-3">
                    <img src="<?php echo htmlspecialchars($top_post['image']); ?>" class="card-img-top"
                        alt="Top Post Image">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a
                                href="post.php?id=<?php echo $top_post['id']; ?>"><?php echo htmlspecialchars($top_post['title']); ?></a>
                        </h5>
                        <p class="card-text"><?php echo htmlspecialchars(substr($top_post['content'], 0, 100)) . '...'; ?>
                        </p>
                        <p class="card-text"><small class="text-body-secondary">Last updated
                                <?php echo htmlspecialchars($top_post['created_at']); ?></small></p>
                    </div>
                </div>
            <?php endforeach; ?>

            <h2>Recent Posts</h2>
            <hr>
            <?php foreach ($recent_posts as $recent_post): ?>
                <div class="card mb-3">
                    <img src="<?php echo htmlspecialchars($recent_post['image']); ?>" class="card-img-top"
                        alt="Recent Post Image">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a
                                href="post.php?id=<?php echo $recent_post['id']; ?>"><?php echo htmlspecialchars($recent_post['title']); ?></a>
                        </h5>
                        <p class="card-text">
                            <?php echo htmlspecialchars(substr($recent_post['content'], 0, 100)) . '...'; ?></p>
                        <p class="card-text"><small class="text-body-secondary">Last updated
                                <?php echo htmlspecialchars($recent_post['created_at']); ?></small></p>
                    </div>
                </div>
            <?php endforeach; ?>

            <h1>Categories</h1>
            <hr>
            <ul class="list-group">
                <?php foreach ($categories as $category): ?>
                    <li class="list-group-item">
                        <a href="category.php?id=<?php echo $category['id']; ?>">
                            <?php echo htmlspecialchars($category['name']); ?>
                            (<?php echo $category['post_count']; ?>)
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php include 'foot.php'; ?>
</body>

</html>