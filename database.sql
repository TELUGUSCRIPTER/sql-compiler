-- Control Database Schema

CREATE DATABASE IF NOT EXISTS control_db;
USE control_db;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    status ENUM('active', 'banned') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- User Databases Mapping
CREATE TABLE IF NOT EXISTS user_databases (
    user_id INT PRIMARY KEY,
    db_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Query Logs
CREATE TABLE IF NOT EXISTS query_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    sql_text TEXT NOT NULL,
    execution_time_ms FLOAT,
    status ENUM('success', 'error') NOT NULL,
    error_message TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Initial Admin User (Default: admin / admin123)
-- Hash generated for 'admin123' might vary, using a placeholder or standard BCrypt hash
-- For this setup, we'll insert a dummy admin. The register script should be used for real users.
-- INSERT INTO users (name, email, password_hash, role) VALUES ('Admin', 'admin@example.com', '$2y$10$YourHashHere', 'admin');
