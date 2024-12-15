<?php
// Database connection
$con = mysqli_connect("localhost", "root", "", "ration");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle card addition
if (isset($_POST['add_card'])) {
    $card_type = mysqli_real_escape_string($con, $_POST['card_type']);
    $created_at = date('Y-m-d H:i:s'); // Current timestamp
    $rice_quantity = mysqli_real_escape_string($con, $_POST['rice_quantity']);
    $rice_price = mysqli_real_escape_string($con, $_POST['rice_price']);
    $atta_quantity = mysqli_real_escape_string($con, $_POST['atta_quantity']);
    $atta_price = mysqli_real_escape_string($con, $_POST['atta_price']);
    $wheat_quantity = mysqli_real_escape_string($con, $_POST['wheat_quantity']);
    $wheat_price = mysqli_real_escape_string($con, $_POST['wheat_price']);

    $sql = "INSERT INTO cards (card_type, created_at, rice, rice_price, atta, atta_price, wheat, wheat_price) 
            VALUES ('$card_type', '$created_at', '$rice_quantity', '$rice_price', '$atta_quantity', '$atta_price', '$wheat_quantity', '$wheat_price')";
    mysqli_query($con, $sql);
}

// Handle card deletion
if (isset($_POST['delete_card'])) {
    $card_id = mysqli_real_escape_string($con, $_POST['card_id']);
    $sql = "DELETE FROM cards WHERE id = '$card_id'";
    mysqli_query($con, $sql);
}

// Fetch cards
$result = mysqli_query($con, "SELECT * FROM cards");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="staffmanagecard.css">
    <title>Card Management</title>
</head>
<body>
    <div class="sidebar">
        <h2>E-RATION MANAGEMENT SYSTEM</h2>
        <ul>
            <li><a href="staffdash.php">Dashboard</a></li>
            <li><a href="staffmanagecard.php">Manage Card</a></li>
            <li><a href="staffusermanage.php">Manage User</a></li>
            <li><a href="staff_slot.php">View Slot</a></li>
            <li><a href="index1.html">Logout</a></li>
        </ul>
    </div>

    <div class="content">
        <header>
            <h1>Card Management</h1>
        </header>

        <main>
            <h2>Manage Ration Cards</h2>
            <form method="post">
                <select name="card_type" required>
                    <option value="">Select Card Type</option>
                    <option value="white">White</option>
                    <option value="pink">Pink</option>
                    <option value="yellow">Yellow</option>
                    <option value="blue">Blue</option>
                </select>
                <input type="number" name="rice_quantity" placeholder="Rice Quantity" required>
                <input type="number" name="rice_price" placeholder="Rice Price" required>
                <input type="number" name="atta_quantity" placeholder="Atta Quantity" required>
                <input type="number" name="atta_price" placeholder="Atta Price" required>
                <input type="number" name="wheat_quantity" placeholder="Wheat Quantity" required>
                <input type="number" name="wheat_price" placeholder="Wheat Price" required>
                <button type="submit" name="add_card">Add Card</button>
            </form>

            <table>
                <tr>
                    <th>Card ID</th>
                    <th>Card Type</th>
                    <th>Created At</th>
                    <th>Rice Quantity</th>
                    <th>Rice Price</th>
                    <th>Atta Quantity</th>
                    <th>Atta Price</th>
                    <th>Wheat Quantity</th>
                    <th>Wheat Price</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['card_type']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <td><?php echo htmlspecialchars($row['rice']); ?></td>
                    <td><?php echo htmlspecialchars($row['rice_price']); ?></td>
                    <td><?php echo htmlspecialchars($row['atta']); ?></td>
                    <td><?php echo htmlspecialchars($row['atta_price']); ?></td>
                    <td><?php echo htmlspecialchars($row['wheat']); ?></td>
                    <td><?php echo htmlspecialchars($row['wheat_price']); ?></td>
                    <td>
                        <div class="btns">
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="card_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete_card">Delete</button>
                            </form>
                            <a href="edit-card.php?id=<?php echo $row['id']; ?>">Edit</a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </main>
    </div>

</body>
</html>
