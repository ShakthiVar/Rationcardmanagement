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

// Get schedule ID from POST request
$scheduleId = $_POST['id'];

// Prepare SQL statement to delete data from the database
$sql = "DELETE FROM schedules WHERE id = '$scheduleId'";

if ($conn->query($sql) === TRUE) {
    // Data deleted successfully
    echo "Schedule deleted from database successfully";
} else {
    // Error occurred while deleting data
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close database connection
$conn->close();
?>
