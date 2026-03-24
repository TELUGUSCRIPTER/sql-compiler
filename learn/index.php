<?php
// learn/index.php
$pageTitle = "SQL Learning Hub | Free SQL Tutorials & Examples";
$pageDesc = "Start your SQL journey here. Free tutorials, practice examples, and interview questions for MySQL and PostgreSQL.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> | SQLCompiler.shop</title>
    <meta name="description" content="<?= $pageDesc ?>">
    <link rel="icon" type="image/png" href="/assets/logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { background-color: #0a0f1a; color: #fff; font-family: 'Space Grotesk', sans-serif; }</style>
<script src="https://5gvci.com/act/files/tag.min.js?z=10681000" data-cfasync="false" async></script>
</head>
<body class="flex flex-col min-h-screen">
    <nav class="border-b border-white/5 bg-black/20 backdrop-blur-sm">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="../index.php" class="font-bold tracking-tight text-white hover:text-cyan-400 transition">SQL<span class="text-cyan-400">Playground</span></a>
            <div class="flex gap-4">
                <a href="../index.php" class="text-sm text-cyan-400 hover:text-white font-bold">Open Compiler &rarr;</a>
            </div>
        </div>
    </nav>

    <main class="flex-1 py-20 px-6">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-5xl font-bold mb-6 text-center">SQL Learning Hub</h1>
            <p class="text-xl text-slate-400 text-center max-w-2xl mx-auto mb-16">
                Master database management with our free, hands-on tutorials. Read a concept, then run the code immediately in our online compiler.
            </p>

            <div class="grid md:grid-cols-2 gap-8">
                
                <!-- 1. Fundamentals -->
                <div class="bg-white/5 border border-white/5 p-8 rounded-2xl hover:border-cyan-500/30 transition group">
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 rounded bg-cyan-900/50 flex items-center justify-center text-cyan-400 text-sm">1</span>
                        Fundamentals
                    </h2>
                    <ul class="space-y-3 text-slate-400">
                        <li><a href="what-is-sql.php" class="hover:text-cyan-400 transition flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-cyan-500"></div> What is SQL?</a></li>
                        <li><a href="sql-select.php" class="hover:text-cyan-400 transition flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-cyan-500"></div> SQL SELECT Statement</a></li>
                        <li><a href="sql-where.php" class="hover:text-cyan-400 transition flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-cyan-500"></div> SQL WHERE Filtering</a></li>
                        <li><a href="sql-insert.php" class="hover:text-cyan-400 transition flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-cyan-500"></div> SQL INSERT Data</a></li>
                        <li><a href="sql-data-types.php" class="hover:text-cyan-400 transition flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-cyan-500"></div> Common Data Types</a></li>
                    </ul>
                </div>

                <!-- 2. Intermediate -->
                <div class="bg-white/5 border border-white/5 p-8 rounded-2xl hover:border-purple-500/30 transition group">
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 rounded bg-purple-900/50 flex items-center justify-center text-purple-400 text-sm">2</span>
                        Intermediate
                    </h2>
                    <ul class="space-y-3 text-slate-400">
                        <li><a href="sql-update.php" class="hover:text-purple-400 transition flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-purple-500"></div> SQL UPDATE Data</a></li>
                        <li><a href="sql-delete.php" class="hover:text-purple-400 transition flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-purple-500"></div> SQL DELETE Data</a></li>
                        <li><a href="sql-order-by.php" class="hover:text-purple-400 transition flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-purple-500"></div> SQL ORDER BY Sorting</a></li>
                        <li><a href="sql-joins.php" class="hover:text-purple-400 transition flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-purple-500"></div> SQL JOINS Explained</a></li>
                        <li><a href="sql-group-by.php" class="hover:text-purple-400 transition flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-purple-500"></div> GROUP BY & Aggregates</a></li>
                        <li><a href="sql-having.php" class="hover:text-purple-400 transition flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-purple-500"></div> HAVING Clause</a></li>
                        <li><a href="sql-subqueries.php" class="hover:text-purple-400 transition flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-purple-500"></div> Subqueries (Nested)</a></li>
                        <li><a href="sql-constraints.php" class="hover:text-purple-400 transition flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-purple-500"></div> <span class="text-purple-300 font-bold">NEW</span> SQL Constraints</a></li>
                    </ul>
                </div>

                <!-- 3. Advanced Concepts -->
                 <div class="bg-white/5 border border-white/5 p-8 rounded-2xl hover:border-pink-500/30 transition group">
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 rounded bg-pink-900/50 flex items-center justify-center text-pink-400 text-sm">3</span>
                        Advanced
                    </h2>
                    <ul class="space-y-3 text-slate-400">
                        <li><a href="sql-indexes.php" class="hover:text-pink-400 transition flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-pink-500"></div> <span class="text-pink-300 font-bold">NEW</span> SQL Indexes</a></li>
                        <li><a href="sql-vs-mysql-vs-postgresql.php" class="hover:text-pink-400 transition flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-pink-500"></div> MySQL vs PostgreSQL</a></li>
                    </ul>
                 </div>

                <!-- 4. Practice & Interview -->
                <div class="bg-white/5 border border-white/5 p-8 rounded-2xl hover:border-green-500/30 transition group">
                    <h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 rounded bg-green-900/50 flex items-center justify-center text-green-400 text-sm">4</span>
                        Practice & Interview
                    </h2>
                    <ul class="space-y-3 text-slate-400">
                        <li><a href="sql-interview-questions.php" class="hover:text-green-400 transition flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-green-500"></div> Top 10 SQL Interview Questions</a></li>
                        <li><a href="sql-practice-examples.php" class="hover:text-green-400 transition flex items-center gap-2"><div class="w-1.5 h-1.5 rounded-full bg-green-500"></div> Hands-on Practice Examples</a></li>
                    </ul>
                </div>

            </div>

        </div>
    </main>

    <footer class="py-12 border-t border-white/5 bg-[#020409]">
        <div class="max-w-6xl mx-auto px-6 text-center text-slate-600 text-sm">
            &copy; <?= date('Y') ?> SQL Playground. <a href="../privacy.php" class="hover:text-cyan-400">Privacy</a>
        </div>
    </footer>
</body>
</html>
