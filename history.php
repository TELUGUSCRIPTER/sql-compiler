<?php
// history.php
require_once __DIR__ . '/config/auth_middleware.php';
require_once __DIR__ . '/config/db_control.php';
requireLogin();

$userId = getCurrentUserId();
$pdo = getControlDB();
$stmt = $pdo->prepare("SELECT sql_text, execution_time_ms, status, created_at FROM query_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT 50");
$stmt->execute([$userId]);
$logs = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<div class="max-w-6xl mx-auto px-6 py-10 animate-fade-in">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white">Execution History</h1>
            <p class="text-slate-400 mt-1">Recent 50 queries executed on <span class="font-mono text-cyan-400"><?= $_SESSION['db_name'] ?></span></p>
        </div>
        <div class="text-sm text-slate-500">
            Auto-logged
        </div>
    </div>

    <div class="glass-card rounded-xl overflow-hidden border border-white/5">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-black/20 text-slate-400 text-xs uppercase tracking-wider border-b border-white/5">
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Time</th>
                        <th class="px-6 py-4 font-semibold">SQL Query</th>
                        <th class="px-6 py-4 font-semibold text-right">Duration</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-sm">
                    <?php if (empty($logs)): ?>
                        <tr><td colspan="4" class="px-6 py-12 text-center text-slate-500 italic">No history found. Start coding!</td></tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                        <tr class="hover:bg-white/5 transition group">
                            <td class="px-6 py-4">
                                <?php if ($log['status'] === 'success'): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-500/10 text-green-400 border border-green-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> SUCCESS
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-500/10 text-red-400 border border-red-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> ERROR
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-slate-400 whitespace-nowrap">
                                <?= date('H:i:s', strtotime($log['created_at'])) ?>
                                <span class="text-xs text-slate-600 ml-1"><?= date('M j', strtotime($log['created_at'])) ?></span>
                            </td>
                            <td class="px-6 py-4 w-full">
                                <code class="font-mono text-slate-300 block truncate max-w-lg group-hover:text-white transition" title="<?= htmlspecialchars($log['sql_text']) ?>">
                                    <?= htmlspecialchars($log['sql_text']) ?>
                                </code>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-mono text-cyan-400"><?= round($log['execution_time_ms'], 2) ?></span> <span class="text-xs text-slate-500">ms</span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
