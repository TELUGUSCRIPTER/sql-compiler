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
                    <li><a href="sql-group-by.php" class="hover:text-cyan-400">SQL GROUP BY</a></li>
                    <li><a href="sql-subqueries.php" class="text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3 -ml-3">SQL Subqueries</a></li>
                    <li><a href="sql-interview-questions.php" class="hover:text-cyan-400">Interview Qs</a></li>
                </ul>
            </aside>

            <!-- Content -->
            <article class="lg:col-span-3 prose prose-invert prose-lg max-w-none">
                <h1 class="text-4xl font-bold mb-6">SQL Subqueries</h1>
                <p>A <strong>Subquery</strong> (or Inner Query) is a query within another SQL query and embedded within the <code class="text-pink-400">WHERE</code> clause.</p>
                <p>A subquery is used to return data that will be used in the main query as a condition to further restrict the data to be retrieved.</p>
                
                <h2 class="text-2xl font-bold mt-8 mb-4">Important Rules</h2>
                <ul class="list-disc pl-5 mb-6 text-slate-400">
                    <li>Subqueries must be enclosed in parentheses <code class="text-green-400">()</code>.</li>
                    <li>A subquery typically returns a single value or a list of values.</li>
                    <li>You can use subqueries with SELECT, INSERT, UPDATE, and DELETE statements.</li>
                </ul>

                <h2 class="text-2xl font-bold mt-8 mb-4">Example: Finding Above Average Products</h2>
                <p>Suppose you want to find all products that have a price greater than the average price of all products.</p>
                
                <pre><code class="language-sql">SELECT ProductName, Price
FROM Products
WHERE Price > (SELECT AVG(Price) FROM Products);</code></pre>

                <p class="mt-4">Here, <code class="text-green-400">(SELECT AVG(Price) FROM Products)</code> runs first and returns a value (e.g., 25.50). The main query then becomes <code class="text-pink-400">WHERE Price > 25.50</code>.</p>

                <h3 class="text-xl font-bold mt-6 mb-3">Live Example</h3>
                <div class="bg-black/30 rounded-lg border border-white/10 p-6 my-6">
                    <p class="text-sm text-slate-400 mb-2">Find customers who are from the same country as the supplier 'Exotic Liquids':</p>
                    <pre><code class="language-sql">SELECT CustomerName 
FROM Customers 
WHERE Country = (
    SELECT Country 
    FROM Suppliers 
    WHERE SupplierName = 'Exotic Liquids'
);</code></pre>
                    <div class="mt-4">
                        <a href="../index.php" class="inline-flex items-center gap-2 px-4 py-2 bg-cyan-600 hover:bg-cyan-500 text-white text-sm font-bold rounded transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Run this Code
                        </a>
                    </div>
                </div>

            </article>
        </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-sql.min.js"></script>
</div>
<?php include('../includes/footer.php'); ?>
