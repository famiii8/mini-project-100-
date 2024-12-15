<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "ration");

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$user_id = $_SESSION['user_id'];

// Fetch user details from the database
$query = "SELECT name, phno, email, address, pincode, rcardno, card_color FROM users WHERE usid = $user_id";
$result = mysqli_query($con, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="profile.css"> <!-- Include the same CSS file -->
</head>
<body>
    <div class="sidebar">
        <div class="navbar-title">E-RATION MANAGEMENT SYSTEM</div>
        <ul class="nav-menu">
            <li><a href="userdash.php">Dashboard</a></li>
            <li><a href="book_supplies.php">Book Supplies</a></li>
            <li><a href="slot_details.php">Slot Details</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="content">
        <div class="container">
            <h1>User Profile</h1>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($user['phno']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
            <p><strong>Pincode:</strong> <?php echo htmlspecialchars($user['pincode']); ?></p>
            <p><strong>Ration Card Number:</strong> <?php echo htmlspecialchars($user['rcardno']); ?></p>
            <p><strong>Card Color:</strong> <?php echo htmlspecialchars($user['card_color']); ?></p>
            <a class="button" href="edit_profile.php">Edit Profile</a>
        </div>
    </div>
</body>
</html>

<?php
mysqli_close($con);
?>
