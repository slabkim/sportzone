<?php
require 'init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($username && $email && $password && $confirm_password) {
        if ($password === $confirm_password) {
            // Check if username or email already exists
            $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
            $stmt->execute([$username, $email]);
            if ($stmt->fetch()) {
                $error = 'Username or email already exists';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
                if ($stmt->execute([$username, $email, $hashed_password])) {
                    header('Location: login.php');
                    exit;
                } else {
                    $error = 'Registration failed, please try again';
                }
            }
        } else {
            $error = 'Passwords do not match';
        }
    } else {
        $error = 'Please fill in all fields';
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Register - SportZone</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <h2>Register for SportZone</h2>
        <?php if (!empty($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post" action="register.php">
            <label>Username:</label><br>
            <input type="text" name="username" required><br><br>
            <label>Email:</label><br>
            <input type="email" name="email" required><br><br>
            <label>Password:</label><br>
            <input type="password" name="password" required><br><br>
            <label>Confirm Password:</label><br>
            <input type="password" name="confirm_password" required><br><br>
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>

</html>