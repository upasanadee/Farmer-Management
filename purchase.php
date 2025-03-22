<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'customer') {
    header("Location: index.php");
    exit();
}

$district = $_POST['district'];
$crop = $_POST['crop'];
$quantity = $_POST['quantity'];

$csvFile = 'crop_price_data.csv';
$totalPrice = 0;
$found = false;

if (($handle = fopen($csvFile, "r")) !== FALSE) {
    fgetcsv($handle);
    while (($row = fgetcsv($handle)) !== FALSE) {
        if ($row[0] == $district && $row[1] == $crop) {
            $price_per_quintal = $row[4];
            $totalPrice = ($price_per_quintal / 100) * $quantity;
            $found = true;
            break;
        }
    }
    fclose($handle);
}

if ($found): ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Purchase Confirmation</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="container">
            <h2>Purchase Successful!</h2>
            <p>Commodity: <?php echo htmlspecialchars($crop); ?></p>
            <p>Quantity: <?php echo htmlspecialchars($quantity); ?> kg</p>
            <p>Total Price: ₹<?php echo number_format($totalPrice, 2); ?></p>
            <a href="dashboard.php">Back to Dashboard</a>
        </div>
    </body>
    </html>
<?php else:
    echo "Selected crop not av
