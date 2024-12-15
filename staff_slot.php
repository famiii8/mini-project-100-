<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "ration");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status'])) {
    $booking_id = $_POST['booking_id'];
    $new_status = $_POST['status'];

    if (in_array($new_status, ['pending', 'accepted', 'rejected'])) {
        $update_status_query = "UPDATE bookings SET status = '$new_status' WHERE id = $booking_id";
        if (mysqli_query($con, $update_status_query)) {
            echo "<script>alert('Booking status updated successfully!'); window.location.href = ''; </script>";
        } else {
            echo "Error updating status: " . mysqli_error($con);
        }
    }
}

$shop_owner_id = $_SESSION['shop_id'];
$selected_date = isset($_GET['date']) ? date('Y-m-d', strtotime($_GET['date'])) : date('Y-m-d');

$start_time = strtotime('11:00');
$end_time = strtotime('18:00');
$time_slots = [];
while ($start_time <= $end_time) {
    $time_slots[] = date('H:i', $start_time); 
    $start_time += 30 * 60;
}

$booked_slots_query = "
    SELECT time_slot, COUNT(*) AS booking_count 
    FROM bookings 
    WHERE shop_id = $shop_owner_id AND booking_date = '$selected_date'
    GROUP BY time_slot
";
$booked_slots_result = mysqli_query($con, $booked_slots_query);

if (!$booked_slots_result) {
    die("Error in query: " . mysqli_error($con));
}

$booked_counts = [];
while ($row = mysqli_fetch_assoc($booked_slots_result)) {
    $time_slot = date('H:i', strtotime($row['time_slot']));
    $booked_counts[$time_slot] = $row['booking_count'];
}

$max_bookings_per_slot = 5; 
$remaining_slots = [];

foreach ($time_slots as $slot) {
    $booked_count = isset($booked_counts[$slot]) ? $booked_counts[$slot] : 0;
    $remaining_slots[$slot] = $max_bookings_per_slot - $booked_count;
}

$query = "
    SELECT b.id AS booking_id, b.payment_status, b.booking_date, b.time_slot, u.name AS customer_name, b.status 
    FROM bookings b 
    JOIN users u ON b.user_id = u.usid 
    WHERE b.shop_id = $shop_owner_id 
    ORDER BY b.time_slot DESC
";
$result = mysqli_query($con, $query);
if (!$result) {
    die("Error fetching bookings: " . mysqli_error($con));
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
            width: 70%;
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
        .confirmed { color: green; }
        .canceled { color: red; }
        .available { color: green; }
        .fully-booked { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Bookings</h1>

        <!-- Date Selector -->
        <form method="GET" action="">
            <label for="date">Select Date:</label>
            <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($selected_date); ?>">
            <input type="submit" value="View Slots">
        </form>

        <!-- Existing Bookings Table -->
        <h2>All Bookings</h2>
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Slot Time</th>
                    <th>Booking Date</th>
                    <th>Customer Name</th>
                    <th>Status</th>
                    <th>Payment Status</th>
                    <th>Action</th>
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
                            <td><?php echo htmlspecialchars($booking['time_slot']); ?></td>
                            <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                            <td><?php echo htmlspecialchars($booking['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['payment_status']); ?></td>
                            <td class="status <?php echo htmlspecialchars($booking['status']); ?>">
                                <?php echo htmlspecialchars(ucfirst($booking['status'])); ?>
                            </td>

                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking['booking_id']); ?>">
                                    <select name="status">
                                        <option value="pending" <?php if ($booking['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                        <option value="accepted" <?php if ($booking['status'] == 'accepted') echo 'selected'; ?>>Accepted</option>
                                        <option value="rejected" <?php if ($booking['status'] == 'rejected') echo 'selected'; ?>>Rejected</option>
                                    </select>
                                    <input type="submit" value="Update Status">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <h2>Available Slots for <?php echo htmlspecialchars($selected_date); ?></h2>
        <table>
            <thead>
                <tr>
                    <th>Time Slot</th>
                    <th>Remaining Slots</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($remaining_slots as $slot => $remaining): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($slot); ?></td>
                        <td class="<?php echo $remaining > 0 ? 'available' : 'fully-booked'; ?>">
                            <?php echo $remaining > 0 ? $remaining : "Fully Booked"; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="staffdash.php" class="button">Back to Dashboard</a>
    </div>
</body>
</html>
