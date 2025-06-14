<?php
session_start();
require 'config/init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch all facilities
$facilities = [];
$result = mysqli_query($conn, 'SELECT * FROM facilities');
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $facilities[] = $row;
    }
}

// Fetch all bookings
$bookings = [];
$query = 'SELECT b.id, u.username, f.name AS facility_name, b.booking_date, b.booking_time, b.status
          FROM bookings b
          JOIN users u ON b.user_id = u.id
          JOIN facilities f ON b.facility_id = f.id';
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $bookings[] = $row;
    }
}

// Fetch all users
$users = [];
$result = mysqli_query($conn, 'SELECT id, username, email, role, created_at FROM users');
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard - SportZone</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    <main class="container mx-auto flex-grow p-6 max-w-6xl bg-white rounded shadow">
        <h1 class="text-3xl font-bold mb-6">Admin Dashboard</h1>
        <p class="mb-6">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> | <a href="logout.php" class="text-blue-600 hover:underline">Logout</a></p>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">Facilities</h2>
            <a href="facilities.php" class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Manage Facilities</a>
            <a href="backup_list.php" class="inline-block mb-4 ml-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Database Backups</a>
            <table class="w-full border border-gray-300 border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Price</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Availability</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($facilities as $facility): ?>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($facility['name']); ?></td>
                        <td class="border border-gray-300 px-4 py-2">$<?php echo number_format($facility['price'], 2); ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo $facility['available'] ? 'Available' : 'Not Available'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section class="mb-8">
            <h2 class="text-2xl font-semibold mb-4">Bookings</h2>
            <table class="w-full border border-gray-300 border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-4 py-2 text-left">User</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Facility</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Date</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Time</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($booking['username']); ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($booking['facility_name']); ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo $booking['booking_date']; ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo $booking['booking_time']; ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo $booking['status']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section>
            <h2 class="text-2xl font-semibold mb-4">Users</h2>
            <table class="w-full border border-gray-300 border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-4 py-2 text-left">Username</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Email</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Role</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Registered</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($user['username']); ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($user['email']); ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo $user['role']; ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo $user['created_at']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>

</html>