<?php
// dashboard.php - User Dashboard
require_once 'db.php';
requireLogin();
 
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
 
try {
    // Fetch posts belonging to the logged-in user
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
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
    <title>Dashboard | Blogger Clone</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
</head>
<body>
    <nav>
        <a href="index.php" class="logo">Blogger.</a>
        <div class="nav-links">
            <a href="index.php">View Site</a>
            <a href="logout.php" class="btn btn-outline" style="border-color: #666; color: #666;">Logout</a>
        </div>
    </nav>
 
    <div class="container">
        <header class="dashboard-actions">
            <div>
                <h1>Welcome, <?php echo e($username); ?>!</h1>
                <p style="color: var(--text-secondary);">Manage your blog posts here.</p>
            </div>
            <a href="create_post.php" class="btn btn-primary">+ New Post</a>
        </header>
 
        <?php if (empty($posts)): ?>
            <div style="text-align: center; padding: 5rem; background: white; border-radius: 12px; box-shadow: var(--shadow);">
                <h3 style="margin-bottom: 1rem;">You haven't written anything yet.</h3>
                <a href="create_post.php" class="btn btn-primary">Start Writing Your First Story</a>
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="post-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Date Published</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td style="font-weight: 600;"><?php echo e($post['title']); ?></td>
                                <td style="color: var(--text-secondary);"><?php echo date('M d, Y', strtotime($post['created_at'])); ?></td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="view_post.php?id=<?php echo $post['id']; ?>" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; border-color: #4caf50; color: #4caf50;">View</a>
                                        <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; border-color: #2196f3; color: #2196f3;">Edit</a>
                                        <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; border-color: #f44336; color: #f44336;" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
 
    <script src="script.js"></script>
</body>
</html>
 
