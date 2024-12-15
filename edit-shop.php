<?php
$con = mysqli_connect("localhost", "root", "", "ration");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch the shop details to pre-fill the form
if (isset($_GET['email'])) {
    $email = $_GET['email'];
    $query = "SELECT * FROM shops WHERE email = '$email'";
    $result = mysqli_query($con, $query);
    $shop = mysqli_fetch_assoc($result);
}

// Update the shop details
if (isset($_POST['edit_user'])) {
    $original_email = $_POST['original_email'];  // Hidden field with the original email
    $shop_name = $_POST['shop_name'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];
    $shop_owner = $_POST['shop_owner'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $pin = $_POST['pin'];

    // Update the shops table
    $shop_sql = "UPDATE shops SET 
                    shop_name = '$shop_name', 
                    address = '$address', 
                    contact_number = '$contact_number', 
                    shop_owner = '$shop_owner', 
                    email = '$email', 
                    password = '$password', 
                    pincode = '$pin' 
                 WHERE email = '$original_email'";
    mysqli_query($con, $shop_sql);

    // Update the login table
    $login_sql = "UPDATE login SET email = '$email', password = '$password' WHERE email = '$original_email'";
    mysqli_query($con, $login_sql);

    // Redirect back to the shop management page after update
    header("Location: shop-management.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Shop</title>
</head>
<body>
    <h2>Edit Shop</h2>
    <form method="post">
        <input type="hidden" name="original_email" value="<?php echo $shop['email']; ?>"> <!-- Hidden original email -->
        <input type="text" name="shop_name" placeholder="Shop Name" value="<?php echo $shop['shop_name']; ?>" required>
        <input type="text" name="address" placeholder="Address" value="<?php echo $shop['address']; ?>" required>
        <input type="text" name="pin" placeholder="Pin code" value="<?php echo $shop['pincode']; ?>" required>
        <input type="text" name="contact_number" placeholder="Contact Number" value="<?php echo $shop['contact_number']; ?>" required>
        <input type="text" name="shop_owner" placeholder="Shop Owner Name" value="<?php echo $shop['shop_owner']; ?>" required>
        <input type="email" name="email" placeholder="Email" value="<?php echo $shop['email']; ?>" required>
        <input type="password" name="password" placeholder="Password" value="<?php echo $shop['password']; ?>" required>
        <button type="submit" name="edit_user">Update Shop</button>
    </form>
</body>
</html>
