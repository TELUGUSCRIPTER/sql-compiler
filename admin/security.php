<?php
// admin/security.php
require_once __DIR__ . '/../config/db_control.php';
require_once __DIR__ . '/../config/auth_middleware.php';

requireAdmin();

$pdo = getControlDB();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
         $message = "Invalid CSRF Token.";
    } else {
        $keywords = $_POST['banned_keywords'] ?? '';
        // Normalize and clean
        $keywords = implode(',', array_map('trim', explode(',', $keywords)));
        
        $stmt = $pdo->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES ('banned_keywords', ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
        $stmt->execute([$keywords]);
        $message = "Security policy updated.";
    }
}

$stmt = $pdo->prepare("SELECT setting_value FROM system_settings WHERE setting_key = 'banned_keywords'");
$stmt->execute();
$bannedKeywords = $stmt->fetchColumn() ?: '';
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Policies | Admin</title>
    <!-- Ad Script -->

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Space Grotesk', 'sans-serif'] },
                    colors: { cyber: { black: '#0a0f1a', accent: '#8b5cf6', danger: '#ef4444' } }
                }
            }
        }
    </script>
    <style>body { background-color: #0a0f1a; color: #fff; }</style>
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
            <a href="security.php" class="flex items-center px-6 py-3 text-purple-400 bg-purple-500/10 border-r-2 border-purple-500"><i data-lucide="shield" class="w-5 h-5 mr-3"></i>Security</a>
            <a href="db_tools.php" class="flex items-center px-6 py-3 text-slate-400 hover:bg-white/5"><i data-lucide="database" class="w-5 h-5 mr-3"></i>DB Tools</a>
        </nav>
    </aside>

    <main class="flex-1 overflow-y-auto p-8">
        <h1 class="text-3xl font-bold mb-8">Security Policies</h1>

        <?php if ($message): ?>
            <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-lg">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <div class="grid lg:grid-cols-2 gap-8">
            <form method="POST" class="space-y-8">
                <?= csrfInput() ?>
                <div class="p-6 rounded-2xl bg-white/5 border border-white/5 border-l-4 border-l-red-500">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2 text-white">
                        <i data-lucide="ban" class="w-5 h-5 text-red-500"></i> Blacklisted Keywords
                    </h2>
                    <p class="text-sm text-slate-400 mb-4">Any query containing these words (case-insensitive) will be blocked immediately.</p>
                    
                    <div>
                        <label class="block text-sm text-slate-400 mb-2">Keywords (comma separated)</label>
                        <textarea name="banned_keywords" rows="5" class="w-full bg-black/40 border border-white/10 rounded-lg p-4 font-mono text-sm text-red-300 focus:border-red-500 outline-none"><?= htmlspecialchars($bannedKeywords) ?></textarea>
                    </div>
                    
                    <button type="submit" class="mt-4 px-6 py-3 bg-red-600 hover:bg-red-500 text-white font-bold rounded-lg shadow-lg shadow-red-500/20 transition">
                        Update Policy
                    </button>
                </div>
            </form>
            
            <div class="p-6 rounded-2xl bg-white/5 border border-white/5">
                <h2 class="text-xl font-bold mb-4 text-white">Active Restrictions</h2>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-center justify-between p-3 bg-black/20 rounded">
                        <span class="text-slate-400">Cross-Database Access</span>
                        <span class="text-green-400 font-bold font-mono">BLOCKED</span>
                    </li>
                    <li class="flex items-center justify-between p-3 bg-black/20 rounded">
                        <span class="text-slate-400">File System Access</span>
                        <span class="text-green-400 font-bold font-mono">BLOCKED</span>
                    </li>
                    <li class="flex items-center justify-between p-3 bg-black/20 rounded">
                        <span class="text-slate-400">Stored Procedures</span>
                        <span class="text-orange-400 font-bold font-mono">LIMITED</span>
                    </li>
                </ul>
            </div>
        </div>
    </main>
    <script>lucide.createIcons();</script>
</body>
</html>
