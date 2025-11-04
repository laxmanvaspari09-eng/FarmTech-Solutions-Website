<?php
// Database connection settings
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = "";     // Default XAMPP password is empty
$dbname = "user";   // Your database name

// Connect to MySQL (without selecting a database yet)
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the database if it does not exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    // echo "Database checked/created successfully<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Now select the database
$conn->select_db($dbname);

// Create the table if it doesn't exist
$table = "CREATE TABLE IF NOT EXISTS owner_reg (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(100) NOT NULL,
    Password VARCHAR(100) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    Number VARCHAR(15) NOT NULL,
    Location VARCHAR(100) NOT NULL,
    MachineType VARCHAR(100) NOT NULL
)";
if (!$conn->query($table)) {
    die("Error creating table: " . $conn->error);
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data safely
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $email = $_POST['email'] ?? '';
    $number = $_POST['number'] ?? '';
    $location = $_POST['location'] ?? '';
    $machineType = $_POST['machineType'] ?? '';

    // If "Others" is selected
    if ($machineType === "Others") {
        $machineType = $_POST['otherMachine'] ?? '';
    }

    // Prepare SQL statement
    $sql = "INSERT INTO owner_reg (Username, Password, Email, Number, Location, MachineType)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssssss", $username, $password, $email, $number, $location, $machineType);

        if ($stmt->execute()) {
            header("Location: http://localhost/farmtech/ow_home.html");
            exit();
        } else {
            echo "Error executing query: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// Close connection
$conn->close();
?>