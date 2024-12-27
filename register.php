<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="reg.css">
    <title>User Registration</title>
</head>
<body>
    <div class="logincontainer"> 
        <div class="parent">
            <form class="test" method="post" enctype="multipart/form-data">
                <span class="head"><b>User Registration</b></span><br>
                
                <label for="name">Name:</label>
                <input class="input1" type="text" id="name" placeholder="Enter Firstname" name="name" required><br>
                
                <label for="email">Email:</label>
                <input class="input1" type="email" id="email" placeholder="Enter Email" name="email" required><br>
                
                <label for="phno">Ph No:</label>
                <input class="input1" type="tel" id="phno" placeholder="Enter your Ph No" name="phno" required><br>
                
                <label for="address">Address:</label>
                <input class="input2" type="text" id="address" placeholder="Enter your address" name="address" required><br>
                
                <label for="pincode">Pincode:</label>
                <input class="input1" type="number" id="pincode" placeholder="Enter Pincode" name="pincode" required><br>
                
                <label for="rcard">Card Num:</label>
                <input class="input1" type="number" id="rcard" placeholder="Enter Card Num" name="rcard" required><br>
                
                <label for="card_color">Card Color:</label>
                <select name="card_color" id="card_color" class="input1" required>
                    <option value="white">White</option>
                    <option value="blue">Blue</option>
                    <option value="pink">Pink</option>
                    <option value="yellow">Yellow</option>
                </select><br>
                
                <label for="members">Number of Members in House:</label>
                <input class="input1" type="number" id="members" placeholder="Enter Number of Members" name="members" required><br>
                
                <label for="ration_card_image">Ration Card Image:</label>
                <input class="input1" type="file" id="ration_card_image" name="ration_card_image" accept="image/*" required><br>
                
                <label for="pwd">Password:</label>
                <input class="input1" type="password" id="pwd" placeholder="Enter Password" name="pwd" required><br>
                
                <button name="submit" type="submit" class="button1">REGISTER</button>
                
                <p class="abc">Already have an Account? <a href="login1.php">Login</a></p>
            </form>
        </div>
    </div>
</body>
</html>
<?php
$con = mysqli_connect("localhost", "root", "", "ration");
if (!$con) {
    echo "<script>alert('Database not connected');</script>";
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phno = $_POST['phno'];
    $address = $_POST['address'];
    $pincode = $_POST['pincode'];
    $rcard = $_POST['rcard'];
    $card_color = $_POST['card_color'];
    $members = $_POST['members'];
    $pwd = $_POST['pwd'];

    // Check if the email already exists in the users or login table
    $email_check_query = "SELECT * FROM users WHERE email = '$email' UNION SELECT * FROM login WHERE email = '$email'";
    $result = mysqli_query($con, $email_check_query);
    
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('This email is already registered. Please use a different email.'); window.location.href = 'register.php';</script>";
    } else {
        // File upload
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["ration_card_image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check file upload status
        if ($uploadOk == 0) {
            echo "<script>alert('Sorry, your file was not uploaded.')</script>";
        } else {
            if (move_uploaded_file($_FILES["ration_card_image"]["tmp_name"], $target_file)) {
                $sql = "INSERT INTO users(name, phno, email, address, pincode, rcardno, card_color, members, ration_card_image, password) VALUES ('$name','$phno','$email','$address','$pincode','$rcard','$card_color','$members','$target_file','$pwd')";
                $sql1 = "INSERT INTO login(email, password, usertype) VALUES ('$email','$pwd',0)";

                $data = mysqli_query($con, $sql);
                $data1 = mysqli_query($con, $sql1);

                if ($data && $data1) {
                    echo "<script>alert('User registration successful'); window.location.href = 'login.php';</script>";
                } else {
                    echo "<script>alert('User registration failed. Please try again.');</script>";
                }
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file.')</script>";
            }
        }
    }
}
?>