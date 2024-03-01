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

// Get message, fromDate, toDate, and cardType from POST request
$message = $_POST['message'];
$fromDate = $_POST['fromDate'];
$toDate = $_POST['toDate'];
$cardType = $_POST['cardType'];

// Merge fromDate and toDate into a single date string
$date = $fromDate . " to " . $toDate;

// Prepare SQL statement to insert data into the database
$sql = "INSERT INTO schedules (text, date, toDate,cardType) VALUES (?, ?, ?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $message, $date, $toDate,$cardType);

if ($stmt->execute()) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
