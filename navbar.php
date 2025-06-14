<nav class="bg-blue-600 text-white p-4">
  <div class="container mx-auto flex justify-between items-center">
    <a href="home.php" class="text-lg font-semibold">SportZone</a>
    <div class="space-x-4">
      <a href="home.php" class="hover:underline">Home</a>
      <a href="history.php" class="hover:underline">Booking History</a>
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="logout.php" class="hover:underline">Logout</a>
      <?php else: ?>
        <a href="login.php" class="hover:underline">Login</a>
        <a href="register.php" class="hover:underline">Register</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
