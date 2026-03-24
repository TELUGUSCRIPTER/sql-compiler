<?php
// app/user/history.php
require_once __DIR__ . '/../config/auth_middleware.php';
require_once __DIR__ . '/../config/db_control.php';
requireLogin();

$userId = getCurrentUserId();
$pdo = getControlDB();

// Fetch last 50 queries
$stmt = $pdo->prepare("SELECT sql_text, execution_time_ms, status, created_at, error_message FROM query_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT 50");
$stmt->execute([$userId]);
$logs = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
    <meta charset="UTF-8">
    <title>Query History - SQL Playground</title>
    <script src="https://cdn.tailwindcss.com"></script>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
</head>
<body class="bg-gray-100 flex flex-col h-screen">
    <nav class="bg-white border-b px-8 py-4 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-4">
            <a href="dashboard.php" class="text-gray-500 hover:text-gray-900">← Back to Dashboard</a>
            <h1 class="text-xl font-bold">Query History</h1>
        </div>
        <a href="editor.php" class="text-blue-600 hover:underline">Open Editor</a>
    </nav>

    <div class="flex-1 p-8 overflow-auto">
        <div class="bg-white rounded shadow text-sm">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-700 uppercase">
                    <tr>
                        <th class="p-4 border-b">Time</th>
                        <th class="p-4 border-b">Status</th>
                        <th class="p-4 border-b">SQL</th>
                        <th class="p-4 border-b">Exec (ms)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($logs)): ?>
                        <tr><td colspan="4" class="p-4 text-center text-gray-500">No queries run yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="p-4 text-gray-500 whitespace-nowrap"><?= $log['created_at'] ?></td>
                                <td class="p-4">
                                    <?php if ($log['status'] === 'success'): ?>
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs px-2 py-0.5">Success</span>
                                    <?php else: ?>
                                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs px-2 py-0.5">Error</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 font-mono text-gray-800 break-words max-w-xl">
                                    <?= htmlspecialchars($log['sql_text']) ?>
                                    <?php if ($log['error_message']): ?>
                                        <div class="text-red-500 text-xs mt-1"><?= htmlspecialchars($log['error_message']) ?></div>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 text-gray-600"><?= round($log['execution_time_ms'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
