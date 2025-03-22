<?php
require_once 'db.php';

if (isset($_GET['district'])) {
    $district = $_GET['district'];
    $sql = "SELECT DISTINCT Crop FROM crop_prices WHERE District = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $district);
    $stmt->execute();
    $result = $stmt->get_result();

    $crops = [];
    while ($row = $result->fetch_assoc()) {
        $crops[] = $row['Crop'];
    }
    echo json_encode($crops);
}
?>
