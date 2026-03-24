<?php
// app/admin/security.php  
require_once __DIR__ . '/../../config/auth_middleware.php';
require_once __DIR__ . '/../../config/db_control.php';
requireAdmin();

$pdo = getControlDB();

// Filters
$filter = $_GET['filter'] ?? 'all';
$search = $_GET['search'] ?? '';  
$sort = $_GET['sort'] ?? 'attempted_at';
$order = $_GET['order'] ?? 'DESC';
$perPage = (int)($_GET['per_page'] ?? 50);
$page = (int)($_GET['page'] ?? 1);
$offset = ($page - 1) * $perPage;

// Validate
$allowedSort = ['id', 'ip_address', 'email', 'success', 'attempted_at'];
if (!in_array($sort, $allowedSort)) $sort = 'attempted_at';
$order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

// Build query
$where = "1=1";
$params = [];

if ($filter === 'failed') {
    $where .= " AND success = 0";
} elseif ($filter === 'success') {
    $where .= " AND success = 1";
}

if (!empty($search)) {
    $where .= " AND (email LIKE ? OR ip_address LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Get total count
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM registration_attempts WHERE $where");
$countStmt->execute($params);
$totalAttempts = $countStmt->fetchColumn();
$totalPages = ceil($totalAttempts / $perPage);

// Fetch attempts
$stmt = $pdo->prepare("
    SELECT * FROM registration_attempts 
    WHERE $where 
    ORDER BY $sort $order 
    LIMIT $perPage OFFSET $offset
");
$stmt->execute($params);
$attempts = $stmt->fetchAll();

// Stats
$allAttempts = $pdo->query("SELECT COUNT(*) FROM registration_attempts")->fetchColumn();
$successCount = $pdo->query("SELECT COUNT(*) FROM registration_attempts WHERE success = 1")->fetchColumn();
$failedCount = $pdo->query("SELECT COUNT(*) FROM registration_attempts WHERE success = 0")->fetchColumn();
$last24h = $pdo->query("SELECT COUNT(*) FROM registration_attempts WHERE attempted_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)")->fetchColumn();

// Top failure reasons
$topReasons = $pdo->query("
    SELECT failure_reason, COUNT(*) as count 
    FROM registration_attempts 
    WHERE success = 0 AND failure_reason IS NOT NULL 
    GROUP BY failure_reason 
    ORDER BY count DESC 
    LIMIT 5
")->fetchAll();

// Chart: Attempts by hour (last 24h)
$hourlyData = $pdo->query("
    SELECT HOUR(attempted_at) as hour, COUNT(*) as count, SUM(success = 0) as failures
    FROM registration_attempts
    WHERE attempted_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)
    GROUP BY HOUR(attempted_at)
    ORDER BY hour ASC
")->fetchAll();

$hours = array_map(fn($h) => str_pad($h['hour'], 2, '0', STR_PAD_LEFT) . ':00', $hourlyData);
$totalCounts = array_map(fn($h) => $h['count'], $hourlyData);
$failureCounts = array_map(fn($h) => $h['failures'], $hourlyData);

$pageTitle = 'Security Monitor';
require_once __DIR__ . '/includes/admin_nav.php';
?>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500 hover:shadow-xl transition">
        <p class="text-sm font-medium text-gray-600 uppercase">Total Attempts</p>
        <p class="text-4xl font-bold text-gray-900 mt-2"><?= number_format($allAttempts) ?></p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:shadow-xl transition">
        <p class="text-sm font-medium text-gray-600 uppercase">Successful</p>
        <p class="text-4xl font-bold text-green-600 mt-2"><?= number_format($successCount) ?></p>
        <p class="text-xs text-gray-500 mt-2"><?= round(($successCount/max($allAttempts,1))*100, 1) ?>% success rate</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500 hover:shadow-xl transition">
        <p class="text-sm font-medium text-gray-600 uppercase">Blocked</p>
        <p class="text-4xl font-bold text-red-600 mt-2"><?=number_format($failedCount) ?></p>
        <p class="text-xs text-gray-500 mt-2"><?= round(($failedCount/max($allAttempts,1))*100, 1) ?>% blocked</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 hover:shadow-xl transition">
        <p class="text-sm font-medium text-gray-600 uppercase">Last 24h</p>
        <p class="text-4xl font-bold text-blue-600 mt-2"><?= number_format($last24h) ?></p>
    </div>
</div>

<!-- Chart: Hourly Activity -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Registration Activity (Last 24 Hours)</h3>
    <canvas id="hourlyChart"></canvas>
</div>

<!-- Filter & Search Bar -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" class="flex flex-col lg:flex-row gap-4 items-end">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                   placeholder="Search by email or IP..." 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Filter</label>
            <select name="filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>All Attempts</option>
                <option value="success" <?= $filter === 'success' ? 'selected' : '' ?>>Successful Only</option>
                <option value="failed" <?= $filter === 'failed' ? 'selected' : '' ?>>Failed Only</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Per Page</label>
            <select name="per_page" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                <option value="25" <?= $perPage === 25 ? 'selected' : '' ?>>25</option>
                <option value="50" <?= $perPage === 50 ? 'selected' : '' ?>>50</option>
                <option value="100" <?= $perPage === 100 ? 'selected' : '' ?>>100</option>
            </select>
        </div>
        
        <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
        <input type="hidden" name="order" value="<?= htmlspecialchars($order) ?>">
        
        <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-medium">
            Filter
        </button>
        
        <?php if ($search || $filter !== 'all' || $perPage !== 50): ?>
            <a href="security.php" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium text-center">
                Clear
            </a>
        <?php endif; ?>
        
        <a href="export_csv.php?type=security" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export CSV
        </a>
    </form>
</div>

<!-- Top Failure Reasons -->
<?php if (!empty($topReasons)): ?>
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Blocking Reasons</h3>
    <div class="space-y-3">
        <?php foreach ($topReasons as $reason): ?>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                <span class="text-sm text-gray-700 font-medium"><?= htmlspecialchars($reason['failure_reason']) ?></span>
                <span class="bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full">
                    <?= number_format($reason['count']) ?> times
                </span>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Registration Attempts Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Registration Attempts (<?= $totalAttempts ?> total)</h3>
            <p class="text-sm text-gray-500">Showing <?= min($offset + 1, $totalAttempts) ?>-<?= min($offset + $perPage, $totalAttempts) ?> of <?= $totalAttempts ?></p>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 cursor-pointer hover:bg-gray-100" onclick="sortTable('id')">
                        ID <?= $sort === 'id' ? ($order === 'ASC' ? '↑' : '↓') : '' ?>
                    </th>
                    <th class="px-4 py-3 cursor-pointer hover:bg-gray-100" onclick="sortTable('attempted_at')">
                        Time <?= $sort === 'attempted_at' ? ($order === 'ASC' ? '↑' : '↓') : '' ?>
                    </th>
                    <th class="px-4 py-3 cursor-pointer hover:bg-gray-100" onclick="sortTable('email')">
                        Email <?= $sort === 'email' ? ($order === 'ASC' ? '↑' : '↓') : '' ?>
                    </th>
                    <th class="px-4 py-3 cursor-pointer hover:bg-gray-100" onclick="sortTable('ip_address')">
                        IP Address <?= $sort === 'ip_address' ? ($order === 'ASC' ? '↑' : '↓') : '' ?>
                    </th>
                    <th class="px-4 py-3 hidden lg:table-cell">Device FP</th>
                    <th class="px-4 py-3 cursor-pointer hover:bg-gray-100" onclick="sortTable('success')">
                        Status <?= $sort === 'success' ? ($order === 'ASC' ? '↑' : '↓') : '' ?>
                    </th>
                    <th class="px-4 py-3">Reason</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (empty($attempts)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            No registration attempts found
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($attempts as $attempt): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-500 font-mono text-xs">
                                #<?= $attempt['id'] ?>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-gray-500 text-xs">
                                <?= date('M d, H:i', strtotime($attempt['attempted_at'])) ?>
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-mono text-xs text-gray-900">
                                    <?= htmlspecialchars($attempt['email'] ?? 'N/A') ?>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">
                                    <?= htmlspecialchars($attempt['ip_address']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-400 hidden lg:table-cell" title="<?= htmlspecialchars($attempt['device_fingerprint']) ?>">
                                <?= $attempt['device_fingerprint'] ? substr($attempt['device_fingerprint'], 0, 12) . '...' : 'N/A' ?>
                            </td>
                            <td class="px-4 py-3">
                                <?php if ($attempt['success']): ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">
                                        ✓ Success
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">
                                        ✗ Blocked
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-600 max-w-xs truncate" title="<?= htmlspecialchars($attempt['failure_reason'] ?? '') ?>">
                                <?= $attempt['failure_reason'] ? htmlspecialchars($attempt['failure_reason']) : '-' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-700">
                Page <?= $page ?> of <?= $totalPages ?>
            </div>
            <div class="flex gap-2 flex-wrap justify-center">
                <?php if ($page > 1): ?>
                    <a href="?page=1&search=<?= urlencode($search) ?>&filter=<?= $filter ?>&sort=<?= $sort ?>&order=<?= $order ?>&per_page=<?= $perPage ?>" 
                       class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">First</a>
                    <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&filter=<?= $filter ?>&sort=<?= $sort ?>&order=<?= $order ?>&per_page=<?= $perPage ?>" 
                       class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">Previous</a>
                <?php endif; ?>
                
                <?php 
                $start = max(1, $page - 2);
                $end = min($totalPages, $page + 2);
                for ($i = $start; $i <= $end; $i++): 
                ?>
                    <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&filter=<?= $filter ?>&sort=<?= $sort ?>&order=<?= $order ?>&per_page=<?= $perPage ?>" 
                       class="px-3 py-2 border rounded-lg text-sm <?= $i === $page ? 'bg-purple-600 text-white border-purple-600' : 'bg-white border-gray-300 hover:bg-gray-50' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&filter=<?= $filter ?>&sort=<?= $sort ?>&order=<?= $order ?>&per_page=<?= $perPage ?>" 
                       class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">Next</a>
                    <a href="?page=<?= $totalPages ?>&search=<?= urlencode($search) ?>&filter=<?= $filter ?>&sort=<?= $sort ?>&order=<?= $order ?>&per_page=<?= $perPage ?>" 
                       class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">Last</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Chart Script -->
<script>
const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
new Chart(hourlyCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($hours) ?>,
        datasets: [{
            label: 'Total Attempts',
            data: <?= json_encode($totalCounts) ?>,
            backgroundColor: 'rgba(147, 51, 234, 0.6)',
            borderColor: 'rgb(147, 51, 234)',
            borderWidth: 1
        }, {
            label: 'Blocked',
            data: <?= json_encode($failureCounts) ?>,
            backgroundColor: 'rgba(239, 68, 68, 0.6)',
            borderColor: 'rgb(239, 68, 68)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        },
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

function sortTable(column) {
    const urlParams = new URLSearchParams(window.location.search);
    const currentSort = urlParams.get('sort');
    const currentOrder = urlParams.get('order');
    
    let newOrder = 'DESC';
    if (currentSort === column && currentOrder === 'DESC') {
        newOrder = 'ASC';
    }
    
    urlParams.set('sort', column);
    urlParams.set('order', newOrder);
    window.location.search = urlParams.toString();
}
</script>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
