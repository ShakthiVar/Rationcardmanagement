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

// Check if all PHH-AAY areas are booked within the specified date range
$allPHHAAYAreasBooked = false;

if (!empty($fromDate) && !empty($toDate)) {
    // Query to check if all PHH-AAY areas are booked within the date range
    $sql = "SELECT COUNT(DISTINCT area) AS booked_areas_count FROM card_details WHERE card_type = 'PHH-AAY' AND from_date >= ? AND to_date <= ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $fromDate, $toDate);
    $stmt->execute();
    $stmt->bind_result($bookedAreasCount);
    $stmt->fetch();
    $stmt->close();

    // Get the total count of PHH-AAY areas
    $sql_total_phh_aay_areas = "SELECT COUNT(DISTINCT area) AS total_phh_aay_areas FROM cardholders WHERE card_type = 'PHH-AAY'";
    $result_total_phh_aay_areas = $conn->query($sql_total_phh_aay_areas);
    $row_total_phh_aay_areas = $result_total_phh_aay_areas->fetch_assoc();
    $totalPHHAAYAreasCount = $row_total_phh_aay_areas['total_phh_aay_areas'];

    // Compare the count of booked areas with the total count of PHH-AAY areas
    if ($bookedAreasCount >= $totalPHHAAYAreasCount) {
        $allPHHAAYAreasBooked = true;
    }
}

// Close the database connection
$conn->close();

// Return the result as JSON
echo json_encode($allPHHAAYAreasBooked);
?>
