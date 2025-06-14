<?php
session_start();
require 'config/init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($username && $email && $password && $confirm_password) {
        if ($password === $confirm_password) {
            // Check if username or email already exists
            $stmt = mysqli_prepare($conn, 'SELECT id FROM users WHERE username = ? OR email = ?');
            mysqli_stmt_bind_param($stmt, 'ss', $username, $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) > 0) {
                $error = 'Username or email already exists';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = mysqli_prepare($conn, 'INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
                mysqli_stmt_bind_param($stmt, 'sss', $username, $email, $hashed_password);
                if (mysqli_stmt_execute($stmt)) {
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
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register - SportZone</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

    <main class="container mx-auto flex-grow p-6 max-w-md bg-white rounded shadow">
        <h2 class="text-2xl font-semibold mb-4">Register for SportZone</h2>
        <?php if (!empty($error)): ?>
        <p class="text-red-600 mb-4"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post" action="register.php" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium">Username:</label>
                <input type="text" name="username" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
            <div>
                <label class="block mb-1 font-medium">Email:</label>
                <input type="email" name="email" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
            <div>
                <label class="block mb-1 font-medium">Password:</label>
                <input type="password" name="password" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
            <div>
                <label class="block mb-1 font-medium">Confirm Password:</label>
                <input type="password" name="confirm_password" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Register</button>
        </form>
        <p class="mt-4">Already have an account? <a href="login.php" class="text-blue-600 hover:underline">Login here</a></p>
    </main>
</body>

</html>
