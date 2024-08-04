<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blogpoint";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total users
$total_users_result = $conn->query("SELECT COUNT(*) as total_users FROM users");
$total_users = $total_users_result->fetch_assoc()['total_users'];

// Fetch total bloggers
$total_bloggers_result = $conn->query("SELECT COUNT(*) as total_bloggers FROM users WHERE role = 'Blogger'");
$total_bloggers = $total_bloggers_result->fetch_assoc()['total_bloggers'];

// Fetch total posts uploaded today
$date_today = date('Y-m-d');
$total_posts_result = $conn->query("SELECT COUNT(*) as total_posts FROM posts WHERE DATE(created_at) = '$date_today'");
$total_posts = $total_posts_result->fetch_assoc()['total_posts'];

// Fetch top posts by views
$top_posts_result = $conn->query("SELECT id, title, views FROM posts ORDER BY views DESC LIMIT 5");
$top_posts = $top_posts_result->fetch_all(MYSQLI_ASSOC);

// Fetch all users
$users_result = $conn->query("SELECT * FROM users");
$users = $users_result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BlogPoint</title>
    <link rel="stylesheet" href="css/style.css?v=0">
    <link rel="stylesheet" href="css/admin_dashboard.css?v=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="admin-dashboard-container">
        <h1>Admin Dashboard</h1>
        <hr>
        <div class="analytics">
            <h2>Website Analytics</h2>
            <div class="analytics-cards">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text"><?php echo htmlspecialchars($total_users); ?></p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Bloggers</h5>
                        <p class="card-text"><?php echo htmlspecialchars($total_bloggers); ?></p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Posts Uploaded Today</h5>
                        <p class="card-text"><?php echo htmlspecialchars($total_posts); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <h2>Top Posts</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Views</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($top_posts as $post): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($post['id']); ?></td>
                        <td><?php echo htmlspecialchars($post['title']); ?></td>
                        <td><?php echo htmlspecialchars($post['views']); ?></td>
                        <td>
                            <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Users List</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td>
                            <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php include 'foot.php'; ?>
</body>

</html>
