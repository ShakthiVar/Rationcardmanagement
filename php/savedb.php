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

// Get the data from the POST request
$cardType = $_POST['cardType'];
$mainArea = $_POST['area'];
$fromDate = $_POST['from_date'];
$toDate = $_POST['to_date'];
$additionalAreas = isset($_POST['additional_areas']) ? $_POST['additional_areas'] : [];

// Function to insert data into the database
function insertData($conn, $cardType, $area, $fromDate, $toDate) {
    $stmt = $conn->prepare("INSERT INTO card_details (card_type, area, from_date, to_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $cardType, $area, $fromDate, $toDate);
    if ($stmt->execute()) {
        return true;
    } else {
        return "Error: " . $stmt->error;
    }
}

// Check if the same data already exists in the database
$sql = "SELECT * FROM card_details WHERE card_type = ? AND area = ? AND from_date = ? AND to_date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $cardType, $mainArea, $fromDate, $toDate);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Data already exists, return a message or status indicating duplicate entry
    echo "Duplicate entry";
} else {
    // Data does not exist, proceed with insertion
    $errors = [];

    // Insert main area
    $insertMainArea = insertData($conn, $cardType, $mainArea, $fromDate, $toDate);
    if ($insertMainArea !== true) {
        $errors[] = $insertMainArea;
    }

    // Insert additional areas
    foreach ($additionalAreas as $additionalArea) {
        $insertAdditionalArea = insertData($conn, $cardType, $additionalArea, $fromDate, $toDate);
        if ($insertAdditionalArea !== true) {
            $errors[] = $insertAdditionalArea;
        }
    }

    if (empty($errors)) {
        // Return success message if no errors occurred
        echo "Data inserted successfully";
    } else {
        // Return errors if any
        echo implode("<br>", $errors);
    }
}

// Close the database connection
$stmt->close();
$conn->close();
?>
