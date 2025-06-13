<?php
require 'init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$backup_dir = __DIR__ . '/storage/backups/';
$backups = [];

if (is_dir($backup_dir)) {
    $files = scandir($backup_dir);
    foreach ($files as $file) {
if (preg_match('/^sportzone_backup_\\d{4}-\\d{2}-\\d{2}_\\d{2}-\\d{2}-\\d{2}\\.sql$/', $file)) {
            $file_path = $backup_dir . $file;
            $file_size = filesize($file_path);
            $file_mtime = filemtime($file_path);
            $backups[] = [
                'name' => $file,
                'size' => $file_size,
                'mtime' => $file_mtime,
            ];
        }
    }
    usort($backups, function($a, $b) {
        return $b['mtime'] <=> $a['mtime'];
    });
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Backup List - SportZone</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 10px;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
        text-align: left;
    }
    </style>
</head>

<body>
    <div class="container">
        <h1>Daftar Backup Database</h1>
        <p><a href="admin.php">Back to Admin Dashboard</a> | <a href="logout.php">Logout</a></p>

        <?php if (isset($_GET['status']) && isset($_GET['message'])): ?>
        <div
            style="padding: 10px; margin-bottom: 20px; border: 1px solid <?php echo $_GET['status'] === 'success' ? 'green' : 'red'; ?>; color: <?php echo $_GET['status'] === 'success' ? 'green' : 'red'; ?>;">
            <strong><?php echo $_GET['status'] === 'success' ? 'Sukses' : 'Gagal'; ?>:</strong>
            <pre><?php echo htmlspecialchars($_GET['message']); ?></pre>
        </div>
        <?php endif; ?>

        <form method="post" action="backup.php" style="margin-bottom: 20px;">
            <button type="submit">Buat Backup Baru</button>
        </form>

        <p>Debug: Jumlah backup ditemukan: <?php echo count($backups); ?></p>

        <?php if (count($backups) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Nama File</th>
                    <th>Ukuran</th>
                    <th>Tanggal Backup</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($backups as $backup): ?>
                <tr>
                    <td><?php echo htmlspecialchars($backup['name']); ?></td>
                    <td><?php echo number_format($backup['size'] / 1024, 2); ?> KB</td>
                    <td><?php echo date('Y-m-d H:i:s', $backup['mtime']); ?></td>
                    <td><a href="backup/<?php echo htmlspecialchars($backup['name']); ?>" download>Download</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>Tidak ada file backup yang ditemukan.</p>
        <?php endif; ?>
    </div>
</body>

</html>