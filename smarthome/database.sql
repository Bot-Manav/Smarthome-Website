-- Create database
DROP DATABASE IF EXISTS smarthome_db;
CREATE DATABASE smarthome_db;
USE smarthome_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Devices table
CREATE TABLE IF NOT EXISTS devices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    device_name VARCHAR(100) NOT NULL,
    custom_name VARCHAR(100) DEFAULT NULL,
    device_icon VARCHAR(100),
    status VARCHAR(20) DEFAULT 'OFF',
    speed INT DEFAULT 1,
    temperature INT DEFAULT 24,
    volume INT DEFAULT 10,
    last_toggled TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Device Actions / History table
CREATE TABLE IF NOT EXISTS device_actions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    device_id INT NOT NULL,
    action VARCHAR(255) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (device_id) REFERENCES devices(id) ON DELETE CASCADE
);

-- Sample user (Password is 'password' hashed using default bcrypt)
-- Note: 'password' hashed in PHP password_verify fits $2y$10$...
INSERT INTO users (id, username, email, password) VALUES
(1, 'testuser', 'test@example.com', '$2y$10$tZ261jY/64jJp/r0.GjDGOx0c4bWlXn4C4806K/gWkK18e5tM9Wd2');

-- Sample devices
INSERT INTO devices (user_id, device_name, custom_name, device_icon, status, speed, temperature, volume, last_toggled) VALUES
(1, 'Fan', 'Living Room Fan', '💨', 'ON', 3, 24, 10, NOW()),
(1, 'AC', 'Bedroom AC', '❄️', 'OFF', 1, 22, 10, NOW()),
(1, 'TV', 'Media Room TV', '📺', 'ON', 1, 24, 35, NOW());