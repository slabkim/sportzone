<?php
require 'config/init.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard - SportZone</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <h1>Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
        <p><a href="home.php">Home</a> | <a href="history.php">Booking History</a> | <a href="logout.php">Logout</a></p>
    </div>
</body>

</html>