<?php
session_start();
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'];

    if (isset($_POST['like'])) {
        $checkLikeQuery = "SELECT * FROM likes WHERE post_id = '$post_id' AND user_id = '$user_id'";
        $result = $conn->query($checkLikeQuery);
        if ($result->num_rows === 0) {
            $insertLikeQuery = "INSERT INTO likes (post_id, user_id) VALUES ('$post_id', '$user_id')";
            $conn->query($insertLikeQuery);
        }
    }
    if (isset($_POST['comment']) && !empty($_POST['comment'])) {
        $comment = $conn->real_escape_string(trim($_POST['comment']));
        $insertCommentQuery = "INSERT INTO comments (post_id, user_id, content) VALUES ('$post_id', '$user_id', '$comment')";
        $conn->query($insertCommentQuery);
    }

    header("Location: index.php");
    exit;
}
