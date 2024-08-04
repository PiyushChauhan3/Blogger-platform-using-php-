<?php
session_start();
include 'db.php'; // Include the database connection

// Get the category ID from the URL
$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the category name
$stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$stmt->bind_result($category_name);
$stmt->fetch();
$stmt->close();

// Fetch posts in the selected category
$stmt = $conn->prepare("SELECT id, title, content, author, image FROM posts WHERE category_id = ?");
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();
$posts = [];
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts in <?php echo htmlspecialchars($category_name); ?></title>
    <link rel="stylesheet" href="css/style.css?v=1">
    <link rel="stylesheet" href="css/category.css?v=0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <h1>Posts in "<?php echo htmlspecialchars($category_name); ?>"</h1>
        <hr>
        <?php if (empty($posts)): ?>
            <p>No posts found in this category.</p>
        <?php else: ?>
            <ul class="list-group">
                
                <?php foreach ($posts as $post): ?>
                    <li class="list-group-item">
                        <div class="list-f">
                        <span class="img-cat">
                        <img src="<?php echo htmlspecialchars($post['image']); ?>" class="search-image" alt="Post Image">
                        </span>
                        <span class="cat-text">
                        <h3>
                        <a href="post.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a>
                        </h3>
                        <p><?php echo substr($post['content'], 0, 100) . '...'; ?></p>
                        <small>By <?php echo htmlspecialchars($post['author']); ?></small>
                        </span>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <?php include 'foot.php'; ?>
</body>
</html>
