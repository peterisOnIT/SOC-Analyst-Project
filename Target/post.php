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

$post_error = '';

// Handle new post submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['post_submit'])) {
    $user_id = $_SESSION['user_id']; // Ensure this session variable is set correctly
    $title = $_POST['title']; // Removed escaping
    $content = $_POST['content']; // Removed escaping

    // Manually escape single quotes to avoid SQL syntax errors
    $title = str_replace("'", "\\'", $title);
    $content = str_replace("'", "\\'", $content);

    $sql = "INSERT INTO posts (user_id, title, content, created_at) VALUES ('$user_id', '$title', '$content', NOW())";
    if ($conn->query($sql) !== TRUE) {
        $post_error = "Error: " . $sql . "<br>" . $conn->error;
    } else {
        header("Location: forum.php");
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Post</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Create a Post</h1>
        <?php if ($post_error): ?>
            <p class="error"><?php echo $post_error; ?></p>
        <?php endif; ?>
        <form method="post" action="post.php">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" rows="5" required></textarea>
            </div>
            <button type="submit" name="post_submit" class="btn">Submit</button>
        </form>
        <div class="btn-container">
            <a href="forum.php" class="btn">Go Back to Forum</a>
            <a href="logout.php" class="btn">Logout</a>
        </div>
    </div>
</body>
</html>
