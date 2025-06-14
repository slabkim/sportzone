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

<body class="bg-gray-50 font-sans text-gray-900 min-h-screen">
    <main class="container mx-auto p-8 max-w-7xl bg-white rounded-lg shadow-2xl mt-10 overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-4xl font-extrabold text-indigo-600">Admin Dashboard</h1>
            <p class="text-lg font-medium">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> | 
                <a href="logout.php" class="text-blue-500 hover:text-blue-600">Logout</a>
            </p>
        </div>

        <!-- Facilities Section -->
        <section class="bg-white p-6 rounded-xl shadow-lg mb-6">
            <h2 class="text-2xl font-semibold text-indigo-700 mb-4">Facilities</h2>
            <div class="flex space-x-4 mb-4">
                <a href="facilities.php" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Manage Facilities</a>
                <a href="backup_list.php" class="inline-block px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">Database Backups</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse bg-gray-100 rounded-lg shadow-md">
                    <thead class="bg-indigo-600 text-white">
                        <tr>
                            <th class="px-4 py-2">Name</th>
                            <th class="px-4 py-2">Price</th>
                            <th class="px-4 py-2">Availability</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($facilities as $facility): ?>
                        <tr class="hover:bg-gray-200">
                            <td class="px-4 py-2"><?php echo htmlspecialchars($facility['name']); ?></td>
                            <td class="px-4 py-2">$<?php echo number_format($facility['price'], 2); ?></td>
                            <td class="px-4 py-2"><?php echo $facility['available'] ? 'Available' : 'Not Available'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Bookings Section -->
        <section class="bg-white p-6 rounded-xl shadow-lg mb-6">
            <h2 class="text-2xl font-semibold text-indigo-700 mb-4">Bookings</h2>
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse bg-gray-100 rounded-lg shadow-md">
                    <thead class="bg-indigo-600 text-white">
                        <tr>
                            <th class="px-4 py-2">User</th>
                            <th class="px-4 py-2">Facility</th>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Time</th>
                            <th class="px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                        <tr class="hover:bg-gray-200">
                            <td class="px-4 py-2"><?php echo htmlspecialchars($booking['username']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($booking['facility_name']); ?></td>
                            <td class="px-4 py-2"><?php echo $booking['booking_date']; ?></td>
                            <td class="px-4 py-2"><?php echo $booking['booking_time']; ?></td>
                            <td class="px-4 py-2"><?php echo $booking['status']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Users Section -->
        <section class="bg-white p-6 rounded-xl shadow-lg mb-6">
            <h2 class="text-2xl font-semibold text-indigo-700 mb-4">Users</h2>
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse bg-gray-100 rounded-lg shadow-md">
                    <thead class="bg-indigo-600 text-white">
                        <tr>
                            <th class="px-4 py-2">Username</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Role</th>
                            <th class="px-4 py-2">Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-gray-200">
                            <td class="px-4 py-2"><?php echo htmlspecialchars($user['username']); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($user['email']); ?></td>
                            <td class="px-4 py-2"><?php echo $user['role']; ?></td>
                            <td class="px-4 py-2"><?php echo $user['created_at']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>
</body>

</html>
