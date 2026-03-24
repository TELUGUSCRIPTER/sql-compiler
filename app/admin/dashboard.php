<?php
// app/admin/dashboard.php
require_once __DIR__ . '/../../config/auth_middleware.php';
require_once __DIR__ . '/../../config/db_control.php';
requireAdmin();

$pdo = getControlDB();

// Stats
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
$activeUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user' AND status='active'")->fetchColumn();
$bannedUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user' AND status='banned'")->fetchColumn();
$queryCount = $pdo->query("SELECT COUNT(*) FROM query_logs WHERE created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)")->fetchColumn();
$avgTime = $pdo->query("SELECT AVG(execution_time_ms) FROM query_logs")->fetchColumn();

// Security Stats
$registrationAttempts24h = 0;
$failedAttempts24h = 0;
try {
    $registrationAttempts24h = $pdo->query("SELECT COUNT(*) FROM registration_attempts WHERE attempted_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)")->fetchColumn();
   $failedAttempts24h = $pdo->query("SELECT COUNT(*) FROM registration_attempts WHERE success = 0 AND attempted_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)")->fetchColumn();
} catch (Exception $e) {
    // Table might not exist
}

// Chart Data: User registrations last 7 days
$regData = $pdo->query("
    SELECT DATE(created_at) as date, COUNT(*) as count
    FROM users WHERE role = 'user'
    AND created_at > DATE_SUB(NOW(), INTERVAL 7 DAY)
    GROUP BY DATE(created_at)
    ORDER BY date ASC
")->fetchAll();

$regDates = array_map(fn($r) => date('M d', strtotime($r['date'])), $regData);
$regCounts = array_map(fn($r) => $r['count'], $regData);

// Chart Data: Query success/failure
$queryStats = $pdo->query("
    SELECT status, COUNT(*) as count
    FROM query_logs
    GROUP BY status
")->fetchAll();

$successCount = 0;
$errorCount = 0;
foreach ($queryStats as $stat) {
    if ($stat['status'] === 'success') $successCount = $stat['count'];
    if ($stat['status'] === 'error') $errorCount = $stat['count'];
}

// Recent Users
$recentUsers = $pdo->query("
    SELECT id, name, email, created_at, status 
    FROM users 
    WHERE role = 'user' 
    ORDER BY created_at DESC 
    LIMIT 5
")->fetchAll();

// Most Active Users (by query count)
$activeUsersTop = $pdo->query("
    SELECT u.name, COUNT(q.id) as query_count
    FROM users u
    LEFT JOIN query_logs q ON u.id = q.user_id
    WHERE u.role = 'user'
    GROUP BY u.id, u.name
    ORDER BY query_count DESC
    LIMIT 5
")->fetchAll();

$activeUserNames = array_map(fn($u) => $u['name'], $activeUsersTop);
$activeUserCounts = array_map(fn($u) => $u['query_count'], $activeUsersTop);

$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/admin_nav.php';
?>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase">Total Users</p>
                <p class="text-4xl font-bold text-gray-900 mt-2"><?= $totalUsers ?></p>
                <p class="text-xs text-gray-500 mt-2">All registered users</p>
            </div>
            <div class="bg-blue-100 rounded-full p-4">
                <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Active Users -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase">Active Users</p>
                <p class="text-4xl font-bold text-green-600 mt-2"><?= $activeUsers ?></p>
                <p class="text-xs text-gray-500 mt-2"><?= round(($activeUsers/$totalUsers)*100, 1) ?>% of total</p>
            </div>
            <div class="bg-green-100 rounded-full p-4">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Queries (24h) -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500 hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase">Queries (24h)</p>
                <p class="text-4xl font-bold text-purple-600 mt-2"><?= number_format($queryCount) ?></p>
                <p class="text-xs text-gray-500 mt-2">Last 24 hours</p>
            </div>
            <div class="bg-purple-100 rounded-full p-4">
                <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
        </div>
    </div>
    
    <!-- Avg Latency -->
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500 hover:shadow-xl transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 uppercase">Avg Latency</p>
                <p class="text-4xl font-bold text-yellow-600 mt-2"><?= round($avgTime ?: 0, 1) ?> <span class="text-lg text-gray-500">ms</span></p>
                <p class="text-xs text-gray-500 mt-2">Query performance</p>
            </div>
            <div class="bg-yellow-100 rounded-full p-4">
                <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Registration Trend Chart -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">User Registrations (Last 7 Days)</h3>
        <canvas id="registrationChart"></canvas>
    </div>
    
    <!-- Query Success Rate -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Query Success Rate</h3>
        <canvas id="queryChart"></canvas>
    </div>
</div>

<!-- Most Active Users Chart -->
<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Top 5 Most Active Users</h3>
    <canvas id="activeUsersChart" style="max-height: 300px;"></canvas>
</div>

<!-- Secondary Stats & Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Security Stats -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-700 uppercase">Security (24h)</h3>
            <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2 py-1 rounded">New</span>
        </div>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Total Attempts</span>
                <span class="text-lg font-bold text-gray-900"><?= $registrationAttempts24h ?></span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Failed</span>
                <span class="text-lg font-bold text-red-600"><?= $failedAttempts24h ?></span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Success Rate</span>
                <span class="text-lg font-bold text-green-600">
                    <?= $registrationAttempts24h > 0 ? round((($registrationAttempts24h - $failedAttempts24h) / $registrationAttempts24h) * 100, 1) : 0 ?>%
                </span>
            </div>
        </div>
        <a href="security.php" class="mt-4 block text-center text-sm text-purple-600 hover:text-purple-800 font-medium">View Details →</a>
    </div>
    
    <!-- Banned Users -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-700 uppercase">Banned Users</h3>
            <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded">Alert</span>
        </div>
        <div class="text-center">
            <p class="text-5xl font-bold text-red-600"><?= $bannedUsers ?></p>
            <p class="text-sm text-gray-500 mt-2"><?= round(($bannedUsers / max($totalUsers, 1)) * 100, 1) ?>% of total</p>
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-xs text-gray-600">Requires review if count increases</p>
            </div>
        </div>
    </div>
    
    <!-- System Status -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-gray-700 uppercase">System Status</h3>
            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">Healthy</span>
        </div>
        <div class="space-y-3">
            <div class="flex items-center">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                <span class="text-sm text-gray-700">Database Connected</span>
            </div>
            <div class="flex items-center">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                <span class="text-sm text-gray-700">Queries Running</span>
            </div>
            <div class="flex items-center">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                <span class="text-sm text-gray-700">Security Active</span>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-200">
            <p class="text-xs text-gray-500">Last checked: Just now</p>
        </div>
    </div>
</div>

<!-- Recent Users & Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Users -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Recent Registrations</h3>
        </div>
        <div class="divide-y divide-gray-200">
            <?php if (empty($recentUsers)): ?>
                <div class="px-6 py-8 text-center text-gray-500">
                    No users yet
                </div>
            <?php else: ?>
                <?php foreach ($recentUsers as $user): ?>
                    <div class="px-6 py-4 hover:bg-gray-50 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate"><?= htmlspecialchars($user['name']) ?></p>
                                <p class="text-sm text-gray-500 truncate"><?= htmlspecialchars($user['email']) ?></p>
                            </div>
                            <div class="flex items-center space-x-3 ml-4">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded <?= $user['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= $user['status'] ?>
                                </span>
                                <span class="text-xs text-gray-400 whitespace-nowrap"><?= date('M d', strtotime($user['created_at'])) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
            <a href="users.php" class="text-sm font-medium text-purple-600 hover:text-purple-800">
                View all users →
            </a>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
        </div>
        <div class="p-6 space-y-4">
            <a href="users.php" class="block p-4 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition group">
                <div class="flex items-center">
                    <div class="bg-purple-100 rounded-lg p-3 group-hover:bg-purple-200 transition">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-semibold text-gray-900">Manage Users</p>
                        <p class="text-xs text-gray-500">View, edit, and manage user accounts</p>
                    </div>
                </div>
            </a>
            
            <a href="security.php" class="block p-4 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition group">
                <div class="flex items-center">
                    <div class="bg-red-100 rounded-lg p-3 group-hover:bg-red-200 transition">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-semibold text-gray-900">Security Monitor</p>
                        <p class="text-xs text-gray-500">View registration attempts and blocked IPs</p>
                    </div>
                </div>
            </a>
            
            <a href="logs.php" class="block p-4 border-2 border-gray-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition group">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-lg p-3 group-hover:bg-blue-200 transition">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-semibold text-gray-900">Query Logs</p>
                        <p class="text-xs text-gray-500">Inspect SQL query history and performance</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Charts JavaScript -->
<script>
// Registration Trend Chart
const regCtx = document.getElementById('registrationChart').getContext('2d');
new Chart(regCtx, {
    type: 'line',
    data: {
        labels: <?= json_encode($regDates) ?>,
        datasets: [{
            label: 'New Users',
            data: <?= json_encode($regCounts) ?>,
            borderColor: 'rgb(147, 51, 234)',
            backgroundColor: 'rgba(147, 51, 234, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// Query Success Chart
const queryCtx = document.getElementById('queryChart').getContext('2d');
new Chart(queryCtx, {
    type: 'doughnut',
    data: {
        labels: ['Success', 'Errors'],
        datasets: [{
            data: [<?= $successCount ?>, <?= $errorCount ?>],
            backgroundColor: ['rgb(16, 185, 129)', 'rgb(239, 68, 68)']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

// Active Users Chart
const activeCtx = document.getElementById('activeUsersChart').getContext('2d');
new Chart(activeCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($activeUserNames) ?>,
        datasets: [{
            label: 'Query Count',
            data: <?= json_encode($activeUserCounts) ?>,
            backgroundColor: 'rgba(59, 130, 246, 0.8)',
            borderColor: 'rgb(59, 130, 246)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        scales: {
            y: { beginAtZero: true }
        },
        plugins: {
            legend: { display: false }
        }
    }
});
</script>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
