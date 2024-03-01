<?php
// Database connection
$servername = "localhost";
$username = "root"; // Enter your MySQL username
$password = ""; // Enter your MySQL password
$dbname = "productdetails"; // Enter your database name
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and escape special characters to prevent SQL injection
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $area = mysqli_real_escape_string($conn, $_POST['area']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $product_received = mysqli_real_escape_string($conn, $_POST['product_received']);

    // Insert data into database
    $sql = "INSERT INTO product_status_data (name, area, phone, product_received) VALUES ('$name', '$area', '$phone', '$product_received')";
    if ($conn->query($sql) === TRUE) {
        echo "<p>New record created successfully</p>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
