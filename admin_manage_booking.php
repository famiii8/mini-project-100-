<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "ration");

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all bookings, joining with the users table to get customer names and shops
$query = "
    SELECT b.id AS booking_id, b.time_slot, u.name AS customer_name, s.shop_owner, b.status 
    FROM bookings b 
    JOIN users u ON b.user_id = u.usid 
    JOIN shops s ON b.shop_id = s.id 
    ORDER BY b.time_slot DESC
";
$result = mysqli_query($con, $query);
if (!$result) {
    die("Error fetching bookings: " . mysqli_error($con));
}

// Check if the form is submitted to change status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];
    $new_status = $_POST['status'];

    $status_update_query = "UPDATE bookings SET status = '$new_status' WHERE id = $booking_id";
    mysqli_query($con, $status_update_query);
    header('Location: admin_manage_booking.php'); // Refresh the page to see updated status
    exit();
}

$bookings = [];
while ($row = mysqli_fetch_assoc($result)) {
    $bookings[] = $row;
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Bookings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .status {
            font-weight: bold;
        }
        .pending { color: orange; }
        .accepted { color: green; }
        .rejected { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage All Bookings</h1>

        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Slot Time</th>
                    <th>Customer Name</th>
                    <th>Shop Owner Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bookings)): ?>
                    <tr>
                        <td colspan="6">No bookings found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                            <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($booking['time_slot']))); ?></td>
                            <td><?php echo htmlspecialchars($booking['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['shop_owner']); ?></td>
                            <td class="status <?php echo htmlspecialchars($booking['status']); ?>">
                                <?php echo htmlspecialchars(ucfirst($booking['status'])); ?>
                    </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="admindash.php" class="button">Back to Dashboard</a>
    </div>
</body>
</html>
