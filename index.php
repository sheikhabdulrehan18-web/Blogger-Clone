<?php
// index.php - Homepage
require_once 'db.php';
 
try {
    // Fetch latest posts with author details
    $stmt = $pdo->query("
        SELECT posts.*, users.username 
        FROM posts 
        JOIN users ON posts.user_id = users.id 
        ORDER BY posts.created_at DESC
    ");
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    $posts = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogger Clone | Share Your Stories</title>
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
 
    <header style="text-align: center; padding: 4rem 1rem; background: linear-gradient(to bottom, #fff, #fafafa);">
        <h1 style="font-size: 3.5rem; margin-bottom: 1rem; color: var(--secondary-color);">Share your stories with the world.</h1>
        <p style="font-size: 1.2rem; color: var(--text-secondary); max-width: 600px; margin: 0 auto 2rem;">Create a unique and beautiful blog. It’s easy and free.</p>
        <?php if (!isLoggedIn()): ?>
            <a href="signup.php" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 1.1rem;">Create Your Blog</a>
        <?php else: ?>
            <a href="create_post.php" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 1.1rem;">Write a New Post</a>
        <?php endif; ?>
    </header>
 
    <div class="container">
        <h2 style="margin-bottom: 2rem; border-bottom: 2px solid var(--primary-color); display: inline-block; padding-bottom: 0.5rem;">Latest Stories</h2>
 
        <?php if (empty($posts)): ?>
            <div style="text-align: center; padding: 3rem; background: white; border-radius: 12px; box-shadow: var(--shadow);">
                <h3>No posts yet. Be the first to write something!</h3>
            </div>
        <?php else: ?>
            <div class="blog-grid">
                <?php foreach ($posts as $post): ?>
                    <article class="post-card">
                        <h2><?php echo e($post['title']); ?></h2>
                        <p><?php echo e(mb_strimwidth(strip_tags($post['content']), 0, 150, "...")); ?></p>
                        <div class="post-meta">
                            <span>By <strong><?php echo e($post['username']); ?></strong></span>
                            <span><?php echo date('M d, Y', strtotime($post['created_at'])); ?></span>
                        </div>
                        <a href="view_post.php?id=<?php echo $post['id']; ?>" class="btn btn-outline" style="width: 100%;">Read More</a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
 
    <footer style="text-align: center; padding: 3rem; margin-top: 5rem; border-top: 1px solid #eee; color: #999;">
        <p>&copy; <?php echo date('Y'); ?> Blogger Clone. All rights reserved.</p>
    </footer>
 
    <script src="script.js"></script>
</body>
</html>
 
