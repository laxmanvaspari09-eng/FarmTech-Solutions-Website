<?php
// -----------------------------
// DATABASE CONFIGURATION
// -----------------------------
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = "";     // Default XAMPP password (usually empty)
$dbname = "user";   // Database name

// -----------------------------
// CREATE CONNECTION TO MYSQL SERVER
// -----------------------------
$conn = new mysqli($servername, $username, $password);

// Check server connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// -----------------------------
// CREATE DATABASE IF NOT EXISTS
// -----------------------------
$sql = "CREATE DATABASE IF NOT EXISTS `$dbname`";
if ($conn->query($sql) !== TRUE) {
    die("Error creating database: " . $conn->error);
}

// Select database
$conn->select_db($dbname);

// -----------------------------
// CREATE TABLES IF NOT EXISTS
// -----------------------------
$reg_table = "CREATE TABLE IF NOT EXISTS reg (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Email VARCHAR(100),
    FullName VARCHAR(100)
)";

$owner_table = "CREATE TABLE IF NOT EXISTS owner_reg (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    Email VARCHAR(100),
    BusinessName VARCHAR(100)
)";

$conn->query($reg_table);
$conn->query($owner_table);

// -----------------------------
// INSERT SAMPLE USERS IF EMPTY
// -----------------------------
$check_reg = $conn->query("SELECT * FROM reg");
if ($check_reg->num_rows == 0) {
    $conn->query("INSERT INTO reg (Username, Password, Email, FullName)
                  VALUES ('testuser', '1234', 'user@test.com', 'John Doe')");
}

$check_owner = $conn->query("SELECT * FROM owner_reg");
if ($check_owner->num_rows == 0) {
    $conn->query("INSERT INTO owner_reg (Username, Password, Email, BusinessName)
                  VALUES ('owner1', 'admin123', 'owner@test.com', 'FarmFresh')");
}

// -----------------------------
// HANDLE LOGIN FORM SUBMISSION
// -----------------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_input = $_POST['username'];
    $password_input = $_POST['password'];

    // Check in 'reg' table
    $stmt = $conn->prepare("SELECT * FROM reg WHERE Username = ? AND Password = ?");
    $stmt->bind_param("ss", $username_input, $password_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: http://localhost/farmtech/home.html"); // Redirect normal user
        exit;
    } else {
        // Check in 'owner_reg' table
        $stmt = $conn->prepare("SELECT * FROM owner_reg WHERE Username = ? AND Password = ?");
        $stmt->bind_param("ss", $username_input, $password_input);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            header("Location: http://localhost/farmtech/ow_home.html"); // Redirect owner
            exit;
        } else {
            echo "<h2 style='color:red; text-align:center;'>Login failed! Please enter your details correctly.</h2>";
        }
    }

    $stmt->close();
}

$conn->close();
?>
