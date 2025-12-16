<?php
session_start();
require 'db.php';

$devices = [];
if (isset($_SESSION['user_id'])) {
  $stmt = $conn->prepare("SELECT id, device_name, device_icon, status, speed, temperature, volume, custom_name, last_toggled FROM devices WHERE user_id = ?");
  $stmt->bind_param("i", $_SESSION['user_id']);
  $stmt->execute();
  $result = $stmt->get_result();
  $devices = $result->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart Home Connect</title>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar">
    <div class="logo">SmartHome</div>
    <div class="nav-links">
      <?php if (isset($_SESSION['user_id'])): ?>
        <button id="logoutBtn" onclick="window.location.href='logout.php'">Logout</button>
        <button id="profileBtn" onclick="window.location.href='profile.php'">Profile</button>
      <?php else: ?>
        <button id="loginBtn" onclick="window.location.href='login.php'">Login</button>
      <?php endif; ?>
      <button id="controlBtn" onclick="window.location.href='devices.php'">Manage Devices</button>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <h1>Welcome to Smart Home Connect</h1>
      <p>Monitor and control all your smart devices from one place.</p>
      <?php if (isset($_SESSION['user_id'])): ?>
        <button id="startBtn" onclick="window.location.href='devices.php'">Start Controlling</button>
      <?php else: ?>
        <button id="startBtn" onclick="window.location.href='login.php'">Start Controlling</button>
      <?php endif; ?>
    </div>
  </section>

  <!-- Devices Overview Section -->
  <section class="devices">
    <h2>Your Devices</h2>
    <div class="device-grid">
      <?php if (empty($devices)): ?>
        <p>No devices found. <a href="devices.php">Add some devices</a>.</p>
      <?php else: ?>
        <?php foreach ($devices as $device): ?>
          <div class="device-card">
            <div class="device-icon"><?= htmlspecialchars($device['device_icon']) ?></div>
            <h3><?= htmlspecialchars($device['custom_name'] ?? $device['device_name']) ?></h3>

            <p>
              Status: <span class="status"><?= htmlspecialchars($device['status']) ?></span>
              <?php if ($device['device_name'] === 'Fan' || $device['device_name'] === 'Automatic Broom/Mop'): ?>
                | Speed: <?= isset($device['speed']) ? min($device['speed'], 4) : 1 ?>
              <?php elseif ($device['device_name'] === 'AC'): ?>
                | Temperature: <?= isset($device['temperature']) ? max(16, min($device['temperature'], 30)) : '24' ?>°C
              <?php elseif ($device['device_name'] === 'TV'): ?>
                | Volume: <?= isset($device['volume']) ? $device['volume'] : '10' ?>
              <?php endif; ?>
            </p>
            <?php if (isset($device['last_toggled'])): ?>
              <p class="last-toggled">Last Toggled: <?= date('d M Y, H:i:s', strtotime($device['last_toggled'])) ?></p>
            <?php endif; ?>

            <form method="GET" action="toggle.php" style="margin-top:10px;">
              <input type="hidden" name="device_id" value="<?= $device['id'] ?>">
              <button type="submit" class="toggleBtn">Toggle / Control</button>
            </form>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <p>© 2025 Smart Home Connect. All rights reserved.</p>
  </footer>
</body>

</html>