<?php
require 'init.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch user bookings and payment status
$stmt = $pdo->prepare('SELECT b.id, f.name AS facility_name, b.booking_date, b.booking_time, b.status AS booking_status, p.status AS payment_status
                       FROM bookings b
                       JOIN facilities f ON b.facility_id = f.id
                       LEFT JOIN payments p ON b.id = p.booking_id
                       WHERE b.user_id = ?
                       ORDER BY b.booking_date DESC, b.booking_time DESC');
$stmt->execute([$_SESSION['user_id']]);
$bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Booking History - SportZone</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <h2>Your Booking History</h2>
        <p><a href="home.php">Back to Home</a> | <a href="logout.php">Logout</a></p>

        <?php if (count($bookings) > 0): ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Facility</th>
                <th>Date</th>
                <th>Time</th>
                <th>Booking Status</th>
                <th>Payment Status</th>
                <th>Action</th>
            </tr>
            <?php foreach ($bookings as $booking): ?>
            <tr>
                <td><?php echo htmlspecialchars($booking['facility_name']); ?></td>
                <td><?php echo $booking['booking_date']; ?></td>
                <td><?php echo $booking['booking_time']; ?></td>
                <td><?php echo $booking['booking_status']; ?></td>
                <td><?php echo $booking['payment_status'] ?? 'Not Paid'; ?></td>
                <td>
                    <?php if ($booking['booking_status'] === 'pending'): ?>
                    <a href="payment.php?booking_id=<?php echo $booking['id']; ?>">Pay Now</a>
                    <?php else: ?>
                    -
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
        <p>You have no bookings yet.</p>
        <?php endif; ?>
    </div>
</body>

</html>