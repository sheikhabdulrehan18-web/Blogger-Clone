<?php
// delete_post.php - Delete a Blog Post
require_once 'db.php';
requireLogin();
 
$id = $_GET['id'] ?? 0;
$user_id = $_SESSION['user_id'];
 
if ($id) {
    try {
        // Only allow deletion if the post belongs to the user
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user_id]);
    } catch (PDOException $e) {
        // Log error or handle as needed
    }
}
 
header('Location: dashboard.php');
exit;
?>
 
