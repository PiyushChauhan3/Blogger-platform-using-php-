<?php
session_start();
include 'db.php'; // Include the database connection

// Fetch new uploaded posts
$new_posts_result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 5");
$new_posts = $new_posts_result->fetch_all(MYSQLI_ASSOC);

// Fetch recent posts
$recent_posts_result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC LIMIT 2");
$recent_posts = $recent_posts_result->fetch_all(MYSQLI_ASSOC);

// Fetch top posts (assuming you have a 'views' column to determine popularity)
$top_posts_result = $conn->query("SELECT * FROM posts ORDER BY views DESC LIMIT 3");
$top_posts = $top_posts_result->fetch_all(MYSQLI_ASSOC);

// Fetch categories with the count of posts
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
    <title>BlogPoint</title>
    <link rel="stylesheet" href="css/style.css?v=0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="body" style="background-image: url(./img/bg-01-free-img.jpg);">
        <?php include 'nav.php'; ?>
        <section>
            <div class="main">
                <?php if (isset($_SESSION['username'])): ?>
                    <h1 class="main-text"> Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>.</h1>
                    <p class="main-sub-text">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.<br> Possimus aspernatur tempora pariatur
                        temporibus nesciunt<br>consequatur molestias sequi quisquam. Dolores nihil magnam <br>fuga facilis
                        iusto voluptates voluptatem mollitia, <br>dolor esse asperiores!
                    </p>
                <?php else: ?>
                    <h1 class="main-text">BlogPoint</h1>
                    <p class="main-sub-text">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.<br> Possimus aspernatur tempora pariatur
                        temporibus nesciunt<br>consequatur molestias sequi quisquam. Dolores nihil magnam <br>fuga facilis
                        iusto voluptates voluptatem mollitia, <br>dolor esse asperiores!
                    </p>
                    <div class="bttn">
                        <button class="main-more btn btn-danger" onclick="document.location='posts.php'">Read More</button>
                        <button class="main-editor btn btn-danger" onclick="document.location='signup.php'">Become
                            Blogger</button>
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <section class="section2">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <h1>New Uploaded Posts</h1>
                        <hr>
                        <?php foreach ($new_posts as $post): ?>
                            <div class="card mb-3">
                                <img src="<?php echo htmlspecialchars($post['image']); ?>" class="card-img-top"
                                    alt="Post Image" onerror="this.onerror=null;this.src='default-image.jpg';">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a
                                            href="post.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a>
                                    </h5>
                                    <p class="card-text">
                                        <?php echo substr(htmlspecialchars($post['content']), 0, 80) . '...'; ?>
                                    </p>
                                    <p class="card-text"><small class="text-body-secondary">Last updated
                                            <?php echo htmlspecialchars($post['created_at']); ?></small></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="col-md-4">
                        <h1>Search</h1>
                        <hr>
                        <form class="d-flex mb-3" role="search" action="search.php" method="GET">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"
                                name="query" required>
                            <button class="btn btn-outline-success" type="submit"><i class="fa fa-search"
                                    style="font-size:24px"></i>
                            </button>
                        </form>

                        <h1>Top Posts</h1>
                        <hr>
                        <?php foreach ($top_posts as $post): ?>
                            <div class="card mb-3">
                                <img src="<?php echo htmlspecialchars($post['image']); ?>" class="card-img-top"
                                    alt="Post Image" onerror="this.onerror=null;this.src='default-image.jpg';">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a
                                            href="post.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a>
                                    </h5>
                                    <p class="card-text">
                                        <?php echo htmlspecialchars(substr($post['content'], 0, 100)) . '...'; ?>
                                    </p>
                                    <p class="card-text"><small class="text-body-secondary">Last updated
                                            <?php echo htmlspecialchars($post['created_at']); ?></small></p>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <h1>Recent Posts</h1>
                        <hr>
                        <?php foreach ($recent_posts as $post): ?>
                            <div class="card mb-3">
                                <img src="<?php echo htmlspecialchars($post['image']); ?>" class="card-img-top"
                                    alt="Post Image" onerror="this.onerror=null;this.src='default-image.jpg';">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a
                                            href="post.php?id=<?php echo $post['id']; ?>"><?php echo htmlspecialchars($post['title']); ?></a>
                                    </h5>
                                    <p class="card-text">
                                        <?php echo htmlspecialchars(substr($post['content'], 0, 100)) . '...'; ?>
                                    </p>
                                    <p class="card-text"><small class="text-body-secondary">Last updated
                                            <?php echo htmlspecialchars($post['created_at']); ?></small></p>
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
            </div>
        </section>
        <?php include 'foot.php'; ?>
    </div>
</body>

</html>