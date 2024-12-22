<?php
session_start();
include('config.php');

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$query = "SELECT * FROM posts WHERE user_id != '$user_id' ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Welcome to the Blog</h1>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="register.php" class="btn btn-primary">Register</a>
            <a href="login.php" class="btn btn-primary">Login</a>
        <?php else: ?>
            <a href="my_posts.php" class="btn btn-info">My Posts</a>
            <a href="create_post.php" class="btn btn-success">Create Post</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        <?php endif; ?>

        <h3 class="mt-5">All Posts</h3>
        <div class="row">
            <?php while ($post = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <?php if ($post['image']): ?>
                            <img src="<?= $post['image']; ?>" class="card-img-top" alt="Post Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($post['title']); ?></h5>
                            <p class="card-text"><?= nl2br(htmlspecialchars($post['content'])); ?></p>
                            <p class="text-muted"><?= $post['created_at']; ?></p>

                            <?php if (isset($_SESSION['user_id'])): ?>
                                <form action="like_comment.php" method="POST">
                                    <input type="hidden" name="post_id" value="<?= $post['id']; ?>">
                                    <button type="submit" name="like" class="btn btn-primary btn-sm">Like</button>
                                </form>

                                <form action="like_comment.php" method="POST" class="mt-2">
                                    <input type="hidden" name="post_id" value="<?= $post['id']; ?>">
                                    <textarea name="comment" class="form-control" placeholder="Leave a comment"></textarea>
                                    <button type="submit" name="comment" class="btn btn-secondary btn-sm mt-2">Comment</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
