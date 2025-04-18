<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'facebook_clone');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Handle new post
if (isset($_POST['content'])) {
    $content = $_POST['content'];
    $uid = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
    $stmt->bind_param("is", $uid, $content);
    $stmt->execute();
    header('Location: home.php');
    exit();
}

// Fetch all posts
$result = $conn->query("SELECT posts.content, posts.created_at, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>News Feed</h1>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>

        <h2>Post Something</h2>
        <form method="POST" class="mb-4">
            <div class="mb-3">
                <textarea name="content" class="form-control" placeholder="What's on your mind?" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Post</button>
        </form>

        <h2>News Feed</h2>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-1">
                        <?php echo htmlspecialchars($row['username']); ?>
                        <small class="text-muted float-end"><?php echo $row['created_at']; ?></small>
                    </h5>
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
