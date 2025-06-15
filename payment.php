<?php
session_start();
require 'config/init.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$booking_id = $_GET['booking_id'] ?? null;
if (!$booking_id) {
    header('Location: history.php');
    exit;
}

try {
    // Fetch booking details
    $stmt = $pdo->prepare('SELECT b.*, f.price FROM bookings b JOIN facilities f ON b.facility_id = f.id WHERE b.id = ? AND b.user_id = ?');
    $stmt->execute([$booking_id, $_SESSION['user_id']]);
    $booking = $stmt->fetch();

    if (!$booking) {
        echo "Booking not found.";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // For simplicity, we mock payment success
        $stmt = $pdo->prepare('INSERT INTO payments (booking_id, amount, status) VALUES (?, ?, ?)');
        $amount = $booking['price'];
        $status = 'completed';
        if ($stmt->execute([$booking_id, $amount, $status])) {
            // Update booking status to confirmed
            $stmt = $pdo->prepare('UPDATE bookings SET status = ? WHERE id = ?');
            $confirmed = 'confirmed';
            $stmt->execute([$confirmed, $booking_id]);
            $success = "Payment successful! Your booking is confirmed.";
        } else {
            $error = "Payment failed. Please try again.";
        }
    }
} catch (PDOException $e) {
    die("Database query error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Payment - SportZone</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <h2>Payment for Booking #<?php echo $booking_id; ?></h2>
        <p>Facility Price: $<?php echo number_format($booking['price'], 2); ?></p>
        <?php if (!empty($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post" action="payment.php?booking_id=<?php echo $booking_id; ?>">
            <button type="submit">Pay Now</button>
        </form>
        <?php if (!empty($success)): ?>
        <p class="success"><?php echo htmlspecialchars($success); ?></p>
        <p><a href="history.php">View Booking History</a></p>
        <?php else: ?>
        <p><a href="history.php">Back to Booking History</a></p>
        <?php endif; ?>
    </div>
</body>

</html>