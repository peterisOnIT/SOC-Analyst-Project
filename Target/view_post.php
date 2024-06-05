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

$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the post
$post_sql = "SELECT posts.title, posts.content, posts.created_at, users.username FROM posts JOIN users ON posts.user_id = users.id WHERE posts.id = $post_id";
$post_result = $conn->query($post_sql);

if ($post_result->num_rows == 0) {
    echo "Post not found.";
    exit();
}

$post = $post_result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1><?php echo $post['title']; ?></h1>
        <p><?php echo $post['content']; ?></p>
        <p>Posted by <?php echo $post['username']; ?> on <?php echo $post['created_at']; ?></p>
        <div class="btn-container">
            <a href="forum.php" class="btn">Go Back to Forum</a>
            <a href="logout.php" class="btn">Logout</a>
        </div>
    </div>
</body>
</html>
