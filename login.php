<?php
session_start();
require 'config/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $stmt = mysqli_prepare($conn, 'SELECT * FROM users WHERE username = ?');
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    if ($user['role'] === 'admin') {
        header('Location: admin.php');
    } else {
        header('Location: home.php');
    }
    exit;
} else {
    $error = 'Invalid username or password';
}
    } else {
        $error = 'Please enter username and password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - SportZone</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

    <main class="container mx-auto flex-grow p-6 max-w-md bg-white rounded shadow">
        <h2 class="text-2xl font-semibold mb-4">Login to SportZone</h2>
        <?php if (!empty($error)): ?>
        <p class="text-red-600 mb-4"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post" action="login.php" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium">Username:</label>
                <input type="text" name="username" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
            <div>
                <label class="block mb-1 font-medium">Password:</label>
                <input type="password" name="password" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Login</button>
        </form>
        <p class="mt-4">Don't have an account? <a href="register.php" class="text-blue-600 hover:underline">Register here</a></p>
    </main>
</body>

</html>
