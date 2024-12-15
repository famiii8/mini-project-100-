<?php
// user-management.php
$con = mysqli_connect("localhost", "root", "", "ration");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle user deletion
if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];

    // Fetch the user's email from the `users` table to also delete from `login` table
    $email_query = "SELECT email FROM users WHERE usid = '$user_id'";
    $email_result = mysqli_query($con, $email_query);
    $email_row = mysqli_fetch_assoc($email_result);
    $email = $email_row['email'];

    // Delete from the `users` table
    $delete_user_sql = "DELETE FROM users WHERE usid = '$user_id'";
    mysqli_query($con, $delete_user_sql);

    // Delete from the `login` table
    $delete_login_sql = "DELETE FROM login WHERE email = '$email'";
    mysqli_query($con, $delete_login_sql);

    // Redirect to prevent re-executing the delete operation on page refresh
    header("Location: user-management.php");
    exit();
}

$result = mysqli_query($con, "SELECT * FROM users");
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
        <h2>Manage Users</h2>

        <table>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Action</th> <!-- New Action column -->
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['usid']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phno']; ?></td>
                <td>
                    <a href="user-management.php?delete=<?php echo $row['usid']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                </td> <!-- Delete button -->
            </tr>
            <?php endwhile; ?>
        </table>
    </main>

    <footer>
        <p>&copy; 2024 Ration Shop Management. All rights reserved.</p>
    </footer>
</body>
</html>
