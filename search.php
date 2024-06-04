<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Target"; // Updated database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['username'])) {
    $search_username = $_GET['username'];

    // Vulnerable SQL query
    $sql = "SELECT * FROM users WHERE username = '$search_username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "ID: " . $row["id"]. " - Name: " . $row["username"]. " - Email: " . $row["email"]. "<br>";
        }
    } else {
        echo "0 results";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Injection Practice - Search</title>
</head>
<body>
    <h1>Search Users</h1>
    <form action="search.php" method="GET">
        <label for="username">Search User:</label>
        <input type="text" id="username" name="username">
        <button type="submit">Search</button>
    </form>
    <div id="results">
        <!-- Search results will be displayed here -->
    </div>
    <a href="logout.php">Logout</a>
</body>
</html>
