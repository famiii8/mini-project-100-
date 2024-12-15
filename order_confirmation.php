<?php
session_start(); // Start the session

$conn = mysqli_connect("localhost", "root", "", "ration");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION['user_id'];

// Get shop ID from the form submission
$shop_id = $_POST['shop_id'];

// Get the selected supplies
$supplies = $_POST['supplies'];

// Prepare an array to hold order details
$order_details = [];

// Check if supplies are selected
if (isset($supplies) && !empty($supplies)) {
    foreach ($supplies as $supply) {
        // Split the supply identifier to get the type and shop ID
        list($type, $shop_id) = explode('_', $supply);

        // Fetch the price and available quantity for the selected supply type
        $supply_query = "SELECT * FROM cards WHERE card_type = (SELECT card_color FROM users WHERE usid = '$user_id')";
        $supply_result = mysqli_query($conn, $supply_query);
        $supply_data = mysqli_fetch_assoc($supply_result);

        switch ($type) {
            case 'rice':
                $quantity = $supply_data['rice']; // or get from user input if implemented
                $price = $supply_data['rice_price'];
                break;
            case 'atta':
                $quantity = $supply_data['atta']; // or get from user input
                $price = $supply_data['atta_price'];
                break;
            case 'wheat':
                $quantity = $supply_data['wheat']; // or get from user input
                $price = $supply_data['wheat_price'];
                break;
            default:
                continue;
        }

        // Store order details
        $order_details[] = [
            'type' => $type,
            'quantity' => $quantity,
            'price' => $price,
            'shop_id' => $shop_id,
        ];
    }

    // Process the order (e.g., insert into orders table)
    // Example query to insert order details (you'll need to adjust according to your schema)
    foreach ($order_details as $order) {
        $insert_query = "INSERT INTO orders (user_id, shop_id, supply_type, quantity, price) VALUES ('$user_id', '$order[shop_id]', '$order[type]', '$order[quantity]', '$order[price]')";
        mysqli_query($conn, $insert_query);
    }

    echo "<h1>Order Confirmation</h1>";
    echo "<p>Your order has been placed successfully!</p>";
    echo "<h2>Order Details:</h2>";
    echo "<ul>";
    foreach ($order_details as $order) {
        echo "<li>{$order['quantity']} units of {$order['type']} for ₹{$order['price']} from shop ID {$order['shop_id']}</li>";
    }
    echo "</ul>";
} else {
    echo "<h1>No supplies selected</h1>";
}

mysqli_close($conn);
?>
