<?php
// migration_add_reset_token.php
require_once __DIR__ . '/config/db_control.php';

try {
    $pdo = getControlDB();
    echo "Connected to database.\n";

    // Add reset_token_hash column
    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN reset_token_hash VARCHAR(64) NULL AFTER status");
        echo "Added reset_token_hash column.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column') !== false) {
             echo "Column reset_token_hash already exists.\n";
        } else {
             throw $e;
        }
    }

    // Add reset_token_expires_at column
    try {
        $pdo->exec("ALTER TABLE users ADD COLUMN reset_token_expires_at DATETIME NULL AFTER reset_token_hash");
        echo "Added reset_token_expires_at column.\n";
    } catch (PDOException $e) {
         if (strpos($e->getMessage(), 'Duplicate column') !== false) {
             echo "Column reset_token_expires_at already exists.\n";
        } else {
             throw $e;
        }
    }

    echo "Migration completed successfully.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
