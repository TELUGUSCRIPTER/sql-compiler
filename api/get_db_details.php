<?php
// api/get_db_details.php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/auth_middleware.php';

if (!isLoggedIn()) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$dbName = getCurrentUserDb();
if (!$dbName) {
    echo json_encode(['error' => 'No database']);
    exit();
}

try {
    require_once __DIR__ . '/../config/db_control.php';
    $pdo = getControlDB(); // Use control DB (single DB architecture)
    
    // Get Tables
    $tables = [];
    $stmt = $pdo->query("SHOW TABLES");
    $allTables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $userId = getCurrentUserId();
    $prefix = "user_{$userId}_";

    foreach ($allTables as $table) {
        // Filter: Only show tables for this user
        if (strpos($table, $prefix) !== 0) {
            continue;
        }

        // Display Name: Strip unique prefix
        $displayName = substr($table, strlen($prefix));

        // Get Columns
        $stmtCols = $pdo->query("SHOW COLUMNS FROM `$table`");
        $cols = [];
        while ($row = $stmtCols->fetch(PDO::FETCH_ASSOC)) {
            $cols[] = [
                'name' => $row['Field'],
                'type' => $row['Type']
            ];
        }

        // Get Preview Data (Limit 5)
        $stmtData = $pdo->query("SELECT * FROM `$table` LIMIT 5");
        $rows = $stmtData->fetchAll(PDO::FETCH_ASSOC);

        $tables[] = [
            'name' => $displayName, // Show "items", not "user_1_items"
            'columns' => $cols,
            'preview' => $rows
        ];
    }

    echo json_encode(['tables' => $tables]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
