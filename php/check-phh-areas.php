<?php
// Database connection
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

// Get data from the AJAX request
$fromDate = $_POST['from_date'] ?? '';
$toDate = $_POST['to_date'] ?? '';

// Convert dates to MySQL date format (YYYY-MM-DD)
$fromDate = !empty($fromDate) ? date('Y-m-d', strtotime($fromDate)) : null;
$toDate = !empty($toDate) ? date('Y-m-d', strtotime($toDate)) : null;

// Check if all PHH areas are booked within the specified date range
$allPHHAreasBooked = false;

if (!empty($fromDate) && !empty($toDate)) {
    // Query to check if all PHH areas are booked within the date range
    $sql = "SELECT COUNT(DISTINCT area) AS booked_areas_count FROM card_details WHERE card_type = 'PHH' AND from_date >= ? AND to_date <= ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $fromDate, $toDate);
    $stmt->execute();
    $stmt->bind_result($bookedAreasCount);
    $stmt->fetch();
    $stmt->close();

    // Get the total count of PHH areas
    $sql_total_phh_areas = "SELECT COUNT(DISTINCT area) AS total_phh_areas FROM cardholders WHERE card_type = 'PHH'";
    $result_total_phh_areas = $conn->query($sql_total_phh_areas);
    $row_total_phh_areas = $result_total_phh_areas->fetch_assoc();
    $totalPHHAreasCount = $row_total_phh_areas['total_phh_areas'];

    // Compare the count of booked areas with the total count of PHH areas
    if ($bookedAreasCount >= $totalPHHAreasCount) {
        $allPHHAreasBooked = true;
    }
}

// Close the database connection
$conn->close();

// Return the result as JSON
echo json_encode($allPHHAreasBooked);
?>
