<?php
require 'init.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch available facilities
$stmt = $pdo->query('SELECT * FROM facilities WHERE available = TRUE');
$facilities = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Home - SportZone</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <h1>Welcome to SportZone, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p><a href="logout.php">Logout</a> | <a href="history.php">Booking History</a></p>

        <h2>Available Facilities</h2>
        <?php if (count($facilities) > 0): ?>
        <ul>
            <?php foreach ($facilities as $facility): ?>
            <li>
                <strong><?php echo htmlspecialchars($facility['name']); ?></strong><br>
                <?php echo htmlspecialchars($facility['description']); ?><br>
                Price: $<?php echo number_format($facility['price'], 2); ?><br>
                <a href="booking.php?facility_id=<?php echo $facility['id']; ?>">Book Now</a>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <p>No facilities available at the moment.</p>
        <?php endif; ?>
    </div>
</body>

</html>