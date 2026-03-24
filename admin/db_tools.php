<?php
// admin/db_tools.php
require_once __DIR__ . '/../config/db_control.php';
require_once __DIR__ . '/../config/auth_middleware.php';

requireAdmin();

$pdo = getControlDB();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid CSRF Token.";
    } else {
        $action = $_POST['action'] ?? '';
    
        if ($action === 'nuke_all_user_dbs') {
        // Dangerous: Drops all user_databases
        try {
            $dbs = $pdo->query("SELECT db_name FROM user_databases")->fetchAll(PDO::FETCH_COLUMN);
            foreach ($dbs as $db) {
                $pdo->exec("DROP DATABASE IF EXISTS `$db`");
                // Recreate empty
                $pdo->exec("CREATE DATABASE `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            }
            $message = "All user databases have been reset to empty state.";
        } catch (Exception $e) { $error = $e->getMessage(); }
    } elseif ($action === 'clear_history') {
        try {
            $pdo->exec("TRUNCATE TABLE query_logs");
            $pdo->exec("TRUNCATE TABLE saved_queries");
            $message = "System query logs and saved user queries have been cleared.";
        } catch (Exception $e) { $error = "Failed to clear history: " . $e->getMessage(); }
    }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DB Tools | Admin</title>
    <link rel="icon" type="image/png" href="/assets/logo.png">
    <!-- Ad Script -->

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { background-color: #0a0f1a; color: #fff; font-family: 'Space Grotesk', sans-serif; }</style>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
</head>
<body class="h-full flex overflow-hidden">
    
    <aside class="w-64 bg-[#0a0f1a] border-r border-white/5 flex flex-col">
        <div class="h-20 flex items-center px-6 gap-3 border-b border-white/5">
             <span class="font-bold text-lg">Admin<span class="text-purple-400">Panel</span></span>
        </div>
        <nav class="flex-1 py-6 space-y-1">
            <a href="dashboard.php" class="flex items-center px-6 py-3 text-slate-400 hover:bg-white/5"><i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>Dashboard</a>
            <a href="settings.php" class="flex items-center px-6 py-3 text-slate-400 hover:bg-white/5"><i data-lucide="settings" class="w-5 h-5 mr-3"></i>Settings</a>
            <a href="security.php" class="flex items-center px-6 py-3 text-slate-400 hover:bg-white/5"><i data-lucide="shield" class="w-5 h-5 mr-3"></i>Security</a>
            <a href="db_tools.php" class="flex items-center px-6 py-3 text-purple-400 bg-purple-500/10 border-r-2 border-purple-500"><i data-lucide="database" class="w-5 h-5 mr-3"></i>DB Tools</a>
        </nav>
    </aside>

    <main class="flex-1 overflow-y-auto p-8">
        <h1 class="text-3xl font-bold mb-8">Database Tools</h1>

        <?php if ($message): ?>
            <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-lg flex items-center gap-2">
                <i data-lucide="check" class="w-5 h-5"></i> <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg flex items-center gap-2">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Danger Zone -->
            <div class="p-6 rounded-2xl border border-red-500/30 bg-red-500/5">
                <h2 class="text-xl font-bold text-red-400 mb-4 flex items-center gap-2">
                    <i data-lucide="alert-octagon" class="w-5 h-5"></i> Danger Zone
                </h2>
                
                <div class="space-y-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-bold text-white">Reset All User Databases</h3>
                            <p class="text-sm text-slate-400">Drops and recreates every user database. Data loss is permanent.</p>
                        </div>
                        <form method="POST" onsubmit="return confirm('EXTREME WARNING: This will delete ALL data in EVERY user database. Are you sure?');">
                            <?= csrfInput() ?>
                            <input type="hidden" name="action" value="nuke_all_user_dbs">
                            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-lg transition">Nuke All</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Utilities -->
            <div class="p-6 rounded-2xl border border-white/5 bg-white/5">
                <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                    <i data-lucide="wrench" class="w-5 h-5 text-purple-400"></i> Utilities
                </h2>
                
                <div class="space-y-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-bold text-white">Clear System History</h3>
                            <p class="text-sm text-slate-400">Truncates global query logs AND <strong class="text-orange-400">all user saved queries</strong>.</p>
                        </div>
                        <form method="POST" onsubmit="return confirm('WARNING: This will delete ALL query logs and ALL user saved queries. This cannot be undone. Are you sure?');">
                            <?= csrfInput() ?>
                            <input type="hidden" name="action" value="clear_history">
                            <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold rounded-lg transition">Clear All History</button>
                        </form>
                    </div>

                    <div class="flex items-start justify-between pt-6 border-t border-dashed border-white/10">
                        <div>
                            <h3 class="font-bold text-white">Optimize Control DB</h3>
                            <p class="text-sm text-slate-400">Rebuild indexes and clean up overhead on the main system DB.</p>
                        </div>
                        <button disabled class="px-4 py-2 bg-slate-700 text-slate-400 text-sm font-bold rounded-lg cursor-not-allowed">Auto-Scheduled</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>lucide.createIcons();</script>
</body>
</html>
