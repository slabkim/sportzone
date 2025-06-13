<?php
require 'config/init.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$facility_id = $_GET['facility_id'] ?? null;
if (!$facility_id) {
    header('Location: home.php');
    exit;
}

// Fetch facility details
$stmt = $pdo->prepare('SELECT * FROM facilities WHERE id = ? AND available = TRUE');
$stmt->execute([$facility_id]);
$facility = $stmt->fetch();

if (!$facility) {
    echo "Facility not available.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_date = $_POST['booking_date'] ?? '';
    $booking_time = $_POST['booking_time'] ?? '';

    if ($booking_date && $booking_time) {
        // Check availability using the stored function
        $stmt = $pdo->prepare('SELECT IsFacilityAvailable(?, ?, ?) AS available');
        $stmt->execute([$facility_id, $booking_date, $booking_time]);
        $result = $stmt->fetch();

        if ($result && $result['available']) {
            // Add booking using stored procedure
            $stmt = $pdo->prepare('CALL AddBooking(?, ?, ?, ?)');
            $stmt->execute([$_SESSION['user_id'], $facility_id, $booking_date, $booking_time]);
            echo "Booking successful!";
            echo '<br><a href="home.php">Back to Home</a>';
            exit;
        } else {
            $error = "Facility is not available at the selected date and time.";
        }
    } else {
        $error = "Please select booking date and time.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Book Facility - SportZone</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <h2>Book Facility: <?php echo htmlspecialchars($facility['name']); ?></h2>
        <?php if (!empty($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="post" action="booking.php?facility_id=<?php echo $facility_id; ?>">
            <label>Booking Date:</label><br>
            <input type="date" name="booking_date" required><br><br>
            <label>Booking Time:</label><br>
            <input type="time" name="booking_time" required><br><br>
            <button type="submit">Book Now</button>
        </form>
        <p><a href="home.php">Back to Home</a></p>
    </div>
</body>

</html>