<?php
require '../assets/variables/sql.php';

header('Content-Type: application/json');


if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$sql = "SELECT upc, isrc, date, raw_view, raw_revenue FROM analytics";
$result = query($sql);

$analyticsData = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Decode JSON strings for raw_view and raw_revenue
        $row['raw_view'] = json_decode($row['raw_view'], true);
        $row['raw_revenue'] = json_decode($row['raw_revenue'], true);
        $analyticsData[] = $row;
    }
}

echo json_encode($analyticsData);

$conn->close();
?>