<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blogify";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch categories from the "categories" table
$sql_categories = "SELECT * FROM categories";
$result_categories = $conn->query($sql_categories);
$categories = [];
if ($result_categories->num_rows > 0) {
    while ($row = $result_categories->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Add new blog
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id']; // Corrected variable name
    $author = $_SESSION['username'];

    // Insert blog into database
    $sql = "INSERT INTO posts (title, content, category_id, author) VALUES ('$title', '$content', '$category_id', '$author')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Blog added successfully!');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Remove blog
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];

    // Delete blog from database
    $sql = "DELETE FROM posts WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Blog removed successfully!');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Home</title>
    <link rel="stylesheet" href="home.css">
</head>
<body>
    <div class="container">
        <h1>Welcome <?php echo $_SESSION['username']; ?>!</h1>
        
        <div class="add-blog">
            <h2>Add New Blog</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="text" name="title" placeholder="Title" required>
                <textarea name="content" placeholder="Content" required></textarea>
                <select name="category_id" required> <!-- Dropdown menu for selecting category -->
                    <option value="" selected disabled>Select Category</option>
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                    <?php } ?>
                </select>
                <button type="submit">Add Blog</button>
            </form>
        </div>

        <div class="user-blogs">
            <h2>Your Blogs</h2>
            <?php
            $author = $_SESSION['username'];
            $sql = "SELECT * FROM posts WHERE author='$author'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='blog-item'>";
                    echo "<h3>" . $row['title'] . "</h3>";
                    echo "<p>Category: " . $row['category_id'] . "</p>";
                    echo "<p>" . $row['content'] . "</p>";
                    echo "<a href='home.php?remove=" . $row['id'] . "' class='remove'>Remove</a>";
                    echo "</div>";
                }
            } else {
                echo "<p>No blogs found.</p>";
            }
            ?>
        </div>

        <p><a href="dashboard.php">Back to Dashboard</a></p>
        <p><a href="logout.php">Logout</a></p>
    </div>
</body>
</html>

<?php $conn->close(); ?>
