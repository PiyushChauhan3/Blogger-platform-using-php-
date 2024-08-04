<?php
session_start();
include 'db.php'; // Include the database connection

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content']; // This will be the CKEditor content
    $author = $_SESSION['username'];
    $category_id = $_POST['category'];
    $new_category = trim($_POST['new_category']);
    $image_url = trim($_POST['imageUrl']); // For image URL input

    // Handle new category
    if (!empty($new_category)) {
        // Insert new category into the database
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $new_category);

        if ($stmt->execute()) {
            $category_id = $stmt->insert_id; // Get the ID of the new category
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    }

    // Handle file upload or URL
    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $imagePath = 'uploads/' . $imageName; // Set your upload directory

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($imageTmpPath, $imagePath)) {
            // File uploaded successfully
        } else {
            $error_message = "Error uploading image.";
        }
    } elseif (!empty($image_url)) {
        // Use the image URL if provided
        $imagePath = $image_url;
    } else {
        $error_message = "No image uploaded or image URL provided.";
    }

    // Insert post into the database
    if (empty($error_message)) {
        $stmt = $conn->prepare("INSERT INTO posts (title, category_id, content, author, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisss", $title, $category_id, $content, $author, $imagePath);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="css/style.css?v=1">
    <link rel="stylesheet" href="css/create_post.css?v=0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
</head>
<body>
    <?php include 'nav.php'; ?>
    <div class="container mt-5 cp">
        <h1>Create a New Post</h1><hr>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form action="create_post.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="mb-3">
                <label for="title" class="form-label">Post Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-control" id="category" name="category">
                    <option value="">Select a category</option>
                    <?php
                    // Fetch existing categories from the database
                    $result = $conn->query("SELECT id, name FROM categories");
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['name']) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="new_category" class="form-label">Or Add New Category</label>
                <input type="text" class="form-control" id="new_category" name="new_category">
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Upload Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
            </div>
            <div class="mb-3">
                <label for="imageUrl" class="form-label">Or Enter Image URL</label>
                <input type="url" class="form-control" id="imageUrl" name="imageUrl" placeholder="https://example.com/image.jpg">
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Post Content</label>
                <textarea class="form-control" id="content" name="content" rows="10" style="display: none;"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Post</button>
        </form>
    </div>

    <script>
        // Initialize CKEditor
        let editor;
        ClassicEditor
            .create(document.querySelector('#content'))
            .then(newEditor => {
                editor = newEditor;
            })
            .catch(error => {
                console.error(error);
            });

        // Validate form before submission
        function validateForm() {
            const contentData = editor.getData();
            if (!contentData) {
                alert("Content cannot be empty!");
                return false;
            }
            // Set the content in the hidden textarea
            document.querySelector('#content').value = contentData;
            return true; // Allow form submission
        }
    </script>

    <?php include 'foot.php'; ?>
</body>
</html>
