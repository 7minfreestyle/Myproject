<?php
session_start();
include('db.php');

// Check if user is logged in, otherwise redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get inventory and sales data
$inventoryResult = $conn->query("SELECT * FROM inventory");
$salesResult = $conn->query("SELECT sales.id, inventory.product_name, sales.quantity_sold, sales.total, sales.date
                            FROM sales
                            JOIN inventory ON sales.product_id = inventory.id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 0; }
        .container { width: 80%; margin: 50px auto; padding: 20px; background: white; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; }
        table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #4CAF50; color: white; }
        .logout { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Dashboard</h2>

        <h3>Logged in as: <?= $_SESSION['username']; ?></h3>

        <h3>Inventory</h3>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $inventoryResult->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['product_name']; ?></td>
                        <td><?= $row['quantity']; ?></td>
                        <td>$<?= $row['price']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h3>Sales</h3>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity Sold</th>
                    <th>Total</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $salesResult->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['product_name']; ?></td>
                        <td><?= $row['quantity_sold']; ?></td>
                        <td>$<?= $row['total']; ?></td>
                        <td><?= $row['date']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>
