<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'facebook_clone');

$conn->query("CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)");

if (!isset($_SESSION['user_id']) || !isset($_POST['post_id'], $_POST['comment'])) {
    http_response_code(400);
    echo "Invalid request.";
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$post_id = (int)$_POST['post_id'];
$comment = $conn->real_escape_string($_POST['comment']);

$conn->query("INSERT INTO comments (post_id, user_id, comment) VALUES ($post_id, $user_id, '$comment')");

$result = $conn->query("
    SELECT c.comment, c.created_at, u.username 
    FROM comments c 
    JOIN users u ON c.user_id = u.id 
    WHERE c.post_id = $post_id 
    ORDER BY c.created_at ASC
");

while ($row = $result->fetch_assoc()) {
    echo "<div class='comment'>
        <strong>" . htmlspecialchars($row['username']) . ":</strong> " . 
        htmlspecialchars($row['comment']) . "<br>
        <small>" . $row['created_at'] . "</small>
    </div>";
}
?>