<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "ration");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION['user_id']; // Logged-in user's ID
$shop_id = $_SESSION['shop_id']; // Shop ID
$booking_date = $_POST['booking_date'];
$time_slot = $_POST['time_slot'];
$rice_quantity = $_POST['rice_quantity'];
$wheat_quantity = $_POST['wheat_quantity'];
$atta_quantity = $_POST['atta_quantity'];

// Check if the slot is available
$query = "SELECT COUNT(*) AS booking_count 
          FROM bookings 
          WHERE booking_date = '$booking_date' 
          AND time_slot = '$time_slot' 
          AND shop_id = '$shop_id'";

$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);

if ($row['booking_count'] >= 5) {
    echo "This time slot is fully booked. Please go back and select a different slot.";
    exit;
}

// Insert the new booking
$query = "INSERT INTO bookings (user_id, shop_id, booking_date, time_slot, status, rice_quantity, wheat_quantity, atta_quantity, created_at) 
          VALUES ('$user_id', '$shop_id', '$booking_date', '$time_slot', 'Pending', '$rice_quantity', '$wheat_quantity', '$atta_quantity', NOW())";

if (mysqli_query($con, $query)) {
    echo "Booking successful!";
} else {
    echo "Error: " . mysqli_error($con);
}

mysqli_close($con);
?>
