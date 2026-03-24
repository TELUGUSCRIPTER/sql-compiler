<?php
// admin/settings.php
require_once __DIR__ . '/../config/db_control.php';
require_once __DIR__ . '/../config/auth_middleware.php';

requireAdmin();

$pdo = getControlDB();
$message = '';

// Handle Save
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $message = "Invalid CSRF Token.";
    } else {
        // Checkboxes don't send value if unchecked, so we handle explicit defaults
        $defaults = ['maintenance_mode' => '0', 'manual_approval' => '0', 'ip_registration_limit' => '0'];
        $data = array_merge($defaults, $_POST['settings'] ?? []);

        foreach ($data as $key => $value) {
            $stmt = $pdo->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
            $stmt->execute([$key, $value]);
        }
        $message = "Settings updated successfully.";
    }
}

// Fetch Settings
$settings = [];
$stmt = $pdo->query("SELECT * FROM system_settings");
while ($row = $stmt->fetch()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings | Admin</title>
    <!-- Ad Script -->

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Space Grotesk', 'sans-serif'] },
                    colors: { cyber: { black: '#0a0f1a', accent: '#8b5cf6' } }
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
            <a href="users.php" class="flex items-center px-6 py-3 text-slate-400 hover:bg-white/5"><i data-lucide="users" class="w-5 h-5 mr-3"></i>Users</a>
            <a href="settings.php" class="flex items-center px-6 py-3 text-purple-400 bg-purple-500/10 border-r-2 border-purple-500"><i data-lucide="settings" class="w-5 h-5 mr-3"></i>Settings</a>
            <a href="security.php" class="flex items-center px-6 py-3 text-slate-400 hover:bg-white/5"><i data-lucide="shield" class="w-5 h-5 mr-3"></i>Security</a>
            <a href="db_tools.php" class="flex items-center px-6 py-3 text-slate-400 hover:bg-white/5"><i data-lucide="database" class="w-5 h-5 mr-3"></i>DB Tools</a>
        </nav>
    </aside>

    <main class="flex-1 overflow-y-auto p-8">
        <h1 class="text-3xl font-bold mb-8">System Configuration</h1>

        <?php if ($message): ?>
            <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-lg">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="max-w-2xl space-y-8">
            <?= csrfInput() ?>
            <!-- Access Control -->
            <div class="p-6 rounded-2xl bg-white/5 border border-white/5 border-l-4 border-l-blue-500">
                <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <i data-lucide="users" class="w-5 h-5 text-blue-400"></i> Access & Registration
                </h2>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-black/20 rounded-lg border border-white/5">
                        <div>
                            <div class="font-bold text-slate-200">Manual Account Approval</div>
                            <div class="text-xs text-slate-500">New accounts must be approved by admin before logging in.</div>
                        </div>
                        <input type="checkbox" name="settings[manual_approval]" value="1" <?= ($settings['manual_approval'] ?? '0') == '1' ? 'checked' : '' ?> class="accent-blue-500 w-5 h-5">
                    </div>
                    <div class="flex items-center justify-between p-3 bg-black/20 rounded-lg border border-white/5">
                        <div>
                            <div class="font-bold text-slate-200">IP Registration Limit</div>
                            <div class="text-xs text-slate-500">Enable to restrict to 1 account per IP. (When disabled, multiple allowed)</div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="settings[ip_registration_limit]" value="1" <?= ($settings['ip_registration_limit'] ?? '0') == '1' ? 'checked' : '' ?> class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-500"></div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="p-6 rounded-2xl bg-white/5 border border-white/5">
                <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <i data-lucide="globe" class="w-5 h-5 text-purple-400"></i> General
                </h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-slate-400 mb-1">Site Name</label>
                        <input type="text" name="settings[site_name]" value="<?= $settings['site_name'] ?? 'SQL Playground' ?>" 
                            class="w-full bg-black/20 border border-white/10 rounded px-3 py-2 text-white focus:border-purple-500 outline-none">
                    </div>
                    <div class="flex items-center gap-3 mt-4">
                        <input type="checkbox" name="settings[maintenance_mode]" value="1" id="maint" <?= ($settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : '' ?> class="accent-purple-500 w-5 h-5">
                        <label for="maint" class="text-sm text-slate-300">Enable Maintenance Mode</label>
                    </div>
                </div>
            </div>

            <div class="p-6 rounded-2xl bg-white/5 border border-white/5">
                <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <i data-lucide="cpu" class="w-5 h-5 text-purple-400"></i> Execution Limits
                </h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-slate-400 mb-1">Query Timeout (ms)</label>
                        <input type="number" name="settings[query_timeout_ms]" value="<?= $settings['query_timeout_ms'] ?? '5000' ?>" 
                            class="w-full bg-black/20 border border-white/10 rounded px-3 py-2 text-white focus:border-purple-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm text-slate-400 mb-1">Max Query Length (chars)</label>
                        <input type="number" name="settings[max_query_length]" value="<?= $settings['max_query_length'] ?? '10000' ?>" 
                            class="w-full bg-black/20 border border-white/10 rounded px-3 py-2 text-white focus:border-purple-500 outline-none">
                    </div>
                </div>
            </div>

            <button type="submit" class="px-6 py-3 bg-purple-600 hover:bg-purple-500 text-white font-bold rounded-lg shadow-lg shadow-purple-500/20 transition">
                Save Changes
            </button>
        </form>
    </main>
    <script>lucide.createIcons();</script>
</body>
</html>
