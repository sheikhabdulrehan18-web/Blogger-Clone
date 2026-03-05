<?php
// view_post.php - Full Blog Post View & Comments
require_once 'db.php';
 
$id = $_GET['id'] ?? 0;
 
try {
    // Fetch post and author
    $stmt = $pdo->prepare("
        SELECT posts.*, users.username 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        WHERE posts.id = ?
    ");
    $stmt->execute([$id]);
    $post = $stmt->fetch();
 
    if (!$post) {
        header('Location: index.php');
        exit;
    }
 
    // Fetch comments
    $stmt = $pdo->prepare("
        SELECT comments.*, users.username 
        FROM comments 
        JOIN users ON comments.user_id = users.id 
        WHERE post_id = ? 
        ORDER BY created_at DESC
    ");
    $stmt->execute([$id]);
    $comments = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching post data.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($post['title']); ?> | Blogger Clone</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
</head>
<body>
    <nav>
        <a href="index.php" class="logo">Blogger.</a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <?php if (isLoggedIn()): ?>
                <a href="dashboard.php">Dashboard</a>
                <a href="logout.php" class="btn btn-outline">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="signup.php" class="btn btn-primary">Join Now</a>
            <?php endif; ?>
        </div>
    </nav>
 
    <div class="container" style="max-width: 900px;">
        <article class="post-content">
            <header class="post-header">
                <h1><?php echo e($post['title']); ?></h1>
                <div style="color: #999; font-weight: 500;">
                    Published by <span style="color: var(--primary-color);">@<?php echo e($post['username']); ?></span> on <?php echo date('F d, Y', strtotime($post['created_at'])); ?>
                </div>
            </header>
 
            <div class="post-body">
                <?php echo nl2br(e($post['content'])); ?>
            </div>
 
            <div class="comments-section">
                <h3>Comments (<?php echo count($comments); ?>)</h3>
                <hr style="margin: 1.5rem 0; border: none; border-bottom: 1px solid #eee;">
 
                <?php if (isLoggedIn()): ?>
                    <form action="comment.php" method="POST" style="margin-bottom: 3rem;">
                        <input type="hidden" name="post_id" value="<?php echo $id; ?>">
                        <div class="form-group">
                            <textarea name="comment" rows="4" required placeholder="Add a thoughtful comment..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Post Comment</button>
                    </form>
                <?php else: ?>
                    <p style="background: #fdf2f2; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; color: #d32f2f;">
                        Please <a href="login.php" style="color: #d32f2f; font-weight: bold;">login</a> to leave a comment.
                    </p>
                <?php endif; ?>
 
                <?php if (empty($comments)): ?>
                    <p style="color: #999; font-style: italic;">No comments yet. Be the first to share your thoughts!</p>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment-card">
                            <div class="comment-meta">
                                @<?php echo e($comment['username']); ?> 
                                <span style="font-weight: 400; color: #999; font-size: 0.8rem; margin-left: 0.5rem;"><?php echo date('M d, Y h:i A', strtotime($comment['created_at'])); ?></span>
                            </div>
                            <div class="comment-text">
                                <?php echo nl2br(e($comment['comment'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </article>
    </div>
 
    <script src="script.js"></script>
</body>
</html>
 
