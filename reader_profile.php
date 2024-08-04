<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Reader') {
    header("Location: login.php");
    exit;
}

include 'db.php'; // Include the database connection

// Fetch user data if necessary

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
    <title>Reader Profile</title>
    <link rel="stylesheet" href="css/style.css?v=0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/profile.css?v=0">
    <link rel="stylesheet" href="css/nav.css?v=0">
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="container">
    <div class="profile-container">
        <h2>Profile of <?php echo htmlspecialchars($row['username']); ?></h2><hr>

        <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
        <p><strong>Role:</strong> <?php echo htmlspecialchars($row['role']); ?></p>
        <p><strong>Joined on:</strong> <?php echo htmlspecialchars($row['created_at']); ?></p><hr>
        <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>

       
   
    </div>
    </div>
    <?php include 'foot.php'; ?>
</body>

</html>