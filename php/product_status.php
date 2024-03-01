<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('../img/goods.jpg');
            background-size: cover;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            width: 80%; /* Increased width */
            max-width: 800px; /* Limit maximum width */
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Product Status</h2>
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
        
        // Retrieve form data from database
        $sql_yes = "SELECT name, area, phone,product_received FROM product_status_data WHERE product_received = 'Yes'";
        $result_yes = $conn->query($sql_yes);

        if ($result_yes->num_rows > 0) {
            echo "<h3>Product Received - Yes</h3>";
            echo "<table>";
            echo "<tr><th>Name</th><th>Area</th><th>Phone</th><th>Product_Received</th></tr>";
            while ($row = $result_yes->fetch_assoc()) {
                echo "<tr><td>{$row['name']}</td><td>{$row['area']}</td><td>{$row['phone']}</td><td>{$row['product_received']}</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No data available for 'Product Received - Yes'.</p>";
        }

        $sql_no = "SELECT name, area, phone,product_received FROM product_status_data WHERE product_received = 'No'";
        $result_no = $conn->query($sql_no);

        if ($result_no->num_rows > 0) {
            echo "<h3>Product Received - No</h3>";
            echo "<table>";
            echo "<tr><th>Name</th><th>Area</th><th>Phone</th><th>Product_Received</th></tr>";
            while ($row = $result_no->fetch_assoc()) {
                echo "<tr><td>{$row['name']}</td><td>{$row['area']}</td><td>{$row['phone']}</td><td>{$row['product_received']}</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No data available for 'Product Received - No'.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
