<?php
// comment.php - Handle Comment Submission
require_once 'db.php';
requireLogin();
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = $_POST['post_id'] ?? 0;
    $comment = trim($_POST['comment'] ?? '');
    $user_id = $_SESSION['user_id'];
 
    if (!empty($comment) && $post_id) {
        try {
            $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)");
            $stmt->execute([$post_id, $user_id, $comment]);
        } catch (PDOException $e) {
            // Silently fail or handle error
        }
    }
 
    // Redirect back to the post
    header("Location: view_post.php?id=$post_id");
    exit;
} else {
    header('Location: index.php');
    exit;
}
?>
