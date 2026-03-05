<?php
// signup.php - User Registration
require_once 'db.php';
 
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}
 
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
 
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'All fields are required.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        try {
            // Check if username or email exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                $error = 'Username or email already exists.';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $hashed_password]);
 
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                header('Location: dashboard.php');
                exit;
            }
        } catch (PDOException $e) {
            $error = 'Something went wrong. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Blogger Clone</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
</head>
<body>
    <nav>
        <a href="index.php" class="logo">Blogger.</a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="login.php" class="btn btn-outline">Login</a>
        </div>
    </nav>
 
    <div class="container">
        <div class="auth-container">
            <h2 style="text-align: center; margin-bottom: 2rem;">Create Account</h2>
 
            <?php if ($error): ?>
                <p style="color: #ff5252; text-align: center; margin-bottom: 1rem;"><?php echo e($error); ?></p>
            <?php endif; ?>
 
            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required placeholder="Choose a username">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required placeholder="Enter your email">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Strong password">
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required placeholder="Repeat password">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Sign Up</button>
            </form>
            <p style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem;">
                Already have an account? <a href="login.php" style="color: var(--primary-color);">Login here</a>
            </p>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
 
