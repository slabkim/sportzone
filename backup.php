<?php
require 'init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$backup_dir = __DIR__ . '/backup/';
if (!is_dir($backup_dir)) {
    mkdir($backup_dir, 0755, true);
}

$backup_file = $backup_dir . 'sportzone_backup_' . date('Ymd_His') . '.sql';

$command = "mysqldump -u root sportzone > " . escapeshellarg($backup_file);
$output = null;
$return_var = null;
exec($command, $output, $return_var);

if ($return_var === 0) {
    $message = "Backup created successfully: " . basename($backup_file);
} else {
    $message = "Backup failed.";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Database Backup - SportZone</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <h1>Database Backup</h1>
        <p><a href="admin.php">Back to Admin Dashboard</a> | <a href="logout.php">Logout</a></p>
        <p><?php echo htmlspecialchars($message); ?></p>
    </div>
</body>

</html>