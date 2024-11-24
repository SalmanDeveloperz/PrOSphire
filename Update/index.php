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

// Fetch users
$sql = "SELECT * FROM users ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>

    <!-- Include FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2 id="formHeader">Enter User</h2>
<form id="editForm" method="POST">
    <input type="hidden" name="id" id="userId">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required><br>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required><br>
    <label for="phone">Phone:</label>
    <input type="text" name="phone" id="phone" required><br>
    <label for="address">Address:</label>
    <input type="text" name="address" id="address" required><br>
    <input type="submit" id="submitButton" value="Add User">
</form>

<h2>Users List</h2>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Address</th>
        <th>Created At</th>
        <th>Updated At</th>
        <th>Action</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()) { ?>
        <tr id="user_<?php echo $row['id']; ?>">
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <td><?php echo $row['address']; ?></td>
            <td><?php echo $row['created_at']; ?></td>
            <td><?php echo $row['updated_at']; ?></td>
            <td>
                <!-- Edit button with pencil icon -->
                <button onclick="editUser(<?php echo $row['id']; ?>)">
                    <i class="fas fa-pencil-alt"></i> Edit
                </button>
                
                <!-- Delete button with trash icon -->
                <button onclick="deleteUser(<?php echo $row['id']; ?>)">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </td>
        </tr>
    <?php } ?>
</table>

<script>
    // Function to fill the edit form with user data
    function editUser(id) {
        // Change the header text and button text
        document.getElementById("formHeader").innerText = "Edit User";
        document.getElementById("submitButton").value = "Update";

        // Fetch user data by id via AJAX
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "getUser.php?id=" + id, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                const user = JSON.parse(xhr.responseText);
                // Fill the form with user data
                document.getElementById("userId").value = user.id;
                document.getElementById("username").value = user.username;
                document.getElementById("email").value = user.email;
                document.getElementById("phone").value = user.phone;
                document.getElementById("address").value = user.address;
            }
        };
        xhr.send();
    }

    // Function to handle deleting a user
    function deleteUser(id) {
        if (confirm("Are you sure you want to delete this user?")) {
            // Send AJAX request to delete the user
            const xhr = new XMLHttpRequest();
            xhr.open("GET", "deleteUser.php?id=" + id, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert("User deleted successfully!");
                    document.getElementById('user_' + id).remove(); // Remove the row from the table
                }
            };
            xhr.send();
        }
    }

    // Handle form submission via AJAX to add new user
    document.getElementById("editForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent the form from submitting normally

        const username = document.getElementById("username").value;
        const email = document.getElementById("email").value;
        const phone = document.getElementById("phone").value;
        const address = document.getElementById("address").value;

        // Validation to check if the fields are not empty
        if (username === "" || email === "" || phone === "" || address === "") {
            alert("Please fill in all fields!");
            return;
        }

        // Additional validation for email format
        const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!emailPattern.test(email)) {
            alert("Please enter a valid email address!");
            return;
        }

        // Send form data to the server via AJAX
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "addUser.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        const data = `username=${username}&email=${email}&phone=${phone}&address=${address}`;

        xhr.onload = function() {
            if (xhr.status === 200) {
                // Parse the response and add the new user row to the table
                const newUser = JSON.parse(xhr.responseText);
                const table = document.querySelector("table");
                const row = table.insertRow(1); // Insert new row at the top
                row.innerHTML = `
                    <td>${newUser.id}</td>
                    <td>${newUser.username}</td>
                    <td>${newUser.email}</td>
                    <td>${newUser.phone}</td>
                    <td>${newUser.address}</td>
                    <td>${newUser.created_at}</td>
                    <td>${newUser.updated_at}</td>
                    <td>
                        <button onclick="editUser(${newUser.id})">
                            <i class="fas fa-pencil-alt"></i> Edit
                        </button>
                        <button onclick="deleteUser(${newUser.id})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                `;

                // Clear the input fields after adding the user
                document.getElementById("editForm").reset();
            }
        };

        xhr.send(data);
    });
</script>

</body>
</html>
