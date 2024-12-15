<?php
session_start(); // Start the session

$con = mysqli_connect("localhost", "root", "", "ration");
if (!$con) {
    die("Database connection failed.");
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check login credentials
    $sql = "SELECT * FROM login WHERE email='$email' AND `password`='$password'";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $login_data = mysqli_fetch_assoc($result);

            if ($login_data['usertype'] == 0) {
                $user_sql = "SELECT * FROM users WHERE email='$email'";
                $user_result = mysqli_query($con, $user_sql);

                if ($user_result && mysqli_num_rows($user_result) > 0) {
                    $user_data = mysqli_fetch_assoc($user_result);
                    $_SESSION['user_id'] = $user_data['usid'];
                    $_SESSION['card_color'] = $user_data['card_color'];
                    header('Location: userdash.php');
                    exit();
                } else {
                    echo "<script>alert('User details not found.');</script>";
                }
            } elseif ($login_data['usertype'] == 1) {

                $shop_sql = "SELECT * FROM shops WHERE email='$email'";
                $shop_result = mysqli_query($con, $shop_sql);

                if ($shop_result && mysqli_num_rows($shop_result) > 0) {
                    $shop_data = mysqli_fetch_assoc($shop_result);
                    // Store shop ID in session
                    $_SESSION['shop_id'] = $shop_data['id']; // Shop ID from shop table
                    header('Location: staffdash.php');
                    exit();
                } else {
                    echo "<script>alert('Shop details not found.');</script>";
                }
            } elseif ($login_data['usertype'] == 3) {
                header('Location: admindash.php');
                exit();            
            }
        }
    } 


mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login1.css"> <!-- Optional CSS for styling -->
    <title>Login Page</title>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-title">E-RATION MANAGEMENT SYSTEM</div>
        <ul class="nav-menu">
            <li><a href="./index1.html"><b>Home</b></a></li>
            <li><a href="./fee.php"><b>Feedback</b></a></li>
        </ul>
    </nav>

    <div class="logincontainer">
        <form class="loginform" action="" method="post">
            <h1>E-RATION</h1>
            <input class="doc1" type="text" name="email" placeholder="Email" required>
            <input class="doc1" type="password" name="password" placeholder="Password" required>
            <input class="LoginButton" type="submit" name="submit" value="LOGIN">
            <div class="signup-link">
                <p>Don't have an account? <a href="./register.php">Register</a></p>
            </div>
        </form>
    </div>
</body>
</html>
