<?php
$pageTitle = "SQL HAVING Clause | Filtering Groups | SQL Playground";
$pageDesc = "Learn the difference between WHERE and HAVING. Master the SQL HAVING clause to filter aggregated data like COUNT and SUM.";
include('../includes/header.php');
include('../includes/schema_article.php');
?>
<div class="py-12 px-6">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism-tomorrow.min.css" rel="stylesheet" />
    
    <article class="max-w-3xl mx-auto prose prose-invert prose-lg">
        <h1 class="text-5xl font-bold mb-6 bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-blue-500">SQL HAVING Clause</h1>
        
        <p class="lead text-xl text-slate-300">
            The <code>HAVING</code> clause was added to SQL because the <code>WHERE</code> keyword could not be used with aggregate functions. It allows you to filter the <em>results</em> of a grouping.
        </p>

        <h2 id="syntax" class="scroll-mt-24 group">
            Basic Syntax
            <a href="#syntax" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>
        
        <pre><code class="language-sql text-cyan-300">SELECT column_name, COUNT(*)
FROM table_name
GROUP BY column_name
HAVING COUNT(*) > value;</code></pre>

        <h2 id="difference" class="scroll-mt-24 group">
            HAVING vs WHERE: The Interview Question
            <a href="#difference" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>
        <p>This is the most common SQL interview question. Here is the definitive answer:</p>
        
        <div class="grid md:grid-cols-2 gap-6 my-8 not-prose">
            <div class="p-6 bg-white/5 rounded-xl border border-white/5">
                <div class="text-cyan-400 font-bold mb-2">WHERE Clause</div>
                <ul class="list-disc pl-5 space-y-2 text-sm text-slate-400">
                    <li>Filters rows <strong>BEFORE</strong> grouping.</li>
                    <li>Cannot use aggregates (SUM, COUNT).</li>
                    <li>Operates on individual records.</li>
                </ul>
            </div>
            <div class="p-6 bg-white/5 rounded-xl border border-white/5">
                <div class="text-purple-400 font-bold mb-2">HAVING Clause</div>
                <ul class="list-disc pl-5 space-y-2 text-sm text-slate-400">
                    <li>Filters groups <strong>AFTER</strong> grouping.</li>
                    <li>Used specifically with aggregates.</li>
                    <li>Operates on the summary result.</li>
                </ul>
            </div>
        </div>

        <h2 id="examples" class="scroll-mt-24 group">
            Examples
            <a href="#examples" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>

        <h3 class="text-white mt-8 mb-4">1. Find Active Customers (Orders > 5)</h3>
        <p class="text-slate-400 mb-4">
            We want to list only customers who have placed more than 5 orders.
        </p>
        <div class="relative group">
            <pre><code class="language-sql text-cyan-300">SELECT CustomerName, COUNT(OrderID) as OrderCount
FROM Orders
GROUP BY CustomerName
HAVING COUNT(OrderID) > 5;</code></pre>
        </div>

        <h3 class="text-white mt-8 mb-4">2. Combined with WHERE</h3>
        <p class="text-slate-400 mb-4">
            You can use both! "Find users from USA (WHERE) who spent more than $1,000 (HAVING)".
        </p>
        <pre><code class="language-sql text-cyan-300">SELECT UserID, SUM(Total) as TotalSpent
FROM Orders
WHERE Country = 'USA'    -- Filter raw rows first
GROUP BY UserID
HAVING SUM(Total) > 1000; -- Filter groups second</code></pre>

        <h2 id="order" class="scroll-mt-24 group">
             SQL Order of Execution
            <a href="#order" class="opacity-0 group-hover:opacity-100 text-cyan-400 ml-2">#</a>
        </h2>
        <p>
            Understanding the order in which SQL executes your query clarifies everything.
        </p>
        <ol class="list-decimal pl-6 space-y-2 text-slate-300 font-mono text-sm bg-black/30 p-4 rounded-lg">
            <li><span class="text-cyan-400">FROM</span> (Load tables)</li>
            <li><span class="text-cyan-400">WHERE</span> (Filter rows)</li>
            <li><span class="text-cyan-400">GROUP BY</span> (Group rows)</li>
            <li><span class="text-cyan-400">HAVING</span> (Filter groups)</li>
            <li><span class="text-cyan-400">SELECT</span> (Return columns)</li>
            <li><span class="text-cyan-400">ORDER BY</span> (Sort results)</li>
            <li><span class="text-cyan-400">LIMIT</span> (Limit rows)</li>
        </ol>

        <div class="mt-12 pt-8 border-t border-white/10">
            <h3 class="text-xl font-bold text-white mb-4">Mastered Filtering?</h3>
            <p class="text-slate-400 mb-6">You now know how to slice and dice data. Next, we will cover the most powerful feature of SQL: Combining tables.</p>
            <div class="flex gap-4">
                <a href="../editor.php" class="px-6 py-3 bg-cyan-500 hover:bg-cyan-400 text-black font-bold rounded-lg transition">Open Compiler</a>
                <a href="sql-joins.php" class="px-6 py-3 border border-white/20 hover:border-white text-white rounded-lg transition">Next: SQL JOINS Explained &rarr;</a>
            </div>
        </div>

    </article>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-sql.min.js"></script>
</div>
<?php include('../includes/footer.php'); ?>
