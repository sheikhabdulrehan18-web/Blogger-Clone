<?php
// edit_post.php - Edit an Existing Blog Post
require_once 'db.php';
requireLogin();
 
$id = $_GET['id'] ?? 0;
$user_id = $_SESSION['user_id'];
 
// Fetch the post and verify ownership
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);
$post = $stmt->fetch();
 
if (!$post) {
    header('Location: dashboard.php');
    exit;
}
 
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
 
    if (empty($title) || empty($content)) {
        $error = 'Please provide both title and content.';
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$title, $content, $id, $user_id]);
            header('Location: dashboard.php');
            exit;
        } catch (PDOException $e) {
            $error = 'Failed to update post.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post | Blogger Clone</title>
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
            <h2 style="margin-bottom: 2rem;">Edit Your Story</h2>
 
            <?php if ($error): ?>
                <p style="color: #ff5252; text-align: center; margin-bottom: 1rem;"><?php echo e($error); ?></p>
            <?php endif; ?>
 
            <form method="POST">
                <div class="form-group">
                    <label>Post Title</label>
                    <input type="text" name="title" value="<?php echo e($post['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Content</label>
                    <textarea name="content" rows="15" required><?php echo e($post['content']); ?></textarea>
                </div>
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 2;">Update Changes</button>
                    <a href="dashboard.php" class="btn btn-outline" style="flex: 1;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
 
