<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="reg.css">
    <title>Document</title>
    <style>
        .test{
            height:500px;
        }
    </style>
</head>
<body>
    <div class="logincontainer"> 
    <div class="parent">
    <form class="test" method="post" enctype="multipart/form-data">
    <span class="head"><b>Feedback</b></span><br>
            <label>Name:</label> <input class="input1" type="text" placeholder="Enter Firstname" name="name" required><br>
            <label>Email:</label> <input class="input1" type="email" placeholder="Enter Email" name="email" required><br>
            <label>Message:</label><input class="input1" type="msg" placeholder="Enter your feedback" name="msg" required><br>
            <button name="submit" type="submit" class="button1">Submit</button>
        </form>
    </div>

</body>

</html>

<?php
$con=mysqli_connect("localhost","root","","ration");
if(!$con){
    echo "db not connected";
}
if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $msg = $_POST['msg'];
            $sql = "INSERT INTO `feedback`(`name`, `email`, `msg`) VALUES ('$name','$email','$msg')";
            
            $data = mysqli_query($con, $sql);
            
            if ($data) {
                echo "<script>alert('Thanku For your valuable feedback.'); window.location.href = 'login1.php';</script>";
            } else {
                echo "<script>alert(' Failed'); window.location.href = 'login1.php';</script>";
            }
            
        }

?>
