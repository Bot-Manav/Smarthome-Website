<?php
session_start();
require 'db.php';

$message = "";

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $checkUser = $conn->query("SELECT * FROM users WHERE username='$username' OR email='$email'");
    if ($checkUser->num_rows > 0) {
        $message = "Username or Email already exists!";
    } else {
        $conn->query("INSERT INTO users (username, email, password) VALUES ('$username','$email','$password')");
        $message = "Registration successful! You can now login.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register - Smart Home Connect</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="auth-form">
        <h2>Register</h2>
        <?php if ($message != "") {
            echo "<p>$message</p>";
        } ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="register">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>

</html>