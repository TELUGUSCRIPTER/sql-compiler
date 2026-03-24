<?php
$pageTitle = "SQL ORDER BY | Sorting & Ranking Data | SQL Playground";
$pageDesc = "Learn how to use the SQL ORDER BY clause to sort your query results. Master multiple column sorting, custom ranking, and performance optimization.";
include('../includes/header.php');
include('../includes/schema_article.php');
?>
<div class="py-12 px-6">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism-tomorrow.min.css" rel="stylesheet" />
    
    <article class="max-w-3xl mx-auto prose prose-invert prose-lg">
        <h1 class="text-5xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-indigo-500">SQL ORDER BY: The Art of Sorting</h1>
        
        <p class="lead text-xl text-slate-300">
            By default, SQL databases do not guarantee any specific order when retrieving records. To present data logically—alphabetically, by date, or by rank—you must use the <code>ORDER BY</code> clause.
        </p>

        <h2 id="syntax" class="scroll-mt-24 group">
            Basic Syntax
            <a href="#syntax" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>
        
        <pre><code class="language-sql text-cyan-300">SELECT column1, column2
FROM table_name
ORDER BY column1 [ASC|DESC], column2 [ASC|DESC];</code></pre>

        <ul class="list-disc pl-6 space-y-2 text-slate-400">
            <li><code>ASC</code>: Ascending order (A-Z, 0-9, Oldest-Newest). This is the <strong>default</strong> if omitted.</li>
            <li><code>DESC</code>: Descending order (Z-A, 9-0, Newest-Oldest).</li>
        </ul>

        <h2 id="advanced" class="scroll-mt-24 group">
            Advanced Techniques
            <a href="#advanced" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>

        <h3 class="text-white mt-8 mb-4">1. Sorting by Multiple Columns</h3>
        <p class="text-slate-400 mb-4">
            You can prioritize sorting. For example, sort by `Country` first, and if two people are from the same country, sort them by `Salary` (Highest first).
        </p>
        <pre><code class="language-sql text-cyan-300">SELECT name, country, salary
FROM employees
ORDER BY country ASC, salary DESC;</code></pre>

        <h3 class="text-white mt-8 mb-4">2. Sorting by Calculation (Expressions)</h3>
        <p class="text-slate-400 mb-4">
            You don't just have to sort by raw columns. You can sort by the result of a math operation.
        </p>
        <pre><code class="language-sql text-cyan-300">-- Show cheapest products first, based on price per unit
SELECT product_name, price, quantity
FROM inventory
ORDER BY (price / quantity) ASC;</code></pre>

        <h3 class="text-white mt-8 mb-4">3. Custom Sorting with FIELD()</h3>
        <p class="text-slate-400 mb-4">
            Sometimes alphabetical order isn't what you want. E.g., for Priority (Low, Medium, High). In MySQL, use `FIELD()`.
        </p>
        <pre><code class="language-sql text-cyan-300">SELECT task_name, priority
FROM tasks
ORDER BY FIELD(priority, 'High', 'Medium', 'Low');</code></pre>

        <h2 id="nulls" class="scroll-mt-24 group">
            Handling NULL Values
            <a href="#nulls" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>
        <p>
            Different databases handle NULL differently. MySQL puts NULLs first in valid ASC order. To force NULLs to the end:
        </p>
        <pre><code class="language-sql text-cyan-300">-- A clever trick to push NULLs to the bottom
SELECT * FROM users
ORDER BY -last_login DESC; -- (Sorts by negative value)

-- Or standard ANSI SQL way
SELECT * FROM users
ORDER BY CASE WHEN last_login IS NULL THEN 1 ELSE 0 END, last_login DESC;</code></pre>

        <h2 id="performance" class="scroll-mt-24 group">
            Performance: Filesort vs Index
            <a href="#performance" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>
        <div class="bg-white/5 border border-white/10 p-6 rounded-lg my-6">
             <h4 class="font-bold text-white mb-2">How Databases Sort</h4>
             <p class="text-sm text-slate-300 mb-4">
                <strong>Fast Way (Index):</strong> If you have an Index on the column (e.g., `created_at`), the data is already stored in order. The database reads it sequentially. Instant.
                <br><br>
                <strong>Slow Way (Filesort):</strong> If no index exists, the database must query all rows, load them into a memory buffer (Sort Buffer), and manually sort them using CPU. This is slow for large datasets.
             </p>
             <div class="bg-blue-900/20 text-blue-200 text-xs p-2 rounded border border-blue-500/20">
                <strong>Pro Tip:</strong> Always add an Index to columns you frequently sort by (like 'date' or 'price').
             </div>
        </div>

        <div class="mt-12 pt-8 border-t border-white/10">
            <h3 class="text-xl font-bold text-white mb-4">Practice Sorting</h3>
            <p class="text-slate-400 mb-6">Try running a query and sorting the output in our compiler.</p>
            <div class="flex gap-4">
                <a href="../editor.php" class="px-6 py-3 bg-cyan-500 hover:bg-cyan-400 text-black font-bold rounded-lg transition">Open Compiler</a>
                <a href="sql-group-by.php" class="px-6 py-3 border border-white/20 hover:border-white text-white rounded-lg transition">Next: GROUP BY & Aggregates &rarr;</a>
            </div>
        </div>

    </article>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-sql.min.js"></script>
</div>
<?php include('../includes/footer.php'); ?>
