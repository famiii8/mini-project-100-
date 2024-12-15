<?php
session_start(); // Start the session

$conn = mysqli_connect("localhost", "root", "", "ration");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get form data and sanitize inputs
$user_id = intval($_POST['user_id']);
$shop_id = intval($_POST['shop_id']);
$booking_date = $_POST['booking_date']; // Use basic validation
$time_slot = $_POST['time_slot']; // Use basic validation

if (empty($booking_date) || empty($time_slot)) {
    die("Invalid input. Please select a date and a time slot.");
}

$booking_month = date('Y-m', strtotime($booking_date));

$check_query = "SELECT COUNT(*) AS booking_count FROM bookings 
                WHERE user_id = $user_id AND DATE_FORMAT(booking_date, '%Y-%m') = '$booking_month'";
$check_result = mysqli_query($conn, $check_query);
$row = mysqli_fetch_assoc($check_result);

if ($row['booking_count'] > 0) {
    echo "<script>
    alert('You already have a booking for this month. Please choose a different month.');
    window.location.href = 'book_supplies.php';
</script>";
    exit();
}

// Construct the SQL query to insert the new booking
$sql = "INSERT INTO bookings (user_id, shop_id, booking_date, time_slot) 
        VALUES ($user_id, $shop_id, '$booking_date', '$time_slot')";

// Execute the query and check if successful
if (mysqli_query($conn, $sql)) {
    echo "<h1>Booking Confirmed!</h1>";
    echo "<script>
    alert('Your booking for <strong>{$booking_date}</strong> at <strong>{$time_slot}</strong> has been confirmed.');window.location.href = 'book_supplies.php';
</script>";
} else {
    echo "<h1>Error!</h1>";
    echo "<p>There was an error confirming your booking: " . mysqli_error($conn) . "</p>";
}

mysqli_close($conn);
?>
