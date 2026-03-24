<?php
$pageTitle = "<?= $pageTitle ?> | SQLCompiler.shop";
$pageDesc = "<?= $pageDesc ?>";
include('../includes/header.php');
include('../includes/schema_article.php');
?>
<div class="py-12 px-6">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism-tomorrow.min.css" rel="stylesheet" />
    
        <div class="max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <aside class="hidden lg:block space-y-4">
                <h3 class="font-bold text-white uppercase tracking-wider text-sm mb-4">SQL Tutorial</h3>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li><a href="sql-joins.php" class="hover:text-cyan-400">SQL JOINS</a></li>
                    <li><a href="sql-subqueries.php" class="hover:text-cyan-400">SQL Subqueries</a></li>
                    <li><a href="sql-interview-questions.php" class="text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3 -ml-3">Interview Qs</a></li>
                    <li><a href="sql-practice-examples.php" class="hover:text-cyan-400">Practice Examples</a></li>
                </ul>
            </aside>

            <!-- Content -->
            <article class="lg:col-span-3 prose prose-invert prose-lg max-w-none">
                <h1 class="text-4xl font-bold mb-6">Common SQL Interview Questions</h1>
                <p class="lead text-xl text-slate-400">Going for a Data Analyst or Backend Developer interview? Here are the most frequently asked SQL questions.</p>

                <div class="space-y-12 mt-12">
                    <!-- Q1 -->
                    <div>
                        <h3 class="text-2xl font-bold text-white mb-3">1. What is the difference between DELETE and TRUNCATE?</h3>
                        <p class="text-slate-400 mb-2"><strong class="text-cyan-400">DELETE</strong> is a DML command that removes rows from a table based on a WHERE condition. It can be rolled back.</p>
                        <p class="text-slate-400"><strong class="text-purple-400">TRUNCATE</strong> is a DDL command that removes all rows from a table by deallocating the pages. It is faster but cannot be rolled back in some SQL implementations (though in Transactional tables it typically can be).</p>
                    </div>

                    <!-- Q2 -->
                    <div>
                        <h3 class="text-2xl font-bold text-white mb-3">2. What are the different subsets of SQL?</h3>
                        <ul class="list-disc pl-5 text-slate-400 space-y-2">
                            <li><strong>DDL (Data Definition Language):</strong> CREATE, ALTER, DROP</li>
                            <li><strong>DML (Data Manipulation Language):</strong> SELECT, INSERT, UPDATE, DELETE</li>
                            <li><strong>DCL (Data Control Language):</strong> GRANT, REVOKE</li>
                        </ul>
                    </div>

                    <!-- Q3 -->
                    <div>
                        <h3 class="text-2xl font-bold text-white mb-3">3. How do you find the second highest salary?</h3>
                        <p class="text-slate-400">This is a classic question. Here is the query:</p>
                        <pre><code class="language-sql">SELECT MAX(Salary) 
FROM Employees 
WHERE Salary < (SELECT MAX(Salary) FROM Employees);</code></pre>
                        <p class="text-sm mt-2 text-slate-500">Alternatively, you can use <code class="text-xs bg-slate-800 p-1 rounded">LIMIT n-1, 1</code> or window functions like <code class="text-text-xs bg-slate-800 p-1 rounded">DENSE_RANK()</code>.</p>
                    </div>

                    <!-- Q4 -->
                    <div>
                        <h3 class="text-2xl font-bold text-white mb-3">4. What is a Primary Key vs Unique Key?</h3>
                        <table class="w-full text-left border-collapse mt-4">
                            <thead>
                                <tr class="border-b border-slate-700">
                                    <th class="p-2 text-cyan-400">Primary Key</th>
                                    <th class="p-2 text-purple-400">Unique Key</th>
                                </tr>
                            </thead>
                            <tbody class="text-slate-400 text-sm">
                                <tr class="border-b border-slate-800">
                                    <td class="p-2">Only one per table</td>
                                    <td class="p-2">Multiple allowed per table</td>
                                </tr>
                                <tr class="border-b border-slate-800">
                                    <td class="p-2">Cannot be NULL</td>
                                    <td class="p-2">Can contain one NULL value</td>
                                </tr>
                                <tr>
                                    <td class="p-2">Creates Clustered Index (usually)</td>
                                    <td class="p-2">Creates Non-Clustered Index</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Q5 -->
                    <div>
                        <h3 class="text-2xl font-bold text-white mb-3">5. What is specific to SQL NULL values?</h3>
                        <p class="text-slate-400">A NULL value is not zero or an empty string; it represents missing or unknown data. You cannot use normal comparison operators (<code class="text-pink-400">=</code>, <code class="text-pink-400"><</code>) with NULL. instead, you must use <code class="text-green-400">IS NULL</code> or <code class="text-green-400">IS NOT NULL</code>.</p>
                    </div>
                </div>

                <div class="mt-12 p-6 bg-cyan-900/10 border border-cyan-500/20 rounded-xl">
                    <h3 class="text-xl font-bold text-white mb-2">Want to practice these?</h3>
                    <p class="text-slate-400 mb-4">You can try creating a table and running these queries right now in our online compiler.</p>
                    <a href="../index.php" class="inline-block px-6 py-3 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-lg transition">Start Coding Now</a>
                </div>

            </article>
        </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-sql.min.js"></script>
</div>
<?php include('../includes/footer.php'); ?>
