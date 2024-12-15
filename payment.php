<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "ration");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION['user_id'];
$shop_id = $_POST['shop_id'];
$booking_date = $_POST['booking_date'];
$time_slot = $_POST['time_slot'];
$rice_quantity = $_POST['rice_quantity'];
$wheat_quantity = $_POST['wheat_quantity'];
$atta_quantity = $_POST['atta_quantity'];

$check_booking_query = "SELECT COUNT(*) as booking_count FROM bookings WHERE shop_id = '$shop_id' AND booking_date = '$booking_date' AND time_slot = '$time_slot'";
$booking_check_result = mysqli_query($conn, $check_booking_query);
$booking_check = mysqli_fetch_assoc($booking_check_result);

if ($booking_check['booking_count'] >= 5) {
     echo "<script>alert('Max bookings reached for this time slot. Please choose another time slot.');
    window.location.href = 'book_supplies.php';
    </script>";
}

else{

$total_price = (floatval($_POST['rice_price']) * $rice_quantity) +
               (floatval($_POST['wheat_price']) * $wheat_quantity) +
               (floatval($_POST['atta_price']) * $atta_quantity);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Options</title>
    <link rel="stylesheet" href="payment.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-title">E-RATION MANAGEMENT SYSTEM</div>
        <ul class="nav-menu">
            <li><a href="book_supplies.php">Book Supplies</a></li>
            <li><a href="order_details.php">Order Details</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <h1>Payment Options</h1>
    <p>Total Amount: ₹<?php echo number_format($total_price, 2); ?></p>

    <h3>Select Payment Method:</h3>
    <form action="confirm_payment.php" method="post">
        <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <input type="hidden" name="shop_id" value="<?php echo $shop_id; ?>">
        <input type="hidden" name="booking_date" value="<?php echo $booking_date; ?>">
        <input type="hidden" name="time_slot" value="<?php echo $time_slot; ?>">
        <input type="hidden" name="rice_quantity" value="<?php echo $rice_quantity; ?>">
        <input type="hidden" name="wheat_quantity" value="<?php echo $wheat_quantity; ?>">
        <input type="hidden" name="atta_quantity" value="<?php echo $atta_quantity; ?>">

        <h3>Payment Method</h3>
<label>
    <input type="radio" name="payment_method" value="online" required> Pay Online
</label>
<br>
<label>
    <input type="radio" name="payment_method" value="cash" required> Cash
</label>

        <br><br>
        <input type="submit" value="Proceed to Pay">
    </form>
</body>
</html>
