<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$post_id = $_GET['id'];


$query = "SELECT * FROM posts WHERE id = '$post_id' AND user_id = '$user_id'";
$result = $conn->query($query);
if ($result->num_rows === 0) {
    header("Location: my_posts.php");
    exit;
}

$post = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string(trim($_POST['title']));
    $content = $conn->real_escape_string(trim($_POST['content']));

    $updateQuery = "UPDATE posts SET title = '$title', content = '$content' WHERE id = '$post_id' AND user_id = '$user_id'";
    $conn->query($updateQuery);

    header("Location: my_posts.php");
    exit;
}
?>

<form action="edit_post.php?id=<?= $post['id']; ?>" method="POST">
    <input type="text" name="title" value="<?= htmlspecialchars($post['title']); ?>" required>
    <textarea name="content" rows="5"><?= htmlspecialchars($post['content']); ?></textarea>
    <button type="submit">Update Post</button>
</form>
