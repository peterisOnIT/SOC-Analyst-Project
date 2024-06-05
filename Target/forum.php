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

// Fetch forum posts
$sql = "SELECT id, title, content, created_at, user_id FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forum</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Forum Posts</h1>
        <a href="post.php" class="btn">Create New Post</a>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="post">
                    <h2><?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?></h2>
                    <p><?php echo htmlspecialchars($row['content'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p>Posted on <?php echo $row['created_at']; ?></p>
                    <a href="view_post.php?id=<?php echo $row['id']; ?>" class="btn">View Post</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No posts found.</p>
        <?php endif; ?>
        <div class="btn-container">
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                <a href="admin_view.php" class="btn">Go Back to Admin View</a>
            <?php else: ?>
                <a href="user_info.php" class="btn">Go Back to User Info</a>
            <?php endif; ?>
            <a href="logout.php" class="btn">Logout</a>
        </div>
    </div>
</body>
</html>
