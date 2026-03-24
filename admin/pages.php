<?php
// admin/pages.php
require_once __DIR__ . '/../config/db_control.php';
require_once __DIR__ . '/../config/auth_middleware.php';

requireAdmin();

$learnDir = __DIR__ . '/../learn';
$files = [];

$search = $_GET['search'] ?? '';

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $error = "Invalid CSRF Token.";
    } else {
        $fileToDelete = basename($_POST['file']);
        $filePath = $learnDir . '/' . $fileToDelete;
        if (file_exists($filePath) && unlink($filePath)) {
            $message = "File '$fileToDelete' deleted successfully.";
        } else {
             $error = "Failed to delete file.";
        }
    }
}

// Get all .php files in learn directory
if (is_dir($learnDir)) {
    $scanned = scandir($learnDir);
    foreach ($scanned as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'php' && $file !== '.' && $file !== '..') {
            if ($search && stripos($file, $search) === false) continue; // Filter
            
            $path = $learnDir . '/' . $file;
            $files[] = [
                'name' => $file,
                'size' => round(filesize($path) / 1024, 2) . ' KB',
                'modified' => date('Y-m-d H:i:s', filemtime($path)),
                'preview' => substr(file_get_contents($path), 0, 500) // Preview snippet
            ];
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
    <title>Pages | Admin</title>
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
            <a href="users.php" class="flex items-center px-6 py-3 text-slate-400 hover:bg-white/5"><i data-lucide="users" class="w-5 h-5 mr-3"></i>Users</a>
            <a href="pages.php" class="flex items-center px-6 py-3 text-purple-400 bg-purple-500/10 border-r-2 border-purple-500"><i data-lucide="file-text" class="w-5 h-5 mr-3"></i>Pages</a>
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
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold">Content Management</h1>
                <div class="text-sm text-slate-400">Manage tutorial pages</div>
            </div>
            
            <div class="flex gap-3 w-full md:w-auto">
                 <form method="GET" class="relative group flex-1">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search pages..." 
                        class="w-full bg-black/20 border border-white/10 rounded-lg px-4 py-2 pl-10 text-sm text-white focus:border-purple-500 outline-none transition-all">
                    <i data-lucide="search" class="w-4 h-4 text-slate-500 absolute left-3 top-2.5"></i>
                </form>

                <a href="edit_page.php?new=true" class="bg-purple-600 hover:bg-purple-500 text-white px-4 py-2 rounded-lg text-sm font-bold transition flex items-center gap-2 whitespace-nowrap shadow-lg shadow-purple-500/20">
                    <i data-lucide="plus" class="w-4 h-4"></i> New Page
                </a>
            </div>
        </div>

        <?php if (isset($message)): ?>
            <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-lg flex items-center gap-2"><i data-lucide="check" class="w-4 h-4"></i> <?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg flex items-center gap-2"><i data-lucide="alert-triangle" class="w-4 h-4"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="bg-white/5 rounded-2xl overflow-hidden border border-white/5">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-black/20 text-slate-500 border-b border-white/5">
                            <th class="py-4 px-6">Filename</th>
                            <th class="py-4 px-6">Size</th>
                            <th class="py-4 px-6">Modified</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <?php foreach ($files as $f): ?>
                        <tr class="hover:bg-white/5 transition group">
                            <td class="py-4 px-6 font-mono text-purple-300">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="file-code" class="w-4 h-4 text-slate-500"></i>
                                    <?= htmlspecialchars($f['name']) ?>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-slate-400"><?= $f['size'] ?></td>
                            <td class="py-4 px-6 text-slate-400"><?= $f['modified'] ?></td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button onclick="openPreview('<?= htmlspecialchars($f['name']) ?>', `<?= htmlspecialchars(base64_encode($f['preview'])) ?>`)" class="p-1.5 text-slate-400 hover:text-white bg-white/5 hover:bg-white/10 rounded transition" title="Preview">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                    <a href="edit_page.php?file=<?= urlencode($f['name']) ?>" class="p-1.5 text-blue-400 hover:text-white bg-blue-500/10 hover:bg-blue-500 rounded transition" title="Edit">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                    <form method="POST" onsubmit="return confirm('Delete <?= htmlspecialchars($f['name']) ?>?');" class="inline">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="file" value="<?= htmlspecialchars($f['name']) ?>">
                                        <?= csrfInput() ?>
                                        <button class="p-1.5 text-red-400 hover:text-white bg-red-500/10 hover:bg-red-500 rounded transition" title="Delete">
                                            <i data-lucide="trash" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($files)): ?>
                            <tr><td colspan="4" class="py-8 text-center text-slate-500">No pages found matching "<?= htmlspecialchars($search) ?>"</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Preview Modal -->
    <div id="preview-modal" class="fixed inset-0 z-50 flex items-center justify-center pointer-events-none opacity-0 transition-opacity duration-300">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closePreview()"></div>
        <div class="bg-[#0a0f1a] border border-white/10 rounded-2xl w-full max-w-2xl max-h-[80vh] overflow-hidden shadow-2xl transform scale-95 transition-transform duration-300 pointer-events-auto flex flex-col">
            <div class="p-4 border-b border-white/5 flex justify-between items-center bg-white/5">
                <h3 id="preview-title" class="font-bold text-white flex items-center gap-2">
                    <i data-lucide="file-code" class="w-4 h-4 text-purple-400"></i> <span id="preview-filename">...</span>
                </h3>
                <button onclick="closePreview()" class="text-slate-400 hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="flex-1 overflow-auto p-0 bg-[#0d1117]">
                <pre class="text-xs font-mono text-slate-300 p-4 overflow-x-auto"><code id="preview-content"></code></pre>
            </div>
            <div class="p-4 border-t border-white/5 bg-white/5 flex justify-end gap-2">
                <button onclick="closePreview()" class="px-4 py-2 text-sm text-slate-400 hover:text-white transition">Close</button>
                <a id="preview-edit-btn" href="#" class="px-4 py-2 text-sm bg-purple-600 hover:bg-purple-500 text-white rounded font-bold transition">Edit File</a>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
        
        const modal = document.getElementById('preview-modal');
        const modalContent = modal.children[1];

        function openPreview(filename, contentBase64) {
            document.getElementById('preview-filename').textContent = filename;
            document.getElementById('preview-content').textContent = atob(contentBase64);
            document.getElementById('preview-edit-btn').href = 'edit_page.php?file=' + encodeURIComponent(filename);
            
            modal.classList.remove('pointer-events-none', 'opacity-0');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }

        function closePreview() {
            modal.classList.add('pointer-events-none', 'opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
        }
    </script>
</body>
</html>
