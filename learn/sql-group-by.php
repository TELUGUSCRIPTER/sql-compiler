<?php
$pageTitle = "SQL GROUP BY | Aggregating Data | SQL Playground";
$pageDesc = "Master the SQL GROUP BY statement. Learn how to summarize data using COUNT, SUM, AVG, and handle common grouping errors.";
include('../includes/header.php');
include('../includes/schema_article.php');
?>
<div class="py-12 px-6">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism-tomorrow.min.css" rel="stylesheet" />
    
    <article class="max-w-3xl mx-auto prose prose-invert prose-lg">
        <h1 class="text-5xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-blue-500">SQL GROUP BY: Summarizing Data</h1>
        
        <p class="lead text-xl text-slate-300">
            Raw data is often too granular to be useful. The <code>GROUP BY</code> statement allows you to collapse thousands of rows into summary "buckets" to answer questions like "What is the total revenue <em>per country</em>?"
        </p>

        <h2 id="syntax" class="scroll-mt-24 group">
            Basic Syntax
            <a href="#syntax" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>
        
        <pre><code class="language-sql text-cyan-300">SELECT column_name, AGGREGATE_FUNCTION(column_name)
FROM table_name
GROUP BY column_name;</code></pre>

        <h2 id="functions" class="scroll-mt-24 group">
            Aggregate Functions
            <a href="#functions" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 my-6 not-prose">
            <div class="bg-white/5 p-4 rounded border border-white/10">
                <code class="text-green-400 font-bold">COUNT()</code>
                <p class="text-xs text-slate-400 mt-1">Count rows</p>
            </div>
            <div class="bg-white/5 p-4 rounded border border-white/10">
                <code class="text-green-400 font-bold">SUM()</code>
                <p class="text-xs text-slate-400 mt-1">Total value</p>
            </div>
             <div class="bg-white/5 p-4 rounded border border-white/10">
                <code class="text-green-400 font-bold">AVG()</code>
                <p class="text-xs text-slate-400 mt-1">Average value</p>
            </div>
             <div class="bg-white/5 p-4 rounded border border-white/10">
                <code class="text-green-400 font-bold">MAX()</code>
                <p class="text-xs text-slate-400 mt-1">Largest value</p>
            </div>
             <div class="bg-white/5 p-4 rounded border border-white/10">
                <code class="text-green-400 font-bold">MIN()</code>
                <p class="text-xs text-slate-400 mt-1">Smallest value</p>
            </div>
        </div>

        <h2 id="examples" class="scroll-mt-24 group">
            Real-World Examples
            <a href="#examples" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>

        <h3 class="text-white mt-8 mb-4">1. Total Users per Country</h3>
        <p class="text-slate-400 mb-4">
            Imagine a table of 1,000 users. We want to see where they live.
        </p>
        <div class="relative group">
            <pre><code class="language-sql text-cyan-300">SELECT Country, COUNT(*) as UserCount
FROM Users
GROUP BY Country;</code></pre>
             <a href="../editor.php?query=SELECT Country, COUNT(*) as UserCount FROM Customers GROUP BY Country;" class="absolute top-2 right-2 px-3 py-1 bg-cyan-500 hover:bg-cyan-400 text-black text-xs font-bold rounded transition">Run Code &rarr;</a>
        </div>

        <h3 class="text-white mt-8 mb-4">2. Grouping by Multiple Columns</h3>
        <p class="text-slate-400 mb-4">
            You can group by more than one column. For example, "Total sales per City, per Year".
        </p>
        <pre><code class="language-sql text-cyan-300">SELECT Country, City, SUM(TotalSpent)
FROM Orders
GROUP BY Country, City;</code></pre>

        <h2 id="errors" class="scroll-mt-24 group">
            Common Error: The "Only Full Group By" Rule
            <a href="#errors" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>
        <div class="bg-red-900/10 border-l-4 border-red-500 p-6 my-6">
            <h4 class="text-red-400 font-bold mb-2">Error 1055 or similar</h4>
            <p class="text-slate-300 text-sm italic mb-2">
                "Expression #1 of SELECT list is not in GROUP BY clause..."
            </p>
            <p class="text-slate-400 text-sm">
                <strong>Explanation:</strong> If you GROUP BY `Country`, you cannot select `CustomerName`. Why? Because there are 50 customers in 'USA'. Which name should SQL show? It doesn't know, so it throws an error.
                <br><br>
                <strong>Rule:</strong> Any column in your SELECT list must be either (a) in the GROUP BY clause or (b) inside an aggregate function like MAX().
            </p>
        </div>

        <h2 id="performance" class="scroll-mt-24 group">
             Performance Optimization
            <a href="#performance" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>
        <p>
            Grouping requires the database to sort the entire result set. To speed this up, create an <strong>Index</strong> on the column you are grouping by. This allows the database to "walk" down the pre-sorted index and count items instantly.
        </p>

        <div class="mt-12 pt-8 border-t border-white/10">
            <h3 class="text-xl font-bold text-white mb-4">Next Challenge</h3>
            <p class="text-slate-400 mb-6">What if you want to filter groups? E.g. "Only show countries with > 100 users"? You can't use WHERE. You need HAVING.</p>
            <div class="flex gap-4">
                <a href="../editor.php" class="px-6 py-3 bg-cyan-500 hover:bg-cyan-400 text-black font-bold rounded-lg transition">Open Compiler</a>
                <a href="sql-having.php" class="px-6 py-3 border border-white/20 hover:border-white text-white rounded-lg transition">Next: SQL HAVING Clause &rarr;</a>
            </div>
        </div>

    </article>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-sql.min.js"></script>
</div>
<?php include('../includes/footer.php'); ?>
