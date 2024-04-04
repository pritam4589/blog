<!DOCTYPE html<html>
<head>
<title>Create Registration Page</title>
<link rel="stylesheet" href="register_styles.css">
</head><body>
<h1>Create Account</h1>
	<form action="register.php" method="post">
		<label for="username">Username:</label><br>
		<input type="text" id="username" name="username" required><br>
		<label for="email">Email:</label><br>
		<input type="email" id="email" name="email" required><br>
		<label for="password">Password:</label><br>
		<input type="password" id="password" name="password" required><br>
		<label for="confirmpassword">Confirm Password:</label><br>
		<input type="password" id="confirmpassword" name="confirmpassword" required><br>
		<input type="submit" value="Register">
	</form>
</body>
</html>


<?php
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

// Check if all form fields are set and not empty
if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirmpassword']) &&
    !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirmpassword'])) {

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmpassword = $_POST['confirmpassword'];

    // Check if passwords match
    if ($password != $confirmpassword) {
        echo "Passwords do not match";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement
    $sql = "INSERT INTO users (username, email, password_hash) VALUES ('$username', '$email', '$hashed_password')";

    // Execute SQL statement
    if ($conn->query($sql) === TRUE) {
        // Redirect to login page upon successful registration
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "All fields are required";
}

$conn->close();
?>
