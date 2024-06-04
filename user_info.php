<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

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

$user_id = $_SESSION['user_id'];

// Secure SQL query with prepared statements
$stmt = $conn->prepare("SELECT username, email, bank_name, account_number, balance FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $bank_name, $account_number, $balance);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Information</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>User Information</h1>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Bank Name:</strong> <?php echo htmlspecialchars($bank_name); ?></p>
        <p><strong>Account Number:</strong> <?php echo htmlspecialchars($account_number); ?></p>
        <p><strong>Balance:</strong> $<?php echo number_format($balance, 2); ?></p>
        <?php if ($username == 'admin'): ?>
            <p><a href="admin_view.php">View All Users' Bank Information</a></p>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
