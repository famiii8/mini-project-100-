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
// Only process this block if the request is for updating the slots
if (isset($_GET['shop_id']) && isset($_GET['booking_date'])) {
    $shop_id = $_GET['shop_id'];
    $booking_date = $_GET['booking_date'];

    // Generate time slots
    $time_slots = [];
    $start_time = strtotime('11:00');
    $end_time = strtotime('18:00');
    for ($time = $start_time; $time <= $end_time; $time += 30 * 60) {
        $time_slots[] = date('H:i', $time);
    }

    // Fetch slot availability for the selected date
    $slot_availability = [];
    foreach ($time_slots as $slot) {
        $check_slot_query = "SELECT COUNT(*) as booking_count FROM bookings WHERE shop_id = '$shop_id' AND booking_date = '$booking_date' AND time_slot = '$slot'";
        $slot_result = mysqli_query($conn, $check_slot_query);
        $slot_data = mysqli_fetch_assoc($slot_result);
        $slot_availability[$slot] = $slot_data['booking_count'];
    }

    // Return only the table rows for the available slots
    echo '<tbody>';
    foreach ($time_slots as $slot) {
        echo '<tr>';
        echo '<td>' . $slot . '</td>';
        echo '<td>';
        echo $slot_availability[$slot] >= 5 ? 'Fully Booked' : (5 - $slot_availability[$slot]) . ' Slots Available';
        echo '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    
    exit; // End the PHP script after sending the response
}


// Fetch slot availability dynamically based on selected date
$selected_date = date('Y-m-d'); // Default to today's date
if (isset($_GET['booking_date'])) {
    $selected_date = $_GET['booking_date'];
}

$slot_availability = [];
foreach ($time_slots as $slot) {
    $check_slot_query = "SELECT COUNT(*) as booking_count FROM bookings WHERE shop_id = '$shop_id' AND booking_date = '$selected_date' AND time_slot = '$slot'";
    $slot_result = mysqli_query($conn, $check_slot_query);
    $slot_data = mysqli_fetch_assoc($slot_result);
    $slot_availability[$slot] = $slot_data['booking_count'];
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

        function updateSlots() {
    const bookingDate = document.getElementById('booking_date').value;
    const shopId = '<?php echo $shop_id; ?>';  // Assuming $shop_id is embedded in JavaScript

    if (bookingDate) {
        fetch(shop_detail.php?shop_id=${shopId}&booking_date=${bookingDate})
            .then(response => response.text())
            .then(data => {
                // Update the slots table body with the new slot data
                const slotsTableBody = document.querySelector('#slots_table tbody');
                slotsTableBody.innerHTML = data; // Only update the table body, not the form
            });
    }
}




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
                       required onchange="updateSlots()">
                
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

                <button type="submit" name="submit_booking">Confirm Booking</button>
            </form>

            <h2>Available Slots for <?php echo $selected_date; ?></h2>
            <table border="1" id="slots_table">
                <thead>
                    <tr>
                        <th>Time Slot</th>
                        <th>Availability</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($time_slots as $slot): ?>
                        <tr>
                            <td><?php echo $slot; ?></td>
                            <td>
                                <?php echo $slot_availability[$slot] >= 5 ? 'Fully Booked' : (5 - $slot_availability[$slot]) . ' Slots Available'; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Shop not found.</p>
        <?php endif; ?>
    </div>
</body>
</html>