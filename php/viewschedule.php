<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scheduleinfo";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get schedule data from POST request
$text = $_POST['text'];
$date = $_POST['date'];

// Prepare SQL statement to insert data into the database
$sql = "INSERT INTO schedules (text, date) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $text, $date);

if ($stmt->execute()) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
