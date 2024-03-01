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

// Fetch cardholders from the server based on the selected card type
$cardType = isset($_POST['card_type']) ? $_POST['card_type'] : ''; // Check if 'card_type' key exists
$sql = "SELECT * FROM cardholders WHERE card_type = '$cardType'";
$result = $conn->query($sql);

// Check if any cardholders found
if ($result->num_rows > 0) {
    // Output data of each row
    echo "<thead><tr><th>Select All</th><th>Name</th><th>Phone</th><th>Area</th><th>Card Type</th></tr></thead><tbody>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td><input type='checkbox' class='select-checkbox' name='selectedCardholders[]' value='" . $row["phone_number"] . "'></td>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["phone_number"] . "</td>";
        echo "<td>" . $row["area"] . "</td>";
        echo "<td>" . $row["card_type"] . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
} else {
    echo "<tr><td colspan='5'>No cardholders found</td></tr>";
}

$conn->close();
?>
