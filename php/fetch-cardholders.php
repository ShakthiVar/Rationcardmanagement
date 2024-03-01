// Database connection
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "details";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from the request
$cardType = $_POST['cardType'] ?? '';
$area = $_POST['area'] ?? '';
$addarea = $_POST['addarea'] ?? '';
$fromDate = $_POST['from_date'] ?? '';
$toDate = $_POST['to_date'] ?? '';


if (!empty($cardType) && !empty($area)) {
    // Explode the area string to separate main area and additional area
    $areas = explode(", ", $area);
    $mainArea = $areas[0];
    $additionalArea = isset($areas[1]) ? $areas[1] : '';

    // Build the SQL query based on the main area and additional area
    $sql = "SELECT * FROM cardholders WHERE card_type = '$cardType' AND (area = '$mainArea' OR area = '$additionalArea')";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data of each row with checkboxes
        echo "<table><thead><tr><th><input type='checkbox' id='select-all'></th><th>Name</th><th>Phone Number</th><th>Card Type</th><th>Area</th></tr></thead><tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td><input type='checkbox' class='select-checkbox' value='" . $row["id"] . "'></td><td>" . $row["name"] . "</td><td>" . $row["phone_number"] . "</td><td>" . $row["card_type"] . "</td><td>" . $row["area"] . "</td></tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "No cardholders found for the selected card type and area.";
    }
} else {
    echo "Card type or area is not specified.";
}
$conn->close();
?>