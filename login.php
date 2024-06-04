<?php
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Target"; // Ensure this matches your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    // Vulnerable SQL query
    $sql = "SELECT id, username FROM users WHERE username = '$input_username' AND password = PASSWORD('$input_password')";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        header("Location: user_info.php");
        exit();
    } else {
        $error = urlencode("Invalid username or password");
        header("Location: index.html?error=" . $error);
        exit();
    }
}

$conn->close();
?>
