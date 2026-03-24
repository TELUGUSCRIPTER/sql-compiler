<?php
require_once __DIR__ . '/config/db_control.php';

$pdo = getControlDB();

echo "Checking indexes...\n";

// 1. Users Table Indexes
try {
    $pdo->exec("CREATE INDEX idx_users_email ON users(email)");
    echo "Added index: idx_users_email\n";
} catch (Exception $e) { echo "Index idx_users_email might already exist.\n"; }

try {
    $pdo->exec("CREATE INDEX idx_users_name ON users(name)");
    echo "Added index: idx_users_name\n";
} catch (Exception $e) { echo "Index idx_users_name might already exist.\n"; }

try {
    $pdo->exec("CREATE INDEX idx_users_created ON users(created_at)");
    echo "Added index: idx_users_created\n";
} catch (Exception $e) { echo "Index idx_users_created might already exist.\n"; }

try {
    $pdo->exec("CREATE INDEX idx_users_last_activity ON users(last_activity)");
    echo "Added index: idx_users_last_activity\n";
} catch (Exception $e) { echo "Index idx_users_last_activity might already exist.\n"; }

// 2. Query Logs Indexes (Critical for dashboard stats)
try {
    $pdo->exec("CREATE INDEX idx_query_logs_created ON query_logs(created_at)");
    echo "Added index: idx_query_logs_created\n";
} catch (Exception $e) { echo "Index idx_query_logs_created might already exist.\n"; }

try {
    $pdo->exec("CREATE INDEX idx_query_logs_user ON query_logs(user_id)");
    echo "Added index: idx_query_logs_user\n";
} catch (Exception $e) { echo "Index idx_query_logs_user might already exist.\n"; }

echo "Optimization complete.\n";
?>
