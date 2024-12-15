<?php
// user-management.php
$con = mysqli_connect("localhost", "root", "", "ration");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the sorting parameter safely
$order_by = isset($_GET['sort']) ? $_GET['sort'] : 'usid';

// Validate the order_by value to prevent SQL injection
$valid_sort_columns = ['usid', 'name', 'email', 'phno', 'card_color']; // Add valid column names
if (in_array($order_by, $valid_sort_columns)) {
    $result = mysqli_query($con, "SELECT * FROM users ORDER BY $order_by");
} else {
    $result = mysqli_query($con, "SELECT * FROM users ORDER BY usid"); // Default sort
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="staffusermanage.css">
</head>
<body>
    <div class="sidebar">
        <h2>E-RATION MANAGEMENT SYSTEM</h2>
        <ul>
            <li><a href="staffdash.php">Dashboard</a></li>
            <li><a href="staffmanagecard.php">Manage Card</a></li>
            <li><a href="staffusermanage.php">Manage User</a></li>
            <li><a href="staff_slot.php">View Slot</a></li>
            <li><a href="index1.html">Logout</a></li>
        </ul>
    </div>

    <div class="content">
        <header>
            <h1>User Management</h1>
        </header>

        <main>
            <h2>Manage Users</h2>

            <!-- Sorting Options -->
            <div>
                <strong>Sort by:</strong>
                <a href="?sort=card_color">Card Color</a>
                <a href="?sort=name">Name</a>
                <a href="?sort=usid">User ID</a>
            </div>

            <table>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Card Color</th> <!-- New column for Card Color -->
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['usid']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phno']); ?></td>
                    <td><?php echo htmlspecialchars($row['card_color']); ?></td> <!-- Fetch and display Card Color -->
                </tr>
                <?php endwhile; ?>
            </table>
        </main>
    </div>

    <footer>
        <p>&copy; 2024 Ration Shop Management. All rights reserved.</p>
    </footer>
</body>
</html>
