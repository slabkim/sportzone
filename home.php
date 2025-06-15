<?php
require 'config/init.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch available facilities
$facilities = [];
$facilities = [];
$result = mysqli_query($conn, 'SELECT * FROM facilities WHERE available = TRUE');
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $facilities[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Home - SportZone</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    <?php include 'navbar.php'; ?>

    <main class="container mx-auto flex-grow p-6">
        <h1 class="text-3xl font-bold mb-4">Welcome to SportZone,
            <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <div class="mb-6">
            <a href="history.php" class="text-blue-600 hover:underline mr-4">Booking History</a>
            <a href="logout.php" class="text-blue-600 hover:underline">Logout</a>
        </div>

        <section>
            <h2 class="text-2xl font-semibold mb-4">Available Facilities</h2>
            <?php if (count($facilities) > 0): ?>
            <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($facilities as $facility): ?>
                <li class="bg-white rounded shadow p-4">
                    <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($facility['name']); ?></h3>
                    <p class="mb-2"><?php echo htmlspecialchars($facility['description']); ?></p>
                    <p class="mb-4 font-semibold">Price: $<?php echo number_format($facility['price'], 2); ?></p>
                    <a href="booking.php?facility_id=<?php echo $facility['id']; ?>"
                        class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Book Now</a>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php else: ?>
            <p>No facilities available at the moment.</p>
            <?php endif; ?>
        </section>
    </main>
</body>

</html>