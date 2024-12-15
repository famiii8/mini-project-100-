<?php
$con = mysqli_connect("localhost", "root", "", "ration");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Add shop logic
if (isset($_POST['add_user'])) {
    $shop_name = $_POST['shop_name'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];
    $shop_owner = $_POST['shop_owner'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $pin = $_POST['pin'];

    // Insert into shops table
    $shop_sql = "INSERT INTO shops (shop_name, address, contact_number, created_at, shop_owner, email, password, pincode) 
                 VALUES ('$shop_name', '$address', '$contact_number', NOW(), '$shop_owner', '$email', '$password', '$pin')";
    mysqli_query($con, $shop_sql);

    // Insert into login table
    $login_sql = "INSERT INTO login (email, password, usertype) VALUES ('$email', '$password', 1)";
    mysqli_query($con, $login_sql);
}

// Delete shop logic
if (isset($_GET['delete'])) {
    $email = $_GET['delete'];

    // Delete from shops table
    $delete_shop_sql = "DELETE FROM shops WHERE email = '$email'";
    mysqli_query($con, $delete_shop_sql);

    // Delete from login table
    $delete_login_sql = "DELETE FROM login WHERE email = '$email'";
    mysqli_query($con, $delete_login_sql);

    header("Location: shop-management.php");
    exit();
}

// Edit shop logic
if (isset($_POST['edit_user'])) {
    $original_email = $_POST['original_email'];
    $shop_name = $_POST['shop_name'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];
    $shop_owner = $_POST['shop_owner'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $pin = $_POST['pin'];

    // Update shops table
    $shop_sql = "UPDATE shops SET shop_name = '$shop_name', address = '$address', contact_number = '$contact_number', 
                 shop_owner = '$shop_owner', email = '$email', password = '$password', pincode = '$pin' 
                 WHERE email = '$original_email'";
    mysqli_query($con, $shop_sql);

    // Update login table (based on original email)
    $login_sql = "UPDATE login SET email = '$email', password = '$password' WHERE email = '$original_email'";
    mysqli_query($con, $login_sql);

    header("Location: shop-management.php");
    exit();
}

// Fetch shops data
$result = mysqli_query($con, "SELECT * FROM shops");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="usermanage.css">
</head>
<body>
    <header>
        <h1>User Management</h1>
        <nav>
            <ul>
                <li><a href="admindash.php">Dashboard</a></li>
                <li><a href="shop-management.php">Shop Management</a></li>
                <li><a href="card-management.php">Card Management</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Manage Shops</h2>
        <form method="post">
            <input type="text" name="shop_name" placeholder="Shop Name" required>
            <input type="text" name="address" placeholder="Address" required>
            <input type="text" name="pin" placeholder="Pin code" required>
            <input type="text" name="contact_number" placeholder="Contact Number" required>
            <input type="text" name="shop_owner" placeholder="Shop Owner Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="add_user">Add Shop</button>
        </form>

        <table>
            <tr>
                <th>Shop ID</th>
                <th>Shop Name</th>
                <th>Address</th>
                <th>Contact Number</th>
                <th>Shop Owner</th>
                <th>Email</th>
                <th>Pincode</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['shop_name']; ?></td>
                <td><?php echo $row['address']; ?></td>
                <td><?php echo $row['contact_number']; ?></td>
                <td><?php echo $row['shop_owner']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['pincode']; ?></td>
                <td>
                    <a href="edit-shop.php?email=<?php echo $row['email']; ?>">Edit</a> |
                    <a href="shop-management.php?delete=<?php echo $row['email']; ?>" onclick="return confirm('Are you sure you want to delete this shop?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </main>

    <footer>
        <p>&copy; 2024 Ration Shop Management. All rights reserved.</p>
    </footer>
</body>
</html>
