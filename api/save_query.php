<?php
// app/api/save_query.php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db_control.php';
require_once __DIR__ . '/../config/auth_middleware.php';

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$userId = getCurrentUserId();
$pdo = getControlDB();

// Handle GET (List) and POST (Save)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare("SELECT id, title, sql_text, created_at FROM saved_queries WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$userId]);
    $raw = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Sanitize for XSS Prevention on Output
    $sanitized = array_map(function($q) {
        $q['title'] = htmlspecialchars($q['title']);
        $q['sql_text'] = htmlspecialchars($q['sql_text']);
        return $q;
    }, $raw);

    echo json_encode($sanitized);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $title = trim($input['title'] ?? 'Untitled Query');
    $sql = trim($input['sql'] ?? '');

    if (empty($sql)) {
         echo json_encode(['error' => 'SQL cannot be empty']);
         exit();
    }

    $stmt = $pdo->prepare("INSERT INTO saved_queries (user_id, title, sql_text) VALUES (?, ?, ?)");
    $stmt->execute([$userId, $title, $sql]);
    
    echo json_encode(['status' => 'success', 'id' => $pdo->lastInsertId()]);
}
