<?php
// app/api/reset_db.php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db_control.php';
require_once __DIR__ . '/../config/db_user.php';
require_once __DIR__ . '/../config/auth_middleware.php';

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$userId = getCurrentUserId();
$dbName = getCurrentUserDb();

if (!$dbName) {
    http_response_code(500);
    echo json_encode(['error' => 'No database assigned']);
    exit();
}

$pdo = getControlDB();

try {
    // Single DB Architecture: Drop all tables belonging to this user
    
    // 1. Get Tables
    // Note: SHOW TABLES doesn't support prepared statements, but $userId is from session (validated integer)
    $prefix = "user_{$userId}_";
    
    // Validate $userId to prevent SQL injection (should be integer)
    if (!is_numeric($userId)) {
        throw new Exception("Invalid user ID");
    }
    
    $stmt = $pdo->query("SHOW TABLES LIKE '{$prefix}%'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // 2. Drop Each
    if ($tables) {
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        foreach ($tables as $table) {
            $pdo->exec("DROP TABLE IF EXISTS `$table`");
        }
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    }
    
    // Log intent
    logAction($userId, 'reset_db', "Reset database (Dropped " . count($tables) . " tables)");

    echo json_encode(['status' => 'success', 'message' => 'Database reset successfully.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Reset failed: ' . $e->getMessage()]);
}

function logAction($userId, $action, $details) {
    // Ideally we'd have an audit_log, but query_logs can double as activity log 
    // or just skip for now as not explicitly required in schema
}
