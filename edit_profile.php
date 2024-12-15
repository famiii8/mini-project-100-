<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "ration");

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details from the database
$query = "SELECT name, phno, email, address, pincode, rcardno, card_color FROM users WHERE usid = $user_id";
$result = mysqli_query($con, $query);
if (!$result) {
    die("Error fetching user details: " . mysqli_error($con));
}
$user = mysqli_fetch_assoc($result);

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $phno = mysqli_real_escape_string($con, $_POST['phno']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $pincode = mysqli_real_escape_string($con, $_POST['pincode']);
    $rcardno = mysqli_real_escape_string($con, $_POST['rcardno']);
    $card_color = mysqli_real_escape_string($con, $_POST['card_color']);

    // Update user details in the database
    $update_query = "UPDATE users SET name = '$name', phno = '$phno', email = '$email', address = '$address', pincode = '$pincode', rcardno = '$rcardno', card_color = '$card_color' WHERE usid = $user_id";
    
    if (!mysqli_query($con, $update_query)) {
        die("Error updating user: " . mysqli_error($con));
    }

    // Get the old email before updating
    $old_email_query = "SELECT email FROM users WHERE usid = $user_id";
    $old_email_result = mysqli_query($con, $old_email_query);
    
    if (!$old_email_result) {
        die("Error fetching old email: " . mysqli_error($con));
    }

    $old_email_row = mysqli_fetch_assoc($old_email_result);
    $old_email = $old_email_row['email'];

    // Check if the old email exists in the login table
    $check_email_query = "SELECT * FROM login WHERE email = '$old_email'";
    $check_email_result = mysqli_query($con, $check_email_query);
    
    if (mysqli_num_rows($check_email_result) > 0) {
        // Update the email in the login table
        $login_update_query = "UPDATE login SET email = '$email' WHERE email = '$old_email'";
        
        if (!mysqli_query($con, $login_update_query)) {
            die("Error updating login table: " . mysqli_error($con));
        }
    } else {
        echo "Old email not found in login table: $old_email";
    }

    // Redirect to profile page to see changes
    header('Location: profile.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label, input {
            display: block;
            margin-bottom: 10px;
            width: 100%;
        }
        input[type="text"], input[type="email"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .button {
            margin-top: 20px;
            text-align: center;
            display: block;
            text-decoration: none;
            color: white;
            background-color: #28a745;
            padding: 10px;
            border-radius: 5px;
        }
        .button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Profile</h1>

        <form method="POST" action="">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

            <label for="phno">Phone Number:</label>
            <input type="text" id="phno" name="phno" value="<?php echo htmlspecialchars($user['phno']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>

            <label for="pincode">Pincode:</label>
            <input type="text" id="pincode" name="pincode" value="<?php echo htmlspecialchars($user['pincode']); ?>" required>

            <label for="rcardno">Ration Card Number:</label>
            <input type="text" id="rcardno" name="rcardno" value="<?php echo htmlspecialchars($user['rcardno']); ?>" required>

            <label for="card_color">Card Color:</label>
            <input type="text" id="card_color" name="card_color" value="<?php echo htmlspecialchars($user['card_color']); ?>">

            <input type="submit" value="Update Profile">
        </form>

        <a class="button" href="profile.php">Cancel</a>
    </div>
</body>
</html>

<?php
mysqli_close($con);
?>
