<?php
// login.php - User Login
require_once 'db.php';
 
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}
 
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
 
    if (empty($login) || empty($password)) {
        $error = 'Please enter both fields.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$login, $login]);
            $user = $stmt->fetch();
 
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Invalid username/email or password.';
            }
        } catch (PDOException $e) {
            $error = 'Something went wrong.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Blogger Clone</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
</head>
<body>
    <nav>
        <a href="index.php" class="logo">Blogger.</a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="signup.php" class="btn btn-primary">Join Now</a>
        </div>
    </nav>
 
    <div class="container">
        <div class="auth-container">
            <h2 style="text-align: center; margin-bottom: 2rem;">Welcome Back</h2>
 
            <?php if ($error): ?>
                <p style="color: #ff5252; text-align: center; margin-bottom: 1rem;"><?php echo e($error); ?></p>
            <?php endif; ?>
 
            <form method="POST">
                <div class="form-group">
                    <label>Username or Email</label>
                    <input type="text" name="login" required placeholder="Enter username or email">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Enter password">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
            </form>
            <p style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem;">
                New here? <a href="signup.php" style="color: var(--primary-color);">Create an account</a>
            </p>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
 
