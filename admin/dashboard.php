<?php
// admin/dashboard.php
require_once __DIR__ . '/../config/db_control.php';
session_start();

require_once __DIR__ . '/../config/auth_middleware.php';

// Auth Check
requireAdmin();

$pdo = getControlDB();

// Caching Logic
$cacheFile = __DIR__ . '/../cache/admin_stats.json';
$cacheTime = 300; // 5 minutes

$stats = null;
if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
    $data = file_get_contents($cacheFile);
    if ($data) {
        $stats = json_decode($data, true);
    }
}

if (!$stats) {
    // Optimization: avoid DATE() function on indexed column
    $todayStart = date('Y-m-d 00:00:00');
    $todayEnd = date('Y-m-d 23:59:59');

    // Optimization: Count only id for speed
    $stats = [
        'users' => $pdo->query("SELECT COUNT(id) FROM users")->fetchColumn(),
        'queries' => $pdo->query("SELECT COUNT(id) FROM query_logs WHERE created_at BETWEEN '$todayStart' AND '$todayEnd'")->fetchColumn(),
        'active' => $pdo->query("SELECT COUNT(id) FROM users WHERE last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE)")->fetchColumn(),
        'revenue' => number_format($pdo->query("SELECT COUNT(id) FROM users")->fetchColumn() * 9.99, 2)
    ];
    
    // Ensure cache dir exists
    if (!is_dir(dirname($cacheFile))) mkdir(dirname($cacheFile), 0777, true);
    file_put_contents($cacheFile, json_encode($stats));
}

// Chart Data (Last 7 Days)
$chartData = $pdo->query("
    SELECT DATE(created_at) as date, COUNT(id) as count 
    FROM query_logs 
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) 
    GROUP BY DATE(created_at)
")->fetchAll(PDO::FETCH_KEY_PAIR);

