<?php
require 'init.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$booking_id = $_GET['booking_id'] ?? null;
if (!$booking_id) {
    header('Location: history.php');
    exit;
}

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
    if ($stmt->execute([$booking_id, $booking['price'], 'completed'])) {
        // Update booking status to confirmed
        $stmt = $pdo->prepare('UPDATE bookings SET status = ? WHERE id = ?');
        $stmt->execute(['confirmed', $booking_id]);
        echo "Payment successful! Your booking is confirmed.";
        echo '<br><a href="history.php">View Booking History</a>';
        exit;
    } else {
        $error = "Payment failed. Please try again.";
    }
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
        <p><a href="history.php">Back to Booking History</a></p>
    </div>
</body>

</html>