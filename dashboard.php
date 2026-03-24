<?php
// dashboard.php
require_once __DIR__ . '/config/auth_middleware.php';
require_once __DIR__ . '/config/db_control.php';
requireLogin();

$userId = getCurrentUserId();
$name = $_SESSION['name'];
$dbName = $_SESSION['db_name'];

// Data is now fetched via API for skeleton effect
require_once 'includes/header.php';
?>

<div class="max-w-6xl mx-auto px-6 py-10 animate-fade-in relative">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-4">
        <div>
            <h1 class="text-4xl font-bold text-white mb-2">Overview</h1>
            <p class="text-slate-400">Welcome back, <span class="text-cyan-400 font-semibold"><?= htmlspecialchars($name) ?></span>.</p>
        </div>
        <div class="flex gap-3">
            <a href="editor.php" class="px-6 py-2.5 bg-cyan-600 hover:bg-cyan-500 text-white font-semibold rounded-lg shadow-lg shadow-cyan-500/20 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Query
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12" id="stats-grid">
        <!-- Card 1: Storage -->
        <div class="glass-card p-6 rounded-2xl relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-cyan-500/10 rounded-bl-full group-hover:bg-cyan-500/20 transition"></div>
            <div class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Storage Used</div>
            
            <!-- Skeleton -->
            <div class="skeleton-loader h-8 w-24 bg-white/10 rounded animate-pulse mb-1"></div>
            
            <!-- Content (Hidden) -->
            <div class="stat-content hidden">
                 <div class="text-3xl font-mono font-bold text-white mb-1"><span id="stat-storage">0</span> <span class="text-base text-slate-500 font-sans font-normal">MB</span></div>
                 <div class="flex items-center text-xs text-cyan-400 gap-1">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/></svg>
                    <span>Healthy</span>
                </div>
            </div>
        </div>
        
        <!-- Card 2: Queries -->
        <div class="glass-card p-6 rounded-2xl relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-purple-500/10 rounded-bl-full group-hover:bg-purple-500/20 transition"></div>
            <div class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Queries (24h)</div>
            
            <!-- Skeleton -->
            <div class="skeleton-loader h-8 w-16 bg-white/10 rounded animate-pulse mb-1"></div>

            <!-- Content -->
            <div class="stat-content hidden">
                <div class="text-3xl font-mono font-bold text-white mb-1" id="stat-queries">0</div>
                <div class="text-xs text-slate-500">Executions</div>
            </div>
        </div>

        <!-- Card 3: Latency -->
        <div class="glass-card p-6 rounded-2xl relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-24 h-24 bg-pink-500/10 rounded-bl-full group-hover:bg-pink-500/20 transition"></div>
            <div class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Avg Latency</div>
            
             <!-- Skeleton -->
            <div class="skeleton-loader h-8 w-20 bg-white/10 rounded animate-pulse mb-1"></div>

            <!-- Content -->
            <div class="stat-content hidden">
                <div class="text-3xl font-mono font-bold text-white mb-1"><span id="stat-latency">0</span> <span class="text-base text-slate-500 font-sans font-normal">ms</span></div>
                <div class="text-xs text-slate-500">Fast response</div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            fetch('api/get_dashboard_stats.php')
                .then(r => r.json())
                .then(data => {
                    // Update Values
                    document.getElementById('stat-storage').textContent = data.db_size;
                    document.getElementById('stat-queries').textContent = data.query_count;
                    document.getElementById('stat-latency').textContent = data.avg_latency;

                    // Toggle Skeletons
                    document.querySelectorAll('.skeleton-loader').forEach(el => el.classList.add('hidden'));
                    document.querySelectorAll('.stat-content').forEach(el => {
                        el.classList.remove('hidden');
                        el.classList.add('animate-fade-in');
                    });
                })
                .catch(err => console.error('Failed to load stats', err));
        });
    </script>

    <!-- Quick Actions -->
    <h2 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
        <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        Quick Actions
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="editor.php" class="glass-card p-8 rounded-2xl border-l-4 border-l-cyan-500 hover:bg-white/5 transition group">
            <h3 class="text-xl font-bold text-white mb-2 group-hover:text-cyan-400 transition">Open SQL Editor</h3>
            <p class="text-slate-400 text-sm">Access the IDE to write queries, create tables, and visualize data.</p>
        </a>
        <a href="history.php" class="glass-card p-8 rounded-2xl border-l-4 border-l-purple-500 hover:bg-white/5 transition group">
            <h3 class="text-xl font-bold text-white mb-2 group-hover:text-purple-400 transition">Audit Logs</h3>
            <p class="text-slate-400 text-sm">Review your past executions and performance metrics.</p>
        </a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
