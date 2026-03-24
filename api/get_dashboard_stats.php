<?php
// api/get_dashboard_stats.php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db_control.php';
require_once __DIR__ . '/../config/auth_middleware.php';

if (!isLoggedIn()) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['user_id'];
$dbName = $_SESSION['db_name'];
$pdo = getControlDB();

// IDOR Protection: Verify user owns this database
$stmtVerify = $pdo->prepare("SELECT db_name FROM user_databases WHERE user_id = ?");
$stmtVerify->execute([$userId]);
$verifiedDb = $stmtVerify->fetchColumn();
if ($verifiedDb !== $dbName) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// 1. Get DB Size
$dbSize = 0;
try {
    $stmtSize = $pdo->prepare("SELECT SUM(data_length + index_length) / 1024 / 1024 as size_mb FROM information_schema.TABLES WHERE table_schema = ?");
    $stmtSize->execute([$dbName]);
    $dbSize = round($stmtSize->fetchColumn() ?: 0, 2);
} catch(Exception $e) { }

// 2. Get Query Stats (Simulated delay for skeleton effect)
// usleep(500000); // Optional: 500ms delay to show off skeletons
$stmt = $pdo->prepare("SELECT COUNT(*) as count, AVG(execution_time_ms) as avg_time FROM query_logs WHERE user_id = ? AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)");
$stmt->execute([$userId]);
$stats = $stmt->fetch();

echo json_encode([
    'db_size' => $dbSize,
    'query_count' => number_format($stats['count'] ?? 0),
    'avg_latency' => round($stats['avg_time'] ?? 0, 2)
]);
