<?php
// admin/edit_page.php
require_once __DIR__ . '/../config/db_control.php';
require_once __DIR__ . '/../config/auth_middleware.php';

// Auth Check
requireAdmin();

$pdo = getControlDB();
$learnDir = __DIR__ . '/../learn';
$file = $_GET['file'] ?? '';
$isNew = isset($_GET['new']);
$content = '';
$message = '';
$error = '';

if ($file && !$isNew) {
    // Security: Prevent directory traversal
    $file = basename($file);
    $path = $learnDir . '/' . $file;
    
    if (file_exists($path)) {
        $content = file_get_contents($path);
    } else {
        $error = "File not found.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = basename($_POST['filename'] ?? '');
    $content = $_POST['content'] ?? '';
    $token = $_POST['csrf_token'] ?? '';
    
    if (!validateCsrfToken($token)) {
        $error = "Invalid CSRF Token.";
    } elseif (!$file) {
        $error = "Filename is required.";
    } elseif (pathinfo($file, PATHINFO_EXTENSION) !== 'php') {
        $error = "Only .php files are allowed.";
    } else {
        $path = $learnDir . '/' . $file;
        if (file_put_contents($path, $content) !== false) {
            $message = "File saved successfully.";
            if ($isNew) {
                // Redirect to edit mode
                header("Location: edit_page.php?file=" . urlencode($file));
                exit;
            }
        } else {
            $error = "Failed to write file. Check permissions.";
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
    <title>Edit Page | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- CodeMirror for nice editing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/dracula.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/htmlmixed/htmlmixed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/php/php.min.js"></script>
    
    <style>
        body { background-color: #0a0f1a; color: #fff; font-family: sans-serif; }
        .CodeMirror { height: 600px; border-radius: 0.5rem; font-family: monospace; }
    </style>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
</head>
<body class="h-full flex flex-col">

    <header class="h-16 border-b border-white/5 flex items-center justify-between px-6 bg-[#0a0f1a]">
        <div class="flex items-center gap-4">
            <a href="pages.php" class="text-slate-400 hover:text-white transition"><i data-lucide="arrow-left" class="w-5 h-5"></i></a>
            <h1 class="font-bold text-lg"><?= $isNew ? 'Create New Page' : 'Editing: ' . htmlspecialchars($file) ?></h1>
        </div>
        <div class="text-sm text-slate-500">Admin Editor</div>
    </header>

    <main class="flex-1 p-6 overflow-hidden flex flex-col">
        <?php if ($message): ?>
            <div class="mb-4 bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-2 rounded flex items-center gap-2">
                <i data-lucide="check" class="w-4 h-4"></i> <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="mb-4 bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-2 rounded flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="flex-1 flex flex-col gap-4">
            <?= csrfInput() ?>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Filename</label>
                <input type="text" name="filename" value="<?= htmlspecialchars($file) ?>" required 
                    class="w-full bg-black/20 border border-white/10 rounded px-4 py-2 text-white focus:border-purple-500 outline-none font-mono"
                    <?= !$isNew ? 'readonly' : '' ?> placeholder="example.php">
            </div>
            
            <div class="flex-1 flex flex-col">
                <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Content</label>
                <textarea id="code-editor" name="content"><?= htmlspecialchars($content) ?></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-white/5">
                <a href="pages.php" class="px-4 py-2 text-slate-400 hover:text-white transition">Cancel</a>
                <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white px-6 py-2 rounded font-bold shadow-lg shadow-purple-500/20 transition flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Save Changes
                </button>
            </div>
        </form>
    </main>

    <script>
        lucide.createIcons();
        var editor = CodeMirror.fromTextArea(document.getElementById("code-editor"), {
            lineNumbers: true,
            mode: "application/x-httpd-php",
            theme: "dracula",
            indentUnit: 4,
            matchBrackets: true
        });
    </script>
</body>
</html>
