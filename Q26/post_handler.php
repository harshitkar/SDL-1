<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'facebook_clone');

if (!isset($_SESSION['user_id']) || !isset($_POST['content'])) {
    exit("Unauthorized or empty content.");
}

$content = $_POST['content'];
$uid = $_SESSION['user_id'];

$conn->query("INSERT INTO posts (user_id, content) VALUES ('$uid', '$content')");

echo "Post submitted successfully!";
?>