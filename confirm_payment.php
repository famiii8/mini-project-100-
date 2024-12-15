<?php
session_start(); // Start the session

// Database connection
$conn = mysqli_connect("localhost", "root", "", "ration");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve and sanitize POST data
$user_id = intval($_POST['user_id']);
$shop_id = intval($_POST['shop_id']);
$booking_date = mysqli_real_escape_string($conn, $_POST['booking_date']);
$time_slot = mysqli_real_escape_string($conn, $_POST['time_slot']);
$rice_quantity = intval($_POST['rice_quantity']);
$wheat_quantity = intval($_POST['wheat_quantity']);
$atta_quantity = intval($_POST['atta_quantity']);
$payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);

// Check if the user already has a booking for the current month
$month = date('Y-m', strtotime($booking_date));
$check_query = "SELECT COUNT(*) AS count FROM bookings WHERE user_id = ? AND DATE_FORMAT(booking_date, '%Y-%m') = ?";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param("is", $user_id, $month);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result) {
    $row = $check_result->fetch_assoc();
    $count = $row['count'];

    if ($count > 0) {
        echo "<script>alert('You already have a booking for this month.'); window.location.href = 'userdash.php';</script>";
        exit;
    }
} else {
    echo "Error: " . $conn->error;
    exit;
}

// Set payment status and booking status
$payment_status = ($payment_method === 'online') ? 'paid' : 'cash';
$booking_status = 'pending'; // Default status for new bookings

// Insert booking into the database
$sql = "INSERT INTO bookings (user_id, shop_id, booking_date, time_slot, rice_quantity, wheat_quantity, atta_quantity, payment_status, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "iissiiiss", 
    $user_id, 
    $shop_id, 
    $booking_date, 
    $time_slot, 
    $rice_quantity, 
    $wheat_quantity, 
    $atta_quantity, 
    $payment_status, 
    $booking_status
);

if ($stmt->execute()) {
    echo "<script>alert('Booking confirmed successfully!'); window.location.href = 'userdash.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
