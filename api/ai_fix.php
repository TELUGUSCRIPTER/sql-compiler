<?php
// api/ai_fix.php
error_log(0);
require_once __DIR__ . '/../config/auth_middleware.php';
requireLogin();

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$sql = $input['sql'] ?? '';
$error = $input['error'] ?? '';

if (!$sql) {
    echo json_encode(['error' => 'No SQL provided']);
    exit;
}

require_once __DIR__ . '/../config/db_control.php';
require_once __DIR__ . '/../includes/AIHelper.php';

try {
    $pdo = getControlDB();
    $aiHelper = new AIHelper($pdo);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database Error: ' . $e->getMessage()]);
    exit;
}

// 2. Check Daily Limit
$userId = $_SESSION['user_id'];
if (!$aiHelper->checkLimit($userId)) {
    http_response_code(429);
    echo json_encode(['error' => 'Daily limit reached (5/5). Try again tomorrow!']);
    exit;
}

// 3. Call AI
$result = $aiHelper->fixSql($sql, $error);

if (isset($result['error'])) {
    echo json_encode($result);
    exit;
}

// 4. Increment Usage & Return
$aiHelper->incrementUsage($userId);
echo json_encode($result);
