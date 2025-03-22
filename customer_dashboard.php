<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

$crops = $conn->query("SELECT DISTINCT crop FROM crop_prices");
$districts = $conn->query("SELECT DISTINCT district FROM crop_prices");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $crop = $_POST['crop'];
    $district = $_POST['district'];
    $quantity = $_POST['quantity'];

    $priceQuery = "SELECT price FROM crop_prices WHERE crop='$crop' AND district='$district' LIMIT 1";
    $priceResult = $conn->query($priceQuery);

    if ($priceResult->num_rows > 0) {
        $priceRow = $priceResult->fetch_assoc();
        $totalPrice = $quantity * $priceRow['price'];
        echo "<script>alert('Purchase successful! Total cost: ₹" . number_format($totalPrice, 2) . "');</script>";
    } else {
        echo "<script>alert('Crop not found in the selected district!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, <?= $_SESSION['username']; ?> (Customer)</h2>
        <a href="logout.php">Logout</a>

        <h3>Buy Crops</h3>
        <form method="POST">
            <select name="district" required>
                <option value="">Select District</option>
                <?php while ($row = $districts->fetch_assoc()) { ?>
                    <option value="<?= $row['district']; ?>"><?= $row['district']; ?></option>
                <?php } ?>
            </select>

            <select name="crop" required>
                <option value="">Select Crop</option>
                <?php while ($row = $crops->fetch_assoc()) { ?>
                    <option value="<?= $row['crop']; ?>"><?= $row['crop']; ?></option>
                <?php } ?>
            </select>

            <input type="number" name="quantity" placeholder="Enter Quantity" required>
            <button type="submit">Buy</button>
        </form>
    </div>
</body>
</html>
