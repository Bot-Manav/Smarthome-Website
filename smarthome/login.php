<?php
session_start();
require 'db.php';

$message = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE username='$username'");
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit;
        } else {
            $message = "Incorrect password!";
        }
    } else {
        $message = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Smart Home Connect</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <!-- Page Transition Overlay -->
    <div class="page-transition-overlay">
        <div class="page-transition-spinner"></div>
    </div>

    <!-- Parallax Background Shapes -->
    <div class="parallax-bg">
        <div class="parallax-shape parallax-shape-1" data-speed="0.1"></div>
        <div class="parallax-shape parallax-shape-2" data-speed="0.25"></div>
        <div class="parallax-shape parallax-shape-3" data-speed="0.15"></div>
    </div>

    <div class="auth-form">
        <h2>Login</h2>
        <?php if ($message != "") {
            echo "<p>$message</p>";
        } ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
    <script src="script.js"></script>
</body>

</html>