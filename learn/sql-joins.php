<?php
$pageTitle = "SQL JOINs Explained | Inner, Left, Right | SQL Playground";
$pageDesc = "Visual guide to SQL JOINs. Understand how to combine rows from two or more tables using INNER JOIN, LEFT JOIN, and RIGHT JOIN.";
include('../includes/header.php');
include('../includes/schema_article.php');
?>
<div class="py-12 px-6">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism-tomorrow.min.css" rel="stylesheet" />
    
        <article class="max-w-3xl mx-auto prose prose-invert prose-lg">
            <h1 class="text-4xl font-bold text-white mb-6">Mastering SQL JOINs</h1>
            
            <p class="text-slate-300 text-xl leading-relaxed">
                A <code>JOIN</code> clause is used to combine rows from two or more tables, based on a related column between them. This is the superpower of relational databases.
            </p>

            <div class="my-8 p-6 bg-white/5 border border-white/10 rounded-xl">
                 <!-- Ad Unit -->
                 <div class="text-center text-xs text-slate-600 mb-2">Advertisement</div>
                 <div class="h-24 bg-black/20 rounded flex items-center justify-center text-slate-700 font-mono">[AdSense Responsive Slot]</div>
            </div>

            <h2 class="text-2xl font-bold text-white mt-12 mb-4">Different Types of JOINs</h2>
            <div class="grid grid-cols-2 gap-4 not-prose">
                <div class="p-4 bg-white/5 rounded-lg border border-white/5">
                    <strong class="text-cyan-400 block mb-2">INNER JOIN</strong>
                    <span class="text-sm text-slate-400">Returns records that have matching values in both tables.</span>
                </div>
                <div class="p-4 bg-white/5 rounded-lg border border-white/5">
                    <strong class="text-purple-400 block mb-2">LEFT JOIN</strong>
                    <span class="text-sm text-slate-400">Returns all records from the left table, and the matched records from the right table.</span>
                </div>
                <div class="p-4 bg-white/5 rounded-lg border border-white/5">
                    <strong class="text-pink-400 block mb-2">RIGHT JOIN</strong>
                    <span class="text-sm text-slate-400">Returns all records from the right table, and the matched records from the left table.</span>
                </div>
                <div class="p-4 bg-white/5 rounded-lg border border-white/5">
                    <strong class="text-yellow-400 block mb-2">FULL JOIN</strong>
                    <span class="text-sm text-slate-400">Returns all records when there is a match in either left or right table.</span>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-white mt-12 mb-4">INNER JOIN Example</h2>
            <p>Suppose we have <code>Orders</code> and <code>Customers</code> tables, linked by <code>CustomerID</code>.</p>
            <pre><code class="language-sql">SELECT Orders.OrderID, Customers.CustomerName
FROM Orders
INNER JOIN Customers ON Orders.CustomerID = Customers.CustomerID;</code></pre>
            <p>This query will only return orders that belong to a valid customer.</p>

            <h2 class="text-2xl font-bold text-white mt-12 mb-4">Visualizing Logic</h2>
            <p class="text-slate-400">
                Think of JOINs as a Venn diagram. INNER JOIN is the intersection. LEFT JOIN is the entire left circle plus the intersection.
            </p>

            <div class="my-12 p-8 bg-pink-900/20 border border-pink-500/30 rounded-2xl">
                <h3 class="text-xl font-bold text-pink-400 mb-2">Build Complex Queries</h3>
                <p class="mb-4 text-sm text-slate-400">Create multiple tables and join them in our compiler.</p>
                <a href="../register.php" class="inline-block px-6 py-3 bg-pink-600 hover:bg-pink-500 text-white font-bold rounded-lg transition">Open Compiler &rarr;</a>
            </div>

        </article>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-core.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-sql.min.js"></script>
</div>
<?php include('../includes/footer.php'); ?>
