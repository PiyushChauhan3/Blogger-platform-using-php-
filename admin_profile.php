<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit;
}

// Sample admin data (you would fetch this from the database)
$username = $_SESSION['username'];
// Fetch any other admin-specific data you need from the database

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - BlogPoint</title>
    <link rel="stylesheet" href="css/style.css?v=0">
    <link rel="stylesheet" href="css/admin_profile.css?v=0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="admin-profile-container">
        <h1>Welcome, Admin : <?php echo htmlspecialchars($username); ?>!</h1>
        <hr>
        <div class="admin-options">
            <h2>Admin Dashboard</h2>
            <ul>
                <li><a href="admin_dashboard.php" class="btn btn-primary">Admin Dashboard</a></li>
                <li><a href="logout.php" class="btn btn-danger">Logout</a></li>
            </ul>
        </div>
    </div>
    <?php include 'foot.php'; ?>
</body>

</html>
