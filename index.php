<?php
// Database configuration
$servername = "localhost";
$username = "root"; // default XAMPP username
$password = "";     // default XAMPP password (leave empty)
$dbname = "user";

// Step 1: Connect to MySQL (without selecting a database)
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Create the database if it does not exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) !== TRUE) {
    die("Error creating database: " . $conn->error);
}

// Step 3: Select the database
$conn->select_db($dbname);

// Step 4: Create the 'reg' table if it does not exist
$table = "CREATE TABLE IF NOT EXISTS reg (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(100) NOT NULL,
    Password VARCHAR(255) NOT NULL
)";
if (!$conn->query($table)) {
    die("Error creating table: " . $conn->error);
}

// Step 5: Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Optional: Enable password hashing for security
    // $password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO reg (Username, Password) VALUES (?, ?)");
    
    if ($stmt) {
        $stmt->bind_param("ss", $username, $password);

        // Execute the statement
        if ($stmt->execute()) {
            header("Location: home.html"); // Redirect to next page
            exit();
        } else {
            echo "Error executing query: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// Step 6: Close connection
$conn->close();
?>