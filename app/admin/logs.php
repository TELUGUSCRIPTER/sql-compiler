<?php
// app/admin/logs.php
require_once __DIR__ . '/../../config/auth_middleware.php';
require_once __DIR__ . '/../../config/db_control.php';
requireAdmin();

$pdo = getControlDB();

// Filters 
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? 'all';
$sort = $_GET['sort'] ?? 'created_at';
$order = $_GET['order'] ?? 'DESC';
$perPage = (int)($_GET['per_page'] ?? 50);
$page = (int)($_GET['page'] ?? 1);
$offset = ($page - 1) * $perPage;

// Validate
$allowedSort = ['id', 'user_id', 'status', 'execution_time_ms', 'created_at'];
if (!in_array($sort, $allowedSort)) $sort = 'created_at';
$order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

// Build query
$where = "1=1";
$params = [];

if (!empty($search)) {
    $where .= " AND (u.name LIKE ? OR q.sql_text LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($status !== 'all') {
    $where .= " AND q.status = ?";
    $params[] = $status;
}

// Get total count
$countStmt = $pdo->prepare("
    SELECT COUNT(*) FROM query_logs q 
    JOIN users u ON q.user_id = u.id 
    WHERE $where
");
$countStmt->execute($params);
$totalLogs = $countStmt->fetchColumn();
$totalPages = ceil($totalLogs / $perPage);

$stmt = $pdo->prepare("
    SELECT q.*, u.name 
    FROM query_logs q 
    JOIN users u ON q.user_id = u.id 
    WHERE $where
    ORDER BY q.$sort $order
    LIMIT $perPage OFFSET $offset
");
$stmt->execute($params);
$logs = $stmt->fetchAll();

// Stats
$totalQueries = $pdo->query("SELECT COUNT(*) FROM query_logs")->fetchColumn();
$successQueries = $pdo->query("SELECT COUNT(*) FROM query_logs WHERE status = 'success'")->fetchColumn();
$errorQueries = $pdo->query("SELECT COUNT(*) FROM query_logs WHERE status = 'error'")->fetchColumn();
$avgLatency = $pdo->query("SELECT AVG(execution_time_ms) FROM query_logs")->fetchColumn();

$pageTitle = 'Query Logs';
require_once __DIR__ . '/includes/admin_nav.php';
?>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500 hover:shadow-xl transition">
        <p class="text-sm font-medium text-gray-600 uppercase">Total Queries</p>
        <p class="text-4xl font-bold text-gray-900 mt-2"><?= number_format($totalQueries) ?></p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500 hover:shadow-xl transition">
        <p class="text-sm font-medium text-gray-600 uppercase">Successful</p>
        <p class="text-4xl font-bold text-green-600 mt-2"><?= number_format($successQueries) ?></p>
        <p class="text-xs text-gray-500 mt-2"><?= round(($successQueries/$totalQueries)*100, 1) ?>% success rate</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500 hover:shadow-xl transition">
        <p class="text-sm font-medium text-gray-600 uppercase">Errors</p>
        <p class="text-4xl font-bold text-red-600 mt-2"><?= number_format($errorQueries) ?></p>
        <p class="text-xs text-gray-500 mt-2"><?= round(($errorQueries/$totalQueries)*100, 1) ?>% error rate</p>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500 hover:shadow-xl transition">
        <p class="text-sm font-medium text-gray-600 uppercase">Avg Latency</p>
        <p class="text-4xl font-bold text-blue-600 mt-2"><?= round($avgLatency ?: 0, 1) ?> <span class="text-base text-gray-500">ms</span></p>
    </div>
</div>

<!-- Filter Bar -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" class="flex flex-col lg:flex-row gap-4 items-end">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                   placeholder="Search by user or SQL query..." 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                <option value="all" <?= $status === 'all' ? 'selected' : '' ?>>All Status</option>
                <option value="success" <?= $status === 'success' ? 'selected' : '' ?>>Success Only</option>
                <option value="error" <?= $status === 'error' ? 'selected' : '' ?>>Errors Only</option>
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
        
        <?php if ($search || $status !== 'all' || $perPage !== 50): ?>
            <a href="logs.php" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium text-center">
                Clear
            </a>
        <?php endif; ?>
        
        <a href="export_csv.php?type=logs" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export CSV
        </a>
    </form>
</div>

<!-- Query Logs Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Query History (<?= $totalLogs ?> total)</h3>
            <p class="text-sm text-gray-500">Showing <?= min($offset + 1, $totalLogs) ?>-<?= min($offset + $perPage, $totalLogs) ?> of <?= $totalLogs ?></p>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-100" onclick="sortTable('id')">
                        ID <?= $sort === 'id' ? ($order === 'ASC' ? '↑' : '↓') : '' ?>
                    </th>
                    <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-100" onclick="sortTable('created_at')">
                        Time <?= $sort === 'created_at' ? ($order === 'ASC' ? '↑' : '↓') : '' ?>
                    </th>
                    <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-100" onclick="sortTable('user_id')">
                        User <?= $sort === 'user_id' ? ($order === 'ASC' ? '↑' : '↓') : '' ?>
                    </th>
                    <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-100" onclick="sortTable('status')">
                        Status <?= $sort === 'status' ? ($order === 'ASC' ? '↑' : '↓') : '' ?>
                    </th>
                    <th class="px-4 py-3 text-left">SQL Query</th>
                    <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-100 hidden md:table-cell" onclick="sortTable('execution_time_ms')">
                        Latency <?= $sort === 'execution_time_ms' ? ($order === 'ASC' ? '↑' : '↓') : '' ?>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            No query logs found
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-500 font-mono text-xs">
                            #<?= $log['id'] ?>
                        </td>
                        <td class="px-4 py-3 text-gray-500 whitespace-nowrap text-xs">
                            <?= date('M d, H:i:s', strtotime($log['created_at'])) ?>
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-semibold text-gray-900 text-xs">
                                <?= htmlspecialchars($log['name']) ?>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded <?= $log['status'] === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                <?= $log['status'] ?>
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-mono text-xs text-gray-800 max-w-2xl truncate cursor-help" 
                                 title="<?= htmlspecialchars($log['sql_text']) ?>"
                                 onclick="showFullQuery(this, '<?= htmlspecialchars(addslashes($log['sql_text'])) ?>')">
                                <?= htmlspecialchars(substr($log['sql_text'], 0, 100)) ?>
                                <?= strlen($log['sql_text']) > 100 ? '... (click to expand)' : '' ?>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-xs font-medium hidden md:table-cell">
                            <span class="<?= $log['execution_time_ms'] > 100 ? 'text-red-600' : 'text-gray-600' ?>">
                                <?= round($log['execution_time_ms'], 1) ?> ms
                            </span>
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
                    <a href="?page=1&search=<?= urlencode($search) ?>&status=<?= $status ?>&sort=<?= $sort ?>&order=<?= $order ?>&per_page=<?= $perPage ?>" 
                       class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">First</a>
                    <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&status=<?= $status ?>&sort=<?= $sort ?>&order=<?= $order ?>&per_page=<?= $perPage ?>" 
                       class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">Previous</a>
                <?php endif; ?>
                
                <?php 
                $start = max(1, $page - 2);
                $end = min($totalPages, $page + 2);
                for ($i = $start; $i <= $end; $i++): 
                ?>
                    <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&status=<?= $status ?>&sort=<?= $sort ?>&order=<?= $order ?>&per_page=<?= $perPage ?>" 
                       class="px-3 py-2 border rounded-lg text-sm <?= $i === $page ? 'bg-purple-600 text-white border-purple-600' : 'bg-white border-gray-300 hover:bg-gray-50' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&status=<?= $status ?>&sort=<?= $sort ?>&order=<?= $order ?>&per_page=<?= $perPage ?>" 
                       class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">Next</a>
                    <a href="?page=<?= $totalPages ?>&search=<?= urlencode($search) ?>&status=<?= $status ?>&sort=<?= $sort ?>&order=<?= $order ?>&per_page=<?= $perPage ?>" 
                       class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">Last</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Mobile note -->
    <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 text-xs text-gray-500 md:hidden">
        📱 Some columns hidden on mobile. Use desktop for full view.
    </div>
</div>

<!-- Full Query Modal -->
<div id="queryModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4" onclick="closeModal()">
    <div class="bg-white rounded-lg max-w-4xl w-full max-h-[80vh] overflow-auto" onclick="event.stopPropagation()">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center sticky top-0 bg-white">
            <h3 class="text-lg font-semibold text-gray-900">Full SQL Query</h3>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="p-6">
            <pre id="queryContent" class="bg-gray-50 p-4 rounded border border-gray-200 font-mono text-sm overflow-x-auto"></pre>
        </div>
    </div>
</div>

<script>
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

function showFullQuery(element, query) {
    document.getElementById('queryContent').textContent = query;
    document.getElementById('queryModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('queryModal').classList.add('hidden');
}

// Close modal with Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeModal();
    }
});
</script>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
