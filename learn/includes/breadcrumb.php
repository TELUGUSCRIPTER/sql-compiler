<?php
// learn/includes/breadcrumb.php
$breadcrumbs = $breadcrumbs ?? [];
?>
<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <ol class="flex items-center space-x-2 text-sm">
            <li>
                <a href="/" class="text-gray-500 hover:text-gray-700">Home</a>
            </li>
            <?php foreach ($breadcrumbs as $crumb): ?>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <?php if (isset($crumb['url'])): ?>
                        <a href="<?= $crumb['url'] ?>" class="text-gray-500 hover:text-gray-700"><?= htmlspecialchars($crumb['title']) ?></a>
                    <?php else: ?>
                        <span class="text-gray-900 font-medium"><?= htmlspecialchars($crumb['title']) ?></span>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</nav>
