<?php
session_start();
require 'db.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if(!isset($_GET['device_id'])) {
    header("Location: index.php");
    exit;
}

$device_id = intval($_GET['device_id']);

// Fetch device info
$stmt = $conn->prepare("SELECT * FROM devices WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $device_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$device = $result->fetch_assoc();
$stmt->close();

if(!$device) {
    die("Device not found.");
}

// Handle form submission
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action_description = "";

    // Toggle ON/OFF
    if(isset($_POST['toggle'])) {
        $new_status = ($device['status'] === 'OFF') ? 'ON' : 'OFF';
        $stmt = $conn->prepare("UPDATE devices SET status=?, last_toggled=NOW() WHERE id=? AND user_id=?");
        $stmt->bind_param("sii", $new_status, $device_id, $user_id);
        $stmt->execute();
        $stmt->close();
        $action_description .= "Toggled $new_status; ";
    }

    // Update speed (Fan / Broom/Mop)
    if(isset($_POST['speed']) && in_array($device['device_name'], ['Fan', 'Automatic Broom/Mop'])) {
        $speed = max(1, min(4, intval($_POST['speed'])));
        $stmt = $conn->prepare("UPDATE devices SET speed=?, last_toggled=NOW() WHERE id=? AND user_id=?");
        $stmt->bind_param("iii", $speed, $device_id, $user_id);
        $stmt->execute();
        $stmt->close();
        $action_description .= "Set speed to $speed; ";
    }

    // Update temperature (AC)
    if(isset($_POST['temperature']) && $device['device_name'] === 'AC') {
        $temp = max(16, min(30, intval($_POST['temperature'])));
        $stmt = $conn->prepare("UPDATE devices SET temperature=?, last_toggled=NOW() WHERE id=? AND user_id=?");
        $stmt->bind_param("iii", $temp, $device_id, $user_id);
        $stmt->execute();
        $stmt->close();
        $action_description .= "Set temperature to $temp; ";
    }

    // Update volume (TV)
    if(isset($_POST['volume']) && $device['device_name'] === 'TV') {
        $vol = max(0, min(100, intval($_POST['volume'])));
        $stmt = $conn->prepare("UPDATE devices SET volume=?, last_toggled=NOW() WHERE id=? AND user_id=?");
        $stmt->bind_param("iii", $vol, $device_id, $user_id);
        $stmt->execute();
        $stmt->close();
        $action_description .= "Set volume to $vol; ";
    }

    // Store action in history
    if($action_description !== "") {
        $stmt = $conn->prepare("INSERT INTO device_actions (user_id, device_id, action) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $device_id, $action_description);
        $stmt->execute();
        $stmt->close();
    }

    // Refresh device info
    $stmt = $conn->prepare("SELECT * FROM devices WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $device_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $device = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Control <?= htmlspecialchars($device['device_name']) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="control-page">
    <h2>Control: <?= htmlspecialchars($device['device_name']) ?></h2>
    <p>Status: <strong><?= $device['status'] ?></strong></p>
    <?php if($device['last_toggled']): ?>
        <p class="last-toggled">Last Toggled: <?= date('d M Y, H:i:s', strtotime($device['last_toggled'])) ?></p>
    <?php endif; ?>

    <form method="POST">
        <button type="submit" name="toggle">Toggle ON/OFF</button><br><br>

        <?php if(in_array($device['device_name'], ['Fan', 'Automatic Broom/Mop'])): ?>
            <label>Speed (1-4):</label>
            <input type="range" name="speed" min="1" max="4" value="<?= $device['speed'] ?? 1 ?>">
            <span><?= $device['speed'] ?? 1 ?></span><br><br>
        <?php endif; ?>

        <?php if($device['device_name'] === 'AC'): ?>
            <label>Temperature (16-30°C):</label>
            <input type="number" name="temperature" min="16" max="30" value="<?= $device['temperature'] ?? 24 ?>"><br><br>
        <?php endif; ?>

        <?php if($device['device_name'] === 'TV'): ?>
            <label>Volume (0-100):</label>
            <input type="number" name="volume" min="0" max="100" value="<?= $device['volume'] ?? 10 ?>"><br><br>
        <?php endif; ?>

        <button type="submit">Apply Changes</button>
    </form>

    <br>
    <a href="index.php">⬅ Back to Dashboard</a>
</div>

</body>
</html>
