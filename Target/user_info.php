<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Target";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];

// Fetch user info
$sql = "SELECT username, email, bank_name, account_number, balance FROM users WHERE username = '$username'";
$result = $conn->query($sql);
$user_info = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Info</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>User Information</h1>
        <p>Username: <?php echo htmlspecialchars($user_info['username'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p>Email: <?php echo htmlspecialchars($user_info['email'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p>Bank Name: <?php echo htmlspecialchars($user_info['bank_name'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p>Account Number: <?php echo htmlspecialchars($user_info['account_number'], ENT_QUOTES, 'UTF-8'); ?></p>
        <p>Balance: $<?php echo htmlspecialchars($user_info['balance'], ENT_QUOTES, 'UTF-8'); ?></p>
        <div class="btn-container">
            <a href="forum.php" class="btn">Go to Forum</a>
            <a href="logout.php" class="btn">Logout</a>
        </div>
    </div>
</body>
</html>
