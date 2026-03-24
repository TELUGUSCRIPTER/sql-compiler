<?php
// admin/logs.php
require_once __DIR__ . '/../config/db_control.php';
require_once __DIR__ . '/../config/auth_middleware.php';

// Auth Check
requireAdmin();

$pdo = getControlDB();

// Filters
$limit = 100;
$userFilter = $_GET['user_id'] ?? '';
$statusFilter = $_GET['status'] ?? '';

$sql = "
    SELECT q.*, u.name as user_name, u.email 
    FROM query_logs q 
    JOIN users u ON q.user_id = u.id 
    WHERE 1=1
";
$params = [];

if ($userFilter) {
    $sql .= " AND q.user_id = ?";
    $params[] = $userFilter;
}
if ($statusFilter) {
    $sql .= " AND q.status = ?";
    $params[] = $statusFilter;
}

$sql .= " ORDER BY q.created_at DESC LIMIT $limit";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$logs = $stmt->fetchAll();

// Get users for filter
$users = $pdo->query("SELECT id, name FROM users ORDER BY name")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Logs | Admin</title>
    <!-- Ad Script -->

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
         tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Space Grotesk', 'sans-serif'], mono: ['JetBrains Mono', 'monospace'], },
                    colors: { cyber: { black: '#0a0f1a', accent: '#8b5cf6' } },
                    animation: { 'fade-in': 'fadeIn 0.5s ease-out forwards' },
                    keyframes: {
                        fadeIn: { 'from': { opacity: '0', transform: 'translateY(10px)' }, 'to': { opacity: '1', transform: 'translateY(0)' } }
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #0a0f1a; color: #fff; }
        .glass-panel { background: rgba(17, 24, 39, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.05); }
        .glass-sidebar { background: rgba(10, 15, 26, 0.95); border-right: 1px solid rgba(255,255,255,0.05); }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0a0f1a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 3px; }
    </style>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
</head>
<body class="h-full flex overflow-hidden antialiased">

    <!-- SIDEBAR -->
    <aside class="w-64 glass-sidebar flex flex-col z-50">
        <div class="h-20 flex items-center px-6 gap-3 border-b border-white/5">
            <div class="p-2 bg-gradient-to-tr from-purple-600 to-blue-600 rounded-lg shadow-lg shadow-purple-500/20">
                <i data-lucide="shield" class="w-5 h-5 text-white"></i>
            </div>
            <span class="font-bold text-lg tracking-tight">Admin</span>
        </div>
        <nav class="flex-1 py-6 space-y-1">
            <a href="dashboard.php" class="flex items-center px-6 py-3.5 text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition group">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3 group-hover:text-purple-400 transition"></i>
                Dashboard
            </a>
            <a href="users.php" class="flex items-center px-6 py-3.5 text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition group">
                <i data-lucide="users" class="w-5 h-5 mr-3 group-hover:text-purple-400 transition"></i>
                Users
            </a>
             <a href="logs.php" class="flex items-center px-6 py-3.5 text-sm font-medium text-purple-400 bg-purple-500/10 border-r-2 border-purple-500 transition group">
                <i data-lucide="database" class="w-5 h-5 mr-3 transition"></i>
                Queries
            </a>
        </nav>
        <div class="p-4 border-t border-white/5">
             <a href="../login.php" class="flex items-center px-4 py-2 text-sm text-red-400 hover:bg-red-500/10 rounded-lg transition">
                <i data-lucide="log-out" class="w-4 h-4 mr-2"></i>
                Logout
            </a>
        </div>
    </aside>

    <!-- CONTENT -->
    <main class="flex-1 flex flex-col min-w-0 bg-[#0a0f1a] relative overflow-y-auto p-8">
        
        <div class="max-w-7xl mx-auto w-full animate-fade-in">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Query Logs</h1>
                    <p class="text-slate-400">Live feed of all SQL executions in the system.</p>
                </div>
                
                <!-- Filters -->
                <form class="flex gap-3 bg-white/5 p-2 rounded-xl border border-white/5">
                    <select name="user_id" class="bg-[#0a0f1a] border border-white/10 text-slate-300 text-sm rounded-lg px-3 py-2 outline-none focus:border-purple-500">
                        <option value="">All Users</option>
                        <?php foreach ($users as $u): ?>
                            <option value="<?= $u['id'] ?>" <?= $userFilter == $u['id'] ? 'selected' : '' ?>><?= htmlspecialchars($u['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="status" class="bg-[#0a0f1a] border border-white/10 text-slate-300 text-sm rounded-lg px-3 py-2 outline-none focus:border-purple-500">
                        <option value="">All Status</option>
                        <option value="success" <?= $statusFilter === 'success' ? 'selected' : '' ?>>Success</option>
                        <option value="error" <?= $statusFilter === 'error' ? 'selected' : '' ?>>Error</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white text-sm font-medium rounded-lg transition">
                        Filter
                    </button>
                    <a href="logs.php" class="px-3 py-2 text-slate-400 hover:text-white transition flex items-center">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    </a>
                </form>
            </div>

            <div class="glass-panel rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="bg-black/20 text-slate-500 border-b border-white/5">
                                <th class="py-4 px-6 font-semibold w-24">Status</th>
                                <th class="py-4 px-6 font-semibold w-40">Time</th>
                                <th class="py-4 px-6 font-semibold w-48">User</th>
                                <th class="py-4 px-6 font-semibold">Query</th>
                                <th class="py-4 px-6 font-semibold text-right w-32">Perf (ms)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 font-mono">
                            <?php if (empty($logs)): ?>
                                <tr><td colspan="5" class="py-12 text-center text-slate-500 italic">No logs found matching criteria.</td></tr>
                            <?php else: ?>
                                <?php foreach ($logs as $log): ?>
                                <tr class="group hover:bg-white/5 transition">
                                    <td class="py-3 px-6">
                                        <?php if ($log['status'] === 'success'): ?>
                                            <span class="text-green-400 text-xs font-bold">200 OK</span>
                                        <?php else: ?>
                                            <span class="text-red-400 text-xs font-bold">ERR</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-6 text-slate-500 text-xs">
                                        <?= date('H:i:s', strtotime($log['created_at'])) ?>
                                        <span class="block text-[10px] opacity-50"><?= date('Y-m-d', strtotime($log['created_at'])) ?></span>
                                    </td>
                                    <td class="py-3 px-6 text-slate-300">
                                        <?= htmlspecialchars($log['user_name']) ?>
                                    </td>
                                    <td class="py-3 px-6 w-full">
                                        <div class="max-w-2xl overflow-hidden text-ellipsis whitespace-nowrap text-cyan-200/80 group-hover:text-cyan-200 transition">
                                            <?= htmlspecialchars($log['sql_text']) ?>
                                        </div>
                                        <?php if ($log['error_message']): ?>
                                            <div class="text-xs text-red-500 mt-1"><?= htmlspecialchars($log['error_message']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-6 text-right text-slate-400">
                                        <?= round($log['execution_time_ms'], 2) ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
