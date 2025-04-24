<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'facebook_clone');

$conn->query("CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)");

$conn->query("CREATE TABLE IF NOT EXISTS likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    UNIQUE (post_id, user_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)");

$conn->query("CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)");

$result = $conn->query("
    SELECT posts.id, posts.content, posts.created_at, users.username,
        (SELECT COUNT(*) FROM likes WHERE post_id = posts.id) AS like_count,
        (SELECT COUNT(*) FROM comments WHERE post_id = posts.id) AS comment_count
    FROM posts 
    JOIN users ON posts.user_id = users.id 
    ORDER BY posts.created_at DESC
");

while ($row = $result->fetch_assoc()) {
    $postId = $row['id'];
    $userId = $_SESSION['user_id']; 

    echo "<div class='post' data-post-id='{$postId}'>
        <div class='post-header'>
            <span>" . htmlspecialchars($row['username']) . "</span>
            <small>" . $row['created_at'] . "</small>
        </div>
        <div class='post-content'>" . nl2br(htmlspecialchars($row['content'])) . "</div>
        
        <div class='post-actions'>";
    
    if ($conn->query("SELECT id FROM likes WHERE post_id = $postId AND user_id = $userId")->num_rows > 0) {
        echo "<button class='like-btn unike'>Unlike</button>";
    } else {
        echo "<button class='like-btn'>Like</button>";
    }

    echo "
            <span class='like-count' id='like-count-$postId'>" . $row['like_count'] . " Likes</span>
            <div class='comment-count' id='comment-count-$postId'>" . $row['comment_count'] . " Comments</div>
        </div>

        <div class='comment-section'>
            <input type='text' class='comment-input' id='comment-input-$postId' placeholder='Write a comment...'>
            <button class='comment-btn'>Comment</button>
            <div class='comments' id='comments-$postId'>";

    $comments = $conn->query("
        SELECT c.comment, c.created_at, u.username 
        FROM comments c
        JOIN users u ON c.user_id = u.id
        WHERE c.post_id = $postId
        ORDER BY c.created_at DESC
    ");
    while ($c = $comments->fetch_assoc()) {
        echo "<div class='comment'>
                <strong>" . htmlspecialchars($c['username']) . ":</strong> 
                " . nl2br(htmlspecialchars($c['comment'])) . "
                <div class='comment-time'><small>" . $c['created_at'] . "</small></div>
            </div>";
    }

    echo "</div></div></div>";
}
?>