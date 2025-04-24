<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'facebook_clone');

if (!isset($_SESSION['user_id']) || !isset($_POST['post_id'])) {
    http_response_code(400);
    echo "Invalid request.";
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$post_id = (int)$_POST['post_id'];

if ($$conn->query("SELECT id FROM likes WHERE user_id = $user_id AND post_id = $post_id")->num_rows > 0) {
    $conn->query("DELETE FROM likes WHERE user_id = $user_id AND post_id = $post_id");
} else {
    $conn->query("INSERT INTO likes (user_id, post_id) VALUES ($user_id, $post_id)");
}

$countResult = $conn->query("SELECT COUNT(*) AS count FROM likes WHERE post_id = $post_id");
echo $countResult->fetch_assoc()['count'];
?>
