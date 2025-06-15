<?php
session_start();
require 'config/init.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$bookings = [];
$stmt = mysqli_prepare($conn, 'SELECT b.id, f.name AS facility_name, b.booking_date, b.booking_time, b.status AS booking_status, p.status AS payment_status
                       FROM bookings b
                       JOIN facilities f ON b.facility_id = f.id
                       LEFT JOIN payments p ON b.id = p.booking_id
                       WHERE b.user_id = ?
                       ORDER BY b.booking_date DESC, b.booking_time DESC');
mysqli_stmt_bind_param($stmt, 'i', $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $bookings[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Booking History - SportZone</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    <?php include 'navbar.php'; ?>

    <main class="container mx-auto flex-grow p-6 max-w-4xl bg-white rounded shadow">
        <h2 class="text-2xl font-semibold mb-4">Your Booking History</h2>
        <p class="mb-6"><a href="home.php" class="text-blue-600 hover:underline">Back to Home</a> | <a href="logout.php"
                class="text-blue-600 hover:underline">Logout</a></p>

        <?php if (count($bookings) > 0): ?>
        <table class="w-full border border-gray-300 border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2 text-left">Facility</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Date</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Time</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Booking Status</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Payment Status</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td class="border border-gray-300 px-4 py-2">
                        <?php echo htmlspecialchars($booking['facility_name']); ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?php echo $booking['booking_date']; ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?php echo $booking['booking_time']; ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?php echo $booking['booking_status']; ?></td>
                    <td class="border border-gray-300 px-4 py-2"><?php echo $booking['payment_status'] ?? 'Not Paid'; ?>
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        <?php if ($booking['booking_status'] === 'pending'): ?>
                        <a href="payment.php?booking_id=<?php echo $booking['id']; ?>"
                            class="text-blue-600 hover:underline">Pay Now</a>
                        <?php else: ?>
                        -
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>You have no bookings yet.</p>
        <?php endif; ?>
    </main>
</body>

</html>