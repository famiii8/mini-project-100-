<?php
// Database connection
$con = mysqli_connect("localhost", "root", "", "ration");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch card details for editing
if (isset($_GET['id'])) {
    $card_id = $_GET['id'];
    $result = mysqli_query($con, "SELECT * FROM cards WHERE id = '$card_id'");
    $card = mysqli_fetch_assoc($result);
}

// Handle card update
if (isset($_POST['update_card'])) {
    $card_type = $_POST['card_type'];
    $rice_quantity = $_POST['rice_quantity'];
    $rice_price = $_POST['rice_price'];
    $atta_quantity = $_POST['atta_quantity'];
    $atta_price = $_POST['atta_price'];
    $wheat_quantity = $_POST['wheat_quantity'];
    $wheat_price = $_POST['wheat_price'];

    $sql = "UPDATE cards SET card_type='$card_type', rice='$rice_quantity', rice_price='$rice_price', 
            atta='$atta_quantity', atta_price='$atta_price', wheat='$wheat_quantity', wheat_price='$wheat_price' 
            WHERE id='$card_id'";
    
    if (mysqli_query($con, $sql)) {
        header("Location: card-management.php"); 
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($con);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="editcard.css">
    <title>Edit Card</title>
</head>
<body>
    <h2>Edit Ration Card</h2>
    <form method="post">
        <select name="card_type" required>
            <option value="white" <?php if($card['card_type'] == 'white') echo 'selected'; ?>>White</option>
            <option value="pink" <?php if($card['card_type'] == 'pink') echo 'selected'; ?>>Pink</option>
            <option value="yellow" <?php if($card['card_type'] == 'yellow') echo 'selected'; ?>>Yellow</option>
            <option value="blue" <?php if($card['card_type'] == 'blue') echo 'selected'; ?>>Blue</option>
        </select>
        <input type="number" name="rice_quantity" placeholder="Rice Quantity" value="<?php echo $card['rice']; ?>" required>
        <input type="number" name="rice_price" placeholder="Rice Price" value="<?php echo $card['rice_price']; ?>" required>
        <input type="number" name="atta_quantity" placeholder="Atta Quantity" value="<?php echo $card['atta']; ?>" required>
        <input type="number" name="atta_price" placeholder="Atta Price" value="<?php echo $card['atta_price']; ?>" required>
        <input type="number" name="wheat_quantity" placeholder="Wheat Quantity" value="<?php echo $card['wheat']; ?>" required>
        <input type="number" name="wheat_price" placeholder="Wheat Price" value="<?php echo $card['wheat_price']; ?>" required>
        <button type="submit" name="update_card">Update Card</button>
    </form>
    <a href="card-management.php">Cancel</a>
</body>
</html>
