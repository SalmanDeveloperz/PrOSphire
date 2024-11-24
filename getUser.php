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

$id = $_GET['id'];
$sql = "SELECT * FROM users WHERE id=$id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output the data of the user
    $user = $result->fetch_assoc();
    echo json_encode($user);
} else {
    echo "No user found";
}

$conn->close();
?>
