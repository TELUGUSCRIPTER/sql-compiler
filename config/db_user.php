<?php
// app/config/db_user.php

require_once __DIR__ . '/db_control.php';

function getUserDBConnection($dbName) {
    try {
        // Single DB Architecture: 
        // We ignore the passed $dbName (which is just for session compat)
        // and connect to the main control DB.
        // The isolation happens via table prefixing in run_sql.php
        
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_CONTROL_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => true,
            // Timeout to prevent long running queries from hanging the php process
            PDO::ATTR_TIMEOUT            => 5 
        ];
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (\PDOException $e) {
        throw new Exception("User DB Connection Failed: " . $e->getMessage());
    }
}
