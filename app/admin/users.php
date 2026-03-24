<?php
// app/admin/users.php
require_once __DIR__ . '/../../config/auth_middleware.php';
require_once __DIR__ . '/../../config/db_control.php';
requireAdmin();

$pdo = getControlDB();
$message = '';
$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? 'created_at';
$order = $_GET['order'] ?? 'DESC';
$perPage = (int)($_GET['per_page'] ?? 25);
$page = (int)($_GET['page'] ?? 1);
$offset = ($page - 1) * $perPage;

// Handle Bulk Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_action']) && !empty($_POST['user_ids'])) {
    $action = $_POST['bulk_action'];
    $userIds = array_map('intval', $_POST['user_ids']);
    $placeholders = str_repeat('?,', count($userIds) - 1) . '?';
    
    switch ($action) {
        case 'ban':
            $stmt = $pdo->prepare("UPDATE users SET status = 'banned' WHERE id IN ($placeholders) AND role != 'admin'");
            $stmt->execute($userIds);
            $message = count($userIds) . " user(s) banned successfully.";
            break;
        case 'unban':
            $stmt = $pdo->prepare("UPDATE users SET status = 'active' WHERE id IN ($placeholders) AND role != 'admin'");
            $stmt->execute($userIds);
            $message = count($userIds) . " user(s) unbanned successfully.";
            break;
    }
}

// Handle Single Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $userId = $_POST['user_id'] ?? null;
    $action = $_POST['action'] ?? null;

    if ($userId && $action) {
        if ($action === 'toggle_status') {
            $current = $_POST['current_status'];
            $new = ($current === 'active') ? 'banned' : 'active';
            $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
            $stmt->execute([$new, $userId]);
            $message = "User status updated to $new.";
        }
        if ($action === 'reset_db') {
             $stmt = $pdo->prepare("SELECT db_name FROM user_databases WHERE user_id = ?");
             $stmt->execute([$userId]);
             $udb = $stmt->fetch();
             if($udb) {
                $dbName = $udb['db_name'];
                try {
                    $pdo->exec("DROP DATABASE IF EXISTS `$dbName`");
                    $pdo->exec("CREATE DATABASE `$dbName`");
                    $message = "Database $dbName reset successfully.";
                } catch(Exception $e) {
                    $message = "Error resetting DB: " . $e->getMessage();
                }
             }
        }
    }
}

// Validate sort column
$allowedSort = ['id', 'name', 'email', 'role', 'status', 'created_at', 'registration_ip'];
if (!in_array($sort, $allowedSort)) $sort = 'created_at';

// Validate order
$order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

