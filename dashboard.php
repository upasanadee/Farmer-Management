<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

require_once 'db.php'; // Ensure this contains correct DB connection

$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <a href="logout.php" class="logout-btn">Logout</a>

        <?php if ($role == 'farmer'): ?>
            <h3>Crop Price List</h3>
            <table border="1">
                <tr>
                    <th>District</th>
                    <th>Crop</th>
                    <th>Market</th>
                    <th>Date</th>
                    <th>Price (INR/quintal)</th>
                </tr>
                <?php
                $sql = "SELECT * FROM crop_prices";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['District']}</td>
                                <td>{$row['Crop']}</td>
                                <td>{$row['Market']}</td>
                                <td>{$row['Date']}</td>
                                <td>".number_format($row['Price'], 2)."</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No data available.</td></tr>";
                }
                ?>
            </table>

        <?php else: ?>
            <h3>Purchase Crops</h3>
            <form action="purchase.php" method="POST">
                <label for="district">Select District:</label>
                <select name="district" id="district" required>
                    <option value="">Select District</option>
                    <?php
                    $districtQuery = "SELECT DISTINCT District FROM crop_prices";
                    $districts = $conn->query($districtQuery);
                    while ($row = $districts->fetch_assoc()) {
                        echo "<option value='{$row['District']}'>{$row['District']}</option>";
                    }
                    ?>
                </select>

                <label for="crop">Select Crop:</label>
                <select name="crop" id="crop" required>
                    <option value="">Select Crop</option>
                </select>

                <label for="quantity">Enter Quantity (in quintals):</label>
                <input type="number" name="quantity" min="1" placeholder="Enter Quantity" required>

                <button type="submit">Proceed to Buy</button>
            </form>

            <script>
                document.getElementById("district").addEventListener("change", function () {
                    let district = this.value;
                    let cropDropdown = document.getElementById("crop");
                    cropDropdown.innerHTML = '<option value="">Select Crop</option>';

                    if (district) {
                        fetch("fetch_crops.php?district=" + district)
                            .then(response => response.json())
                            .then(data => {
                                data.forEach(crop => {
                                    let option = document.createElement("option");
                                    option.value = crop;
                                    option.textContent = crop;
                                    cropDropdown.appendChild(option);
                                });
                            })
                            .catch(error => console.error("Error fetching crops:", error));
                    }
                });
            </script>
        <?php endif; ?>
    </div>
</body>
</html>
