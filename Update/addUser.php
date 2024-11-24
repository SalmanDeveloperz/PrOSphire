<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the POST data
$username = $_POST['username'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];

// Insert new user into the database
$sql = "INSERT INTO users (username, email, phone, address) VALUES ('$username', '$email', '$phone', '$address')";

if ($conn->query($sql) === TRUE) {
    // Get the inserted user data
    $id = $conn->insert_id;
    $created_at = date("Y-m-d H:i:s"); // Assuming you want to set the current timestamp
    $updated_at = $created_at;

    // Prepare response data
    $newUser = [
        'id' => $id,
        'username' => $username,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'created_at' => $created_at,
        'updated_at' => $updated_at
    ];

    // Send response as JSON
    echo json_encode($newUser);
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