// Build query with search
$where = "1=1";
$params = [];
if (!empty($search)) {
    $where .= " AND (u.name LIKE ? OR u.email LIKE ? OR u.registration_ip LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Get total count
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM users u WHERE $where");
$countStmt->execute($params);
$totalUsers = $countStmt->fetchColumn();
$totalPages = ceil($totalUsers / $perPage);

// Get users with pagination
$stmt = $pdo->prepare("
    SELECT u.id, u.name, u.email, u.role, u.status, u.created_at, u.last_login, u.registration_ip, u.device_fingerprint, d.db_name 
    FROM users u 
    LEFT JOIN user_databases d ON u.id = d.user_id 
    WHERE $where
    ORDER BY u.$sort $order
    LIMIT $perPage OFFSET $offset
");
$stmt->execute($params);
$users = $stmt->fetchAll();

$pageTitle = 'User Management';
require_once __DIR__ . '/includes/admin_nav.php';
?>

<?php if($message): ?>
    <div class="bg-blue-100 border border-blue-400 text-blue-800 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
        <span><?= htmlspecialchars($message) ?></span>
        <button onclick="this.parentElement.remove()" class="text-blue-600 hover:text-blue-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
<?php endif; ?>

<!-- Search & Controls -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" class="flex flex-col lg:flex-row gap-4 items-end">
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                   placeholder="Search by name, email, or IP..." 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
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
            Search
        </button>
        <?php if ($search || $perPage !== 25): ?>
            <a href="users.php" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium text-center">
                Clear
            </a>
        <?php endif; ?>
        
        <a href="export_csv.php?type=users" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export CSV
        </a>
    </form>
</div>

<!-- Users Table -->
<form method="POST" id="bulkForm">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Users (<?= $totalUsers ?> total)</h3>
                <p class="text-sm text-gray-500">Showing <?= min($offset + 1, $totalUsers) ?>-<?= min($offset + $perPage, $totalUsers) ?> of <?= $totalUsers ?></p>
            </div>
            
            <div class="flex gap-2">
                <select name="bulk_action" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="">Bulk Actions...</option>
                    <option value="ban">Ban Selected</option>
                    <option value="unban">Unban Selected</option>
                </select>
                <button type="submit" onclick="return confirmBulk()" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                    Apply
                </button>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">
                            <input type="checkbox" id="selectAll" class="rounded border-gray-300" onclick="toggleAll(this)">
                        </th>
                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-100" onclick="sortTable('id')">
                            ID <?= $sort === 'id' ? ($order === 'ASC' ? '↑' : '↓') : '' ?>
                        </th>
                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-100" onclick="sortTable('name')">
                            Name <?= $sort === 'name' ? ($order === 'ASC' ? '↑' : '↓') : '' ?>
                        </th>
                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-100" onclick="sortTable('email')">
                            Email <?= $sort === 'email' ? ($order === 'ASC' ? '↑' : '↓') : '' ?>
                        </th>
                        <th class="px-4 py-3 text-left hidden md:table-cell">DB Name</th>
                        <th class="px-4 py-3 text-left hidden lg:table-cell cursor-pointer hover:bg-gray-100" onclick="sortTable('registration_ip')">
                            IP <?= $sort === 'registration_ip' ? ($order === 'ASC' ? '↑' : '↓') : '' ?>
                        </th>
                        <th class="px-4 py-3 text-left hidden xl:table-cell">Device</th>
                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-100" onclick="sortTable('role')">
                            Role <?= $sort === 'role' ? ($order === 'ASC' ? '↑' : '↓') : '' ?>
                        </th>
                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-100" onclick="sortTable('status')">
                            Status <?= $sort === 'status' ? ($order === 'ASC' ? '↑' : '↓') : '' ?>
                        </th>
                        <th class="px-4 py-3 text-left cursor-pointer hover:bg-gray-100 hidden lg:table-cell" onclick="sortTable('created_at')">
                            Created <?= $sort === 'created_at' ? ($order === 'ASC' ? '↑' : '↓') : '' ?>
                        </th>
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="11" class="px-6 py-8 text-center text-gray-500">
                                No users found
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $u): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <?php if ($u['role'] !== 'admin'): ?>
                                    <input type="checkbox" name="user_ids[]" value="<?= $u['id'] ?>" class="rounded border-gray-300 userCheckbox">
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 font-mono text-gray-600"><?= $u['id'] ?></td>
                            <td class="px-4 py-3">
                                <div class="font-semibold text-gray-900"><?= htmlspecialchars($u['name']) ?></div>
                                <div class="text-xs text-gray-500 md:hidden"><?= htmlspecialchars($u['email']) ?></div>
                            </td>
                            <td class="px-4 py-3 text-gray-600 hidden md:table-cell">
                                <span class="font-mono text-xs"><?= htmlspecialchars($u['email']) ?></span>
                            </td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-500 hidden md:table-cell">
                                <?= $u['db_name'] ?? '-' ?>
                            </td>
                            <td class="px-4 py-3 hidden lg:table-cell">
                                <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">
                                    <?= $u['registration_ip'] ?? 'N/A' ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 font-mono text-xs text-gray-400 hidden xl:table-cell" title="<?= htmlspecialchars($u['device_fingerprint'] ?? '') ?>">
                                <?= $u['device_fingerprint'] ? substr($u['device_fingerprint'], 0, 10) . '...' : 'N/A' ?>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded <?= $u['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' ?>">
                                    <?= $u['role'] ?>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded <?= $u['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= $u['status'] ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500 hidden lg:table-cell whitespace-nowrap">
                                <?= date('M d, Y', strtotime($u['created_at'])) ?>
                            </td>
                            <td class="px-4 py-3">
                                <?php if($u['role'] !== 'admin'): ?>
                                    <div class="flex gap-2">
                                        <form method="POST" onsubmit="return confirm('Change status?');" class="inline">
                                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                            <input type="hidden" name="action" value="toggle_status">
                                            <input type="hidden" name="current_status" value="<?= $u['status'] ?>">
                                            <button class="text-blue-600 hover:text-blue-800 font-medium text-xs">
                                                <?= $u['status'] === 'active' ? 'Ban' : 'Unban' ?>
                                            </button>
                                        </form>
                                        <span class="text-gray-300">|</span>
                                        <form method="POST" onsubmit="return confirm('Reset Database? All data will be wiped.');" class="inline">
                                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                            <input type="hidden" name="action" value="reset_db">
                                            <button class="text-red-600 hover:text-red-800 font-medium text-xs">Reset DB</button>
                                        </form>
                                    </div>
                                <?php else: ?>
                                    <span class="text-gray-400 text-xs">Protected</span>
                                <?php endif; ?>
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
                <div class="flex gap-2">
                    <?php if ($page > 1): ?>
                        <a href="?page=1&search=<?= urlencode($search) ?>&sort=<?= $sort ?>&order=<?= $order ?>&per_page=<?= $perPage ?>" 
                           class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">First</a>
                        <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&sort=<?= $sort ?>&order=<?= $order ?>&per_page=<?= $perPage ?>" 
                           class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">Previous</a>
                    <?php endif; ?>
                    
                    <?php 
                    $start = max(1, $page - 2);
                    $end = min($totalPages, $page + 2);
                    for ($i = $start; $i <= $end; $i++): 
                    ?>
                        <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&sort=<?= $sort ?>&order=<?= $order ?>&per_page=<?= $perPage ?>" 
                           class="px-3 py-2 border rounded-lg text-sm <?= $i === $page ? 'bg-purple-600 text-white border-purple-600' : 'bg-white border-gray-300 hover:bg-gray-50' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&sort=<?= $sort ?>&order=<?= $order ?>&per_page=<?= $perPage ?>" 
                           class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">Next</a>
                        <a href="?page=<?= $totalPages ?>&search=<?= urlencode($search) ?>&sort=<?= $sort ?>&order=<?= $order ?>&per_page=<?= $perPage ?>" 
                           class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm">Last</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Mobile-friendly note -->
        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 text-xs text-gray-500 md:hidden">
            📱 Scroll horizontally or use larger screen to see all columns
        </div>
    </div>
</form>

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

function toggleAll(checkbox) {
    const checkboxes = document.querySelectorAll('.userCheckbox');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
}

function confirmBulk() {
    const selected = document.querySelectorAll('.userCheckbox:checked').length;
    if (selected === 0) {
        alert('Please select at least one user');
        return false;
    }
    return confirm(`Apply action to ${selected} selected user(s)?`);
}
</script>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
