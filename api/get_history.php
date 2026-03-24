<?php
// api/get_history.php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db_control.php';
require_once __DIR__ . '/../config/auth_middleware.php';

if (!isLoggedIn()) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['user_id'];
$pdo = getControlDB();

try {
    $stmt = $pdo->prepare("
        SELECT sql_text, status, execution_time_ms, created_at 
        FROM query_logs 
        WHERE user_id = ? 
        ORDER BY created_at DESC 
        LIMIT 50
    ");
    $stmt->execute([$userId]);
    $history = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(['history' => $history]);
} catch (Exception $e) {
    echo json_encode(['error' => 'Failed to fetch history']);
}
?>
