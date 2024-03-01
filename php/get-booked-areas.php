<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "schedules";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch booked areas from the database
$sql = "SELECT DISTINCT area FROM card_details WHERE CURDATE() BETWEEN from_date AND to_date";
$result = $conn->query($sql);

$bookedAreas = array();

if ($result && $result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $area = $row["area"];
        $bookedAreas[] = $area;
    }
} else {
    // No booked areas found
    $error_message = "No booked areas found. SQL error: " . $conn->error;
    echo json_encode(["error" => $error_message]);
    exit(); // Terminate the script after outputting the error message
}

// Close connection
$conn->close();

// Return booked areas as JSON
header('Content-Type: application/json');
echo json_encode($bookedAreas);
?>
