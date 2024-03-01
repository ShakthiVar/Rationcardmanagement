<?php

// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "rationmessgae");

if ($conn === false) {
    echo json_encode(['message' => 'Could not connect to the database']);
    exit();
}

// Get the entered username and password
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Use prepared statement to prevent SQL injection
$sql = "SELECT username, password FROM users WHERE username=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);

// Get the result
$result = mysqli_stmt_get_result($stmt);

if ($result) {
    // Check the number of rows returned
    if (mysqli_num_rows($result) >= 1) {
        // Fetch user details
        $row = mysqli_fetch_assoc($result);

        // Verify hashed password
        if (password_verify($password, $row['password'])) {
            // Password is correct
            echo json_encode(['message' => 'Login successful']);
        } else {
            // Password is incorrect
            echo json_encode(['message' => 'Login failed. Incorrect username or password.']);
        }
    } else {
        // No user found with the provided username
        echo json_encode(['message' => 'Login failed. Incorrect username or password.']);
    }
} else {
    // Error in executing the query
    echo json_encode(['message' => 'Error: ' . mysqli_error($conn)]);
}

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
