<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'farmer') {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT * FROM crop_prices");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Farmer Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, <?= $_SESSION['username']; ?> (Farmer)</h2>
        <a href="logout.php">Logout</a>
        <h3>Crop Price List</h3>
        <table border="1" width="100%">
            <tr>
                <th>District</th>
                <th>Crop</th>
                <th>Market</th>
                <th>Date</th>
                <th>Price</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['district']; ?></td>
                    <td><?= $row['crop']; ?></td>
                    <td><?= $row['market']; ?></td>
                    <td><?= $row['date']; ?></td>
                    <td>₹<?= number_format($row['price'], 2); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>
