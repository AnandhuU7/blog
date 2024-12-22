<?php
session_start();
include('config.php');

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string(trim($_POST['title']));
    $content = $conn->real_escape_string(trim($_POST['content']));
    $user_id = $_SESSION['user_id'];  // Assuming user ID is stored in session
    $image = ""; // Default value, if no image is uploaded

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Check if the file is an image and its size
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = $_FILES['image']['type'];
        $fileSize = $_FILES['image']['size'];

        if (!in_array($fileType, $allowedTypes)) {
            $_SESSION['error'] = "Only image files (JPEG, PNG, GIF) are allowed.";
            header("Location: create_post.php");
            exit;
        }

        if ($fileSize > 5 * 1024 * 1024) { // 5 MB max size
            $_SESSION['error'] = "File size must be under 5 MB.";
            header("Location: create_post.php");
            exit;
        }

        // Upload image
        $image = "uploads/" . basename($_FILES['image']['name']);
        // Make sure the uploads directory exists
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    $created_at = date("Y-m-d H:i:s"); // Current date and time

    // Insert the post into the database
    $insertPostQuery = "INSERT INTO posts (title, content, user_id, image, created_at) 
                        VALUES ('$title', '$content', '$user_id', '$image', '$created_at')";
    
    if ($conn->query($insertPostQuery) === TRUE) {
        $_SESSION['success'] = "Post created successfully!";
        header("Location: index.php");  // Redirect back to the homepage/dashboard
        exit;
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Create Post</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error']; ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="create_post.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>
            <button type="submit" class="btn btn-primary">Create Post</button>
        </form>
    </div>
</body>
</html>
