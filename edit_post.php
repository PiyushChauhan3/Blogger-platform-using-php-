<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Blogger') {
    header("Location: login.php");
    exit;
}

include 'db.php'; // Include the database connection

$post_id = $_GET['id'];

// Fetch the existing post
$sql = "SELECT * FROM posts WHERE id = ? AND author = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $post_id, $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $post = $result->fetch_assoc();
} else {
    echo "Post not found or you do not have permission to edit this post.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $imageUrl = $_POST['image_url']; // Image URL from input

    // Initialize variables for new image path
    $newImagePath = null;

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $newImagePath = 'uploads/' . $imageName; // Set your upload directory

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($imageTmpPath, $newImagePath)) {
            // If the file was uploaded successfully, use the new path
        } else {
            echo "Error uploading the image.";
            exit();
        }
    } elseif (!empty($imageUrl)) {
        // If no new image, but a URL is provided
        $newImagePath = $imageUrl;
    } else {
        // If no new image and no URL, keep the existing image path
        $newImagePath = $post['image'];
    }

    // Prepare the update query
    $update_sql = "UPDATE posts SET title = ?, content = ?, image = ? WHERE id = ? AND author = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssis", $title, $content, $newImagePath, $post_id, $_SESSION['username']);

    // Execute the update query and handle errors
    if ($update_stmt->execute()) {
        header("Location: blogger_profile.php");
        exit();
    } else {
        echo "Error updating record: " . $update_stmt->error; // Show the error message
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="style.css?v=0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/edit_post.css?v=1">
</head>
<body>
    <?php include 'nav.php'; ?>
    <div class="container">
        <div class="edit-post-container">
            <h1>Edit Post</h1>
            <form method="POST" action="edit_post.php?id=<?php echo $post_id; ?>" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="content" class="form-label">Content</label>
                    <textarea class="form-control" id="content" name="content" rows="10" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="image_url" class="form-label">Image URL</label>
                    <input type="text" class="form-control" id="image_url" name="image_url" placeholder="Enter image URL" value="<?php echo htmlspecialchars($post['image']); ?>">
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Upload New Image (optional)</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Update Post</button>
            </form>
        </div>
    </div>
    <?php include 'foot.php'; ?>
</body>
</html>
