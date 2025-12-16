-- Create database
CREATE DATABASE IF NOT EXISTS smarthome_db;
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
    device_icon VARCHAR(100),
    status VARCHAR(20) DEFAULT 'OFF',
    speed INT DEFAULT 0,
    temperature INT DEFAULT 0,
    volume INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Sample user
INSERT INTO users (username, email, password) VALUES
('testuser', 'test@example.com', 'password');

-- Sample devices
INSERT INTO devices (user_id, device_name, device_icon, status, speed, temperature, volume) VALUES
(1, 'Smart Light', 'light.png', 'ON', 0, 0, 0),
(1, 'Smart Fan', 'fan.png', 'OFF', 3, 25, 0),
(1, 'Smart Thermostat', 'thermostat.png', 'ON', 0, 22, 0);