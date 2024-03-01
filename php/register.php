<?php
header('Content-Type: application/json');

// Connect to the database (replace with your database connection code)
$conn = mysqli_connect("localhost", "root", "", "rationmessgae");

if ($conn === false) {
    echo json_encode(['error' => 'Could not connect to the database']);
    exit();
}

// Check if it's a registration or forgot password request
if(isset($_POST['action']) && $_POST['action'] === 'register') {
    // Registration process
    registerUser($conn);
} elseif(isset($_POST['action']) && $_POST['action'] === 'forgotPassword') {
    // Forgot password process
    forgotPassword($conn);
} else {
    echo json_encode(['error' => 'Invalid request']);
}

function registerUser($conn) {
    // Retrieve data from the AJAX request
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $shopName = mysqli_real_escape_string($conn, $_POST['shopName']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $contactInfo = mysqli_real_escape_string($conn, $_POST['contactInfo']);

    // Validate data (you should add more robust validation)
    if (empty($username) || empty($password) || empty($shopName) || empty($address) || empty($contactInfo)) {
        echo json_encode(['error' => 'Please fill in all the fields']);
        exit();
    }

    // Hash the password (use a stronger hashing algorithm in production)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind the statement
    $stmt = mysqli_prepare($conn, "INSERT INTO users (username, password, shop_name, address, contact_info) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, 'sssss', $username, $hashedPassword, $shopName, $address, $contactInfo);

    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['message' => 'Registration successful']);
    } else {
        echo json_encode(['error' => 'Registration failed']);
    }

    mysqli_stmt_close($stmt);
}

function forgotPassword($conn) {
    // Retrieve email address from the AJAX request
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Validate email address
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['error' => 'Invalid email address']);
        exit();
    }

    // Check if the email exists in the database (replace with your own logic)
    // Here, we assume a table named "users" with an "email" column
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // Email exists, proceed with password reset
        // Here you would generate a unique token, save it to the database,
        // and send an email to the user with a link to the password reset page
        // For simplicity, let's just echo a success message for now
        echo json_encode(['message' => 'Password reset email sent successfully']);
    } else {
        // Email does not exist in the database
        echo json_encode(['error' => 'Email not found']);
    }
}

// Close the database connection
mysqli_close($conn);
?>
