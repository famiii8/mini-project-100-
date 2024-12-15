<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "ration");

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch all bookings, joining with the users table to get customer names and shops
$query = "  SELECT * FROM feedback
";
$result = mysqli_query($con, $query);
if (!$result) {
    die("Error fetching bookings: " . mysqli_error($con));
}



$bookings = [];
while ($row = mysqli_fetch_assoc($result)) {
    $bookings[] = $row;
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Feedback</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .status {
            font-weight: bold;
        }
        .pending { color: orange; }
        .accepted { color: green; }
        .rejected { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h1>View Feedbacks</h1>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bookings)): ?>
                    <tr>
                        <td colspan="6">No feedback found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['email']); ?></td>
                            <td><?php echo htmlspecialchars($booking['msg']); ?></td>
                    </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="admindash.php" class="button">Back to Dashboard</a>
    </div>
</body>
</html>
