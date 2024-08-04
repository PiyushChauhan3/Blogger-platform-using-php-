<?php
session_start();
include 'db.php'; // Include your database connection

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    // Prepare and execute the search query
    $sql = "SELECT * FROM posts WHERE title LIKE ? OR content LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = '%' . $query . '%';
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch results
    $posts = [];
    while ($post = $result->fetch_assoc()) {
        $posts[] = $post;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results for "<?php echo htmlspecialchars($query); ?>"</title>
    <link rel="stylesheet" href="css/style.css?v=1">
    <link rel="stylesheet" href="css/search.css?v=1">
    <link rel="stylesheet" href="css/nav.css?v=0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <h1>Search Results for "<?php echo htmlspecialchars($query); ?>"</h1>

        <?php if (empty($posts)): ?>
            <p>No posts found.</p>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="image-search">
                        <img src="<?php echo htmlspecialchars($post['image']); ?>" class="search-image" alt="Post Image">
                        </div>
                        <div class="search-detail">
                        <h5 class="card-title">
                            <a href="post.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a>
                        </h5>
                        <p class="card-text"><?php echo htmlspecialchars(substr($post['content'], 0, 100)) . '...'; ?></p>
                        <p class="card-text-small">Last updated <?php echo htmlspecialchars($post['created_at']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php include 'foot.php'; ?>
</body>
</html>
