<?php
session_start(); // Start the session

$conn = mysqli_connect("localhost", "root", "", "ration");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION['user_id'];
$shop_id = $_GET['shop_id'];
$user_card_color = $_SESSION['card_color']; // Example: 'white', 'blue', etc.

// Fetch shop details
$shop_query = "SELECT * FROM shops WHERE id = '$shop_id'";
$shop_result = mysqli_query($conn, $shop_query);
$shop = mysqli_fetch_assoc($shop_result);

// Fetch card details based on user's card color
$card_query = "SELECT * FROM cards WHERE card_type = '$user_card_color'";
$card_result = mysqli_query($conn, $card_query);
$card_data = mysqli_fetch_assoc($card_result);

// Generate time slots
$time_slots = [];
$start_time = strtotime('11:00');
$end_time = strtotime('18:00');

for ($time = $start_time; $time <= $end_time; $time += 30 * 60) {
    $time_slots[] = date('H:i', $time);
}

// Check if the time slot has already been booked 5 times
$booking_error = '';
if (isset($_POST['booking_date']) && isset($_POST['time_slot'])) {
    $booking_date = $_POST['booking_date'];
    $time_slot = $_POST['time_slot'];

    $check_booking_query = "SELECT COUNT(*) as booking_count FROM bookings WHERE shop_id = '$shop_id' AND booking_date = '$booking_date' AND time_slot = '$time_slot'";
    $booking_check_result = mysqli_query($conn, $check_booking_query);
    $booking_check = mysqli_fetch_assoc($booking_check_result);

    if ($booking_check['booking_count'] >= 5) {
        $booking_error = 'Max bookings reached for this time slot. Please choose another time slot.';
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop Booking</title>
    <link rel="stylesheet" href="shop_detail.css">
    <script>
        function calculateTotal() {
            const ricePrice = parseFloat(document.getElementById('rice_price').value);
            const wheatPrice = parseFloat(document.getElementById('wheat_price').value);
            const attaPrice = parseFloat(document.getElementById('atta_price').value);

            const riceQuantity = parseInt(document.getElementById('rice_quantity').value) || 0;
            const wheatQuantity = parseInt(document.getElementById('wheat_quantity').value) || 0;
            const attaQuantity = parseInt(document.getElementById('atta_quantity').value) || 0;

            const riceAvailable = parseInt(document.getElementById('rice_available').value);
            const wheatAvailable = parseInt(document.getElementById('wheat_available').value);
            const attaAvailable = parseInt(document.getElementById('atta_available').value);

            let valid = true;

            if (riceQuantity > riceAvailable) {
                alert("You cannot order more rice than available: " + riceAvailable);
                document.getElementById('rice_quantity').value = riceAvailable; // Reset to available quantity
                valid = false;
            }
            if (wheatQuantity > wheatAvailable) {
                alert("You cannot order more wheat than available: " + wheatAvailable);
                document.getElementById('wheat_quantity').value = wheatAvailable; // Reset to available quantity
                valid = false;
            }
            if (attaQuantity > attaAvailable) {
                alert("You cannot order more atta than available: " + attaAvailable);
                document.getElementById('atta_quantity').value = attaAvailable; // Reset to available quantity
                valid = false;
            }

            if (valid) {
                const total = (ricePrice * riceQuantity) + (wheatPrice * wheatQuantity) + (attaPrice * attaQuantity);
                document.getElementById('total_price').innerText = 'Total Price: ' + total.toFixed(2);
            }

            return valid; // Return whether the input is valid
        }

        // Function to check booking error before form submission
        function checkBookingError() {
            const bookingError = '<?php echo $booking_error; ?>';
            if (bookingError) {
                alert(bookingError);  // Display error message
                return false; // Prevent form submission
            }
            return true; // Proceed with form submission
        }
    </script>
</head>
<body>
    <div class="sidebar">
        <div class="navbar-title">E-RATION MANAGEMENT SYSTEM</div>
        <ul class="nav-menu">
            <li><a href="book_supplies.php">Book Supplies</a></li>
            <li><a href="order_details.php">Order Details</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="content">
        <h1>Book a Slot</h1>

        <?php if ($shop): ?>
            <h2><?php echo htmlspecialchars($shop['shop_name']); ?></h2>
            <p>Address: <?php echo htmlspecialchars($shop['address']); ?></p>
            <p>Owner: <?php echo htmlspecialchars($shop['shop_owner']); ?></p>

            <h2>Booking Form</h2>
            <form action="payment.php" method="post" onsubmit="return checkBookingError() && calculateTotal();">
                <label for="booking_date">Select Date:</label>
                <input type="date" id="booking_date" name="booking_date" 
                       min="<?php echo date('Y-m-01'); ?>" 
                       max="<?php echo date('Y-m-t'); ?>" 
                       required>
                
                <label for="time_slot">Select Time Slot:</label>
                <select id="time_slot" name="time_slot" required>
                    <option value="">Select a time</option>
                    <?php foreach ($time_slots as $slot): ?>
                        <option value="<?php echo $slot; ?>"><?php echo $slot; ?></option>
                    <?php endforeach; ?>
                </select>

                <h3>Select Items</h3>
                <label for="rice_quantity">Rice (Price: <?php echo $card_data['rice_price']; ?>, Available: <span id="rice_available"><?php echo $card_data['rice']; ?></span>):</label>
                <input type="number" id="rice_quantity" name="rice_quantity" min="0" oninput="calculateTotal()">
                <input type="hidden" id="rice_price" name="rice_price" value="<?php echo $card_data['rice_price']; ?>">
                <input type="hidden" id="rice_available" value="<?php echo $card_data['rice']; ?>">

                <label for="wheat_quantity">Wheat (Price: <?php echo $card_data['wheat_price']; ?>, Available: <span id="wheat_available"><?php echo $card_data['wheat']; ?></span>):</label>
                <input type="number" id="wheat_quantity" name="wheat_quantity" min="0" oninput="calculateTotal()">
                <input type="hidden" id="wheat_price" name="wheat_price" value="<?php echo $card_data['wheat_price']; ?>">
                <input type="hidden" id="wheat_available" value="<?php echo $card_data['wheat']; ?>">

                <label for="atta_quantity">Atta (Price: <?php echo $card_data['atta_price']; ?>, Available: <span id="atta_available"><?php echo $card_data['atta']; ?></span>):</label>
                <input type="number" id="atta_quantity" name="atta_quantity" min="0" oninput="calculateTotal()">
                <input type="hidden" id="atta_price" name="atta_price" value="<?php echo $card_data['atta_price']; ?>">
                <input type="hidden" id="atta_available" value="<?php echo $card_data['atta']; ?>">

                <h4 id="total_price">Total Price: 0.00</h4>

                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <input type="hidden" name="shop_id" value="<?php echo $shop_id; ?>">
                <input type="submit" value="Book Slot">
            </form>

        <?php else: ?>
            <p>Shop not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