// Success Rate (Pie Chart Data)
$successRateData = $pdo->query("
    SELECT status, COUNT(*) as count 
    FROM query_logs 
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
    GROUP BY status
")->fetchAll(PDO::FETCH_KEY_PAIR);

$successCount = $successRateData['success'] ?? 0;
$errorCount = $successRateData['error'] ?? 0;

// Top Users (Last 24h)
$topUsers = $pdo->query("
    SELECT u.name, COUNT(l.id) as query_count 
    FROM users u 
    JOIN query_logs l ON u.id = l.user_id 
    WHERE l.created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
    GROUP BY u.id 
    ORDER BY query_count DESC 
    LIMIT 5
")->fetchAll();

// Fill gaps with 0
$labels = [];
$counts = [];
for ($i = 6; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-$i days"));
    $labels[] = date('D', strtotime($d)); // Mon, Tue...
    $counts[] = $chartData[$d] ?? 0;
}

$recentUsers = $pdo->query("SELECT id, name, email, role, status, created_at FROM users ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Admin</title>
    <link rel="icon" type="image/png" href="/assets/logo.png">
    <!-- Ad Script -->

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Space Grotesk', 'sans-serif'], mono: ['JetBrains Mono', 'monospace'], },
                    colors: { cyber: { black: '#0a0f1a', accent: '#8b5cf6', success: '#10b981', primary: '#06b6d4' } },
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
        .nav-item.active { background: rgba(139, 92, 246, 0.1); color: #a78bfa; border-right: 3px solid #8b5cf6; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0a0f1a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 3px; }
    </style>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
</head>
<body class="h-full flex overflow-hidden antialiased">

    <!-- SIDEBAR -->
    <aside id="sidebar" class="w-64 glass-sidebar flex flex-col transition-all duration-300 z-50">
        <script>
            // Apply state immediately to prevent flicker
            const savedState = localStorage.getItem('admin_sidebar_collapsed');
            if (savedState === 'true') {
                 document.getElementById('sidebar').classList.remove('w-64');
                 document.getElementById('sidebar').classList.add('w-20');
            }
        </script>
        <!-- Header -->
        <div class="h-20 flex items-center px-6 gap-3 border-b border-white/5">
            <div class="p-2 bg-gradient-to-tr from-purple-600 to-blue-600 rounded-lg shadow-lg shadow-purple-500/20">
                <i data-lucide="shield" class="w-5 h-5 text-white"></i>
            </div>
            <span class="font-bold text-lg tracking-tight sidebar-text">Admin</span>
        </div>

        <!-- Nav -->
        <nav class="flex-1 py-6 space-y-1 overflow-y-auto">
            <a href="dashboard.php" class="flex items-center px-6 py-3.5 text-sm font-medium nav-item active group">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3 group-hover:text-purple-400 transition"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>
            <a href="users.php" class="flex items-center px-6 py-3.5 text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition group">
                <i data-lucide="users" class="w-5 h-5 mr-3 group-hover:text-purple-400 transition"></i>
                <span class="sidebar-text">Users</span>
            </a>
            <a href="pages.php" class="flex items-center px-6 py-3.5 text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition group">
                <i data-lucide="file-text" class="w-5 h-5 mr-3 group-hover:text-purple-400 transition"></i>
                <span class="sidebar-text">Pages</span>
            </a>
            <a href="logs.php" class="flex items-center px-6 py-3.5 text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition group">
                <i data-lucide="database" class="w-5 h-5 mr-3 group-hover:text-purple-400 transition"></i>
                <span class="sidebar-text">Queries</span>
            </a>
            <a href="security.php" class="flex items-center px-6 py-3.5 text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition group">
                <i data-lucide="shield" class="w-5 h-5 mr-3 group-hover:text-purple-400 transition"></i>
                <span class="sidebar-text">Security</span>
            </a>
             <a href="db_tools.php" class="flex items-center px-6 py-3.5 text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition group">
                <i data-lucide="wrench" class="w-5 h-5 mr-3 group-hover:text-purple-400 transition"></i>
                <span class="sidebar-text">DB Tools</span>
            </a>
            <div class="px-6 pt-4 pb-2">
                <div class="h-px bg-white/5"></div>
            </div>
            <a href="settings.php" class="flex items-center px-6 py-3.5 text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition group">
                <i data-lucide="settings" class="w-5 h-5 mr-3 group-hover:text-purple-400 transition"></i>
                <span class="sidebar-text">Settings</span>
            </a>
        </nav>

        <!-- Footer -->
        <div class="p-4 border-t border-white/5">
            <button onclick="toggleSidebar()" class="mb-4 text-slate-500 hover:text-white transition p-2">
                <i data-lucide="chevrons-left" class="w-5 h-5 transition-transform" id="collapse-icon"></i>
            </button>
            <a href="../login.php" class="flex items-center px-4 py-2 text-sm text-red-400 hover:bg-red-500/10 rounded-lg transition sidebar-text-container">
                <i data-lucide="log-out" class="w-4 h-4 mr-2"></i>
                <span class="sidebar-text">Logout</span>
            </a>
        </div>
    </aside>

    <!-- CONTENT -->
    <main class="flex-1 flex flex-col min-w-0 bg-[#0a0f1a] relative">
        
        <!-- Header -->
        <header class="h-20 border-b border-white/5 px-8 flex items-center justify-between bg-[#0a0f1a]/50 backdrop-blur sticky top-0 z-40">
            <div>
                <h1 class="text-xl font-bold text-white">Dashboard</h1>
                <p class="text-xs text-slate-500 font-mono mt-0.5"><?= date('l, F j, Y') ?></p>
            </div>
            <div class="flex items-center gap-6">
                
                <!-- Global Search -->
                <div class="hidden md:block relative w-64">
                    <input type="text" id="global-search" placeholder="Search..." 
                        class="w-full bg-black/20 border border-white/10 rounded-full px-4 py-1.5 pl-10 text-sm text-white focus:border-purple-500 focus:bg-black/40 outline-none transition-all">
                    <i data-lucide="search" class="w-4 h-4 text-slate-500 absolute left-3 top-2"></i>
                </div>

                 <!-- System Status -->
                <div class="hidden lg:flex items-center gap-2 px-3 py-1.5 rounded-full bg-green-500/10 border border-green-500/20">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-xs font-medium text-green-400">All Systems Operational</span>
                </div>

                <div class="relative">
                    <i data-lucide="bell" class="w-5 h-5 text-slate-400 hover:text-white cursor-pointer transition"></i>
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full text-[10px] flex items-center justify-center font-bold">3</span>
                </div>

                <div class="flex items-center gap-3 pl-6 border-l border-white/5">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-bold text-white">Admin</div>
                        <div class="text-[10px] text-purple-400 uppercase tracking-wider">Super User</div>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-indigo-500 flex items-center justify-center font-bold text-sm shadow-lg shadow-purple-500/20">A</div>
                </div>
            </div>
        </header>

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto p-8">
            
            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- User Stat -->
                <div class="glass-panel p-6 rounded-2xl animate-fade-in" style="animation-delay: 0ms;">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-purple-500/10 rounded-xl text-purple-400">
                            <i data-lucide="users" class="w-6 h-6"></i>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-500/10 text-green-400">+12%</span>
                    </div>
                    <div class="text-3xl font-bold text-white mb-1"><?= $stats['users'] ?></div>
                    <div class="text-sm text-slate-500">Total Users</div>
                </div>

                <!-- Queries Stat -->
                <div class="glass-panel p-6 rounded-2xl animate-fade-in" style="animation-delay: 100ms;">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-cyan-500/10 rounded-xl text-cyan-400">
                            <i data-lucide="database" class="w-6 h-6"></i>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-500/10 text-green-400">+5%</span>
                    </div>
                    <div class="text-3xl font-bold text-white mb-1"><?= $stats['queries'] ?></div>
                    <div class="text-sm text-slate-500">Queries Today</div>
                </div>

                <!-- Activity Stat -->
                <div class="glass-panel p-6 rounded-2xl animate-fade-in" style="animation-delay: 200ms;">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-indigo-500/10 rounded-xl text-indigo-400">
                            <i data-lucide="activity" class="w-6 h-6"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-white mb-1"><?= $stats['active'] ?></div>
                    <div class="text-sm text-slate-500">Active Sessions</div>
                </div>

                <!-- Revenue Stat -->
                <div class="glass-panel p-6 rounded-2xl animate-fade-in" style="animation-delay: 300ms;">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 bg-green-500/10 rounded-xl text-green-400">
                            <i data-lucide="dollar-sign" class="w-6 h-6"></i>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-500/10 text-green-400">+8%</span>
                    </div>
                    <div class="text-3xl font-bold text-white mb-1">$<?= $stats['revenue'] ?></div>
                    <div class="text-sm text-slate-500">Revenue (Sim)</div>
                </div>
            </div>

            <!-- Main Grid -->
            <div class="grid lg:grid-cols-3 gap-8">
                
                <!-- Recent Users Table -->
                <div class="lg:col-span-2 glass-panel rounded-2xl p-6 animate-fade-in" style="animation-delay: 400ms;">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-white">Recent Users</h3>
                        <a href="users.php" class="text-xs text-purple-400 hover:text-white transition">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm whitespace-nowrap">
                            <thead>
                                <tr class="text-slate-500 border-b border-white/5">
                                    <th class="pb-3 px-4">User</th>
                                    <th class="pb-3 px-4">Status</th>
                                    <th class="pb-3 px-4">Joined</th>
                                    <th class="pb-3 px-4 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <?php foreach ($recentUsers as $u): ?>
                                <tr class="group hover:bg-white/5 transition">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-300">
                                                <?= strtoupper(substr($u['name'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div class="font-medium text-white"><?= htmlspecialchars($u['name']) ?></div>
                                                <div class="text-[10px] text-slate-500"><?= htmlspecialchars($u['email']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <?php if ($u['status'] === 'active'): ?>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">Active</span>
                                        <?php else: ?>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20">Banned</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-4 text-slate-500 font-mono text-xs"><?= date('M j', strtotime($u['created_at'])) ?></td>
                                    <td class="py-3 px-4 text-right">
                                        <button class="text-slate-500 hover:text-white transition"><i data-lucide="more-horizontal" class="w-4 h-4"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Analytics Column -->
                <div class="space-y-6">
                    <!-- Query Volume Chart -->
                    <div class="glass-panel rounded-2xl p-6 animate-fade-in" style="animation-delay: 500ms;">
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-white">Query Volume</h3>
                            <p class="text-xs text-slate-500">Last 7 days</p>
                        </div>
                        <div class="relative h-48 w-full">
                            <canvas id="queryChart"></canvas>
                        </div>
                    </div>

                    <!-- Success Rate Chart -->
                    <div class="glass-panel rounded-2xl p-6 animate-fade-in" style="animation-delay: 600ms;">
                        <div class="mb-4 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-white">Success Rate</h3>
                            <span class="text-xs font-mono text-emerald-400">24h</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="relative h-24 w-24">
                                <canvas id="successChart"></canvas>
                            </div>
                            <div class="flex-1 space-y-2">
                                <div class="flex justify-between text-xs">
                                    <span class="text-slate-400">Success</span>
                                    <span class="text-emerald-400 font-bold"><?= $successCount ?></span>
                                </div>
                                <div class="w-full bg-white/5 rounded-full h-1.5">
                                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: <?= ($successCount + $errorCount > 0) ? ($successCount / ($successCount + $errorCount) * 100) : 0 ?>%"></div>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-slate-400">Error</span>
                                    <span class="text-red-400 font-bold"><?= $errorCount ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Users -->
                    <div class="glass-panel rounded-2xl p-6 animate-fade-in" style="animation-delay: 700ms;">
                         <h3 class="text-lg font-bold text-white mb-4">Top Users (24h)</h3>
                         <div class="space-y-3">
                            <?php foreach($topUsers as $idx => $user): ?>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-bold text-slate-500">#<?= $idx + 1 ?></span>
                                    <span class="text-sm text-slate-300"><?= htmlspecialchars($user['name']) ?></span>
                                </div>
                                <span class="text-xs font-mono text-purple-400"><?= $user['query_count'] ?> queries</span>
                            </div>
                            <?php endforeach; ?>
                            <?php if(empty($topUsers)): ?>
                                <div class="text-xs text-slate-500 text-center py-2">No activity yet</div>
                            <?php endif; ?>
                         </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();
        
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const collapseIcon = document.getElementById('collapse-icon');
        let collapsed = localStorage.getItem('admin_sidebar_collapsed') === 'true';

        // Initial UI Sync
        function syncUI() {
            if (collapsed) {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20');
                document.querySelectorAll('.sidebar-text').forEach(el => el.classList.add('hidden'));
                sidebar.querySelectorAll('.group').forEach(el => el.classList.add('justify-center'));
                if(collapseIcon) collapseIcon.style.transform = 'rotate(180deg)';
                const items = sidebar.querySelectorAll('.nav-item i');
                items.forEach(i => i.classList.remove('mr-3'));
            } else {
                sidebar.classList.add('w-64');
                sidebar.classList.remove('w-20');
                document.querySelectorAll('.sidebar-text').forEach(el => el.classList.remove('hidden'));
                sidebar.querySelectorAll('.group').forEach(el => el.classList.remove('justify-center'));
                if(collapseIcon) collapseIcon.style.transform = 'rotate(0deg)';
                const items = sidebar.querySelectorAll('.nav-item i');
                items.forEach(i => i.classList.add('mr-3'));
            }
        }
        
        // Run on load
        syncUI();

        function toggleSidebar() {
            collapsed = !collapsed;
            localStorage.setItem('admin_sidebar_collapsed', collapsed);
            syncUI();
        }

        // Chart.js Mockup
        const ctx = document.getElementById('queryChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    label: 'Queries',
                    data: <?= json_encode($counts) ?>,
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { display: false },
                    y: { 
                        display: true, 
                        grid: { color: 'rgba(255,255,255,0.05)' },
                        ticks: { color: '#64748b', font: { size: 10 } }
                    }
                }
            }
        });

        // Success Rate Chart
        const ctxSuccess = document.getElementById('successChart').getContext('2d');
        new Chart(ctxSuccess, {
            type: 'doughnut',
            data: {
                labels: ['Success', 'Error'],
                datasets: [{
                    data: [<?= $successCount ?>, <?= $errorCount ?>],
                    backgroundColor: ['#10b981', '#f87171'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: { legend: { display: false } }
            }
        });

        // Global Search
        const searchInput = document.getElementById('global-search');
        if (searchInput) {
            searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    const query = searchInput.value.trim();
                    if (query) {
                        // Simple heuristic: if it looks like a file (ends in .php) go to pages, else users
                        if (query.endsWith('.php') || query.includes('page')) {
                             window.location.href = `pages.php?search=${encodeURIComponent(query)}`;
                        } else {
                             window.location.href = `users.php?search=${encodeURIComponent(query)}`;
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>
