-- Create the database
CREATE DATABASE IF NOT EXISTS guest;

-- Use the database
USE guest;

-- Create the `users` table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    code VARCHAR(6) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS spaces (
    id INT AUTO_INCREMENT PRIMARY KEY,
    space_name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS qr_scans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    qr_content TEXT NOT NULL,
    scanned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

ALTER TABLE qr_scans
    ADD COLUMN space_id INT DEFAULT NULL,
    ADD FOREIGN KEY (space_id) REFERENCES spaces(id);
