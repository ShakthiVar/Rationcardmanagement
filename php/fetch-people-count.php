<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "details";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch people count for each area
$sql = "SELECT area, COUNT(*) AS count FROM cardholders GROUP BY area ORDER BY count DESC";
$result = $conn->query($sql);

$peopleCount = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $peopleCount[$row['area']] = $row['count'];
    }
}

echo json_encode($peopleCount);

$conn->close();
?>
