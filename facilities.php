<?php
require 'config/init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

// Handle add, edit, delete actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $available = isset($_POST['available']) ? 1 : 0;
    $id = $_POST['id'] ?? null;

    if ($action === 'add') {
        if ($name && $price) {
            $stmt = $pdo->prepare('INSERT INTO facilities (name, description, price, available) VALUES (?, ?, ?, ?)');
            if ($stmt->execute([$name, $description, $price, $available])) {
                $success = 'Facility added successfully.';
            } else {
                $error = 'Failed to add facility.';
            }
        } else {
            $error = 'Name and price are required.';
        }
    } elseif ($action === 'edit' && $id) {
        $stmt = $pdo->prepare('UPDATE facilities SET name = ?, description = ?, price = ?, available = ? WHERE id = ?');
        if ($stmt->execute([$name, $description, $price, $available, $id])) {
            $success = 'Facility updated successfully.';
        } else {
            $error = 'Failed to update facility.';
        }
    } elseif ($action === 'delete' && $id) {
        $stmt = $pdo->prepare('DELETE FROM facilities WHERE id = ?');
        if ($stmt->execute([$id])) {
            $success = 'Facility deleted successfully.';
        } else {
            $error = 'Failed to delete facility.';
        }
    }
}

// Fetch all facilities
$facilities = $pdo->query('SELECT * FROM facilities')->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Facilities - SportZone</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <h1>Manage Facilities</h1>
        <p><a href="admin.php">Back to Admin Dashboard</a> | <a href="logout.php">Logout</a></p>

        <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
        <p class="success"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <h2>Add New Facility</h2>
        <form method="post" action="facilities.php">
            <input type="hidden" name="action" value="add">
            <label>Name:</label><br>
            <input type="text" name="name" required><br><br>
            <label>Description:</label><br>
            <textarea name="description"></textarea><br><br>
            <label>Price:</label><br>
            <input type="number" step="0.01" name="price" required><br><br>
            <label>Available:</label>
            <input type="checkbox" name="available" checked><br><br>
            <button type="submit">Add Facility</button>
        </form>

        <h2>Existing Facilities</h2>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Available</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($facilities as $facility): ?>
            <tr>
                <form method="post" action="facilities.php">
                    <td><input type="text" name="name" value="<?php echo htmlspecialchars($facility['name']); ?>"
                            required></td>
                    <td><textarea
                            name="description"><?php echo htmlspecialchars($facility['description']); ?></textarea></td>
                    <td><input type="number" step="0.01" name="price" value="<?php echo $facility['price']; ?>"
                            required></td>
                    <td><input type="checkbox" name="available" <?php echo $facility['available'] ? 'checked' : ''; ?>>
                    </td>
                    <td>
                        <input type="hidden" name="id" value="<?php echo $facility['id']; ?>">
                        <button type="submit" name="action" value="edit">Update</button>
                        <button type="submit" name="action" value="delete"
                            onclick="return confirm('Are you sure?');">Delete</button>
                    </td>
                </form>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>

</html>