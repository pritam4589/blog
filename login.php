<!DOCTYPE html
<html>
<head>
<title>Create Login Page</title>
<link rel="stylesheet" href="login_styles.css" >
</head><body>
<h1>Login</h1>
	<form action="login.php" method="post">
		<label for="email">Email:</label><br>
		<input type="email" id="email" name="email" required><br>
		<label for="password">Password:</label><br>
		<input type="password" id="password" name="password" required><br>
		<input type="submit" value="Login">
	</form>
</body>
</html>


<?php
session_start();
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

// Check if email and password are set in $_POST
if(isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            if (password_verify($password, $row["password_hash"])) { // Verify hashed password
                $_SESSION["username"] = $row["username"];
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Invalid password";
                exit();
            }
        }
    } else {
        echo "Email not found";
        exit();
    }
} else {
    echo "Email and password are required";
    exit();
}

$conn->close();
?>
