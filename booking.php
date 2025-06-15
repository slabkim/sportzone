<?php
session_start();
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

$facility_id = $_GET['facility_id'] ?? null;
if (!$facility_id) {
    header('Location: home.php');
    exit;
}

$facility = null;
$error = '';
$success = '';

$stmt = mysqli_prepare($conn, 'SELECT * FROM facilities WHERE id = ? AND available = TRUE');
mysqli_stmt_bind_param($stmt, 'i', $facility_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if ($result) {
    $facility = mysqli_fetch_assoc($result);
}

if (!$facility) {
    echo "Facility not available.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_date = $_POST['booking_date'] ?? '';
    $booking_time = $_POST['booking_time'] ?? '';

    if ($booking_date && $booking_time) {
        $stmt = mysqli_prepare($conn, 'SELECT IsFacilityAvailable(?, ?, ?) AS available');
        mysqli_stmt_bind_param($stmt, 'iss', $facility_id, $booking_date, $booking_time);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        if ($row && $row['available']) {
            $stmt = mysqli_prepare($conn, 'CALL AddBooking(?, ?, ?, ?)');
            mysqli_stmt_bind_param($stmt, 'iiss', $_SESSION['user_id'], $facility_id, $booking_date, $booking_time);
            mysqli_stmt_execute($stmt);
            $success = "Booking successful!";
        } else {
            $error = "Facility is not available at the selected date and time.";
        }
    } else {
        $error = "Please select booking date and time.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Book Facility - SportZone</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    <?php include 'navbar.php'; ?>

    <main class="container mx-auto flex-grow p-6 max-w-md bg-white rounded shadow">
        <h2 class="text-2xl font-semibold mb-4">Book Facility: <?php echo htmlspecialchars($facility['name']); ?></h2>
        <?php if (!empty($error)): ?>
        <p class="text-red-600 mb-4"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
        <p class="text-green-600 mb-4"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>
        <form method="post" action="booking.php?facility_id=<?php echo $facility_id; ?>" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium">Booking Date:</label>
                <input type="date" name="booking_date" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
            <div>
                <label class="block mb-1 font-medium">Booking Time:</label>
                <input type="time" name="booking_time" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Book Now</button>
        </form>
        <p class="mt-4"><a href="home.php" class="text-blue-600 hover:underline">Back to Home</a></p>
    </main>
</body>

</html>