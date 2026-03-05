<?php
// create_post.php - Write a New Blog Post
require_once 'db.php';
requireLogin();
 
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $user_id = $_SESSION['user_id'];
 
    if (empty($title) || empty($content)) {
        $error = 'Please provide both title and content.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $title, $content]);
            header('Location: dashboard.php');
            exit;
        } catch (PDOException $e) {
            $error = 'Failed to create post. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Post | Blogger Clone</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
</head>
<body>
    <nav>
        <a href="index.php" class="logo">Blogger.</a>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php" class="btn btn-outline">Logout</a>
        </div>
    </nav>
 
    <div class="container" style="max-width: 800px;">
        <div class="post-content">
            <h2 style="margin-bottom: 2rem;">Write a New Story</h2>
 
            <?php if ($error): ?>
                <p style="color: #ff5252; text-align: center; margin-bottom: 1rem;"><?php echo e($error); ?></p>
            <?php endif; ?>
 
            <form method="POST">
                <div class="form-group">
                    <label>Post Title</label>
                    <input type="text" name="title" required placeholder="Enter an engaging title">
                </div>
                <div class="form-group">
                    <label>Content</label>
                    <textarea name="content" rows="15" required placeholder="Write your story here..."></textarea>
                </div>
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 2;">Publish Post</button>
                    <a href="dashboard.php" class="btn btn-outline" style="flex: 1;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
 
