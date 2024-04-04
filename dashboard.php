<?php
session_start();

// Access the $_SESSION variable here
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Retrieve the list of blogs from the database
$conn = new mysqli("localhost", "correct_username", "correct_password", "correct_database_name");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM blogs";
$result = $conn->query($sql);
$blogs = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $blogs[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="container">
        <h1>Welcome <?php echo $_SESSION['username']; ?>!</h1>

        <div class="create-blog">
            <form action="create_blog.php" method="post">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>

                <label for="content">Content:</label>
                <textarea id="content" name="content" rows="10" cols="30" required></textarea>

                <label for="category">Category:</label>
                <select id="category" name="category" required>
                    <option value="">Select a category</option>
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                    <?php } ?>
                </select>

                <button type="submit">Create Blog Post</button>
            </form>
        </div>

        <div class="blog-list">
            <h2>All Blogs</h2>
            <?php if ($blogs) { ?>
                <ul>
                <?php foreach ($blogs as $blog) { ?>
                    <li>
                        <h3><?php echo htmlspecialchars($blog['title']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($blog['content'], 0, 200)); ?>...</p>
                        <p class="author-date">Author: <?php echo htmlspecialchars($blog['author']); ?> | Date: <?php echo htmlspecialchars($blog['created_at']); ?></p>
                        <p class="category">Category: <?php echo htmlspecialchars($blog['category_name']); ?></p>
                    </li>
                <?php } ?>
                </ul>
            <?php } else { ?>
                <p>No blogs found.</p>
            <?php } ?>
        </div>

        <div class="user-home">
            <a href="home.php">
                <img src="home_icon.png" alt="User Home" width="50" height="50">
            </a>
        </div>

        <div class="write-blog">
            <button class="write-blog-button">+</button>
        </div>

        <p><a href="logout.php">Logout</a></p>
    </div>
</body>
</html>