<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Debug: backup.php script started.<br>";

require_once __DIR__ . '/init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$date = date('Y-m-d_H-i-s');
$backup_dir = __DIR__ . '/storage/backups/';
if (!is_dir($backup_dir)) {
    mkdir($backup_dir, 0755, true);
}
$backupFile = $backup_dir . "sportzone_backup_$date.sql";

// Path ke mysqldump
$mysqldump_path = "C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe";
$db_user = 'root';
$db_pass = '';
$db_name = 'sportzone';

// Lebih baik gunakan --result-file untuk Windows
$command = "\"$mysqldump_path\" -u $db_user " . 
    ($db_pass ? "-p$db_pass " : "") . 
    "$db_name --result-file=\"$backupFile\" 2>&1";

exec($command, $output, $return_var);

$message = "Command executed with return code: $return_var\nOutput:\n" . implode("\n", $output);

if ($return_var === 0 && filesize($backupFile) > 0) {
    $message .= "\nBackup successfully saved to: $backupFile";
    $status = 'success';
} else {
    $message .= "\nBackup failed.";
    $status = 'fail';
}

header("Location: backup_list.php?status=$status&message=" . urlencode($message));
exit;
?>