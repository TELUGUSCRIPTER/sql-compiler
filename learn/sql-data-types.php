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
                    <li><a href="sql-interview-questions.php" class="hover:text-cyan-400">Interview Qs</a></li>
                    <li><a href="sql-practice-examples.php" class="hover:text-cyan-400">Practice Examples</a></li>
                    <li><a href="sql-data-types.php" class="text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3 -ml-3">Data Types</a></li>
                </ul>
            </aside>

            <!-- Content -->
            <article class="lg:col-span-3 prose prose-invert prose-lg max-w-none">
                <h1 class="text-4xl font-bold mb-6">SQL Data Types (MySQL)</h1>
                <p>When creating a table, you must specify the data type for each column. Here are the most commonly used types in MySQL.</p>

                <h2 class="text-2xl font-bold mt-8 mb-4">String Data Types</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse bg-white/5 rounded-lg">
                        <thead>
                            <tr class="border-b border-white/10 text-slate-300">
                                <th class="p-3">Type</th>
                                <th class="p-3">Description</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-slate-400">
                            <tr class="border-b border-white/5">
                                <td class="p-3 font-mono text-cyan-400">CHAR(size)</td>
                                <td class="p-3">Fixed length string (0-255). Recommended for fixed codes like 'US', 'UK'.</td>
                            </tr>
                            <tr class="border-b border-white/5">
                                <td class="p-3 font-mono text-cyan-400">VARCHAR(size)</td>
                                <td class="p-3">Variable length string (0-65535). Most common for names, emails.</td>
                            </tr>
                            <tr class="border-b border-white/5">
                                <td class="p-3 font-mono text-cyan-400">TEXT</td>
                                <td class="p-3">Holds a string with a maximum length of 65,535 bytes. Good for blog posts.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h2 class="text-2xl font-bold mt-8 mb-4">Numeric Data Types</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse bg-white/5 rounded-lg">
                        <thead>
                             <tr class="border-b border-white/10 text-slate-300">
                                <th class="p-3">Type</th>
                                <th class="p-3">Description</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-slate-400">
                            <tr class="border-b border-white/5">
                                <td class="p-3 font-mono text-purple-400">INT</td>
                                <td class="p-3">Standard integer. Range: -2B to 2B.</td>
                            </tr>
                            <tr class="border-b border-white/5">
                                <td class="p-3 font-mono text-purple-400">BIGINT</td>
                                <td class="p-3">Large integer. Used for very large IDs.</td>
                            </tr>
                            <tr class="border-b border-white/5">
                                <td class="p-3 font-mono text-purple-400">DECIMAL(p,s)</td>
                                <td class="p-3">Exact fixed-point number. Essential for financial data (money).</td>
                            </tr>
                             <tr class="border-b border-white/5">
                                <td class="p-3 font-mono text-purple-400">FLOAT</td>
                                <td class="p-3">Floating point number. Good for scientific calculations, less precise.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h2 class="text-2xl font-bold mt-8 mb-4">Date & Time</h2>
                <ul class="list-disc pl-5 space-y-2 text-slate-400">
                    <li><code class="text-green-400">DATE</code>: Format YYYY-MM-DD.</li>
                    <li><code class="text-green-400">DATETIME</code>: Format YYYY-MM-DD HH:MI:SS.</li>
                    <li><code class="text-green-400">TIMESTAMP</code>: Similar to DATETIME but often used for tracking record changes.</li>
                </ul>

                <h2 class="text-2xl font-bold mt-8 mb-4">Example Usage</h2>
                <pre><code class="language-sql">CREATE TABLE Products (
    ProductID INT,
    ProductName VARCHAR(100),
    Price DECIMAL(10, 2),
    Description TEXT,
    CreatedAt DATETIME
);</code></pre>

            </article>
        </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-sql.min.js"></script>
</div>
<?php include('../includes/footer.php'); ?>
