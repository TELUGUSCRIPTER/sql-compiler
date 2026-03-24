<?php
$pageTitle = "<?= $pageTitle ?> | SQLCompiler.shop";
$pageDesc = "<?= $pageDesc ?>";
include('../includes/header.php');
include('../includes/schema_article.php');
?>
<div class="py-12 px-6">
    
    
        <div class="max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <aside class="hidden lg:block space-y-4">
                <h3 class="font-bold text-white uppercase tracking-wider text-sm mb-4">SQL Tutorial</h3>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li><a href="sql-interview-questions.php" class="hover:text-cyan-400">Interview Qs</a></li>
                    <li><a href="sql-practice-examples.php" class="hover:text-cyan-400">Practice Examples</a></li>
                    <li><a href="sql-vs-mysql-vs-postgresql.php" class="text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3 -ml-3">Comparisons</a></li>
                </ul>
            </aside>

            <!-- Content -->
            <article class="lg:col-span-3 prose prose-invert prose-lg max-w-none">
                <h1 class="text-4xl font-bold mb-6">SQL vs MySQL vs PostgreSQL</h1>
                <p>Beginners often get confused between SQL (the language) and the software tools (MySQL, PostgreSQL, SQL Server) that use it. Here is a clear breakdown.</p>

                <div class="grid gap-6 mt-8">
                    <!-- SQL -->
                    <div class="bg-white/5 p-6 rounded-xl border border-white/5">
                        <h2 class="text-2xl font-bold text-white mb-2">What is SQL?</h2>
                        <p class="text-slate-400">
                            <strong>SQL (Structured Query Language)</strong> is the standard language used to communicate with databases. It is a set of rules and syntax. Think of it as the "English language" of databases.
                        </p>
                    </div>

                    <!-- MySQL -->
                    <div class="bg-white/5 p-6 rounded-xl border border-white/5">
                        <h2 class="text-2xl font-bold text-cyan-400 mb-2">What is MySQL?</h2>
                        <p class="text-slate-400">
                            <strong>MySQL</strong> is a database management system (DBMS). It is a software product that "speaks" SQL.
                        </p>
                        <ul class="list-disc pl-5 mt-2 text-slate-500 text-sm">
                            <li>Most popular for web applications (WordPress, Facebook).</li>
                            <li>Known for speed and simplicity.</li>
                            <li>Open source and owned by Oracle.</li>
                        </ul>
                    </div>

                    <!-- PostgreSQL -->
                    <div class="bg-white/5 p-6 rounded-xl border border-white/5">
                        <h2 class="text-2xl font-bold text-purple-400 mb-2">What is PostgreSQL?</h2>
                        <p class="text-slate-400">
                            <strong>PostgreSQL</strong> (or Postgres) is arguably the most advanced open-source database.
                        </p>
                        <ul class="list-disc pl-5 mt-2 text-slate-500 text-sm">
                            <li>Stronger support for complex queries and massive data analysis.</li>
                            <li>Supports JSON/JSONB natively (NoSQL features).</li>
                            <li>Extrict adherence to SQL standards.</li>
                        </ul>
                    </div>
                </div>

                <h2 class="text-2xl font-bold mt-12 mb-6">Comparison Table</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-black/40 text-slate-300">
                                <th class="p-3 border-b border-white/10">Feature</th>
                                <th class="p-3 border-b border-white/10 text-cyan-400">MySQL</th>
                                <th class="p-3 border-b border-white/10 text-purple-400">PostgreSQL</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-slate-400">
                            <tr class="border-b border-white/5">
                                <td class="p-3 font-medium text-white">Complexity</td>
                                <td class="p-3">Easier to set up</td>
                                <td class="p-3">Steeper learning curve</td>
                            </tr>
                            <tr class="border-b border-white/5">
                                <td class="p-3 font-medium text-white">JSON Support</td>
                                <td class="p-3">Supported (newer versions)</td>
                                <td class="p-3">Excellent / Native</td>
                            </tr>
                            <tr class="border-b border-white/5">
                                <td class="p-3 font-medium text-white">Ideal Use Case</td>
                                <td class="p-3">Websites, E-commerce</td>
                                <td class="p-3">Data Science, Complex Apps</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </article>
        </div>
    
    
</div>
<?php include('../includes/footer.php'); ?>
