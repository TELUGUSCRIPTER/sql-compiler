<?php
// app/config/db_control.php

define('DB_HOST', 'localhost');
define('DB_CONTROL_NAME', 'your_db_name');
define('DB_USER', 'your_db_user');
define('DB_PREFIX', 'unmqwlgl_');
define('DB_PASS', 'your_db_password');

function getControlDB() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_CONTROL_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (\PDOException $e) {
        // In production, log this error instead of showing it
        error_log("Control DB Connection Failed: " . $e->getMessage()); // Log to server error log
        die("Service Unavailable. Please try again later.");
    }
}
