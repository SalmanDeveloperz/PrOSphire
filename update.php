<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    // Get data from POST
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Update query
    $sql = "UPDATE users SET username='$username', email='$email', phone='$phone', address='$address' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "User updated successfully";
        header("Location: index.php"); // Redirect to index.php after update
    } else {
        echo "Error updating user: " . $conn->error;
    }
}

$conn->close();
?>
