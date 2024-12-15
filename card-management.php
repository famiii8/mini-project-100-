<?php
// Database connection
$con = mysqli_connect("localhost", "root", "", "ration");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle card addition
if (isset($_POST['add_card'])) {
    $card_type = $_POST['card_type'];
    $created_at = date('Y-m-d H:i:s'); // Current timestamp
    $rice_quantity = $_POST['rice_quantity'];
    $rice_price = $_POST['rice_price'];
    $atta_quantity = $_POST['atta_quantity'];
    $atta_price = $_POST['atta_price'];
    $wheat_quantity = $_POST['wheat_quantity'];
    $wheat_price = $_POST['wheat_price'];

    $sql = "INSERT INTO cards (card_type, created_at, rice, rice_price, atta, atta_price, wheat, wheat_price) 
            VALUES ('$card_type', '$created_at', '$rice_quantity', '$rice_price', '$atta_quantity', '$atta_price', '$wheat_quantity', '$wheat_price')";
    mysqli_query($con, $sql);
}

// Handle card deletion
if (isset($_POST['delete_card'])) {
    $card_id = $_POST['card_id'];
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
    <link rel="stylesheet" href="cardmanage.css">
    <title>Card Management</title>
</head>
<body>
    <header>
        <h1>Card Management</h1>
        <nav>
        <nav>
            <ul>
                <li><a href="admindash.php">Dashboard</a></li>
                <li><a href="shop-management.php">Shop Management</a></li>
                <li><a href="card-management.php">Card Management</a></li>
            </ul>
        </nav>
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
                <td><?php echo $row['card_type']; ?></td>
                <td><?php echo $row['created_at']; ?></td>
                <td><?php echo $row['rice']; ?></td>
                <td><?php echo $row['rice_price']; ?></td>
                <td><?php echo $row['atta']; ?></td>
                <td><?php echo $row['atta_price']; ?></td>
                <td><?php echo $row['wheat']; ?></td>
                <td><?php echo $row['wheat_price']; ?></td>
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

    <footer>
        <p>&copy; 2024 Ration Shop Management. All rights reserved.</p>
    </footer>
</body>
</html>
