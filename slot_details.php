<?php
session_start();

// Database connection
$con = mysqli_connect("localhost", "root", "", "ration");

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION['user_id'];

// Fetching booking details
$query = "SELECT booking_date, time_slot, status, rice_quantity, wheat_quantity, atta_quantity, payment_status FROM bookings WHERE user_id = $user_id";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Booking Details</title>
    <link rel="stylesheet" href="slot_details.css"> <!-- Include the same CSS file -->
</head>
<body>
    <div class="sidebar">
        <div class="navbar-title">E-RATION MANAGEMENT SYSTEM</div>
        <ul class="nav-menu">
            <li><a href="userdash.php">Dashboard</a></li>
            <li><a href="book_supplies.php">Book Supplies</a></li>
            <li><a href="slot_details.php">Slot Details</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="content">
        <h1>Your Booking Details</h1>

        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <table>
                <tr>
                    <th>Slot Date</th>
                    <th>Slot Time</th>
                    <th>Status</th>
                    <th>Payment Status</th>
                    <th>Rice Quantity</th>
                    <th>Wheat Quantity</th>
                    <th>Atta Quantity</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['time_slot']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td><?php echo htmlspecialchars($row['payment_status']); ?></td>
                        <td><?php echo htmlspecialchars($row['rice_quantity']); ?></td>
                        <td><?php echo htmlspecialchars($row['wheat_quantity']); ?></td>
                        <td><?php echo htmlspecialchars($row['atta_quantity']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>You have no bookings.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
mysqli_close($con);
?>
