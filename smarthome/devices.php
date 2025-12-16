<?php
session_start();
require 'db.php'; // database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Map allowed devices to icons
$icon_map = [
    "Fan" => "💨",
    "AC" => "❄️",
    "TV" => "📺",
    "Fridge" => "🧊",
    "CCTV" => "📹",
    "Chimney" => "🔥",
    "AI Voice Assistant" => "🗣️",
    "Automatic Broom/Mop" => "🧹",
    "Dishwasher" => "🍽️",
    "Washing Machine" => "🧺"
];

// Add device
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_device'])) {
    $device_name = trim($_POST['device_name']);
    $custom_name = trim($_POST['custom_name']);
    if (!empty($device_name) && isset($icon_map[$device_name])) {
        $icon = $icon_map[$device_name];
        $stmt = $conn->prepare("INSERT INTO devices (user_id, device_name, custom_name, device_icon, status) VALUES (?, ?, ?, ?, 'OFF')");
        $stmt->bind_param("isss", $user_id, $device_name, $custom_name, $icon);
        $stmt->execute();
        $stmt->close();
    }
}

// Remove device
if (isset($_GET['delete'])) {
    $device_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM devices WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $device_id, $user_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all devices of logged-in user
$stmt = $conn->prepare("SELECT id, device_name, custom_name, device_icon, status FROM devices WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$devices = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Devices</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">SmartHome</div>
        <div class="nav-links">
            <button onclick="window.location.href='index.php'">Dashboard</button>
            <button onclick="window.location.href='logout.php'">Logout</button>
            <button onclick="window.location.href='profile.php'">Profile</button>
        </div>
    </nav>

    <div class="device-page">
        <h2>Manage Your Devices</h2>

        <!-- Add Device Form -->
        <form method="POST" class="add-device-form">
            <label>Device Type:</label>
            <select name="device_name" required>
                <option value="" disabled selected>Select Device</option>
                <?php foreach ($icon_map as $device_name => $icon): ?>
                    <option value="<?= $device_name ?>"><?= $device_name ?></option>
                <?php endforeach; ?>
            </select>

            <label>Custom Name (optional):</label>
            <input type="text" name="custom_name" placeholder="e.g. Living Room Fan">

            <button type="submit" name="add_device">Add Device</button>
        </form>

        <h3>Your Devices</h3>
        <div class="device-grid">
            <?php if (empty($devices)): ?>
                <p>No devices added yet.</p>
            <?php else: ?>
                <?php foreach ($devices as $device): ?>
                    <div class="device-card">
                        <div class="device-icon"><?= htmlspecialchars($device['device_icon']) ?></div>
                        <h3><?= htmlspecialchars($device['custom_name'] ?: $device['device_name']) ?></h3>
                        <p>Status: <?= htmlspecialchars($device['status']) ?></p>
                        <a href="devices.php?delete=<?= $device['id'] ?>" class="deleteBtn">Remove</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>