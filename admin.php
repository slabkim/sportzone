<?php
require 'init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch all facilities
$facilities = $pdo->query('SELECT * FROM facilities')->fetchAll();

// Fetch all bookings
$bookings = $pdo->query('SELECT b.id, u.username, f.name AS facility_name, b.booking_date, b.booking_time, b.status
                        FROM bookings b
                        JOIN users u ON b.user_id = u.id
                        JOIN facilities f ON b.facility_id = f.id')->fetchAll();

// Fetch all users
$users = $pdo->query('SELECT id, username, email, role, created_at FROM users')->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard - SportZone</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> | <a href="logout.php">Logout</a></p>

        <h2>Facilities</h2>
        <a href="facilities.php">Manage Facilities</a> | <a href="backup_list.php">Database Backups</a>
        <ul>
            <?php foreach ($facilities as $facility): ?>
            <li><?php echo htmlspecialchars($facility['name']); ?> -
                $<?php echo number_format($facility['price'], 2); ?> -
                <?php echo $facility['available'] ? 'Available' : 'Not Available'; ?></li>
            <?php endforeach; ?>
        </ul>

        <h2>Bookings</h2>
        <ul>
            <?php foreach ($bookings as $booking): ?>
            <li>
                <?php echo htmlspecialchars($booking['username']); ?> booked
                <?php echo htmlspecialchars($booking['facility_name']); ?> on <?php echo $booking['booking_date']; ?> at
                <?php echo $booking['booking_time']; ?> - Status: <?php echo $booking['status']; ?>
            </li>
            <?php endforeach; ?>
        </ul>

        <h2>Users</h2>
        <ul>
            <?php foreach ($users as $user): ?>
            <li><?php echo htmlspecialchars($user['username']); ?> (<?php echo htmlspecialchars($user['email']); ?>) -
                Role: <?php echo $user['role']; ?> - Registered: <?php echo $user['created_at']; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>

</html>