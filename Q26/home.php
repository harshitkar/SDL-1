<?php
    session_start();
    $conn = new mysqli('localhost', 'root', '', 'facebook_clone');

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        h1, h2 {
            margin: 20px 0 10px;
        }
        textarea, input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            resize: vertical;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 14px;
            margin-top: 5px;
            cursor: pointer;
            border-radius: 4px;
        }
        .logout-btn {
            background: #dc3545;
        }
        .post {
            background: white;
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 6px;
        }
        .post-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .post-content {
            white-space: pre-wrap;
        }
        .post-actions {
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .comment-section {
            margin-top: 10px;
        }
        .comments {
            margin-top: 10px;
            padding-left: 10px;
        }
        .comment {
            background: #eee;
            padding: 5px 10px;
            border-radius: 4px;
            margin-bottom: 5px;
        }
        .unlike {
            background: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>News Feed</h1>
            <a href="logout.php"><button class="logout-btn">Logout</button></a>
        </div>

        <h2>Post Something</h2>
        <form id="postForm">
            <textarea name="content" placeholder="What's on your mind?" required></textarea>
            <button type="submit">Post</button>
        </form>
        <div id="postStatus"></div>

        <h2>All Posts</h2>
        <div id="postsContainer">
            <!-- Posts will load here via AJAX -->
        </div>
    </div>

    <script>
        document.getElementById('postForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            fetch('post_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text())
            .then(data => {
                document.getElementById('postStatus').innerHTML = data;
                form.reset();
                loadPosts();
            });
        });

        function loadPosts() {
            fetch('load_posts.php')
                .then(res => res.text())
                .then(html => {
                    document.getElementById('postsContainer').innerHTML = html;
                    attachLikeCommentHandlers();
                });
        }

        function attachLikeCommentHandlers() {
            document.querySelectorAll('.like-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const postId = btn.closest('.post').getAttribute('data-post-id');
                    fetch('like_handler.php', {
                        method: 'POST',
                        body: 'post_id=' + postId
                    }).then(loadPosts);
                });
            });

            document.querySelectorAll('.comment-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const post = btn.closest('.post');
                    const postId = post.getAttribute('data-post-id');
                    const input = post.querySelector('.comment-input');
                    const comment = input.value;
                    if (!comment) return;

                    fetch('comment_handler.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: 'post_id=' + postId + '&comment=' + encodeURIComponent(comment)
                    }).then(() => {
                        input.value = '';
                        loadPosts();
                    });
                });
            });
        }

        window.onload = loadPosts;
    </script>
</body>
</html>