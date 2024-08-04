<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blogpoint";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
    <title><?php echo htmlspecialchars($row['username']); ?>'s Profile</title>
    <link rel="stylesheet" href="css/style.css?v=0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-image: url(bg-01-free-img.jpg);
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center center;
        }

        .profile-container {
            width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .profile-container p {
            font-size: 16px;
            color: #555;
            margin: 10px 0;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            margin: 10px;
            color: white;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn:hover {
            opacity: 0.8;
        }
    </style>
</head>

<body>
    <?php include 'nav.php'; ?>
    <div class="profile-container">
        <h2>Profile of <?php echo htmlspecialchars($row['username']); ?></h2>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
        <p><strong>Role:</strong> <?php echo htmlspecialchars($row['role']); ?></p>
        <p><strong>Joined on:</strong> <?php echo htmlspecialchars($row['created_at']); ?></p>
        <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
    <?php include 'foot.php'; ?>
</body>

</html>