<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM users WHERE id='$user_id'");
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Profile - Smart Home Connect</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="profile-page">
        <h2>Welcome, <?php echo $user['username']; ?></h2>
        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
        <p><strong>Member since:</strong> <?php echo $user['created_at']; ?></p>
        <a href="logout.php"><button>Logout</button></a>
    </div>
</body>

</html>